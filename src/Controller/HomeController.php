<?php

namespace App\Controller;

use App\Repository\SweatshirtRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(SweatshirtRepository $sweatshirtRepository): Response
    {
        $featuredSweatshirts = $sweatshirtRepository->findFeaturedSweatshirts();

        return $this->render('home/index.html.twig', [
            'featuredSweatshirts' => $featuredSweatshirts,
            'controller_name' => 'HomeController',
        ]);
    }
}
