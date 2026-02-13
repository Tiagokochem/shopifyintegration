<?php

namespace App\Providers;

use App\Events\ProductSynced;
use App\Events\ProductSyncFailed;
use App\Listeners\LogProductSync;
use App\Listeners\LogProductSyncFailure;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

/**
 * Event Service Provider
 * 
 * Registers event listeners for the application.
 * This demonstrates the Observer Pattern implementation using Laravel's event system.
 * 
 * Demonstrates: Observer Pattern, Dependency Injection, Single Responsibility
 */
class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        ProductSynced::class => [
            LogProductSync::class,
        ],
        ProductSyncFailed::class => [
            LogProductSyncFailure::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
