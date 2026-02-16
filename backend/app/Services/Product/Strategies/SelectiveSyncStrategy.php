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
        \Illuminate\Support\Facades\Log::debug('SelectiveSyncStrategy: Checking if product should be created', [
            'product_title' => $shopifyProduct['title'] ?? 'unknown',
            'product_vendor' => $shopifyProduct['vendor'] ?? 'none',
            'allowed_vendors' => $this->allowedVendors,
            'min_price' => $this->minPrice,
        ]);
        
        // Business rule: Filter by vendor if configured
        // Only filter if there are actually vendors in the allowed list
        // If allowedVendors is empty or contains only empty strings, allow all vendors
        $validAllowedVendors = array_filter($this->allowedVendors, function($v) {
            return !empty(trim($v));
        });
        
        if (!empty($validAllowedVendors)) {
            $vendor = trim($shopifyProduct['vendor'] ?? '');
            if (empty($vendor) || !in_array($vendor, $validAllowedVendors)) {
                \Illuminate\Support\Facades\Log::warning('SelectiveSyncStrategy: Product skipped - vendor not allowed', [
                    'vendor' => $vendor,
                    'allowed_vendors' => $validAllowedVendors,
                    'product_title' => $shopifyProduct['title'] ?? 'unknown',
                ]);
                return false;
            }
        }
        
        // Business rule: Only sync products with valid price (min_price check)
        // Only apply min_price check if it's explicitly configured (> 0)
        // If min_price is 0 (default), allow all products regardless of price
        if ($this->minPrice > 0) {
            $variants = $shopifyProduct['variants'] ?? [];
            $price = $this->extractMinPrice($variants);
            if ($price < $this->minPrice) {
                \Illuminate\Support\Facades\Log::warning('SelectiveSyncStrategy: Product skipped - price below minimum', [
                    'price' => $price,
                    'min_price' => $this->minPrice,
                    'product_title' => $shopifyProduct['title'] ?? 'unknown',
                ]);
                return false;
            }
        }

        // Allow all statuses (active, draft, archived) to be synced
        // This ensures products are always synced regardless of status
        \Illuminate\Support\Facades\Log::info('SelectiveSyncStrategy: Product should be created', [
            'min_price' => $this->minPrice,
            'allowed_vendors' => $this->allowedVendors,
            'product_title' => $shopifyProduct['title'] ?? 'unknown',
        ]);
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
