<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\CartService;

#[Route('/cart')]
class CartController extends AbstractController
{
    private CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    #[Route('/', name: 'app_cart')]
    public function index(): Response
    {
        return $this->render('cart/index.html.twig', [
            'cart' => $this->cartService->getCart(),
            'total' => $this->cartService->getTotal(),
        ]);
    }

    #[Route('/add/{id}', name: 'cart_add', methods: ['POST'])]
    public function add(int $id, Request $request): Response
    {
        $size = $request->request->get('size');

        if (!$size) {
            $this->addFlash('danger', 'Veuillez sélectionner une taille.');
            return $this->redirectToRoute('sweatshirt_detail', ['id' => $id]);
        }

        $this->cartService->add($id, $size);
        $this->addFlash('success', 'Sweatshirt ajouté au panier.');
        return $this->redirectToRoute('app_cart');
    }

    #[Route('/remove/{id}/{size}', name: 'cart_remove')]
    public function remove(int $id, string $size): Response
    {
        $this->cartService->remove($id, $size);
        $this->addFlash('success', 'Sweatshirt supprimé du panier.');
        return $this->redirectToRoute('app_cart');
    }

    #[Route('/clear', name: 'cart_clear')]
    public function clear(): Response
    {
        $this->cartService->clear();
        $this->addFlash('success', 'Panier vidé.');
        return $this->redirectToRoute('app_cart');
    }
}