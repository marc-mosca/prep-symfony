<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('icon')]
class Icon
{

    public string $name;

    public array $attributes = [];

}
