<?php
//AuthService.php
//nodig voor inloggen, registreren en uitloggen
declare(strict_types=1);

namespace App\Service;

use App\Data\CustomerDAO;

class AuthService
{
    private CustomerDAO $customerDAO;
    private SessionService $session;

    public function __construct()
    {
        $this->customerDAO = new CustomerDAO();
        $this->session = new SessionService();
    }

    public function isLoggedIn(): bool
    {
        return $this->session->has('customer_id');
    }

    public function getLoggedInCustomerId(): ?int
    {
        $id = (int)$this->session->get('customer_id', 0);
        return $id > 0 ? $id : null;
    }

    public function login(string $email, string $password): ?int
    {
        $email = trim($email);

        if ($email === '' || $password === '') {
            return null;
        }

        $customer = $this->customerDAO->getByEmail($email);
        if ($customer === null) {
            return null;
        }

        if (!password_verify($password, $customer->getPasswordHash())) {
            return null;
        }

        $customerId = $customer->getId();

        $this->session->set('customer_id', $customerId);
        $this->session->remove('guest_customer');

        return $customerId;
    }
    public function logout(): void
    {
        $this->session->remove('customer_id');
    }

    public function registerAndLogin(
        string $lastName,
        string $firstName,
        string $street,
        string $houseNumber,
        string $postalCode,
        string $city,
        string $phoneNumber,
        string $email,
        string $password
    ): bool {
        $email = trim($email);

        if ($email === '' || $password === '') {
            return false;
        }

        // email mag nog niet bestaan
        if ($this->customerDAO->getByEmail($email) !== null) {
            return false;
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);

        $newId = $this->customerDAO->create(
            $lastName,
            $firstName,
            $street,
            $houseNumber,
            $postalCode,
            $city,
            $phoneNumber,
            $email,
            $hash
        );

        if ($newId <= 0) {
            return false;
        }

        // auto-login
        $this->session->set('customer_id', $newId);

        // guest data opruimen
        $this->session->remove('guest_customer');

        return true;
    }
}
