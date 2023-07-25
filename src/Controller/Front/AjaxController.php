<?php

namespace App\Controller\Front;

use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

#[Route('/ajax', name: 'app_ajax_')]
class AjaxController extends AbstractController
{

    public const CART_ITEMS = 'cartItems';

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


    #[Route('/add-game-to-cart/{id}', name: 'add_game_to_cart')]
    public function addGameToCart(
        SessionInterface $session,
        string $id
    ): JsonResponse {
        $currentIds = [];
        if ($session->has(self::CART_ITEMS)) {
            $currentIds = $session->get(self::CART_ITEMS);
        }
        if (false === in_array($id, $currentIds)) {
            $currentIds[] = $id;
            $session->set(self::CART_ITEMS, $currentIds);
            return new JsonResponse(['added' => true]);
        }
        return new JsonResponse(['added' => false]);
    }

}
