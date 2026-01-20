<?php

namespace App\Twig\Components;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent("user-table", template: "components/user-table.html.twig")]
class UserTable
{

    use DefaultActionTrait;

    private const int PER_PAGE = 10;

    /** @var User[] */
    #[LiveProp]
    public array $users = [];

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
        $this->loadUsers();
    }

    public function loadUsers(?int $cursor = null, int $limit = self::PER_PAGE): void
    {
        $cursor = $cursor ?? ($this->cursor ?? 0);
        $users = $this->userRepository->findNextUsers(afterId: $cursor, limit: $limit);
        $this->users = array_merge($this->users, $users);
        $this->cursor = end($users)->getId();
    }

    #[LiveAction]
    public function more(): void
    {
        $this->loadUsers();
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
        $this->users = array_values(array_filter($this->users, fn ($u) => $u !== $user));

        $missing = self::PER_PAGE - count($this->users);

        if ($missing > 0)
        {
            $lastId = empty($this->users) ? ($this->cursor ?? 0) : end($this->users)->getId();
            $this->loadUsers($lastId, $missing);
        }
    }

}
