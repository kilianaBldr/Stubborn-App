<?php

namespace App\Controller;

use App\Entity\Sweatshirt;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProductsController extends AbstractController
{
    #[Route('/products', name: 'app_products')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {   
        //Récupération du filtre Prix (par default: 'all')
        $priceFilter = $request->query->get('price_filter', 'all');
       
        //Création de la requete 
        $queryBuilder = $entityManager->getRepository(Sweatshirt::class)->createQueryBuilder('s');

            //Si un filtre de prix est appliqué, on ajoute les conditions a la requete 
            if($priceFilter !== 'all') {
                [$minPrice, $maxPrice] = explode('-', $priceFilter);
                $queryBuilder->where('s.price BETWEEN :min AND :max')
                ->setParameter('min', (int)$minPrice)
                ->setParameter('max', (int)$maxPrice);
            }

            //Execution de la requete 
            $sweatshirts = $queryBuilder->getQuery()->getResult();
            
            //Rendu de la page Twig avec les sweatshirts  filtré et le filtre de prix
            return $this->render('products/index.html.twig', [
                'sweatshirts' => $sweatshirts,
                'priceFilter' => $priceFilter,
            ]);
        }
    }
