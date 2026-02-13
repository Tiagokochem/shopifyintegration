<?php

namespace App\Services\Product\Strategies;

use App\Contracts\Product\ProductSyncStrategyInterface;
use App\Contracts\Product\ProductTransformerInterface;
use App\Models\Product;

/**
 * Aggressive Sync Strategy
 * 
 * Always updates products to ensure data is always fresh.
 * This strategy prioritizes data accuracy over performance and is ideal
 * for stores where products change frequently or data accuracy is critical.
 * 
 * Demonstrates: Strategy Pattern, Single Responsibility Principle
 */
class AggressiveSyncStrategy implements ProductSyncStrategyInterface
{
    public function __construct(
        private readonly ProductTransformerInterface $transformer
    ) {
    }

    public function shouldCreate(array $shopifyProduct): bool
    {
        // Create all products regardless of status
        return true;
    }

    public function shouldUpdate(Product $existingProduct, array $shopifyProduct): bool
    {
        // Always update to ensure data freshness
        // Only skip if synced in the last minute (avoid rapid-fire updates)
        if ($existingProduct->synced_at && $existingProduct->synced_at->gt(now()->subMinute())) {
            return false;
        }

        return true;
    }

    public function transformProductData(array $shopifyProduct): array
    {
        return $this->transformer->transform($shopifyProduct);
    }
}
