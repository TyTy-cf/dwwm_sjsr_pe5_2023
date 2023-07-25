<?php

namespace App\Controller\Front;

use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

#[Route('/ajax', name: 'app_ajax_')]
class AjaxController extends AbstractController
{

    #[Route('/searched-item/{searched}', name: 'searched_items')]
    public function getSearchedItems(
        GameRepository $gameRepository,
        string $searched
    ): JsonResponse {
        return new JsonResponse([
            'html' => $this->render('front/partial/_search_bar_content.html.twig', [
                'games' => $gameRepository->findByApproxSearch($searched),
                'users' => '',
                'categories' => '',
            ])
        ]);
    }

}
