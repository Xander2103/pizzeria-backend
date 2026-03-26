<?php
//Product.php
// Product entity: stelt één product voor (data + getters).
// Wordt opgebouwd door ProductDAO en gebruikt door services/controllers/views.
declare(strict_types=1);

namespace App\Entity;

class Product
{
    public function __construct(
        private int $id,
        private string $name,
        private ?string $description,
        private ?string $image,
        private float $price,
        private ?float $promoPrice
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getPromoPrice(): ?float
    {
        return $this->promoPrice;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }
    public function getImage(): ?string
    {
        return $this->image;
    }
}
