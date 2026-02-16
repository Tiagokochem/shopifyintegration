<?php

namespace App\Services\Shopify;

use App\Models\Product;

/**
 * Shopify Product Formatter
 * 
 * Formats product data for Shopify API requests.
 * Centralizes the formatting logic to avoid duplication.
 * 
 * Demonstrates: Single Responsibility Principle, DRY (Don't Repeat Yourself)
 */
class ShopifyProductFormatter
{
    /**
     * Format product data for Shopify API
     *
     * @param array|Product $productData Product data array or Product model instance
     * @return array Formatted data for Shopify API
     */
    public function format(array|Product $productData): array
    {
        // Handle Product model instance
        if ($productData instanceof Product) {
            $productData = $this->productToArray($productData);
        }

        $shopifyProduct = [
            'title' => $productData['title'] ?? '',
            'body_html' => $productData['description'] ?? '',
            'vendor' => $productData['vendor'] ?? null,
            'product_type' => $productData['product_type'] ?? null,
            'status' => $this->determineStatus($productData),
        ];

        // Handle handle (URL slug)
        if (isset($productData['handle'])) {
            $shopifyProduct['handle'] = $productData['handle'];
        }

        // Handle tags
        if (isset($productData['tags']) && !empty($productData['tags'])) {
            $shopifyProduct['tags'] = is_array($productData['tags']) 
                ? implode(', ', $productData['tags']) 
                : $productData['tags'];
        }

        // Handle SEO fields
        if (isset($productData['meta_title'])) {
            $shopifyProduct['metafields_global_title_tag'] = $productData['meta_title'];
        }
        if (isset($productData['meta_description'])) {
            $shopifyProduct['metafields_global_description_tag'] = $productData['meta_description'];
        }

        // Handle template suffix
        if (isset($productData['template_suffix'])) {
            $shopifyProduct['template_suffix'] = $productData['template_suffix'];
        }

        // Handle images
        if (isset($productData['images']) && is_array($productData['images'])) {
            $shopifyProduct['images'] = array_map(function ($image) {
                $imageData = [];
                if (isset($image['src'])) {
                    $imageData['src'] = $image['src'];
                }
                if (isset($image['alt'])) {
                    $imageData['alt'] = $image['alt'];
                }
                return $imageData;
            }, $productData['images']);
        }

        // Build variant with all pricing and inventory fields
        // Shopify requires at least one variant with a price
        $variant = [];
        
        // Price is required for variants
        if (isset($productData['price']) && $productData['price'] !== null) {
            $variant['price'] = (string) $productData['price'];
        } else {
            // Shopify requires at least a price, default to 0 if not provided
            $variant['price'] = '0.00';
        }
        
        if (isset($productData['compare_at_price']) && $productData['compare_at_price'] !== null) {
            $variant['compare_at_price'] = (string) $productData['compare_at_price'];
        }
        
        if (isset($productData['sku']) && $productData['sku'] !== null && $productData['sku'] !== '') {
            $variant['sku'] = $productData['sku'];
        }
        
        // Weight and weight_unit must be sent together, or not at all
        // Shopify requires weight_unit to be a valid value if weight is present
        if (isset($productData['weight']) && $productData['weight'] !== null && $productData['weight'] > 0) {
            $variant['weight'] = (string) $productData['weight'];
            
            // Get weight_unit, default to 'kg' if not provided or null
            $weightUnit = $productData['weight_unit'] ?? 'kg';
            
            // Validate weight_unit - Shopify accepts: kg, g, lb, oz
            $validUnits = ['kg', 'g', 'lb', 'oz'];
            if (empty($weightUnit) || !in_array($weightUnit, $validUnits)) {
                $weightUnit = 'kg'; // Default to kg if invalid or null
            }
            
            // Only include weight_unit if it's valid (never null)
            $variant['weight_unit'] = $weightUnit;
        }
        
        if (isset($productData['requires_shipping'])) {
            $variant['requires_shipping'] = (bool) $productData['requires_shipping'];
        }
        
        if (isset($productData['tracked'])) {
            $variant['inventory_management'] = $productData['tracked'] ? 'shopify' : null;
        }
        
        if (isset($productData['inventory_quantity']) && $productData['inventory_quantity'] !== null) {
            $variant['inventory_quantity'] = (int) $productData['inventory_quantity'];
        }

        // Always include variants array (Shopify requires at least one variant)
        $shopifyProduct['variants'] = [$variant];

        return $shopifyProduct;
    }

    /**
     * Convert Product model to array
     *
     * @param Product $product
     * @return array
     */
    private function productToArray(Product $product): array
    {
        $data = [
            'title' => $product->title,
            'description' => $product->description,
            'price' => $product->price,
            'compare_at_price' => $product->compare_at_price,
            'vendor' => $product->vendor,
            'product_type' => $product->product_type,
            'tags' => $product->tags,
            'status' => $product->status,
            'handle' => $product->handle,
            'sku' => $product->sku,
            'requires_shipping' => $product->requires_shipping,
            'tracked' => $product->tracked,
            'inventory_quantity' => $product->inventory_quantity,
            'meta_title' => $product->meta_title,
            'meta_description' => $product->meta_description,
            'images' => $product->images,
            'featured_image' => $product->featured_image,
            'template_suffix' => $product->template_suffix,
            'published' => $product->published,
        ];
        
        // Only include weight and weight_unit if weight is present and > 0
        // This prevents sending weight_unit as null to Shopify
        if ($product->weight !== null && $product->weight > 0) {
            $data['weight'] = $product->weight;
            // Ensure weight_unit is never null - default to 'kg' if null
            $data['weight_unit'] = $product->weight_unit ?? 'kg';
        }
        
        return $data;
    }

    /**
     * Determine Shopify status from product data
     *
     * @param array $productData
     * @return string
     */
    private function determineStatus(array $productData): string
    {
        // If published is false, set status to draft
        if (isset($productData['published']) && !$productData['published']) {
            return 'draft';
        }

        // Use status field if available
        if (isset($productData['status'])) {
            return $productData['status'];
        }

        return 'active';
    }
}
