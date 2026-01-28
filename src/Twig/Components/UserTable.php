<?php

namespace App\Twig\Components;

use App\Entity\User;
use App\Form\UserEditFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent("user-table", template: "components/user-table.html.twig")]
class UserTable
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    private const int PER_PAGE = 25;

    /** @var User[] */
    #[LiveProp]
    public array $users = [];

    #[LiveProp]
    public int $usersCount;

    #[LiveProp(writable: true)]
    public ?User $editingUser = null;

    #[LiveProp(writable: true)]
    public ?User $deletingUser = null;

    #[LiveProp]
    public ?int $cursor = null;

    #[LiveProp(writable: true, onUpdated: 'search')]
    public string $query = "";

    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly FormFactoryInterface $formFactory,
    )
    {
    }

    public function mount(): void
    {
        $this->loadUsers();
        $this->updateUsersCount();
    }

    #[LiveAction]
    public function loadUsers(?int $cursor = null, int $limit = self::PER_PAGE): void
    {
        $cursor = $cursor ?? ($this->cursor ?? 0);
        $users = $this->userRepository->findNextUsers(afterId: $cursor, limit: $limit, query: $this->query);

        if (empty($users) === true)
        {
            $this->cursor = null;
            return ;
        }

        $this->users = array_merge($this->users, $users);
        $this->cursor = end($users)->getId();
    }

    private function updateUsersCount(): void
    {
        $this->usersCount = $this->userRepository->countByQuery($this->query);
    }

    #[LiveAction]
    public function openEditModal(#[LiveArg] int $id): void
    {
        $this->editingUser = $this->userRepository->find($id);
        $this->resetForm();
    }

    #[LiveAction]
    public function openDeleteModal(#[LiveArg] int $id): void
    {
        $this->deletingUser = $this->userRepository->find($id);
    }

    protected function instantiateForm(): FormInterface
    {
        return $this->formFactory->create(UserEditFormType::class, $this->editingUser);
    }

    public function search(): void
    {
        $this->users = [];
        $this->cursor = null;

        $this->loadUsers();
        $this->updateUsersCount();
    }

    #[LiveAction]
    public function update(): void
    {
        $this->submitForm();

        if ($this->getForm()->isValid() === true)
        {
            $this->entityManager->flush();

            $index = array_search(
                array_filter($this->users, fn($u) => $u->getId() === $this->editingUser->getId())[0] ?? null,
                $this->users,
                true
            );

            if ($index !== false && $this->editingUser)
            {
                $this->users[$index] = $this->editingUser;
            }

            $this->editingUser = null;
        }
    }

    #[LiveAction]
    public function delete(): void
    {
        $user = $this->deletingUser;

        $this->entityManager->remove($user);
        $this->entityManager->flush();
        $this->usersCount--;
        $this->users = array_values(array_filter($this->users, fn ($u) => $u !== $user));

        $missing = self::PER_PAGE - count($this->users);

        if ($missing > 0)
        {
            $lastId = empty($this->users) ? ($this->cursor ?? 0) : end($this->users)->getId();
            $this->loadUsers($lastId, $missing);
        }

        $this->deletingUser = null;
    }
}
