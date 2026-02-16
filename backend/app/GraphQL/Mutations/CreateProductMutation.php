<?php

namespace App\GraphQL\Mutations;

use App\Contracts\Product\ProductRepositoryInterface;
use App\Events\ProductCreated;
use App\Exceptions\InvalidProductDataException;
use App\Models\Product;
use App\Services\Product\ProductShopifySyncService;
use App\Services\Product\ProductValidator;
use Illuminate\Support\Facades\Log;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * Create Product Mutation
 * 
 * Creates a new product locally and optionally syncs to Shopify
 * if sync_auto is enabled.
 * 
 * Demonstrates: Command Pattern, Observer Pattern
 */
class CreateProductMutation
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository,
        private readonly ProductShopifySyncService $shopifySyncService,
        private readonly ProductValidator $validator
    ) {
    }

    public function __invoke($rootValue, array $args, GraphQLContext $context): Product
    {
        $data = [
            'title' => $args['title'],
            'handle' => $args['handle'] ?? null,
            'description' => $args['description'] ?? null,
            'price' => (float) $args['price'],
            'compare_at_price' => isset($args['compare_at_price']) ? (float) $args['compare_at_price'] : null,
            'vendor' => $args['vendor'] ?? null,
            'product_type' => $args['product_type'] ?? null,
            'tags' => $args['tags'] ?? null,
            'status' => $args['status'] ?? 'active',
            'sku' => $args['sku'] ?? null,
            'weight' => isset($args['weight']) && $args['weight'] !== null ? (float) $args['weight'] : null,
            // Only set weight_unit if weight is present, and validate it
            'weight_unit' => isset($args['weight']) && $args['weight'] !== null 
                ? ($this->validateWeightUnit($args['weight_unit'] ?? 'kg'))
                : null,
            'requires_shipping' => $args['requires_shipping'] ?? true,
            'tracked' => $args['tracked'] ?? false,
            'inventory_quantity' => isset($args['inventory_quantity']) ? (int) $args['inventory_quantity'] : null,
            'meta_title' => $args['meta_title'] ?? null,
            'meta_description' => $args['meta_description'] ?? null,
            'template_suffix' => $args['template_suffix'] ?? null,
            'published' => $args['published'] ?? true,
            'published_at' => isset($args['published']) && $args['published'] ? now() : null,
            'sync_auto' => $args['sync_auto'] ?? false,
            'shopify_id' => null,
        ];

        // Validate data before creating
        $this->validator->validateCreate($data);

        $product = $this->productRepository->create($data);

        // Sync to Shopify if sync_auto is enabled
        if ($product->sync_auto) {
            try {
                $this->shopifySyncService->syncToShopify($product);
            } catch (\Exception $e) {
                Log::error('Auto-sync failed on product creation', [
                    'product_id' => $product->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // Dispatch event for other actions (Observer Pattern)
        event(new ProductCreated($product));

        return $product->fresh();
    }

    /**
     * Validate and normalize weight_unit
     *
     * @param string|null $weightUnit
     * @return string
     */
    private function validateWeightUnit(?string $weightUnit): string
    {
        $validUnits = ['kg', 'g', 'lb', 'oz'];
        if (empty($weightUnit) || !in_array($weightUnit, $validUnits)) {
            return 'kg'; // Default to kg if invalid
        }
        return $weightUnit;
    }
}
