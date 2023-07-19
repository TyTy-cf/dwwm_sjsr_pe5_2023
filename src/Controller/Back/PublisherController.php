<?php

namespace App\Controller\Back;

use App\Entity\Publisher;
use App\Form\PublisherType;
use App\Repository\PublisherRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/editeur', name: 'app_admin_publisher_')]
class PublisherController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(PublisherRepository $publisherRepository): Response
    {
        return $this->render('back/pages/publisher/index.html.twig', [
            'publishers' => $publisherRepository->findBy([], ['name' => 'ASC']),
        ]);
    }

    #[Route('/nouveau', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, PublisherRepository $publisherRepository): Response
    {
        $publisher = new Publisher();
        $form = $this->createForm(PublisherType::class, $publisher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $publisherRepository->save($publisher, true);

            return $this->redirectToRoute('app_admin_publisher_index');
        }

        return $this->renderForm('back/pages/publisher/new.html.twig', [
            'publisher' => $publisher,
            'form' => $form,
        ]);
    }

    #[Route('/{slug}', name: 'show', methods: ['GET'])]
    public function show(Publisher $publisher): Response
    {
        return $this->render('back/pages/publisher/show.html.twig', [
            'publisher' => $publisher,
        ]);
    }

    #[Route('/{slug}/modifier', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Publisher $publisher, PublisherRepository $publisherRepository): Response
    {
        $form = $this->createForm(PublisherType::class, $publisher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $publisherRepository->save($publisher, true);

            return $this->redirectToRoute('app_admin_publisher_index');
        }

        return $this->renderForm('back/pages/publisher/edit.html.twig', [
            'publisher' => $publisher,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Publisher $publisher, PublisherRepository $publisherRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$publisher->getId(), $request->request->get('_token'))) {
            $publisherRepository->remove($publisher, true);
        }

        return $this->redirectToRoute('app_admin_publisher_index');
    }
}
