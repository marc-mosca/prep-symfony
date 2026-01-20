<?php

namespace App\Twig\Components;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent("user-grid", template: "components/user-grid.html.twig")]
class UserGrid
{

    use DefaultActionTrait;

    private const int PER_PAGE = 10;

    #[LiveProp]
    public array $userIds = [];

    #[LiveProp]
    public ?int $cursor = null;

    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly EntityManagerInterface $entityManager,
    )
    {
    }

    public function mount(): void
    {
        $this->loadMore();
    }

    public function getUsers(): array
    {
        if (empty($this->userIds) === true)
        {
            return [];
        }

        return $this->userRepository->findBy(['id' => $this->userIds]);
    }

    #[LiveAction]
    public function more(): void
    {
        $this->loadMore();
    }

    private function loadMore(?int $cursor = null, int $limit = self::PER_PAGE): void
    {
        $cursor = $cursor ?? ($this->cursor ?? 0);
        $ids = $this->userRepository->findNextIds(afterId: $cursor, limit: $limit);

        if (empty($ids) === false)
        {
            $this->userIds = array_merge($this->userIds, $ids);
            $this->cursor  = end($ids);
        }
    }

    public function hasMore(): bool
    {
        return $this->userRepository->hasAfterId($this->cursor ?? 0);
    }

    #[LiveAction]
    public function delete(#[LiveArg] int $id): void
    {
        $user = $this->userRepository->find($id);

        $this->entityManager->remove($user);
        $this->entityManager->flush();
        $this->userIds = array_values(array_filter($this->userIds, fn (int $userId) => $userId !== $id));

        $missing = self::PER_PAGE - count($this->userIds);

        if ($missing > 0)
        {
            $lastId = empty($this->userIds) ? ($this->cursor ?? 0) : max($this->userIds);
            $this->loadMore($lastId, $missing);
        }
    }

}
