<?php
//HomeController.php
//nodig voor homepage, producten tonen en aantal in winkelmand tonen
declare(strict_types=1);

namespace App\Controller;

use App\Service\ProductService;
use App\Service\CartService;
use Twig\Environment;

class HomeController
{
    public function index(Environment $twig): void
    {
        $productService = new ProductService();
        $cartService = new CartService();

        $products = $productService->getAll();
        $cart = $cartService->getCart();

        // totaal aantal items in cart (som van qty)
        $cartCount = 0;
        foreach ($cart as $qty) {
            $cartCount += (int)$qty;
        }

        echo $twig->render('home.twig', [
            'products' => $products,
            'cartCount' => $cartCount,
            'cart' => $cart, // zodat we per product kunnen tonen “in mand: X”
        ]);
    }
}