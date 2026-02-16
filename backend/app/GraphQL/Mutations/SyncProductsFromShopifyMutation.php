<?php

namespace App\GraphQL\Mutations;

use App\Services\Product\ProductSyncService;
use Illuminate\Support\Facades\Log;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

/**
 * Sync Products From Shopify Mutation
 * 
 * Synchronizes products from Shopify to local database.
 * 
 * Demonstrates: Command Pattern
 */
class SyncProductsFromShopifyMutation
{
    public function __construct(
        private readonly ProductSyncService $syncService
    ) {
    }

    public function __invoke($rootValue, array $args, GraphQLContext $context): array
    {
        $limit = $args['limit'] ?? 250;

        try {
            $stats = $this->syncService->syncProducts($limit);

            return [
                'success' => true,
                'message' => 'Products synchronized successfully',
                'stats' => [
                    'created' => $stats['created'],
                    'updated' => $stats['updated'],
                    'skipped' => $stats['skipped'],
                    'errors' => $stats['errors'],
                ],
            ];
        } catch (\Exception $e) {
            Log::error('Failed to sync products from Shopify', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'message' => 'Failed to sync products: ' . $e->getMessage(),
                'stats' => [
                    'created' => 0,
                    'updated' => 0,
                    'skipped' => 0,
                    'errors' => 0,
                ],
            ];
        }
    }
}
