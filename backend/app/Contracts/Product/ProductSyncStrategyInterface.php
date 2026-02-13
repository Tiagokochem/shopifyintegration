<?php

namespace App\Contracts\Product;

use App\Models\Product;

interface ProductSyncStrategyInterface
{
    /**
     * Determine if a product should be created
     *
     * @param array $shopifyProduct
     * @return bool
     */
    public function shouldCreate(array $shopifyProduct): bool;

    /**
     * Determine if a product should be updated
     *
     * @param Product $existingProduct
     * @param array $shopifyProduct
     * @return bool
     */
    public function shouldUpdate(Product $existingProduct, array $shopifyProduct): bool;

    /**
     * Get the data to create/update a product
     *
     * @param array $shopifyProduct
     * @return array
     */
    public function transformProductData(array $shopifyProduct): array;
}
