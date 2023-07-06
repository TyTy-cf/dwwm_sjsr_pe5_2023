<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\CategoryRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class CategoryExtension extends AbstractExtension
{

    public function getFunctions(): array
    {
        return [
            new TwigFunction('getMostSoldCategories', [
                CategoryRuntime::class,
                'getMostSoldCategories'
            ]),
        ];
    }

}
