<?php

namespace App\Controller\Front;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route(name: 'app_profile_')]
class UserController extends AbstractController
{

    public function __construct(
        private UserRepository $userRepository,
        private TranslatorInterface $translator,
    ) { }

    /**
     * @throws NonUniqueResultException
     */
    #[Route('/profil/{name}', name: 'show')]
    public function show(Request $request, string $name): Response {
        $user = $this->userRepository->findOneFullBy($name);

        if ($user === null) {
            $this->addFlash(
                'danger',
                $this->translator->trans('pages.error')
            );
            return $this->redirectToRoute('app_home');
        }

        $form = $this->createForm(
            UserType::class,
            $user,
            ['is_creation' => false]
        );
        $form->handleRequest($request);

        

        return $this->render('front/pages/user/show.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/inscription', name: 'register')]
    public function register(Request $request): Response {
        $form = $this->createForm(UserType::class, new User());
        $form->handleRequest($request); // => traite les $_POST du form

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userRepository->save($form->getData(), true);
            $this->addFlash(
                'success',
                $this->translator->trans('pages.user.success_create')
            );
            return $this->redirectToRoute('app_home');
        }

        return $this->render('front/pages/user/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
