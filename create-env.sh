#!/bin/bash

# Script para criar o arquivo .env

ENV_FILE="backend/.env"
ENV_EXAMPLE="backend/.env.example"

echo "🔧 Criando arquivo .env..."

if [ -f "$ENV_FILE" ]; then
    echo "⚠️  Arquivo .env já existe. Não será sobrescrito."
    exit 0
fi

if [ -f "$ENV_EXAMPLE" ]; then
    echo "📋 Copiando de .env.example..."
    cp "$ENV_EXAMPLE" "$ENV_FILE"
    echo "✅ Arquivo .env criado a partir de .env.example"
else
    echo "📝 Criando .env com valores padrão..."
    cat > "$ENV_FILE" << 'EOF'
APP_NAME="Shopify Integration"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_TIMEZONE=UTC
APP_URL=http://localhost:8080

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file
APP_MAINTENANCE_STORE=database

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=pgsql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=shopify_integration
DB_USERNAME=shopify_user
DB_PASSWORD=shopify_password

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database
CACHE_PREFIX=

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

SHOPIFY_STORE_URL=
SHOPIFY_ACCESS_TOKEN=
SHOPIFY_API_VERSION=2024-10

LIGHTHOUSE_CACHE_ENABLE=false
LIGHTHOUSE_CACHE_KEY=lighthouse-schema
LIGHTHOUSE_CACHE_TTL=3600
EOF
    echo "✅ Arquivo .env criado com valores padrão"
fi

echo ""
echo "📝 Próximos passos:"
echo "   1. Edite backend/.env e configure SHOPIFY_STORE_URL e SHOPIFY_ACCESS_TOKEN"
echo "   2. Execute: docker compose exec php php artisan key:generate"
echo ""
