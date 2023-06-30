<?php

namespace App\Controller\Api\UserOwnGame;

use App\Entity\UserOwnGame;
use App\Repository\GameRepository;
use App\Repository\UserTestRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PostAction extends AbstractController
{

    public function handle(
        Request                $request,
        GameRepository         $gameRepository,
        UserTestRepository     $userRepository,
        EntityManagerInterface $em
    ): JsonResponse {
        $json = json_decode($request->getContent(), true);

        $game = $gameRepository->findOneBy(['slug' => $json['game']['slug']]);

        if (!$game) {
            return throw new NotFoundHttpException('Le jeu envoyé existe pas');
        }

        $user = $userRepository->findOneBy(['name' => $json['user']['name']]);

        if (!$user) {
            return throw new NotFoundHttpException('Le user existe pas');
        }

        $userOwnGame = (new UserOwnGame())
            ->setGame($game)
            ->setUser($user);

        $em->persist($userOwnGame);
        $em->flush();

        return new JsonResponse(['response' => 'OK']);
    }

}
