<?php

namespace App\Service;

class ExcerptService
{

    public function excerpt(string $text): string {
        if (strlen($text) <= 50) return $text;

        return substr($text, 0, 50) . '...';
    }

}
