<?php
// ProductController.php
declare(strict_types=1);

namespace App\Controller;

use App\Service\AuthService;
use App\Service\CartService;
use App\Service\CustomerService;
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

        $authService = new AuthService();
        $customerService = new CustomerService();

        $isLoggedIn = $authService->isLoggedIn();
        $customerName = '';

        if ($isLoggedIn) {
            $customerId = $authService->getLoggedInCustomerId();

            if ($customerId !== null) {
                $customer = $customerService->getById($customerId);

                if ($customer !== null) {
                    $customerName = trim(sprintf(
                        '%s %s',
                        $customer->getFirstName(),
                        $customer->getLastName()
                    ));
                }
            }
        }

        echo $twig->render('products.twig', [
            'products' => $products,
            'cartLines' => $cartLines,
            'is_logged_in' => $isLoggedIn,
            'customer_name' => $customerName,
        ]);
    }
}