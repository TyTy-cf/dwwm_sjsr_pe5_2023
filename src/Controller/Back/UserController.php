<?php

namespace App\Controller\Back;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

    #[Route('/admin/utilisateurs', name: 'app_admin_user_index')]
    public function home(UserRepository $userRepository): Response {
        return $this->render('back/pages/user/index.html.twig', [
            'users' => $userRepository->findAllFull(),
        ]);
    }

}
