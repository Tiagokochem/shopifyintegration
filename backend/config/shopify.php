<?php

return [
    'store_url' => env('SHOPIFY_STORE_URL'),
    'access_token' => env('SHOPIFY_ACCESS_TOKEN'),
    'api_version' => env('SHOPIFY_API_VERSION', '2024-01'), // Versão estável mais recente
    
    // Sync Strategy Configuration
    // Options: 'default', 'conservative', 'aggressive', 'selective'
    'sync_strategy' => env('SHOPIFY_SYNC_STRATEGY', 'default'),
    
    // Options for selective strategy
    'sync_strategy_options' => [
        'allowed_vendors' => env('SHOPIFY_ALLOWED_VENDORS') 
            ? explode(',', env('SHOPIFY_ALLOWED_VENDORS')) 
            : [],
        'min_price' => (float) env('SHOPIFY_MIN_PRICE', 0.0),
    ],
];
