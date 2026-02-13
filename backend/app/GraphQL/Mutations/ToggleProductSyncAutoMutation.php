<?php

namespace App\GraphQL\Mutations;

use App\Contracts\Product\ProductRepositoryInterface;
use App\Exceptions\ProductNotFoundException;
use App\Models\Product;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * Toggle Product Sync Auto Mutation
 * 
 * Enables or disables automatic synchronization for a product.
 * 
 * Demonstrates: Command Pattern
 */
class ToggleProductSyncAutoMutation
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository
    ) {
    }

    public function __invoke($rootValue, array $args, GraphQLContext $context): Product
    {
        $product = $this->productRepository->findByIdOrFail($args['id']);

        if (!$product) {
            throw new ProductNotFoundException($args['id']);
        }

        $product = $this->productRepository->update($product, [
            'sync_auto' => (bool) $args['sync_auto'],
        ]);

        $product = $this->productRepository->update($product, [
            'sync_auto' => $args['sync_auto'],
        ]);

        return $product->fresh();
    }
}
