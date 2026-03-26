<?php
//productDAO.php
//nodig voor producten tonen en order plaatsen
declare(strict_types=1);

namespace App\Data;

use App\Entity\Product;

class ProductDAO
{
    public function getAll(): array
    {
        $sql = 'SELECT product_id, name, description, image , price, promo_price FROM products';
        $stmt = Database::getConnection()->query($sql);

        $products = [];
        foreach ($stmt->fetchAll() as $row) {
            $promo = $row['promo_price'];
            $products[] = new Product(
                (int)$row['product_id'],
                (string)$row['name'],
                $row['description'] === null ? null : (string)$row['description'],
                $row['image'] === null ? null : (string)$row['image'],
                (float)$row['price'],
                $promo === null ? null : (float)$promo
            );
        }

        return $products;
    }

    public function getById(int $id): ?Product
    {
        $sql = 'SELECT product_id, name, description, image, price, promo_price FROM products WHERE product_id = ?';
        $stmt = Database::getConnection()->prepare($sql);
        $stmt->execute([$id]);

        $row = $stmt->fetch();
        if ($row === false) {
            return null;
        }

        $promo = $row['promo_price'];

        return new Product(
            (int)$row['product_id'],
            (string)$row['name'],
            $row['description'] === null ? null : (string)$row['description'],
            $row['image'] === null ? null : (string)$row['image'],
            (float)$row['price'],
            $promo === null ? null : (float)$promo
        );
    }

}
