<?php

namespace App\Controller;

use App\Entity\Sweatshirt;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PublicSweatshirtController extends AbstractController
{
    #[Route('/sweatshirt/{id}', name: 'sweatshirt_detail')]
    public function index(Sweatshirt $sweatshirt): Response
    {
        return $this->render('sweatshirt/detail.html.twig', [
            'sweatshirt' => $sweatshirt,
        ]);
    }
}
