<?php

namespace App\Contracts\Product;

/**
 * Sync Product Command Interface
 * 
 * Encapsulates product sync operations as commands.
 * This allows operations to be queued, logged, undone, and composed.
 * 
 * Demonstrates: Command Pattern, Interface Segregation Principle
 */
interface SyncProductCommandInterface
{
    /**
     * Execute the sync command
     * 
     * @return string Result: 'created', 'updated', or 'skipped'
     */
    public function execute(): string;

    /**
     * Get the Shopify product data
     * 
     * @return array
     */
    public function getShopifyProduct(): array;
}
