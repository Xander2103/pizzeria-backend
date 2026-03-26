<?php
//ProductService.php
//nodig voor ophalen van producten
declare(strict_types=1);

namespace App\Service;

use App\Data\ProductDAO;
use App\Entity\Product;

final class ProductService
{
    private ProductDAO $productDAO;

    public function __construct()
    {
        $this->productDAO = new ProductDAO();
    }

    public function getAll(): array
    {
        return $this->productDAO->getAll();
    }

    public function getById(int $id): ?Product
    {
        return $this->productDAO->getById($id);
    }
}