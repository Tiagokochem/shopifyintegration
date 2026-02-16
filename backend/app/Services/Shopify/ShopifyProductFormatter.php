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
        // If it's already a formatted Shopify product array (has 'variants' key), return as-is
        if (is_array($productData) && isset($productData['variants']) && is_array($productData['variants'])) {
            return $productData;
        }
        
        // Handle Product model instance
        if ($productData instanceof Product) {
            $productData = $this->productToArray($productData);
        }

        $shopifyProduct = [
            'title' => $productData['title'] ?? '',
            'status' => $this->determineStatus($productData),
        ];

        // Only include body_html if description is not null and not empty
        if (isset($productData['description']) && $productData['description'] !== null && trim($productData['description']) !== '') {
            $shopifyProduct['body_html'] = $productData['description'];
        }

        // Only include vendor if it's not null and not empty
        if (isset($productData['vendor']) && $productData['vendor'] !== null && trim($productData['vendor']) !== '') {
            $shopifyProduct['vendor'] = $productData['vendor'];
        }

        // Only include product_type if it's not null and not empty
        if (isset($productData['product_type']) && $productData['product_type'] !== null && trim($productData['product_type']) !== '') {
            $shopifyProduct['product_type'] = $productData['product_type'];
        }

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
        
        // Price is required for variants - must be a valid positive number
        // This should have been validated before calling format(), but double-check here
        if (isset($productData['price']) && $productData['price'] !== null) {
            // Handle different price types (string, float, decimal object)
            $priceValue = $productData['price'];
            if (is_string($priceValue)) {
                $priceValue = (float) $priceValue;
            } elseif (is_object($priceValue) && method_exists($priceValue, '__toString')) {
                $priceValue = (float) (string) $priceValue;
            } else {
                $priceValue = (float) $priceValue;
            }
            
            if ($priceValue > 0) {
                $variant['price'] = (string) $priceValue;
            } else {
                \Illuminate\Support\Facades\Log::error('Product price is 0 or negative in ShopifyProductFormatter', [
                    'price_original' => $productData['price'],
                    'price_value' => $priceValue,
                    'product_title' => $productData['title'] ?? 'unknown',
                ]);
                throw new \InvalidArgumentException(
                    'Product price must be greater than 0. Current price: ' . $priceValue
                );
            }
        } else {
            // This should never happen if validation is correct, but log error if it does
            \Illuminate\Support\Facades\Log::error('Product price is missing in ShopifyProductFormatter', [
                'price' => $productData['price'] ?? null,
                'product_title' => $productData['title'] ?? 'unknown',
                'all_data_keys' => array_keys($productData),
            ]);
            throw new \InvalidArgumentException(
                'Product price is required and must be greater than 0. ' .
                'Current price: ' . ($productData['price'] ?? 'null')
            );
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
        // Always include price - it's required and we'll validate it later
        // Convert decimal cast to float to ensure proper handling
        $price = $product->price;
        
        if ($price !== null) {
            // Handle decimal cast - it may return a string or Decimal object
            if (is_string($price)) {
                $price = (float) $price;
            } elseif (is_object($price) && method_exists($price, '__toString')) {
                $price = (float) (string) $price;
            } else {
                $price = (float) $price;
            }
        }

        $data = [
            'title' => $product->title,
            'status' => $product->status,
            'published' => $product->published,
            'price' => $price, // Always include price, even if 0 or null
        ];

        // Only include description if it's not null or empty to avoid overwriting Shopify data
        if ($product->description !== null && trim($product->description) !== '') {
            $data['description'] = $product->description;
        }

        if ($product->compare_at_price !== null && (float) $product->compare_at_price > 0) {
            $data['compare_at_price'] = $product->compare_at_price;
        }

        if ($product->vendor !== null && trim($product->vendor) !== '') {
            $data['vendor'] = $product->vendor;
        }

        if ($product->product_type !== null && trim($product->product_type) !== '') {
            $data['product_type'] = $product->product_type;
        }

        if ($product->tags !== null && trim($product->tags) !== '') {
            $data['tags'] = $product->tags;
        }

        if ($product->handle !== null && trim($product->handle) !== '') {
            $data['handle'] = $product->handle;
        }

        if ($product->sku !== null && trim($product->sku) !== '') {
            $data['sku'] = $product->sku;
        }

        $data['requires_shipping'] = $product->requires_shipping ?? true;
        $data['tracked'] = $product->tracked ?? false;

        if ($product->inventory_quantity !== null) {
            $data['inventory_quantity'] = $product->inventory_quantity;
        }

        if ($product->meta_title !== null && trim($product->meta_title) !== '') {
            $data['meta_title'] = $product->meta_title;
        }

        if ($product->meta_description !== null && trim($product->meta_description) !== '') {
            $data['meta_description'] = $product->meta_description;
        }

        if ($product->images !== null && is_array($product->images) && !empty($product->images)) {
            $data['images'] = $product->images;
        }

        if ($product->featured_image !== null && trim($product->featured_image) !== '') {
            $data['featured_image'] = $product->featured_image;
        }

        if ($product->template_suffix !== null && trim($product->template_suffix) !== '') {
            $data['template_suffix'] = $product->template_suffix;
        }
        
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
