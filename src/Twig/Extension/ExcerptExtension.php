<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\ExcerptRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class ExcerptExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('excerpt', [
                ExcerptRuntime::class,
                'excerpt'
            ]),
        ];
    }
}
