<?php

namespace App\Controller\Front;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/profil', name: 'app_profile_')]
class UserController extends AbstractController
{

    public function __construct(
        private UserRepository $userRepository,
        private TranslatorInterface $translator,
    ) { }

    /**
     * @throws NonUniqueResultException
     */
    #[Route('/{name}', name: 'show')]
    public function show(string $name): Response {
        $user = $this->userRepository->findOneFullBy($name);

        if ($user === null) {
            $this->addFlash(
                'danger',
                $this->translator->trans('pages.error')
            );
            return $this->redirectToRoute('app_home');
        }

        return $this->render('front/pages/user/show.html.twig', [
            'user' => $user,
        ]);
    }

}
