<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\GameRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class GameExtension extends AbstractExtension
{

    public function getFunctions(): array
    {
        return [
            new TwigFunction('getMostPlayedGames', [
                GameRuntime::class,
                'getMostPlayedGames'
            ]),
        ];
    }

}
