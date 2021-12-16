<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    #[Route('/page/{slug}', name: 'front_page', methods: ['GET'])]
    public function index(string $slug): Response
    {
        return $this->render('front/page/index.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }
}
