<?php

declare(strict_types=1);

namespace App\Service;

final class OrderAddressService
{
    public function __construct(
        private readonly SessionService $session = new SessionService(),
        private readonly CustomerService $customerService = new CustomerService(),
        private readonly DeliveryAreaService $deliveryAreaService = new DeliveryAreaService()
    ) {}

    /**
     * @return array{is_logged_in: bool, customer: mixed, postal_codes: array<int, string>}|null
     */
    public function getEditAddressData(): ?array
    {
        $postalCodes = $this->deliveryAreaService->getAllPostalCodes();
        $customerId = (int)$this->session->get('customer_id', 0);

        if ($customerId > 0) {
            $customer = $this->customerService->getById($customerId);
            if ($customer === null) {
                return null;
            }

            return [
                'is_logged_in' => true,
                'customer' => $customer,
                'postal_codes' => $postalCodes,
            ];
        }

        $guest = $this->session->get('guest_customer', null);
        if (!is_array($guest)) {
            return null;
        }

        return [
            'is_logged_in' => false,
            'customer' => $guest,
            'postal_codes' => $postalCodes,
        ];
    }

    /**
     * @param array<string, mixed> $post
     */
    public function updateAddress(array $post): void
    {
        $street = trim((string)($post['street'] ?? ''));
        $houseNumber = trim((string)($post['house_number'] ?? ''));
        $postalCode = trim((string)($post['postal_code'] ?? ''));
        $city = trim((string)($post['city'] ?? ''));
        $phoneNumber = trim((string)($post['phone_number'] ?? ''));

        $customerId = (int)$this->session->get('customer_id', 0);
        if ($customerId > 0) {
            $this->customerService->updateAddress(
                $customerId,
                $street,
                $houseNumber,
                $postalCode,
                $city,
                $phoneNumber
            );
            return;
        }

        $guest = $this->session->get('guest_customer', []);
        if (!is_array($guest)) {
            $guest = [];
        }

        $guest['street'] = $street;
        $guest['house_number'] = $houseNumber;
        $guest['postal_code'] = $postalCode;
        $guest['city'] = $city;
        $guest['phone_number'] = $phoneNumber;

        $this->session->set('guest_customer', $guest);
    }
}
