<?php

namespace App\Controller\Front;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

#[Route('/ajax', name: 'app_ajax_')]
class AjaxController
{

    #[Route('/searched-item', name: 'searched_items')]
    public function getSearchedItems(
        Environment $twig
    ): JsonResponse {
        return new JsonResponse([
            'html' => $twig->render('', [
                'var' => 'toto'
            ])
        ]);
    }

}
