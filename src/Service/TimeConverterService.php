<?php

namespace App\Service;

class TimeConverterService
{

    public function getDurationToHM(int $duration): string {
        $hours = floor($duration / 3600);
        $minutes = ($duration % 60);
        if ($minutes < 10) {
            $minutes = '0' . $minutes;
        }
        if ($hours < 10) {
            $hours = '0' . $hours;
        }
        return $hours. 'h' . $minutes;
    }

}
