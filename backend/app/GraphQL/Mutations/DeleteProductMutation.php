<?php

namespace App\GraphQL\Mutations;

use App\Contracts\Product\ProductRepositoryInterface;
use App\Events\ProductDeleted;
use App\Models\Product;
use App\Services\Product\ProductShopifySyncService;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * Delete Product Mutation
 * 
 * Deletes a product locally and optionally from Shopify
 * if sync_auto is enabled.
 * 
 * Demonstrates: Command Pattern, Observer Pattern
 */
class DeleteProductMutation
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository,
        private readonly ProductShopifySyncService $shopifySyncService
    ) {
    }

    public function __invoke($rootValue, array $args, GraphQLContext $context): bool
    {
        $product = $this->productRepository->findById($args['id']);

        if (!$product) {
            throw new \RuntimeException("Product with ID {$args['id']} not found");
        }

        // Delete from Shopify if sync_auto is enabled
        if ($product->sync_auto && $product->shopify_id) {
            try {
                $this->shopifySyncService->deleteFromShopify($product);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Auto-sync delete failed', [
                    'product_id' => $product->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // Dispatch event before deletion (Observer Pattern)
        event(new ProductDeleted($product));

        // Delete from local database
        $this->productRepository->delete($product);

        return true;
    }
}
