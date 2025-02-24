<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use App\Repository\SweatshirtRepository;

class CartService
{
    private $session;
    private SweatshirtRepository $sweatshirtRepository;

    public function __construct(RequestStack $requestStack, SweatshirtRepository $sweatshirtRepository)
    {
        $this->session = $requestStack->getSession();
        $this->sweatshirtRepository = $sweatshirtRepository;
    }

    public function add(int $id, string $size): void
    {
        $cart = $this->session->get('cart', []);

        $key = $id . '_' . $size;

        if (!isset($cart[$key])) {
            $cart[$key] = ['id' => $id, 'size' => $size, 'quantity' => 1];
        } else {
            $cart[$key]['quantity']++;
        }

        $this->session->set('cart', $cart);
    }

    public function remove(int $id, string $size): void
    {
        $cart = $this->session->get('cart', []);
        $key = $id . '_' . $size;

        if (isset($cart[$key])) {
            unset($cart[$key]);
            $this->session->set('cart', $cart);
        }
    }

    public function clear(): void
    {
        $this->session->remove('cart');
    }

    public function getCart(): array
    {
        $cart = $this->session->get('cart', []);
        $cartWithData = [];

        foreach ($cart as $item) {
            $sweatshirt = $this->sweatshirtRepository->find($item['id']);
            if ($sweatshirt) {
                $cartWithData[] = [
                    'sweatshirt' => $sweatshirt,
                    'size' => $item['size'],
                    'quantity' => $item['quantity'],
                    'total_price' => $sweatshirt->getPrice() * $item['quantity']
                ];
            }
        }
        return $cartWithData;
    }

    public function getTotal(): float
    {
        return array_reduce($this->getCart(), fn($sum, $item) => $sum + $item['total_price'], 0);
    }
}