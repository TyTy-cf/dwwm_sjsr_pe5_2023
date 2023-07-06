<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\TimeRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class TimeExtension extends AbstractExtension
{

    public function getFilters(): array
    {
        return [
            new TwigFilter('time_converter', [
                TimeRuntime::class,
                'getTimeConverter'
            ]),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('getUserTimeConverter', [
                TimeRuntime::class,
                'getUserTimeConverter'
            ])
        ];
    }

}
