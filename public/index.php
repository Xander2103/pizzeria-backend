<?php

declare(strict_types=1);

//bootstrap nodig om Twig en de controllers te kunnen gebruiken
require_once __DIR__ . '/../bootstrap.php';

use App\Controller\HomeController;
use App\Controller\ProductController;
use App\Controller\CartController;
use App\Controller\OrderController;
use App\Controller\AuthController;

/** @var \Twig\Environment $twig */

$page = (string)($_GET['page'] ?? 'home');

switch ($page) {
    case 'home':
        (new HomeController())->index($twig);
        break;

    case 'products':
        (new ProductController())->index($twig);
        break;

    case 'cart':
        (new CartController())->index($twig);
        break;

    case 'cart_add':
        (new CartController())->add();
        break;

    case 'cart_remove':
        (new CartController())->remove();
        break;

    case 'cart_clear':
        (new CartController())->clear();
        break;


    case 'logout':
        (new AuthController())->logout();
        break;


    case 'order_overview':
        (new OrderController())->overview($twig);
        break;

    case 'place_order':
        (new OrderController())->placeOrder();
        break;

    case 'order_success':
        (new OrderController())->success($twig);
        break;

    case 'address_edit':
        (new OrderController())->editAddress($twig);
        break;

    case 'address_update':
        (new OrderController())->updateAddress();
        break;

    case 'checkout':
        (new OrderController())->checkout($twig); // laat checkout_choice.twig zien
        break;

    case 'checkout_guest_form':
    (new OrderController())->checkoutGuestForm($twig);
    break;

    case 'checkout_guest':
        (new OrderController())->checkoutGuest(); // POST verwerken
        break;

    case 'login':
        (new AuthController())->login($twig); // GET = form tonen, POST = verwerken
        break;



    default:
        http_response_code(404);
        echo $twig->render('404.twig', ['page' => $page]);
        break;
}
