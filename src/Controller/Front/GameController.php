<?php

namespace App\Controller\Front;

use App\Entity\Category;
use App\Entity\Game;
use App\Entity\Review;
use App\Form\ReviewType;
use App\Repository\CategoryRepository;
use App\Repository\CountryRepository;
use App\Repository\GameRepository;
use App\Repository\ReviewRepository;
use Doctrine\ORM\NonUniqueResultException;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/jeu', name: 'app_game_')]
class GameController extends AbstractController
{

    public function __construct(
        private GameRepository $gameRepository,
        private TranslatorInterface $translator,
        private CategoryRepository $categoryRepository,
        private ReviewRepository $reviewRepository,
        private PaginatorInterface $paginator
    ) { }

    /**
     * @throws NonUniqueResultException
     */
    #[Route('/{slug}', name: 'redirect')]
    public function handleRedirection(string $slug, Request $request): Response {
        $game = $this->gameRepository->findFullOneBy($slug);

        if ($game !== null) {
            return $this->show($game, $request);
        }

        $category = $this->categoryRepository->findOneBy(['slug' => $slug]);

        if ($category !== null) {
            return $this->listByCategory($category);
        }

        $this->addFlash(
            'danger',
            $this->translator->trans('pages.error')
        );
        return $this->redirectToRoute('app_home');
    }

    #[Route('/disponible/{slug}', name: 'available_by_language')]
    public function availablebByLanguage(
        string $slug, // $request->get('slug')
        CountryRepository $countryRepository
    ): Response {
        $country = $countryRepository->findOneBy(['slug' => $slug]);

        if ($country === null) {
            $this->addFlash(
                'danger',
                $this->translator->trans('pages.error')
            );
            return $this->redirectToRoute('app_home');
        }

        $games = $this->gameRepository->findByCountry($country);

        return $this->render('front/pages/game/list_by_category.html.twig', [
           'country' => $country,
           'games' => $games,
        ]);
    }

    private function show(Game $game, Request $request): Response
    {
        $review = new Review();
        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $review->setUser($this->getUser());
            $review->setGame($game);
            $this->reviewRepository->save($review, true);
        }

        $relatedGames = $this->gameRepository->findByRelatedCategory($game, 6);
        $reviews = $this->paginator->paginate(
            $this->reviewRepository->getQbByGame($game),
            $request->query->getInt('page', 1),
            8
        );

        return $this->render('front/pages/game/show.html.twig', [
            'game' => $game,
            'reviews' => $reviews,
            'relatedGames' => $relatedGames,
            'formReview' => $form->createView(),
        ]);
    }

    private function listByCategory(Category $category): Response {
        return $this->render('front/pages/game/list_by_category.html.twig', [
            'games' => $this->gameRepository->findByCategory($category),
            'category' => $category,
        ]);
    }

}
