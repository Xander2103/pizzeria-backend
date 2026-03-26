<?php
//ProductController.php
//nodig voor producten tonen
declare(strict_types=1);

namespace App\Controller;

use App\Service\CartService;
use App\Service\ProductService;
use Twig\Environment;

class ProductController
{
    public function index(Environment $twig): void
    {
        $productService = new ProductService();
        $products = $productService->getAll();

        $cartService = new CartService();
        $cart = $cartService->getCart();

        $cartLines = [];
        foreach ($cart as $key => $line) {
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

            $cartLines[] = [
                'key' => (string)$key,
                'product' => $product,
                'qty' => $qty,
                'extras' => $extras,
            ];
        }

        echo $twig->render('products.twig', [
            'products' => $products,
            'cartLines' => $cartLines,
        ]);
    }
}
