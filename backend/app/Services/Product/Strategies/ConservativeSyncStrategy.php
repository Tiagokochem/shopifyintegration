<?php

namespace App\Services\Product\Strategies;

use App\Contracts\Product\ProductSyncStrategyInterface;
use App\Contracts\Product\ProductTransformerInterface;
use App\Models\Product;

/**
 * Conservative Sync Strategy
 * 
 * Only updates products when there are actual changes detected.
 * This strategy minimizes database writes and is ideal for high-volume stores
 * where most products don't change frequently.
 * 
 * Demonstrates: Strategy Pattern, Single Responsibility Principle
 */
class ConservativeSyncStrategy implements ProductSyncStrategyInterface
{
    public function __construct(
        private readonly ProductTransformerInterface $transformer
    ) {
    }

    public function shouldCreate(array $shopifyProduct): bool
    {
        // Only create active products
        $status = $shopifyProduct['status'] ?? 'active';
        return $status === 'active';
    }

    public function shouldUpdate(Product $existingProduct, array $shopifyProduct): bool
    {
        // Never update if product was synced in the last 5 minutes (avoid unnecessary writes)
        if ($existingProduct->synced_at && $existingProduct->synced_at->gt(now()->subMinutes(5))) {
            return false;
        }

        $transformedData = $this->transformer->transform($shopifyProduct);

        // Only update if critical fields have changed
        return $existingProduct->title !== $transformedData['title']
            || abs($existingProduct->price - $transformedData['price']) > 0.01
            || $existingProduct->status !== $transformedData['status']
            || $existingProduct->vendor !== $transformedData['vendor'];
    }

    public function transformProductData(array $shopifyProduct): array
    {
        return $this->transformer->transform($shopifyProduct);
    }
}
