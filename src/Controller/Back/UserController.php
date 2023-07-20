<?php

namespace App\Controller\Back;

use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

    #[Route('/admin/utilisateurs', name: 'app_admin_user_index')]
    public function home(
        UserRepository $userRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {

        $users = $paginator->paginate(
            $userRepository->getQb(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('back/pages/user/index.html.twig', [
            'users' => $users,
        ]);
    }

}

