<?php

namespace App\Services\Product;

use App\Contracts\Product\ProductSyncStrategyInterface;
use App\Contracts\Product\ProductTransformerInterface;
use App\Models\Product;

class DefaultProductSyncStrategy implements ProductSyncStrategyInterface
{
    public function __construct(
        private readonly ProductTransformerInterface $transformer
    ) {
    }

    public function shouldCreate(array $shopifyProduct): bool
    {
        // Always create if product doesn't exist
        return true;
    }

    public function shouldUpdate(Product $existingProduct, array $shopifyProduct): bool
    {
        // Always update to ensure data is in sync
        // This ensures that even if data appears the same, we refresh synced_at timestamp
        // and handle any edge cases where data might have changed in Shopify
        return true;
    }

    public function transformProductData(array $shopifyProduct): array
    {
        return $this->transformer->transform($shopifyProduct);
    }
}
