<?php

namespace App\Controller\Back;

use App\Repository\GameRepository;
use App\Repository\ReviewRepository;
use App\Repository\UserOwnGameRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{

    #[Route('/admin', name: 'app_admin_index')]
    public function home(
        UserRepository $userRepository,
        GameRepository $gameRepository,
        ReviewRepository $reviewRepository,
        UserOwnGameRepository $userOwnGameRepository
    ): Response {
        return $this->render('back/pages/dashboard.html.twig', [
            'users' => $userRepository->findBy([], ['createdAt' => 'DESC'], 4),
            'games' => $gameRepository->findLastBoughtGames(),
            'reviews' => $reviewRepository->findBy([], ['createdAt' => 'DESC'], 4),
            'benefits' => $userOwnGameRepository->findTotalBenefit(),
            'lastBenefitByYear' => $userOwnGameRepository->findBenefitForLastYear(),
        ]);
    }

}
