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

        dump(new JsonResponse([
            'html' => $this->renderView('front/partial/_search_bar_content.html.twig', [
                'games' => $gameRepository->findByApproxSearch($searched),
            ])
        ]));

        return new JsonResponse([
            'html' => $this->renderView('front/partial/_search_bar_content.html.twig', [
                'games' => $gameRepository->findByApproxSearch($searched),
            ])
        ]);
    }

    #[Route('/add-game-to-cart/{id}', name: 'add_game_to_cart')]
    public function addGameToCart(string $id, SessionInterface $session): JsonResponse {
        $currentIds = [];
        if ($session->has(self::CART_ITEMS)) {
            $currentIds = $session->get(self::CART_ITEMS);
        }
        if (false === in_array($id, $currentIds)) {
            $currentIds[] = $id;
            $session->set(self::CART_ITEMS, $currentIds);
        }
        return new JsonResponse(['nbCartElement' => sizeof($currentIds)]);
    }

    #[Route('/empty-cart', name: 'empty_cart')]
    public function emptyCart(SessionInterface $session): JsonResponse {
        if ($session->has(self::CART_ITEMS)) {
            $session->remove(self::CART_ITEMS);
        }
        return new JsonResponse(['nbCartElement' => 0]);
    }

    #[Route('/cart-size', name: 'cart_size')]
    public function cartSize(SessionInterface $session): JsonResponse {
        if ($session->has(self::CART_ITEMS)) {
            $games = $session->get(self::CART_ITEMS);
            return new JsonResponse(['nbCartElement' => sizeof($games)]);
        }
        return new JsonResponse(['nbCartElement' => 0]);
    }

}
