<?php

namespace App\Controller\Front;

use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route('/panier', name: 'app_cart_index')]
    public function index(
        SessionInterface $session,
        GameRepository $gameRepository,
    ): Response
    {
        $gameIds = $session->get(AjaxController::CART_ITEMS);

        return $this->render('front/pages/cart/index.html.twig', [
            'games' => $gameRepository->findBy(['id' => $gameIds]),
        ]);
    }
}
