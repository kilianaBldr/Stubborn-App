<?php

namespace App\Controller;

use App\Entity\Sweatshirt;
use App\Repository\SweatshirtRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProductsController extends AbstractController
{
    #[Route('/products', name: 'app_products')]
    public function index(Request $request, SweatshirtRepository $sweatshirtRepository): Response
    {
        // Récupération des valeurs du filtre depuis l'URL
        $minPrice = $request->query->get('minPrice');
        $maxPrice = $request->query->get('maxPrice');

        // Création de la requête dynamique
        $query = $sweatshirtRepository->createQueryBuilder('s');

        if ($minPrice !== null && $maxPrice !== null) {
            $query->where('s.price BETWEEN :minPrice AND :maxPrice')
                  ->setParameter('minPrice', $minPrice)
                  ->setParameter('maxPrice', $maxPrice);
        }

        $sweatshirts = $query->getQuery()->getResult();

        // Vérification des produits récupérés
        // dd($sweatshirts); // Active cette ligne pour déboguer si besoin

        return $this->render('products/index.html.twig', [
            'sweatshirts' => $sweatshirts,
        ]);
    }
}