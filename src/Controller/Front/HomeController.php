<?php


namespace App\Controller\Front;


use App\Entity\UserTest;
use App\Repository\CategoryRepository;
use App\Repository\GameRepository;
use App\Repository\ReviewRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomeController.php
 *
 * @author Kevin Tourret
 */
class HomeController extends AbstractController
{

    public function __construct(
      private GameRepository $gameRepository,
      private ReviewRepository $reviewRepository,
      private CategoryRepository $categoryRepository
    ) { }

    #[Route('/', name: 'app_home')]
    public function home(): Response {

        $lastMonthDate = new DateTime();
        $lastMonthDate->modify('-1 month');

        dd($this->categoryRepository->getMostSoldCategories());

        return $this->render('front/pages/home.html.twig', [
            'tendances' => $this->gameRepository->findTendances(9, true, $lastMonthDate),
            'lastGames' => $this->gameRepository->findBy([], ['publishedAt' => 'DESC'], 9),
            'bestSellers' => $this->gameRepository->findTendances(9),
            'lastReviews' => $this->reviewRepository->findReviewsBy(['createdAt' => 'DESC'], 4),
//            'lastReviews' => $this->reviewRepository->findBy([], ['createdAt' => 'DESC'], 4),
        ]);
    }

}
