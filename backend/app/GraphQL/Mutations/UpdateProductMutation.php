<?php

namespace App\GraphQL\Mutations;

use App\Contracts\Product\ProductRepositoryInterface;
use App\Events\ProductUpdated;
use App\Exceptions\InvalidProductDataException;
use App\Exceptions\ProductNotFoundException;
use App\Models\Product;
use App\Services\Product\ProductShopifySyncService;
use App\Services\Product\ProductValidator;
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
        if (isset($args['description'])) {
            $data['description'] = $args['description'];
        }
        if (isset($args['price'])) {
            $data['price'] = (float) $args['price'];
        }
        if (isset($args['vendor'])) {
            $data['vendor'] = $args['vendor'];
        }
        if (isset($args['product_type'])) {
            $data['product_type'] = $args['product_type'];
        }
        if (isset($args['status'])) {
            $data['status'] = $args['status'];
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
                \Illuminate\Support\Facades\Log::error('Auto-sync failed on product update', [
                    'product_id' => $product->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // Dispatch event (Observer Pattern)
        event(new ProductUpdated($product));

        return $product->fresh();
    }
}
