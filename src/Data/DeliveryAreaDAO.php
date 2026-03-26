<?php
//DeliveryAreaDAO.php
//nodig voor controleren of postcode in levergebied ligt
declare(strict_types=1);

namespace App\Data;

use PDO;

class DeliveryAreaDAO
{
    public function existsByPostalCode(PDO $pdo, string $postalCode): bool
    {
        $postalCode = trim($postalCode);

        if (!preg_match('/^\d{4}$/', $postalCode)) {
            return false;
        }

        $sql = "SELECT 1 FROM delivery_areas WHERE postal_code = :pc LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':pc' => $postalCode]);

        return (bool)$stmt->fetchColumn();
    }
    public function getAllPostalCodes(): array
    {
        $sql = 'SELECT postal_code FROM delivery_areas ORDER BY postal_code';

        $stmt = Database::getConnection()->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }
}
