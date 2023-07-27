<?php

namespace App\Controller\Back;

use App\Entity\Game;
use App\Form\GameType;
use App\Repository\GameRepository;
use App\Service\SlugService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/jeu', name: 'app_admin_game_')]
class GameController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(GameRepository $gameRepository): Response
    {
        return $this->render('back/pages/game/index.html.twig', [
            'games' => $gameRepository->findBy([], ['publishedAt' => 'DESC']),
        ]);
    }

    #[Route('/nouveau', name: 'new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        GameRepository $gameRepository,
        SlugService $textService
    ): Response
    {
        $game = new Game();
        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $gameRepository->save($game, true);

            return $this->redirectToRoute('app_admin_game_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/pages/game/new.html.twig', [
            'game' => $game,
            'form' => $form,
        ]);
    }

    #[Route('/{slug}', name: 'show', methods: ['GET'])]
    public function show(Game $game): Response
    {
        return $this->render('back/pages/game/show.html.twig', [
            'game' => $game,
        ]);
    }

    #[Route('/{slug}/modifier', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Game $game,
        GameRepository $gameRepository,
        SlugService $textService
    ): Response
    {
        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $gameRepository->save($game, true);

            return $this->redirectToRoute('app_admin_game_index');
        }

        return $this->renderForm('back/pages/game/edit.html.twig', [
            'game' => $game,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Game $game, GameRepository $gameRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$game->getId(), $request->request->get('_token'))) {
            $gameRepository->remove($game, true);
        }

        return $this->redirectToRoute('app_admin_game_index');
    }
}
