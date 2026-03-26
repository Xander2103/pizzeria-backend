<?php
//CustomerDAO.php
//nodig voor login en registratie, en adres aanpassen
declare(strict_types=1);

namespace App\Data;

use App\Entity\Customer;

class CustomerDAO
{
    public function getByEmail(string $email): ?Customer
    {
        $sql = 'SELECT customer_id, last_name, first_name, street, house_number, postal_code, city,
                       phone_number, email, password_hash, remarks, promo_eligible
                FROM customers
                WHERE email = ?';

        $stmt = Database::getConnection()->prepare($sql);
        $stmt->execute([$email]);

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($row === false) {
            return null;
        }

        return new Customer(
            (int)$row['customer_id'],
            (string)$row['last_name'],
            (string)$row['first_name'],
            (string)$row['street'],
            (string)$row['house_number'],
            (string)$row['postal_code'],
            (string)$row['city'],
            (string)$row['phone_number'],
            (string)$row['email'],
            (string)$row['password_hash'],
            $row['remarks'] === null ? null : (string)$row['remarks'],
            (bool)$row['promo_eligible']
        );
    }

    public function getById(int $id): ?Customer
    {
        $sql = 'SELECT customer_id, last_name, first_name, street, house_number, postal_code, city,
                   phone_number, email, password_hash, remarks, promo_eligible
            FROM customers
            WHERE customer_id = ?';

        $stmt = Database::getConnection()->prepare($sql);
        $stmt->execute([$id]);

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($row === false) {
            return null;
        }

        return new Customer(
            (int)$row['customer_id'],
            (string)$row['last_name'],
            (string)$row['first_name'],
            (string)$row['street'],
            (string)$row['house_number'],
            (string)$row['postal_code'],
            (string)$row['city'],
            (string)$row['phone_number'],
            (string)$row['email'],
            (string)$row['password_hash'],
            $row['remarks'] === null ? null : (string)$row['remarks'],
            (bool)$row['promo_eligible']
        );
    }

    public function create(
        string $lastName,
        string $firstName,
        string $street,
        string $houseNumber,
        string $postalCode,
        string $city,
        string $phoneNumber,
        string $email,
        string $passwordHash
    ): int {
        $sql = 'INSERT INTO customers
                (last_name, first_name, street, house_number, postal_code, city,
                 phone_number, email, password_hash, promo_eligible)
            VALUES
                (?, ?, ?, ?, ?, ?, ?, ?, ?, 0)';

        $pdo = Database::getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $lastName,
            $firstName,
            $street,
            $houseNumber,
            $postalCode,
            $city,
            $phoneNumber,
            $email,
            $passwordHash
        ]);

        return (int)$pdo->lastInsertId();
    }

    public function updateAddressById(
        int $id,
        string $street,
        string $houseNumber,
        string $postalCode,
        string $city,
        string $phoneNumber
    ): void {

        $sql = "
        UPDATE customers
        SET
            street = ?,
            house_number = ?,
            postal_code = ?,
            city = ?,
            phone_number = ?
        WHERE customer_id = ?
    ";

        $stmt = Database::getConnection()->prepare($sql);

        $stmt->execute([
            $street,
            $houseNumber,
            $postalCode,
            $city,
            $phoneNumber,
            $id
        ]);
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
        $sql = 'INSERT INTO customers
            (last_name, first_name, street, house_number, postal_code, city,
             phone_number, email, password_hash, promo_eligible)
            VALUES
            (?, ?, ?, ?, ?, ?, ?, NULL, NULL, 0)';

        $pdo = Database::getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $lastName,
            $firstName,
            $street,
            $houseNumber,
            $postalCode,
            $city,
            $phoneNumber
        ]);

        return (int)$pdo->lastInsertId();
    }
}
