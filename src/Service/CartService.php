<?php
//CartService.php
//nodig voor winkelmand beheren (toevoegen, verwijderen, leegmaken)
declare(strict_types=1);

namespace App\Service;

class CartService
{
    private SessionService $session;

    public function __construct()
    {
        $this->session = new SessionService();
    }

   
    public function getCart(): array
    {
        $cart = $this->session->get('cart', []);
        return is_array($cart) ? $cart : [];
    }

    private function normalizeExtras(string $extras): string
    {
        $extras = trim($extras);
        $extras = preg_replace('/\s+/', ' ', $extras) ?? $extras;
        return mb_strtolower($extras);
    }

    private function makeKey(int $productId, string $extras): string
    {
        return $productId . '|' . $this->normalizeExtras($extras);
    }

    public function add(int $productId, string $extras = ''): void
    {
        $cart = $this->getCart();

        $key = $this->makeKey($productId, $extras);

        if (!isset($cart[$key])) {
            $cart[$key] = [
                'product_id' => $productId,
                'qty' => 0,
                'extras' => trim($extras), // originele tekst bewaren
            ];
        }

        $cart[$key]['qty'] = (int)$cart[$key]['qty'] + 1;

        $this->session->set('cart', $cart);
    }

    public function removeOne(string $key): void
    {
        $cart = $this->getCart();

        if (!isset($cart[$key])) {
            return;
        }

        $qty = (int)$cart[$key]['qty'];

        if ($qty <= 1) {
            unset($cart[$key]);
        } else {
            $cart[$key]['qty'] = $qty - 1;
        }

        $this->session->set('cart', $cart);
    }

    public function clear(): void
    {
        $this->session->set('cart', []);
    }
}