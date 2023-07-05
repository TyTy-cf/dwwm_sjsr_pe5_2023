<?php

namespace App\Controller\Front;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/profil', name: 'app_profile_')]
class UserController extends AbstractController
{

    public function __construct(
        private UserRepository $userRepository,
    ) { }

    #[Route('/{name}', name: 'show')]
    public function show(string $name): Response {
        $user = $this->userRepository->findOneFullBy($name);

        return $this->render('front/pages/user/show.html.twig', [
            'user' => $user,
        ]);
    }

}
