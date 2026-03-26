<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\CartService;
use App\Service\CheckoutGuestService;
use App\Service\CheckoutSessionService;
use App\Service\CustomerService;
use App\Service\OrderAddressService;
use App\Service\OrderOverviewService;
use App\Service\OrderService;
use App\Service\SessionService;
use Twig\Environment;

class OrderController
{
    private CartService $cartService;
    private SessionService $session;
    private OrderService $orderService;
    private CheckoutSessionService $checkoutSessionService;
    private CheckoutGuestService $checkoutGuestService;
    private OrderOverviewService $orderOverviewService;
    private OrderAddressService $orderAddressService;

    public function __construct()
    {
        $this->cartService = new CartService();
        $this->session = new SessionService();
        $this->orderService = new OrderService();
        $this->checkoutSessionService = new CheckoutSessionService();
        $this->checkoutGuestService = new CheckoutGuestService();
        $this->orderOverviewService = new OrderOverviewService();
        $this->orderAddressService = new OrderAddressService();
    }

    public function checkout(Environment $twig): void
    {
        if ($this->cartIsEmpty()) {
            $this->redirect('?page=products');
        }

        if ($this->checkoutSessionService->getCurrentCustomerId() !== null) {
            $this->redirect('?page=order_overview');
        }

        $error = (string)$this->session->get('checkout_error', '');
        $this->session->remove('checkout_error');

        echo $twig->render('checkout_choice.twig', [
            'error' => $error,
            'last_email' => (string)($_COOKIE['last_email'] ?? ''),
            'mode' => 'choice',
        ]);
    }

    public function checkoutGuestForm(Environment $twig): void
    {
        if ($this->cartIsEmpty()) {
            $this->redirect('?page=products');
        }

        $state = $this->checkoutGuestService->consumeGuestFormState();

        echo $twig->render('checkout_guest.twig', $state);
    }

    public function checkoutGuest(): void
    {
        if (!$this->isPostRequest()) {
            $this->redirect('?page=checkout_guest_form');
        }

        if ($this->cartIsEmpty()) {
            $this->redirect('?page=products');
        }

        $result = $this->checkoutGuestService->handleGuestCheckout($_POST);
        $this->redirect($result['redirect']);
    }

    public function overview(Environment $twig): void
    {
        $cart = $this->cartService->getCart();

        if (count($cart) === 0) {
            $this->redirect('?page=products');
        }

        $checkoutContext = $this->checkoutSessionService->getCheckoutCustomerContext();

        if ($checkoutContext === null) {
            $this->redirect('?page=checkout');
        }

        $overview = $this->orderOverviewService->buildOverview(
            $cart,
            (bool)$checkoutContext['promo_eligible']
        );

        if (count($overview['lines']) === 0) {
            $this->redirect('?page=products');
        }

        echo $twig->render('order_overview.twig', [
            'customer' => $checkoutContext['customer'],
            'is_logged_in' => $checkoutContext['is_logged_in'],
            'promo_eligible' => $checkoutContext['promo_eligible'],
            'lines' => $overview['lines'],
            'total' => $overview['total'],
        ]);
    }

    public function placeOrder(): void
    {
        if (!$this->isPostRequest()) {
            $this->redirect('?page=order_overview');
        }

        $cart = $this->cartService->getCart();

        if (count($cart) === 0) {
            $this->redirect('?page=products');
        }

        $customerId = (int)$this->session->get('customer_id', 0);
        $promoEligible = false;

        if ($customerId > 0) {
            $customerService = new CustomerService();
            $customer = $customerService->getById($customerId);

            if ($customer === null) {
                $this->session->remove('customer_id');
                $this->session->set('checkout_error', 'Klant niet gevonden. Log opnieuw in.');
                $this->redirect('?page=checkout');
            }

            $promoEligible = $customer->isPromoEligible();
        } else {
            $guest = $this->session->get('guest_customer', null);

            if (!is_array($guest)) {
                $this->session->set('checkout_error', 'Vul eerst je gegevens in.');
                $this->redirect('?page=checkout');
            }

            $lastName = trim((string)($guest['last_name'] ?? ''));
            $firstName = trim((string)($guest['first_name'] ?? ''));
            $street = trim((string)($guest['street'] ?? ''));
            $houseNumber = trim((string)($guest['house_number'] ?? ''));
            $postalCode = trim((string)($guest['postal_code'] ?? ''));
            $city = trim((string)($guest['city'] ?? ''));
            $phoneNumber = trim((string)($guest['phone_number'] ?? ''));

            if (
                $lastName === '' ||
                $firstName === '' ||
                $street === '' ||
                $houseNumber === '' ||
                $postalCode === '' ||
                $city === '' ||
                $phoneNumber === ''
            ) {
                $this->session->set('checkout_error', 'Gastgegevens zijn onvolledig.');
                $this->redirect('?page=checkout_guest_form');
            }

            $customerService = new CustomerService();
            $customerId = $customerService->createGuest(
                $lastName,
                $firstName,
                $street,
                $houseNumber,
                $postalCode,
                $city,
                $phoneNumber
            );

            if ($customerId <= 0) {
                $this->session->set('checkout_error', 'Gastklant aanmaken mislukt.');
                $this->redirect('?page=checkout');
            }
        }

        $courierName = trim((string)($_POST['courier_name'] ?? ''));
        $courierPhone = trim((string)($_POST['courier_phone'] ?? ''));

        if ($courierName !== '' || $courierPhone !== '') {
            $courierInfo = sprintf(
                'Koerier: %s - %s',
                $courierName,
                $courierPhone
            );
        } else {
            $courierInfo = trim((string)($_POST['courier_info'] ?? ''));
        }

        if ($courierInfo === '') {
            $courierInfo = 'Geen koerierinfo opgegeven';
        }

        $orderId = $this->orderService->placeOrder(
            $cart,
            $promoEligible,
            $customerId,
            $courierInfo
        );

        if ($orderId <= 0) {
            $this->session->set('checkout_error', 'Bestelling plaatsen mislukt.');
            $this->redirect('?page=order_overview');
        }

        $this->cartService->clear();
        $this->session->remove('guest_customer');
        $this->session->set('last_order_id', $orderId);

        $this->redirect('?page=order_success');
    }

    public function success(Environment $twig): void
    {
        $orderId = (int)$this->session->get('last_order_id', 0);

        if ($orderId <= 0) {
            $this->redirect('?page=products');
        }

        $this->session->remove('last_order_id');

        $order = $this->orderService->getById($orderId);

        if ($order === null) {
            $this->redirect('?page=products');
        }

        echo $twig->render('order_success.twig', [
            'order' => $order,
            'items' => $this->orderService->getItemsByOrderId($orderId),
        ]);
    }

    public function editAddress(Environment $twig): void
    {
        $data = $this->orderAddressService->getEditAddressData();

        if ($data === null) {
            $this->redirect('?page=checkout');
        }

        echo $twig->render('address_edit.twig', $data);
    }

    public function updateAddress(): void
    {
        if (!$this->isPostRequest()) {
            $this->redirect('?page=order_overview');
        }

        $this->orderAddressService->updateAddress($_POST);
        $this->redirect('?page=order_overview');
    }

    private function isPostRequest(): bool
    {
        return (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST');
    }

    private function cartIsEmpty(): bool
    {
        return count($this->cartService->getCart()) === 0;
    }

    private function redirect(string $location): void
    {
        header(sprintf('Location: %s', $location));
        exit;
    }
}
