<?php

namespace App\GraphQL\Mutations;

use App\Contracts\Product\ProductRepositoryInterface;
use App\Events\ProductUpdated;
use App\Models\Product;
use App\Services\Product\ProductShopifySyncService;
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
        private readonly ProductShopifySyncService $shopifySyncService
    ) {
    }

    public function __invoke($rootValue, array $args, GraphQLContext $context): Product
    {
        $product = $this->productRepository->findById($args['id']);

        if (!$product) {
            throw new \RuntimeException("Product with ID {$args['id']} not found");
        }

        $data = [];
        
        if (isset($args['title'])) {
            $data['title'] = $args['title'];
        }
        if (isset($args['description'])) {
            $data['description'] = $args['description'];
        }
        if (isset($args['price'])) {
            $data['price'] = $args['price'];
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
            $data['sync_auto'] = $args['sync_auto'];
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
