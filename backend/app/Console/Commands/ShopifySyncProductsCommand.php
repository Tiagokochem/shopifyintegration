<?php

namespace App\Console\Commands;

use App\Services\Product\ProductSyncService;
use Illuminate\Console\Command;

class ShopifySyncProductsCommand extends Command
{
    protected $signature = 'shopify:sync-products 
                            {--limit=250 : Maximum number of products to sync}';

    protected $description = 'Synchronize products from Shopify';

    public function __construct(
        private readonly ProductSyncService $syncService
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->info('Starting product synchronization...');

        $limit = (int) $this->option('limit');

        try {
            // Verificar se as credenciais estão configuradas
            $storeUrl = config('shopify.store_url');
            $accessToken = config('shopify.access_token');

            if (!$storeUrl || !$accessToken) {
                $this->error('Shopify credentials are not configured!');
                $this->info('Please set SHOPIFY_STORE_URL and SHOPIFY_ACCESS_TOKEN in your .env file');
                return Command::FAILURE;
            }

            $this->info("Connecting to Shopify store: {$storeUrl}");

            // Verificar quantos produtos existem no Shopify
            $shopifyProductApi = app(\App\Contracts\Shopify\ShopifyProductApiInterface::class);
            $productsCount = $shopifyProductApi->getProductsCount();
            $this->info("Products count from API: {$productsCount}");

            // Tentar buscar produtos diretamente mesmo se count for 0
            // (pode haver produtos que não são contados corretamente)
            $this->info('Attempting to fetch products directly from API...');
            $products = $shopifyProductApi->getProducts($limit);
            $actualCount = count($products);
            $this->info("Found {$actualCount} products when fetching directly");

            if ($actualCount === 0 && $productsCount === 0) {
                $this->warn('No products found in Shopify store. Please add products to your store first.');
                $this->info('Note: Make sure products are published (not just drafts) in your Shopify admin.');
                return Command::SUCCESS;
            }

            if ($actualCount > 0 && $productsCount === 0) {
                $this->warn("API count returned 0, but found {$actualCount} products when fetching directly.");
                $this->info('Proceeding with synchronization...');
            }

            // Usar os produtos já buscados se disponíveis, senão deixar o service buscar
            if ($actualCount > 0) {
                $this->info("Synchronizing {$actualCount} products...");
            }

            $stats = $this->syncService->syncProducts($limit);

            $this->info('Synchronization completed!');
            $this->table(
                ['Action', 'Count'],
                [
                    ['Created', $stats['created']],
                    ['Updated', $stats['updated']],
                    ['Skipped', $stats['skipped']],
                    ['Errors', $stats['errors']],
                ]
            );

            if ($stats['created'] === 0 && $stats['updated'] === 0 && $stats['skipped'] === 0) {
                $this->warn('No products were processed. This might indicate an issue with the API response format.');
                $this->info('Check the logs for more details: docker compose logs php --tail 50');
            }

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Synchronization failed: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());

            return Command::FAILURE;
        }
    }
}
