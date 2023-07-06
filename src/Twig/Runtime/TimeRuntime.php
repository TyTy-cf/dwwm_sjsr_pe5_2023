<?php

namespace App\Twig\Runtime;

use App\Entity\User;
use App\Entity\UserOwnGame;
use App\Service\TimeConverterService;
use Twig\Extension\RuntimeExtensionInterface;

class TimeRuntime implements RuntimeExtensionInterface
{

    public function __construct(
        private TimeConverterService $timeConverterService
    ) { }

    /**
     * @param int $timeInSeconds
     * @return string
     */
    public function getTimeConverter(int $timeInSeconds): string {
        return $this->timeConverterService->getDurationToHM($timeInSeconds);
    }

    /**
     * @param User $user
     * @return string
     */
    public function getUserTimeConverter(User $user): string {
        $totalGameTime = 0;
        foreach ($user->getUserOwnGames() as $userOwnGame) {
            /** @var UserOwnGame $userOwnGame */
            $totalGameTime += $userOwnGame->getGameTime();
        }
        return $this->timeConverterService->getDurationToHM($totalGameTime);
    }

}
