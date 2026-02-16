# Shopify Integration Project

Technical demonstration project showcasing Shopify integration using Laravel 11 + PHP 8.2, Laravel Lighthouse (GraphQL), Nuxt.js 3, Docker, with focus on SOLID principles, clean architecture, and code quality.

> **Quick Guides:**
> - **🚀 Quick Setup:** Run `./setup.sh` (automates everything!)
> - **✅ Verify Setup:** Run `./verify.sh` (checks if everything is OK!)
> - **📊 GraphQL Examples:** See `GRAPHQL_EXAMPLES.md`

## Overview

This project implements a Shopify API integration for product synchronization, demonstrating:

- **SOLID Principles** rigorously applied
- **Clean Architecture** with clear separation of concerns
- **Testability** through interfaces and dependency injection
- **Modern Stack**: Laravel 11, PHP 8.2, Laravel Lighthouse, Nuxt.js 3, TypeScript
- **Complete Docker Environment** fully functional
- **Comprehensive Tests**: Pest (backend) and Playwright (E2E frontend)

## Architecture

### SOLID Principles Applied

#### Single Responsibility Principle (SRP)
Each class has a single, well-defined responsibility:

- `ShopifyApiClient`: Handles HTTP communication with Shopify API
- `ShopifyProductService`: Fetches and transforms products from Shopify API
- `ProductSyncService`: Handles synchronization logic (comparison, creation, update)
- `ProductRepository`: Handles data persistence
- `ProductTransformer`: Transforms Shopify data to internal format
- `ShopifyProductFormatter`: Formats product data for Shopify API (centralized)

#### Open/Closed Principle (OCP)
Interfaces allow extension without modification:

- `ShopifyApiInterface`: Allows different implementations (REST, GraphQL, Mock for tests)
- `ProductRepositoryInterface`: Allows different storages (Database, Cache, etc)
- `ProductSyncStrategyInterface`: Allows different synchronization strategies

#### Liskov Substitution Principle (LSP)
Concrete implementations can be substituted without breaking client code. For example, we can replace `ProductRepository` with an in-memory implementation for tests without changing the code that uses it.

#### Interface Segregation Principle (ISP)
Specific and focused interfaces:

- `ShopifyProductApiInterface`: Only product-related methods
- `ShopifyApiInterface`: Generic HTTP communication methods

#### Dependency Inversion Principle (DIP)
Services depend on abstractions (interfaces), not concrete implementations. All dependencies are injected via Laravel Service Container.

### Design Patterns Implemented

The project demonstrates several design patterns beyond SOLID principles:

- **Strategy Pattern**: Multiple sync strategies (Default, Conservative, Aggressive, Selective)
- **Factory Pattern**: Factory to create strategies based on configuration
- **Observer Pattern**: Events and listeners to decouple actions (logs, notifications)
- **Command Pattern**: Encapsulated commands for sync operations
- **Decorator Pattern**: Decorators to add functionality (retry, throttling) without modifying code

### Directory Structure

```
integration-rule/
├── docker/
│   ├── php/
│   │   ├── Dockerfile
│   │   └── php.ini
│   └── nginx/
│       └── default.conf
├── docker-compose.yml
├── setup.sh
├── backend/ (Laravel)
│   ├── app/
│   │   ├── Contracts/ (Interfaces)
│   │   │   ├── Shopify/
│   │   │   │   ├── ShopifyApiInterface.php
│   │   │   │   └── ShopifyProductApiInterface.php
│   │   │   └── Product/
│   │   │       ├── ProductRepositoryInterface.php
│   │   │       ├── ProductSyncStrategyInterface.php
│   │   │       └── ProductTransformerInterface.php
│   │   ├── Services/
│   │   │   ├── Shopify/
│   │   │   │   ├── ShopifyApiClient.php
│   │   │   │   ├── ShopifyProductService.php
│   │   │   │   └── ShopifyProductFormatter.php
│   │   │   └── Product/
│   │   │       ├── ProductSyncService.php
│   │   │       ├── ProductShopifySyncService.php
│   │   │       ├── ProductTransformer.php
│   │   │       ├── ProductValidator.php
│   │   │       └── Strategies/
│   │   ├── Repositories/
│   │   │   └── ProductRepository.php
│   │   ├── Models/
│   │   │   └── Product.php
│   │   ├── GraphQL/
│   │   │   ├── Mutations/
│   │   │   ├── Queries/
│   │   │   └── Types/
│   │   ├── Exceptions/
│   │   ├── Events/
│   │   ├── Listeners/
│   │   └── Providers/
│   ├── tests/
│   └── graphql/
│       └── schema.graphql
└── frontend/ (Nuxt.js)
    ├── pages/
    ├── composables/
    └── tests/
```

