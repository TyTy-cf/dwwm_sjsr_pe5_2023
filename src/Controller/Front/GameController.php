<?php

namespace App\Controller\Front;

use App\Entity\Category;
use App\Entity\Game;
use App\Repository\CategoryRepository;
use App\Repository\CountryRepository;
use App\Repository\GameRepository;
use Doctrine\ORM\NonUniqueResultException;
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
        private CategoryRepository $categoryRepository
    ) { }

    /**
     * @throws NonUniqueResultException
     */
    #[Route('/{slug}', name: 'redirect')]
    public function handleRedirection(string $slug): Response {
        $game = $this->gameRepository->findFullOneBy($slug);

        if ($game !== null) {
            return $this->show($game);
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

    private function show(Game $game): Response
    {
        $relatedGames = $this->gameRepository->findByRelatedCategory($game, 6);

        return $this->render('front/pages/game/show.html.twig', [
            'game' => $game,
            'relatedGames' => $relatedGames,
        ]);
    }

    private function listByCategory(Category $category): Response {
        return $this->render('front/pages/game/list_by_category.html.twig', [
            'games' => $this->gameRepository->findByCategory($category),
            'category' => $category,
        ]);
    }

}
