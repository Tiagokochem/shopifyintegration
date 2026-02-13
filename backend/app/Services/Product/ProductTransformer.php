<?php

namespace App\Services\Product;

use App\Contracts\Product\ProductTransformerInterface;

/**
 * Product Transformer
 * 
 * Transforms Shopify product data to our internal format.
 * 
 * Demonstrates: Single Responsibility Principle, Dependency Inversion Principle
 */
class ProductTransformer implements ProductTransformerInterface
{
    /**
     * Transform Shopify product data to our internal format
     *
     * @param array $shopifyProduct
     * @return array
     */
    public function transform(array $shopifyProduct): array
    {
        $variants = $shopifyProduct['variants'] ?? [];
        $price = $this->extractPrice($variants);

        return [
            'shopify_id' => (string) $shopifyProduct['id'],
            'title' => $shopifyProduct['title'] ?? '',
            'description' => $shopifyProduct['body_html'] ?? null,
            'price' => $price,
            'vendor' => $shopifyProduct['vendor'] ?? null,
            'product_type' => $shopifyProduct['product_type'] ?? null,
            'status' => $shopifyProduct['status'] ?? 'active',
            'synced_at' => now(),
        ];
    }

    /**
     * Extract the minimum price from product variants
     *
     * @param array $variants
     * @return float
     */
    private function extractPrice(array $variants): float
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
