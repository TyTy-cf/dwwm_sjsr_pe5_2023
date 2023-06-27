<?php


namespace App\Controller\Front;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomeController.php
 *
 * @author Kevin Tourret
 */
class HomeController extends AbstractController
{

    #[Route('/', name: 'app_home')]
    public function home(): Response {
        return $this->render('front/pages/home.html.twig', []);
    }

}
