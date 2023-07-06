<?php

namespace App\Twig\Runtime;

use App\Service\ExcerptService;
use Twig\Extension\RuntimeExtensionInterface;

class ExcerptRuntime implements RuntimeExtensionInterface
{
    public function __construct(
        private ExcerptService $excerptService
    ) { }

    /**
     * @param string $value
     * @return string
     */
    public function excerpt(string $value): string
    {
        return $this->excerptService->excerpt($value);
    }
}
