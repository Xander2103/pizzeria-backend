<?php
//DeliveryAreaService.php
//nodig voor controleren of een postcode binnen het levergebied valt
declare(strict_types=1);

namespace App\Service;

use App\Data\DeliveryAreaDAO;
use App\Data\Database;

final class DeliveryAreaService
{
    private DeliveryAreaDAO $deliveryAreaDAO;

    public function __construct()
    {
        $this->deliveryAreaDAO = new DeliveryAreaDAO();
    }

    public function existsByPostalCode(string $postalCode): bool
    {
        $postalCode = trim($postalCode);

        if ($postalCode === '') {
            return false;
        }

        return $this->deliveryAreaDAO->existsByPostalCode(
            Database::getConnection(),
            $postalCode
        );
    }
    public function getAllPostalCodes(): array
    {
        return $this->deliveryAreaDAO->getAllPostalCodes();
    }
}
