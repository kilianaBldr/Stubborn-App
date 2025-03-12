<?php

namespace App\Controller;

use App\Entity\Sweatshirt;
use App\Service\StripeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
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

    #[Route('/checkout', name:'app_cart_checkout')]
    public function checkout(EntityManagerInterface $entityManager, SessionInterface $session, StripeService $stripeService): Response
    {
        $cart = $session->get('cart', []);
        $lineItems = [];

        foreach($cart as $item){
            $sweatshirt = $entityManager->getRepository(Sweatshirt::class)->find($item['id']);
            if($sweatshirt){
                $lineItems[]=[
                    'price_data'=>[
                        'currency'=>'eur',
                        'product_data'=>[
                            'name'=>$sweatshirt->getName() . ' - Taille ' . $item['size'],
                        ],
                        'unit_amount'=>$sweatshirt->getPrice() * 100,
                    ],
                    'quantity'=> 1,
                ];
            }
        }

        $successUrl = $this->generateUrl('app_cart_success', [], \Symfony\Component\Routing\Generator\UrlGeneratorInterface::ABSOLUTE_URL);
        $cancelUrl = $this->generateUrl('app_cart_cancel', [], \Symfony\Component\Routing\Generator\UrlGeneratorInterface::ABSOLUTE_URL);

        $sessionStripe = $stripeService->createCheckoutSession($lineItems, $successUrl, $cancelUrl);

        return $this->redirect($sessionStripe->url, 303);
    }

    #[Route('/success', name: 'app_cart_success')]
public function success(SessionInterface $session, EntityManagerInterface $entityManager): Response
{
    $cart = $session->get('cart', []);
    $sizeMapping = [
        'XS' => 'StockXS',
        'S' => 'StockS',
        'M' => 'StockM',
        'L' => 'StockL',
        'XL' => 'StockXL'
    ];

    foreach ($cart as $item) {
        $sweatshirt = $entityManager->getRepository(Sweatshirt::class)->find($item['id']);
        if ($sweatshirt && isset($sizeMapping[$item['size']])) {
            $stockGetter = 'get' . $sizeMapping[$item['size']];
            $stockSetter = 'set' . $sizeMapping[$item['size']];

            $currentStock = call_user_func([$sweatshirt, $stockGetter]);

            if ($currentStock > 0) {
                call_user_func([$sweatshirt, $stockSetter], $currentStock - 1);
            } else {
                return $this->render('cart/stock_error.html.twig', [
                    'message' => "Le stock en taille {$item['size']} de cet article n'est plus disponible"
                ]);
            }
        }
    }
        $entityManager->flush();
        $session->remove('cart');
        $homeUrl = $this->generateUrl('app_home', [], \Symfony\Component\Routing\Generator\UrlGeneratorInterface::ABSOLUTE_URL);
        return $this->render('cart/success.html.twig', [
            'homeUrl' => $homeUrl
        ]);
    }

    #[Route('/cancel', name: 'app_cart_cancel')]
    public function cancel(): Response
    {
        $homeUrl = $this->generateUrl('app_home', [], \Symfony\Component\Routing\Generator\UrlGeneratorInterface::ABSOLUTE_URL);
        // Display a cancellation message or redirect to cart
        return $this->render('cart/cancel.html.twig', [
            'homeUrl' => $homeUrl
        ]);
    }

}