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

    private const PER_PAGE = 10;

    #[LiveProp]
    public array $userIds = [];

    #[LiveProp]
    public int $page = 1;

    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function mount(): void
    {
        $this->loadPage(1);
    }

    private function loadPage(int $page): void
    {
        $ids = $this->userRepository->paginateIds($page, self::PER_PAGE);

        $this->userIds = array_values(
            array_unique([...$this->userIds, ...$ids])
        );
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
        $this->page++;
        $this->loadPage($this->page);
    }

    public function hasMore(): bool
    {
        return $this->userRepository->count() > count($this->userIds);
    }

    #[LiveAction]
    public function delete(#[LiveArg] int $id): void
    {
        $user = $this->userRepository->find($id);

        $this->entityManager->remove($user);
        $this->entityManager->flush();
        $this->userIds = array_values(array_filter($this->userIds, fn (int $userId) => $userId !== $id));

        $expectedCount = $this->page * self::PER_PAGE;
        $currentCount  = count($this->userIds);

        if ($currentCount < $expectedCount)
        {
            $missing = $expectedCount - $currentCount;
            $lastId = empty($this->userIds) === true ? 0 : max($this->userIds);

            $nextIds = $this->userRepository->findNextIds(afterId: $lastId, limit: $missing);
            $this->userIds = array_merge($this->userIds, $nextIds);
        }
    }

}
