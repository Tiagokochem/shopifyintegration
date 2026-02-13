<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class CheckProductsCommand extends Command
{
    protected $signature = 'products:check';

    protected $description = 'Check products in database';

    public function handle(): int
    {
        $count = Product::count();
        $this->info("Total products in database: {$count}");

        if ($count > 0) {
            $products = Product::take(5)->get(['id', 'title', 'price', 'status']);
            $this->table(
                ['ID', 'Title', 'Price', 'Status'],
                $products->map(fn($p) => [$p->id, $p->title, $p->price, $p->status])->toArray()
            );
        } else {
            $this->warn('No products found. Run: php artisan shopify:sync-products');
        }

        return Command::SUCCESS;
    }
}
