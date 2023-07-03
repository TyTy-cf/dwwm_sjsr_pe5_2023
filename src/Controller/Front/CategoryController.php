<?php

namespace App\Controller\Front;

use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/categorie', name: 'app_category_')]
class CategoryController extends AbstractController
{

    #[Route('/{id}', name: 'show')]
    public function show(string $id): Response {
        dd($id);
    }

    #[Route('/creer', name: 'new')]
    public function new(Request $request): Response {
        $form = $this->createForm(CategoryType::class, new Category());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            dd($form->getData());
        }

        return $this->render('front/pages/category/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
