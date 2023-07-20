<?php

namespace App\Controller\Front;

use App\Repository\GameRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

#[Route('/ajax', name: 'app_ajax_')]
class AjaxController
{

    #[Route('/searched-item/{searched}', name: 'searched_items')]
    public function getSearchedItems(
        GameRepository $gameRepository,
        Environment $twig,
        string $searched
    ): JsonResponse {
        return new JsonResponse([
            'html' => $twig->render('front/partial/_search_bar_content.html.twig', [
                'games' => $gameRepository->findByApproxSearch($searched),
                'users' => '',
                'categories' => '',
            ])
        ]);
    }

}
