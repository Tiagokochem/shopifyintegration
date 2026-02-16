<?php

namespace App\GraphQL\Mutations;

use App\Contracts\Product\ProductRepositoryInterface;
use App\Events\ProductUpdated;
use App\Exceptions\InvalidProductDataException;
use App\Exceptions\ProductNotFoundException;
use App\Models\Product;
use App\Services\Product\ProductShopifySyncService;
use App\Services\Product\ProductValidator;
use Illuminate\Support\Facades\Log;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * Update Product Mutation
 * 
 * Updates a product locally and optionally syncs to Shopify
 * if sync_auto is enabled.
 * 
 * Demonstrates: Command Pattern, Observer Pattern
 */
class UpdateProductMutation
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository,
        private readonly ProductShopifySyncService $shopifySyncService,
        private readonly ProductValidator $validator
    ) {
    }

    public function __invoke($rootValue, array $args, GraphQLContext $context): Product
    {
        $product = $this->productRepository->findByIdOrFail($args['id']);

        if (!$product) {
            throw new ProductNotFoundException($args['id']);
        }

        $data = [];
        
        if (isset($args['title'])) {
            $data['title'] = $args['title'];
        }
        if (isset($args['handle'])) {
            $data['handle'] = $args['handle'];
        }
        if (isset($args['description'])) {
            $data['description'] = $args['description'];
        }
        if (isset($args['price'])) {
            $data['price'] = (float) $args['price'];
        }
        if (isset($args['compare_at_price'])) {
            $data['compare_at_price'] = (float) $args['compare_at_price'];
        }
        if (isset($args['vendor'])) {
            $data['vendor'] = $args['vendor'];
        }
        if (isset($args['product_type'])) {
            $data['product_type'] = $args['product_type'];
        }
        if (isset($args['tags'])) {
            $data['tags'] = $args['tags'];
        }
        if (isset($args['status'])) {
            $data['status'] = $args['status'];
        }
        if (isset($args['sku'])) {
            $data['sku'] = $args['sku'];
        }
        // Handle weight and weight_unit together - they must be consistent
        if (isset($args['weight'])) {
            $data['weight'] = (float) $args['weight'];
            // If weight is set, ensure weight_unit is also set and valid
            if (isset($args['weight_unit'])) {
                $data['weight_unit'] = $this->validateWeightUnit($args['weight_unit']);
            } elseif ($data['weight'] > 0) {
                // If weight is > 0 but weight_unit not provided, default to 'kg'
                $data['weight_unit'] = 'kg';
            }
        } elseif (isset($args['weight_unit'])) {
            // If weight_unit is set but weight is not, only set weight_unit if weight exists in product
            if ($product->weight !== null && $product->weight > 0) {
                $data['weight_unit'] = $this->validateWeightUnit($args['weight_unit']);
            }
        }
        if (isset($args['requires_shipping'])) {
            $data['requires_shipping'] = (bool) $args['requires_shipping'];
        }
        if (isset($args['tracked'])) {
            $data['tracked'] = (bool) $args['tracked'];
        }
        if (isset($args['inventory_quantity'])) {
            $data['inventory_quantity'] = (int) $args['inventory_quantity'];
        }
        if (isset($args['meta_title'])) {
            $data['meta_title'] = $args['meta_title'];
        }
        if (isset($args['meta_description'])) {
            $data['meta_description'] = $args['meta_description'];
        }
        if (isset($args['template_suffix'])) {
            $data['template_suffix'] = $args['template_suffix'];
        }
        if (isset($args['published'])) {
            $data['published'] = (bool) $args['published'];
            $data['published_at'] = $args['published'] ? now() : null;
        }
        if (isset($args['sync_auto'])) {
            $data['sync_auto'] = (bool) $args['sync_auto'];
        }

        // Validate data before updating
        if (!empty($data)) {
            $this->validator->validateUpdate($data);
        }

        $product = $this->productRepository->update($product, $data);

        // Sync to Shopify if sync_auto is enabled
        if ($product->sync_auto) {
            try {
                $this->shopifySyncService->syncToShopify($product);
            } catch (\Exception $e) {
                Log::error('Auto-sync failed on product update', [
                    'product_id' => $product->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // Dispatch event (Observer Pattern)
        event(new ProductUpdated($product));

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
