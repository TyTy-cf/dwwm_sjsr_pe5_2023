<?php

namespace App\Twig\Runtime;

use App\Repository\GameRepository;
use Twig\Extension\RuntimeExtensionInterface;

class GameRuntime implements RuntimeExtensionInterface
{
    public function __construct(
        private GameRepository $gameRepository
    ) { }

    public function getMostPlayedGames(int $limit = 3): array
    {
        return $this->gameRepository->findMostPlayedGames($limit);
    }
}
