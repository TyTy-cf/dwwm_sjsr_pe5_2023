<?php

namespace App\Controller\Back;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use App\Service\FileUploader;
use App\Service\TextService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/categorie', name: 'app_admin_category_')]
class CategoryController extends AbstractController
{

    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('back/pages/category/index.html.twig', [
            'categories' => $categoryRepository->findBy([], ['name' => 'ASC']),
        ]);
    }

    #[Route('/nouvelle', name: 'new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        CategoryRepository $categoryRepository,
        FileUploader $fileUploader
    ): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('image')->getData() !== null) {
                $category->setImage(
                    $fileUploader->uploadFile(
                        $form->get('image')->getData(), // => UploadedFile
                        '/category'
                    )
                );
            }
            $categoryRepository->save($category, true);

            return $this->redirectToRoute('app_admin_category_index');
        }

        return $this->renderForm('back/pages/category/new.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    #[Route('/{slug}', name: 'show', methods: ['GET'])]
    public function show(Category $category): Response
    {
        return $this->render('back/pages/category/show.html.twig', [
            'category' => $category,
        ]);
    }

    #[Route('/{slug}/modifier', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Category $category,
        CategoryRepository $categoryRepository,
        FileUploader $fileUploader
    ): Response
    {
        $form = $this->createForm(CategoryType::class, $category, ['is_edit' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('image')->getData() !== null) {
                $category->setImage(
                    $fileUploader->uploadFile(
                        $form->get('image')->getData(),
                        '/category'
                    )
                );
            }
            $categoryRepository->save($category, true);

            return $this->redirectToRoute('app_admin_category_index');
        }

        return $this->renderForm('back/pages/category/edit.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Category $category, CategoryRepository $categoryRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $categoryRepository->remove($category, true);
        }

        return $this->redirectToRoute('app_admin_category_index');
    }
}
