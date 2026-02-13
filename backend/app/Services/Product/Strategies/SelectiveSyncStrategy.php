<?php

namespace App\Services\Product\Strategies;

use App\Contracts\Product\ProductSyncStrategyInterface;
use App\Contracts\Product\ProductTransformerInterface;
use App\Models\Product;

/**
 * Selective Sync Strategy
 * 
 * Syncs products based on business rules:
 * - Only active products
 * - Only products with price > 0
 * - Only products from specific vendors (if configured)
 * 
 * Demonstrates: Strategy Pattern, Open/Closed Principle
 */
class SelectiveSyncStrategy implements ProductSyncStrategyInterface
{
    public function __construct(
        private readonly ProductTransformerInterface $transformer,
        private readonly array $allowedVendors = [],
        private readonly float $minPrice = 0.0
    ) {
    }

    public function shouldCreate(array $shopifyProduct): bool
    {
        // Business rule: Only sync active products
        if (($shopifyProduct['status'] ?? '') !== 'active') {
            return false;
        }

        // Business rule: Only sync products with valid price
        $variants = $shopifyProduct['variants'] ?? [];
        $price = $this->extractMinPrice($variants);
        if ($price < $this->minPrice) {
            return false;
        }

        // Business rule: Filter by vendor if configured
        if (!empty($this->allowedVendors)) {
            $vendor = $shopifyProduct['vendor'] ?? '';
            if (!in_array($vendor, $this->allowedVendors)) {
                return false;
            }
        }

        return true;
    }

    public function shouldUpdate(Product $existingProduct, array $shopifyProduct): bool
    {
        // Apply same business rules for updates
        if (!$this->shouldCreate($shopifyProduct)) {
            return false;
        }

        // Update if data changed or not synced recently
        if (!$existingProduct->synced_at || $existingProduct->synced_at->lt(now()->subHours(1))) {
            return true;
        }

        $transformedData = $this->transformer->transform($shopifyProduct);

        return $existingProduct->title !== $transformedData['title']
            || abs($existingProduct->price - $transformedData['price']) > 0.01
            || $existingProduct->status !== $transformedData['status'];
    }

    public function transformProductData(array $shopifyProduct): array
    {
        return $this->transformer->transform($shopifyProduct);
    }

    private function extractMinPrice(array $variants): float
    {
        if (empty($variants)) {
            return 0.0;
        }

        $prices = array_map(function ($variant) {
            return (float) ($variant['price'] ?? 0);
        }, $variants);

        return min($prices);
    }
}
