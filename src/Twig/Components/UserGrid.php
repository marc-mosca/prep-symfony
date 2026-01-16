<?php

namespace App\Twig\Components;

use App\Repository\UserRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent("user-grid", template: "components/user-grid.html.twig")]
class UserGrid
{

    use DefaultActionTrait;

    private const PER_PAGE = 10;

    #[LiveProp]
    public int $page = 1;

    public function __construct(private readonly UserRepository $userRepository)
    {
    }

    public function getUsers(): array
    {
        return $this->userRepository->paginate($this->page, self::PER_PAGE);
    }

    #[LiveAction]
    public function more(): void
    {
        $this->page++;
    }

    public function hasMore(): bool
    {
        return $this->userRepository->count() > ($this->page * self::PER_PAGE);
    }

}
