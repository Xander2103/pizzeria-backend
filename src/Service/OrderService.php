<?php
//OrderService.php
//nodig voor plaatsen van bestellingen en ophalen van bestellingen en bestelitems
declare(strict_types=1);

namespace App\Service;

use App\Data\OrderDAO;
use App\Data\ProductDAO;

class OrderService
{
    public function __construct(
        private readonly OrderDAO $orderDAO = new OrderDAO(),
        private readonly ProductDAO $productDAO = new ProductDAO()
    ) {}


    public function placeOrder(array $cart, bool $promoEligible, ?int $customerId, string $courierInfo): int
    {
        $total = 0.0;
        $items = []; //productId, qty, priceEach, extras

        $courierLines = [];
        foreach ($cart as $line) {
            $productId = (int)($line['product_id'] ?? 0);
            $qty = (int)($line['qty'] ?? 0);
            $extras = trim((string)($line['extras'] ?? ''));

            if ($productId <= 0 || $qty <= 0) {
                continue;
            }

            $product = $this->productDAO->getById($productId);
            if ($product === null) {
                continue;
            }

            $priceEach = $product->getPrice();
            if ($promoEligible) {
                $promo = $product->getPromoPrice();
                if ($promo !== null) {
                    $priceEach = $promo;
                }
            }

            $total += $priceEach * $qty;
            $items[] = [$productId, $qty, $priceEach, $extras];

            $lineText = sprintf('%s x %d', $product->getName(), $qty);
            if ($extras !== '') {
                $lineText .= sprintf(' (%s)', $extras);
            }
            $courierLines[] = $lineText;
        }

        if (count($items) === 0) {
            return 0;
        }

        $courierInfo = trim($courierInfo);

        // courier_info = textbox + overzicht van items
        $fullCourierInfoParts = [];
        if ($courierInfo !== '') {
            $fullCourierInfoParts[] = $courierInfo;
        }
        $fullCourierInfoParts[] = implode(', ', $courierLines);

        $fullCourierInfo = implode(' | ', $fullCourierInfoParts);

        $orderId = $this->orderDAO->createOrder($customerId, $total, $fullCourierInfo);

        foreach ($items as [$pid, $q, $price, $extras]) {
            $this->orderDAO->addOrderItem(
                $orderId,
                (int)$pid,
                (int)$q,
                (float)$price,
                $extras === '' ? null : (string)$extras
            );
        }

        return $orderId;
    }

    public function getById(int $id): ?array
    {
        $order = $this->orderDAO->getById($id);
        return is_array($order) ? $order : null;
    }

    public function getItemsByOrderId(int $orderId): array
    {
        return $this->orderDAO->getItemsByOrderId($orderId);
    }
}
