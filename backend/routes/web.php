<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'message' => 'Shopify Integration API',
        'version' => '1.0.0',
        'endpoints' => [
            'graphql' => url('/graphql'),
            'api' => url('/api'),
        ],
        'documentation' => [
            'graphql_examples' => 'See GRAPHQL_EXAMPLES.md',
            'graphql_endpoint' => url('/graphql'),
        ],
    ]);
});

// GraphQL endpoint info (GET requests)
Route::get('/graphql', function () {
    return response()->json([
        'message' => 'GraphQL Endpoint',
        'info' => 'This is a GraphQL endpoint. Send POST requests with a "query" parameter.',
        'example' => [
            'method' => 'POST',
            'url' => url('/graphql'),
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'body' => [
                'query' => '{ __typename }',
            ],
        ],
        'documentation' => 'See GRAPHQL_EXAMPLES.md for more examples',
    ]);
});
