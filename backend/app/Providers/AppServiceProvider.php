<?php

namespace App\Providers;

use App\Contracts\Product\ProductRepositoryInterface;
use App\Contracts\Product\ProductSyncStrategyInterface;
use App\Contracts\Shopify\ShopifyApiInterface;
use App\Contracts\Shopify\ShopifyProductApiInterface;
use App\Repositories\ProductRepository;
use App\Contracts\Product\ProductTransformerInterface;
use App\Services\Product\DefaultProductSyncStrategy;
use App\Services\Product\ProductSyncStrategyFactory;
use App\Services\Product\ProductTransformer;
use App\Services\Shopify\ShopifyApiClient;
use App\Services\Shopify\ShopifyProductService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Shopify API bindings
        $this->app->singleton(ShopifyApiInterface::class, ShopifyApiClient::class);
        $this->app->singleton(ShopifyProductApiInterface::class, ShopifyProductService::class);

        // Product bindings
        $this->app->singleton(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->singleton(ProductTransformerInterface::class, ProductTransformer::class);
        $this->app->singleton(ProductSyncStrategyFactory::class);
        
        // Bind strategy using factory (allows dynamic strategy selection)
        $this->app->bind(ProductSyncStrategyInterface::class, function ($app) {
            $factory = $app->make(ProductSyncStrategyFactory::class);
            return $factory->createFromConfig();
        });
    }

    public function boot(): void
    {
        //
    }
}
