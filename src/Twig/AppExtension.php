<?php

namespace App\Twig;

use Twig\Attribute\AsTwigFunction;

class AppExtension
{

    #[AsTwigFunction('icon', isSafe: ["html"])]
    public function svgIcon(string $name, array $attributes = []): string
    {
        $html = "";

        foreach ($attributes as $key => $value) {
            $html .= sprintf(' %s="%s"', htmlspecialchars($key), htmlspecialchars($value));
        }

        return <<<HTML
        <svg {$html}>
          <use href="/sprite.svg?logo#{$name}"></use>
        </svg>
        HTML;
    }

}
