<?php
//OrderDAO.php
//nodig voor order plaatsen en order details tonen
declare(strict_types=1);

namespace App\Data;

class OrderDAO
{
    public function createOrder(?int $customerId, float $totalPrice, string $courierInfo): int
    {
        $sql = 'INSERT INTO orders (customer_id, order_datetime, total_price, courier_info)
            VALUES (?, NOW(), ?, ?)';

        $pdo = Database::getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$customerId, $totalPrice, $courierInfo]);

        return (int)$pdo->lastInsertId();
    }

    public function addOrderItem(int $orderId, int $productId, int $quantity, float $priceEach, ?string $extras): void
    {
        $sql = 'INSERT INTO order_items (order_id, product_id, quantity, price_each, extras)
            VALUES (?, ?, ?, ?, ?)';

        $stmt = Database::getConnection()->prepare($sql);
        $stmt->execute([$orderId, $productId, $quantity, $priceEach, $extras]);
    }

    public function getById(int $orderId): ?array
    {
        $sql = 'SELECT order_id, customer_id, order_datetime, total_price, courier_info
            FROM orders
            WHERE order_id = ?';

        $stmt = Database::getConnection()->prepare($sql);
        $stmt->execute([$orderId]);

        $row = $stmt->fetch();
        if ($row === false) {
            return null;
        }

        return $row; // assoc array is ok voor deze opdracht
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getItemsByOrderId(int $orderId): array
    {
        $sql = 'SELECT oi.product_id, oi.quantity, oi.price_each, p.name
            FROM order_items oi
            INNER JOIN products p ON p.product_id = oi.product_id
            WHERE oi.order_id = ?';

        $stmt = Database::getConnection()->prepare($sql);
        $stmt->execute([$orderId]);

        return $stmt->fetchAll();
    }
}
