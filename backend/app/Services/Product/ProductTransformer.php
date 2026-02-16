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
        $firstVariant = $variants[0] ?? [];
        $images = $shopifyProduct['images'] ?? [];
        $firstImage = $images[0] ?? null;

        return [
            'shopify_id' => (string) $shopifyProduct['id'],
            'handle' => $shopifyProduct['handle'] ?? null,
            'title' => $shopifyProduct['title'] ?? '',
            'description' => $shopifyProduct['body_html'] ?? null,
            'price' => $this->extractPrice($variants),
            'compare_at_price' => $this->extractCompareAtPrice($variants),
            'vendor' => $shopifyProduct['vendor'] ?? null,
            'product_type' => $shopifyProduct['product_type'] ?? null,
            'tags' => $this->extractTags($shopifyProduct),
            'status' => $shopifyProduct['status'] ?? 'active',
            'sku' => $firstVariant['sku'] ?? null,
            'weight' => isset($firstVariant['weight']) ? (float) $firstVariant['weight'] : null,
            'weight_unit' => $firstVariant['weight_unit'] ?? 'kg',
            'requires_shipping' => $firstVariant['requires_shipping'] ?? true,
            'tracked' => isset($firstVariant['inventory_management']) && $firstVariant['inventory_management'] !== null,
            'inventory_quantity' => isset($firstVariant['inventory_quantity']) ? (int) $firstVariant['inventory_quantity'] : null,
            'meta_title' => $shopifyProduct['metafields_global_title_tag'] ?? $shopifyProduct['title'] ?? null,
            'meta_description' => $shopifyProduct['metafields_global_description_tag'] ?? null,
            'images' => $this->extractImages($images),
            'featured_image' => $firstImage['src'] ?? null,
            'template_suffix' => $shopifyProduct['template_suffix'] ?? null,
            'published' => $shopifyProduct['published_at'] !== null,
            'published_at' => isset($shopifyProduct['published_at']) ? $shopifyProduct['published_at'] : null,
            'variants_data' => $this->extractVariantsData($variants),
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

    /**
     * Extract the minimum compare_at_price from product variants
     *
     * @param array $variants
     * @return float|null
     */
    private function extractCompareAtPrice(array $variants): ?float
    {
        if (empty($variants)) {
            return null;
        }

        $prices = array_filter(array_map(function ($variant) {
            return isset($variant['compare_at_price']) && $variant['compare_at_price'] ? (float) $variant['compare_at_price'] : null;
        }, $variants));

        return !empty($prices) ? min($prices) : null;
    }

    /**
     * Extract tags as comma-separated string
     *
     * @param array $shopifyProduct
     * @return string|null
     */
    private function extractTags(array $shopifyProduct): ?string
    {
        if (!isset($shopifyProduct['tags']) || empty($shopifyProduct['tags'])) {
            return null;
        }

        // Tags can be a string (comma-separated) or array
        if (is_array($shopifyProduct['tags'])) {
            return implode(', ', $shopifyProduct['tags']);
        }

        return $shopifyProduct['tags'];
    }

    /**
     * Extract images array
     *
     * @param array $images
     * @return array|null
     */
    private function extractImages(array $images): ?array
    {
        if (empty($images)) {
            return null;
        }

        return array_map(function ($image) {
            return [
                'id' => $image['id'] ?? null,
                'src' => $image['src'] ?? null,
                'alt' => $image['alt'] ?? null,
            ];
        }, $images);
    }

    /**
     * Extract variants data summary
     *
     * @param array $variants
     * @return array|null
     */
    private function extractVariantsData(array $variants): ?array
    {
        if (empty($variants)) {
            return null;
        }

        return array_map(function ($variant) {
            return [
                'id' => $variant['id'] ?? null,
                'title' => $variant['title'] ?? null,
                'price' => $variant['price'] ?? null,
                'sku' => $variant['sku'] ?? null,
                'inventory_quantity' => $variant['inventory_quantity'] ?? null,
            ];
        }, $variants);
    }
}
