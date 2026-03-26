<?php

declare(strict_types=1);

namespace App\Service;

final class OrderOverviewService
{
    public function __construct(
        private readonly ProductService $productService = new ProductService()
    ) {}

    /**
     * @param array<string, array<string, mixed>> $cart
     * @return array{lines: array<int, array<string, mixed>>, total: float}
     */
    public function buildOverview(array $cart, bool $promoEligible): array
    {
        $lines = [];
        $total = 0.0;

        foreach ($cart as $key => $line) {
            if (!is_array($line)) {
                continue;
            }

            $productId = (int)($line['product_id'] ?? 0);
            $qty = (int)($line['qty'] ?? 0);
            $extras = trim((string)($line['extras'] ?? ''));

            if ($productId <= 0 || $qty <= 0) {
                continue;
            }

            $product = $this->productService->getById($productId);
            if ($product === null) {
                continue;
            }

            $unitPrice = $product->getPrice();
            if ($promoEligible) {
                $promo = $product->getPromoPrice();
                if ($promo !== null) {
                    $unitPrice = $promo;
                }
            }

            $lineTotal = $unitPrice * $qty;
            $total += $lineTotal;

            $lines[] = [
                'key' => (string)$key,
                'product' => $product,
                'qty' => $qty,
                'extras' => $extras,
                'unit_price' => $unitPrice,
                'line_total' => $lineTotal,
            ];
        }

        return [
            'lines' => $lines,
            'total' => $total,
        ];
    }
}
