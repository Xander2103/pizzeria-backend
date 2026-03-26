<?php
//Customer.php
//nodig voor CustomerDAO en CustomerService
declare(strict_types=1);

namespace App\Entity;

class Customer
{
    public function __construct(
        private int $id,
        private string $lastName,
        private string $firstName,
        private string $street,
        private string $houseNumber,
        private string $postalCode,
        private string $city,
        private string $phoneNumber,
        private string $email,
        private string $passwordHash,
        private ?string $remarks,
        private bool $promoEligible
    ) {}

    public function getId(): int
    {
        return $this->id;
    }
    public function getLastName(): string
    {
        return $this->lastName;
    }
    public function getFirstName(): string
    {
        return $this->firstName;
    }
    public function getStreet(): string
    {
        return $this->street;
    }
    public function getHouseNumber(): string
    {
        return $this->houseNumber;
    }
    public function getPostalCode(): string
    {
        return $this->postalCode;
    }
    public function getCity(): string
    {
        return $this->city;
    }
    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }
    public function getEmail(): string
    {
        return $this->email;
    }
    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }
    public function getRemarks(): ?string
    {
        return $this->remarks;
    }
    public function isPromoEligible(): bool
    {
        return $this->promoEligible;
    }
}
