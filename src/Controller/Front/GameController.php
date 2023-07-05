<?php

namespace App\Controller\Front;

use App\Entity\Category;
use App\Entity\Game;
use App\Repository\CategoryRepository;
use App\Repository\GameRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
            $this->translator->trans('pages.game.show.error')
        );
        return $this->redirectToRoute('app_home');
    }

    private function show(Game $game): Response
    {
        $relatedGames = $this->gameRepository->findByRelatedCategory($game, 9);

        return $this->render('front/pages/game/show.html.twig', [
            'game' => $game,
            'relatedGames' => $relatedGames,
        ]);
    }

    private function listByCategory(Category $category): Response {
        dd($category);
    }

}
