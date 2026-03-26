<?php
//CustomerService.php
//nodig voor ophalen en bijwerken van klantgegevens
declare(strict_types=1);

namespace App\Service;

use App\Data\CustomerDAO;
use App\Entity\Customer;

class CustomerService
{
    private CustomerDAO $dao;

    public function __construct()
    {
        $this->dao = new CustomerDAO();
    }

    public function getById(int $id): ?Customer
    {
        return $this->dao->getById($id);
    }

    public function updateAddress(
        int $id,
        string $street,
        string $houseNumber,
        string $postalCode,
        string $city,
        string $phoneNumber
    ): void {
        $this->dao->updateAddressById(
            $id,
            $street,
            $houseNumber,
            $postalCode,
            $city,
            $phoneNumber
        );
    }
    public function createGuest(
        string $lastName,
        string $firstName,
        string $street,
        string $houseNumber,
        string $postalCode,
        string $city,
        string $phoneNumber
    ): int {
        return $this->dao->createGuest(
            $lastName,
            $firstName,
            $street,
            $houseNumber,
            $postalCode,
            $city,
            $phoneNumber
        );
    }
}
