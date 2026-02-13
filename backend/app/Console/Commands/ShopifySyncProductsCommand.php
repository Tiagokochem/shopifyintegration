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

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Synchronization failed: ' . $e->getMessage());

            return Command::FAILURE;
        }
    }
}
