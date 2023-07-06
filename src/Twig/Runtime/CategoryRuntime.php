<?php

namespace App\Twig\Runtime;

use App\Repository\CategoryRepository;
use Twig\Extension\RuntimeExtensionInterface;

class CategoryRuntime implements RuntimeExtensionInterface
{

    public function __construct(
        private CategoryRepository $categoryRepository
    ) { }

    public function getMostSoldCategories(int $limit = 3): array
    {
        return $this->categoryRepository->getMostSoldCategories($limit);
    }

}
