<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Customer;

final class CheckoutSessionService
{
    public function __construct(
        private readonly SessionService $session = new SessionService(),
        private readonly CustomerService $customerService = new CustomerService()
    ) {}

    /**
     * @return array{customer: Customer|array<string, mixed>, promo_eligible: bool, is_logged_in: bool, customer_id: ?int}|null
     */
    public function getCheckoutCustomerContext(): ?array
    {
        $customerId = (int)$this->session->get('customer_id', 0);

        if ($customerId > 0) {
            $customer = $this->customerService->getById($customerId);

            if ($customer === null) {
                $this->session->remove('customer_id');
                return null;
            }

            return [
                'customer' => $customer,
                'promo_eligible' => $customer->isPromoEligible(),
                'is_logged_in' => true,
                'customer_id' => $customer->getId(),
            ];
        }

        $guest = $this->session->get('guest_customer', null);
        if (!is_array($guest)) {
            return null;
        }

        return [
            'customer' => $guest,
            'promo_eligible' => false,
            'is_logged_in' => false,
            'customer_id' => null,
        ];
    }

    public function getCurrentCustomerId(): ?int
    {
        $customerId = (int)$this->session->get('customer_id', 0);
        return $customerId > 0 ? $customerId : null;
    }
}