## Quick Start

### Prerequisites

- Docker and Docker Compose installed
- Internet access for downloading dependencies

### Installation

1. **Configure Shopify credentials** in `backend/.env`:

```env
SHOPIFY_STORE_URL=https://your-store.myshopify.com
SHOPIFY_ACCESS_TOKEN=your-access-token
SHOPIFY_API_VERSION=2024-01
```

2. **Run the setup script**:

```bash
./setup.sh
```

This script automatically:
- Creates required directories
- Sets correct permissions
- Builds and starts Docker containers
- Installs PHP and Node dependencies
- Generates Laravel application key
- Runs database migrations

3. **Verify installation**:

```bash
./verify.sh
```

### Access Points

- **Backend API**: http://localhost:8082
- **GraphQL Endpoint**: http://localhost:8082/graphql
- **Frontend**: http://localhost:3002

## Usage

### Sync Products from Shopify

```bash
docker compose exec php php artisan shopify:sync-products
```

### Create/Update/Delete Products

Use the frontend interface at http://localhost:3002/products or GraphQL mutations:

```graphql
mutation {
  createProduct(
    title: "New Product"
    price: 99.99
    sync_auto: true
  ) {
    id
    title
    shopify_id
  }
}
```

### GraphQL Queries

```graphql
query {
  products(first: 10, search: "test") {
    data {
      id
      title
      price
      vendor
      sync_auto
    }
    paginatorInfo {
      total
      currentPage
    }
  }
}
```

## Features

### Product Management

- **CRUD Operations**: Create, read, update, and delete products
- **Bidirectional Sync**: Sync from Shopify (pull) and to Shopify (push)
- **Auto Sync**: Automatic synchronization when products are created/updated locally
- **Manual Sync**: Manual sync button for each product
- **Sync Status**: Track sync status and timestamps

### GraphQL API

- Query `products`: List products with filters and pagination
- Query `product(id)`: Get specific product
- Mutation `createProduct`: Create new product
- Mutation `updateProduct`: Update existing product
- Mutation `deleteProduct`: Delete product
- Mutation `syncProductToShopify`: Manual sync to Shopify
- Mutation `toggleProductSyncAuto`: Enable/disable auto sync

### Frontend

- Modern, responsive UI with TailwindCSS
- Product grid layout with cards
- Real-time search and filters
- Pagination
- Auto sync toggle per product
- Sync status indicators

## Testing

### Backend Tests (Pest)

```bash
docker compose exec php php artisan test
```

### Frontend E2E Tests (Playwright)

```bash
docker compose exec node npm run test:e2e
```

## Troubleshooting

### Port Conflicts

If ports are already in use, modify `docker-compose.yml`:

- **8082**: Backend API (Nginx)
- **3002**: Frontend (Nuxt.js)
- **5433**: PostgreSQL (external)
- **6380**: Redis (external)

### Common Issues

**"Shopify credentials are not configured"**
- Ensure `backend/.env` exists with `SHOPIFY_STORE_URL` and `SHOPIFY_ACCESS_TOKEN`
- Run: `docker compose exec php php artisan config:clear`

**"bootstrap/cache directory must be present and writable"**
- Run `./setup.sh` (handles this automatically)
- Or manually: `mkdir -p backend/bootstrap/cache backend/storage/{app,framework/{cache,sessions,views},logs}`

**Products not syncing**
- Verify Shopify credentials
- Check token has `read_products` permission
- Ensure products exist in Shopify store
- Check logs: `docker compose logs php --tail 50`

## Technology Stack

### Backend
- Laravel 11
- PHP 8.2
- Laravel Lighthouse (GraphQL)
- PostgreSQL
- Redis
- Pest (Testing)
- Guzzle (HTTP Client)

### Frontend
- Nuxt.js 3
- TypeScript
- Apollo Client
- TailwindCSS
- Playwright (E2E Testing)

### Infrastructure
- Docker & Docker Compose
- Nginx
- PHP-FPM

## Code Quality Highlights

1. **SOLID Principles**: Single responsibility, open/closed, Liskov substitution, interface segregation, dependency inversion
2. **Design Patterns**: Strategy, Factory, Observer, Command, Decorator
3. **Clean Architecture**: Clear separation between domain, application, and infrastructure layers
4. **Type Safety**: TypeScript on frontend, strict types in PHP
5. **Error Handling**: Custom exceptions with proper error messages
6. **Validation**: Input validation before processing
7. **DRY**: Centralized formatting logic to avoid duplication

## License

This project was created as a technical demonstration for recruitment purposes.
