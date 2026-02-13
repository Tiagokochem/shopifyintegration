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

    /**
     * Create a product in Shopify
     *
     * @param array $productData
     * @return array Created product data from Shopify
     */
    public function createProduct(array $productData): array;

    /**
     * Update a product in Shopify
     *
     * @param string $shopifyId
     * @param array $productData
     * @return array Updated product data from Shopify
     */
    public function updateProduct(string $shopifyId, array $productData): array;

    /**
     * Delete a product from Shopify
     *
     * @param string $shopifyId
     * @return bool
     */
    public function deleteProduct(string $shopifyId): bool;
}
