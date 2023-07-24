<?php

namespace App\Controller\Front;

use App\Entity\User;
use App\Entity\UserOwnGame;
use App\Form\UserType;
use App\Repository\UserOwnGameRepository;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
        private FileUploader $fileUploader,
        private EntityManagerInterface $em
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

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form->get('profileImage')->getData();
            if ($uploadedFile !== null) {
                $this->fileUploader->cleanUnusedFiles($user->getProfileImage());
                $user->setProfileImage(
                    $this->fileUploader->uploadFile(
                        $uploadedFile, // => Objet de type UploadedFile
                        '/user'
                    )
                );
            }
            $this->userRepository->save($user, true);
            $this->addFlash(
                'success',
                $this->translator->trans('pages.user.success_edit')
            );
        }

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
