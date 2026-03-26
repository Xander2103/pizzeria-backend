<?php

declare(strict_types=1);

namespace App\Service;

final class CheckoutGuestService
{
    public function __construct(
        private readonly SessionService $session = new SessionService(),
        private readonly DeliveryAreaService $deliveryAreaService = new DeliveryAreaService(),
        private readonly AuthService $authService = new AuthService()
    ) {}

    /**
     * @return array{error: string, old: array<string, string>}
     */
    public function consumeGuestFormState(): array
    {
        $error = (string)$this->session->get('guest_error', '');
        $this->session->remove('guest_error');

        $old = $this->session->get('guest_old', []);
        $this->session->remove('guest_old');

        if (!is_array($old)) {
            $old = [];
        }

        return [
            'error' => $error,
            'old' => $old,
        ];
    }

    /**
     * @param array<string, mixed> $post
     * @return array{success: bool, redirect: string}
     */
    public function handleGuestCheckout(array $post): array
    {
        $lastName = trim((string)($post['last_name'] ?? ''));
        $firstName = trim((string)($post['first_name'] ?? ''));
        $street = trim((string)($post['street'] ?? ''));
        $houseNumber = trim((string)($post['house_number'] ?? ''));
        $postalCode = trim((string)($post['postal_code'] ?? ''));
        $city = trim((string)($post['city'] ?? ''));
        $phoneNumber = trim((string)($post['phone_number'] ?? ''));
        $createAccount = isset($post['create_account']) && (string)($post['create_account'] ?? '') === '1';
        $newEmail = trim((string)($post['new_email'] ?? ''));
        $newPassword = (string)($post['new_password'] ?? '');

        $this->session->set('guest_old', [
            'last_name' => $lastName,
            'first_name' => $firstName,
            'street' => $street,
            'house_number' => $houseNumber,
            'postal_code' => $postalCode,
            'city' => $city,
            'phone_number' => $phoneNumber,
            'create_account' => $createAccount ? '1' : '0',
            'new_email' => $newEmail,
        ]);

        if (
            $lastName === '' ||
            $firstName === '' ||
            $street === '' ||
            $houseNumber === '' ||
            $postalCode === '' ||
            $city === '' ||
            $phoneNumber === ''
        ) {
            return $this->fail('Vul alle verplichte velden in.');
        }

        if (!preg_match('/^\d{4}$/', $postalCode)) {
            return $this->fail('Postcode moet uit 4 cijfers bestaan.');
        }

        if (!$this->deliveryAreaService->existsByPostalCode($postalCode)) {
            return $this->fail('Sorry, we leveren niet in deze postcode.');
        }

        if ($createAccount) {
            if ($newEmail === '' || $newPassword === '') {
                return $this->fail('Voor een account zijn e-mail en wachtwoord verplicht.');
            }

            $ok = $this->authService->registerAndLogin(
                $lastName,
                $firstName,
                $street,
                $houseNumber,
                $postalCode,
                $city,
                $phoneNumber,
                $newEmail,
                $newPassword
            );

            if (!$ok) {
                return $this->fail('Account aanmaken mislukt (e-mail bestaat al of gegevens ongeldig).');
            }

            $this->session->remove('guest_old');

            return [
                'success' => true,
                'redirect' => '?page=order_overview',
            ];
        }

        $this->session->set('guest_customer', [
            'last_name' => $lastName,
            'first_name' => $firstName,
            'street' => $street,
            'house_number' => $houseNumber,
            'postal_code' => $postalCode,
            'city' => $city,
            'phone_number' => $phoneNumber,
        ]);

        $this->session->remove('guest_old');

        return [
            'success' => true,
            'redirect' => '?page=order_overview',
        ];
    }

    /**
     * @return array{success: false, redirect: string}
     */
    private function fail(string $message): array
    {
        $this->session->set('guest_error', $message);

        return [
            'success' => false,
            'redirect' => '?page=checkout_guest_form',
        ];
    }
}
