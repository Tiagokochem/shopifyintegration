<?php

namespace App\GraphQL\Mutations;

use App\Contracts\Product\ProductRepositoryInterface;
use App\Models\Product;
use App\Services\Product\ProductShopifySyncService;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * Sync Product to Shopify Mutation
 * 
 * Manually syncs a product to Shopify (push operation).
 * This allows manual synchronization regardless of sync_auto setting.
 * 
 * Demonstrates: Command Pattern
 */
class SyncProductToShopifyMutation
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

        return $this->shopifySyncService->syncToShopify($product);
    }
}
