<?php

namespace App\GraphQL\Mutations;

use App\Contracts\Product\ProductRepositoryInterface;
use App\Events\ProductCreated;
use App\Exceptions\InvalidProductDataException;
use App\Models\Product;
use App\Services\Product\ProductShopifySyncService;
use App\Services\Product\ProductValidator;
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
            'description' => $args['description'] ?? null,
            'price' => (float) $args['price'],
            'vendor' => $args['vendor'] ?? null,
            'product_type' => $args['product_type'] ?? null,
            'status' => $args['status'] ?? 'active',
            'sync_auto' => $args['sync_auto'] ?? false,
            'shopify_id' => null, // Will be set if synced to Shopify
        ];

        // Validate data before creating
        $this->validator->validateCreate($data);

        $product = $this->productRepository->create($data);

        // Sync to Shopify if sync_auto is enabled
        if ($product->sync_auto) {
            try {
                $this->shopifySyncService->syncToShopify($product);
            } catch (\Exception $e) {
                // Log error but don't fail the creation
                \Illuminate\Support\Facades\Log::error('Auto-sync failed on product creation', [
                    'product_id' => $product->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // Dispatch event for other actions (Observer Pattern)
        event(new ProductCreated($product));

        return $product->fresh();
    }
}
