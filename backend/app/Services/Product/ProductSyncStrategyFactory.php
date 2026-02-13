<?php

namespace App\Services\Product;

use App\Contracts\Product\ProductSyncStrategyInterface;
use App\Contracts\Product\ProductTransformerInterface;
use App\Services\Product\Strategies\AggressiveSyncStrategy;
use App\Services\Product\Strategies\ConservativeSyncStrategy;
use App\Services\Product\Strategies\SelectiveSyncStrategy;
use InvalidArgumentException;

/**
 * Product Sync Strategy Factory
 * 
 * Creates appropriate sync strategy based on configuration.
 * This demonstrates Factory Pattern and allows easy extension
 * without modifying existing code (Open/Closed Principle).
 * 
 * Demonstrates: Factory Pattern, Open/Closed Principle, Dependency Inversion
 */
class ProductSyncStrategyFactory
{
    public function __construct(
        private readonly ProductTransformerInterface $transformer
    ) {
    }

    /**
     * Create sync strategy based on type
     * 
     * @param string $type Strategy type: 'default', 'conservative', 'aggressive', 'selective'
     * @param array $options Additional options for selective strategy
     * @return ProductSyncStrategyInterface
     * @throws InvalidArgumentException
     */
    public function create(string $type = 'default', array $options = []): ProductSyncStrategyInterface
    {
        return match ($type) {
            'default' => new DefaultProductSyncStrategy($this->transformer),
            'conservative' => new ConservativeSyncStrategy($this->transformer),
            'aggressive' => new AggressiveSyncStrategy($this->transformer),
            'selective' => new SelectiveSyncStrategy(
                $this->transformer,
                $options['allowed_vendors'] ?? [],
                $options['min_price'] ?? 0.0
            ),
            default => throw new InvalidArgumentException("Unknown sync strategy type: {$type}"),
        };
    }

    /**
     * Create strategy from configuration
     * 
     * @return ProductSyncStrategyInterface
     */
    public function createFromConfig(): ProductSyncStrategyInterface
    {
        $type = config('shopify.sync_strategy', 'default');
        $options = config('shopify.sync_strategy_options', []);

        return $this->create($type, $options);
    }
}
