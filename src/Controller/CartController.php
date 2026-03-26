<?php
//CartController.php
//nodig voor winkelmand tonen, producten toevoegen, verwijderen en leegmaken
declare(strict_types=1);

namespace App\Controller;

use App\Service\CartService;
use App\Service\ProductService;
use Twig\Environment;

class CartController
{
    public function index(Environment $twig): void
    {
        $cartService = new CartService();
        $productService = new ProductService();

        //Winkelmand ophalen
        $cart = $cartService->getCart();

        //omzetten in lines met productgegevens
        $lines = [];
        foreach ($cart as $key => $line) {
            if (!is_array($line)) {
                continue;
            }

            //data ophalen uit $line
            $productId = (int)($line['product_id'] ?? 0);
            $qty = (int)($line['qty'] ?? 0);
            $extras = trim((string)($line['extras'] ?? ''));

            if ($productId <= 0 || $qty <= 0) {
                continue;
            }

            $product = $productService->getById($productId);
            if ($product === null) {
                continue;
            }
            //line ophalen voor twig: key, product, qty, extras
            $lines[] = [
                'key' => (string)$key,
                'product' => $product,
                'qty' => $qty,
                'extras' => $extras,
            ];
        }

        echo $twig->render('cart.twig', [
            'lines' => $lines,
        ]);
    }
    //toevoegen aan winkelmand
    public function add(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            header('Location: ?page=products');
            exit;
        }

        $productId = (int)($_POST['product_id'] ?? 0);
        $extras = trim((string)($_POST['extras'] ?? ''));

        if ($productId > 0) {
            (new CartService())->add($productId, $extras);
        }

        header('Location: ?page=products');
        exit;
    }
    //verwijderen uit winkelmand
    public function remove(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            header('Location: ?page=products');
            exit;
        }

        // BELANGRIJK: we werken nu met "key" (productId|extras)
        $key = trim((string)($_POST['key'] ?? ''));
        if ($key !== '') {
            (new CartService())->removeOne($key);
        }

        header('Location: ?page=products');
        exit;
    }
    //leegmaken winkelmand
    public function clear(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            header('Location: ?page=products');
            exit;
        }

        (new CartService())->clear();

        header('Location: ?page=products');
        exit;
    }
}