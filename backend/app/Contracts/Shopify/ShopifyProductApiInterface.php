<?php

namespace App\Contracts\Shopify;

interface ShopifyProductApiInterface
{
    /**
     * Get all products from Shopify
     *
     * @param int $limit
     * @param string|null $sinceId
     * @return array
     */
    public function getProducts(int $limit = 250, ?string $sinceId = null): array;

    /**
     * Get a single product by Shopify ID
     *
     * @param string $shopifyId
     * @return array|null
     */
    public function getProduct(string $shopifyId): ?array;

    /**
     * Get products count
     *
     * @return int
     */
    public function getProductsCount(): int;
}
