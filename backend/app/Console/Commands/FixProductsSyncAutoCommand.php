<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class FixProductsSyncAutoCommand extends Command
{
    protected $signature = 'products:fix-sync-auto';

    protected $description = 'Fix sync_auto field for existing products (set to false if null)';

    public function handle(): int
    {
        $this->info('Fixing sync_auto field for existing products...');

        $updated = Product::whereNull('sync_auto')
            ->update(['sync_auto' => false]);

        $this->info("Updated {$updated} products.");

        return Command::SUCCESS;
    }
}
