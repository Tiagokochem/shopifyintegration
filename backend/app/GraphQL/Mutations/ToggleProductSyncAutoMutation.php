<?php

namespace App\GraphQL\Mutations;

use App\Contracts\Product\ProductRepositoryInterface;
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
        $product = $this->productRepository->findById($args['id']);

        if (!$product) {
            throw new \RuntimeException("Product with ID {$args['id']} not found");
        }

        $product = $this->productRepository->update($product, [
            'sync_auto' => $args['sync_auto'],
        ]);

        return $product->fresh();
    }
}
