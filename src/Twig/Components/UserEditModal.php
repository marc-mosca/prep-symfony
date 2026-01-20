<?php

namespace App\Twig\Components;

use App\Entity\User;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent("user-edit-modal", template: "components/user-edit-modal.html.twig")]
class UserEditModal
{

    public User $user;

    public array $attributes = [];

}
