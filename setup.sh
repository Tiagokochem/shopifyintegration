#!/bin/bash

set -e

echo "🚀 Setting up Shopify Integration Project..."
echo ""

# Função para verificar se containers estão rodando
check_containers() {
    echo "🔍 Verificando containers..."
    if ! docker compose ps | grep -q "Up"; then
        echo "⚠️  Containers não estão rodando. Iniciando..."
        docker compose up -d
        echo "⏳ Aguardando containers ficarem prontos..."
        sleep 15
    fi
}

# Função para criar diretórios do Laravel
create_laravel_directories() {
    echo "📁 Criando diretórios necessários do Laravel..."
    mkdir -p backend/bootstrap/cache
    mkdir -p backend/storage/{app,framework/{cache,sessions,views},logs}
    mkdir -p backend/storage/framework/cache/data
    echo "✅ Diretórios criados"
}

# Função para ajustar permissões
fix_permissions() {
    echo "🔐 Ajustando permissões..."
    USER_ID=$(id -u)
    GROUP_ID=$(id -g)
    
    sudo chown -R $USER_ID:$GROUP_ID backend/ 2>/dev/null || true
    sudo chmod -R 775 backend/ 2>/dev/null || true
    sudo chmod -R 777 backend/storage backend/bootstrap/cache 2>/dev/null || true
    
    echo "✅ Permissões ajustadas"
}

# 1. Criar diretórios ANTES de tudo
echo "═══════════════════════════════════════════════════════════"
echo "PASSO 1: Criando diretórios do Laravel"
echo "═══════════════════════════════════════════════════════════"
create_laravel_directories
echo ""

# 2. Criar .env se não existir
echo "═══════════════════════════════════════════════════════════"
echo "PASSO 2: Configurando arquivo .env"
echo "═══════════════════════════════════════════════════════════"
if [ ! -f backend/.env ]; then
    echo "📋 Criando arquivo .env..."
    if [ -f ./create-env.sh ]; then
        ./create-env.sh
    elif [ -f backend/.env.example ]; then
        cp backend/.env.example backend/.env
        echo "✅ Arquivo .env criado a partir de .env.example"
    else
        echo "⚠️  AVISO: .env.example não encontrado."
        echo "   Crie backend/.env manualmente (veja backend/env.example para referência)"
        echo "   Continuando mesmo assim..."
    fi
else
    echo "✅ Arquivo .env já existe"
fi
echo ""

# 3. Build e start containers
echo "═══════════════════════════════════════════════════════════"
echo "PASSO 3: Construindo e iniciando containers Docker"
echo "═══════════════════════════════════════════════════════════"
echo "📦 Construindo containers..."
docker compose build

echo "🔧 Iniciando containers..."
docker compose up -d

echo "⏳ Aguardando serviços ficarem prontos..."
sleep 15

# Verificar se containers estão rodando
check_containers
echo ""

# 4. Ajustar permissões ANTES de instalar dependências
echo "═══════════════════════════════════════════════════════════"
echo "PASSO 4: Ajustando permissões"
echo "═══════════════════════════════════════════════════════════"
fix_permissions
echo ""

# 5. Instalar dependências PHP
echo "═══════════════════════════════════════════════════════════"
echo "PASSO 5: Instalando dependências PHP (Laravel)"
echo "═══════════════════════════════════════════════════════════"
echo "📥 Instalando dependências (isso pode levar alguns minutos)..."
if docker compose exec -T php composer install --no-interaction --prefer-dist --optimize-autoloader; then
    echo "✅ Dependências PHP instaladas com sucesso"
else
    echo "⚠️  Erro ao instalar dependências. Ajustando permissões novamente..."
    fix_permissions
    echo "🔄 Tentando instalar novamente..."
    docker compose exec -T php composer install --no-interaction --prefer-dist --optimize-autoloader
    echo "✅ Dependências PHP instaladas"
fi
echo ""

# 6. Gerar chave da aplicação
echo "═══════════════════════════════════════════════════════════"
echo "PASSO 6: Gerando chave da aplicação Laravel"
echo "═══════════════════════════════════════════════════════════"
if docker compose exec -T php php artisan key:generate --force 2>/dev/null; then
    echo "✅ Chave da aplicação gerada"
else
    echo "⚠️  Não foi possível gerar a chave (pode já estar configurada)"
fi
echo ""

# 7. Executar migrations
echo "═══════════════════════════════════════════════════════════"
echo "PASSO 7: Executando migrations do banco de dados"
echo "═══════════════════════════════════════════════════════════"
if docker compose exec -T php php artisan migrate --force 2>/dev/null; then
    echo "✅ Migrations executadas com sucesso"
else
    echo "⚠️  Erro ao executar migrations (banco pode não estar pronto ainda)"
    echo "   Execute manualmente depois: docker compose exec php php artisan migrate"
fi
echo ""

# 8. Instalar dependências frontend
echo "═══════════════════════════════════════════════════════════"
echo "PASSO 8: Instalando dependências do frontend (Nuxt.js)"
echo "═══════════════════════════════════════════════════════════"
echo "📦 Instalando dependências Node (isso pode levar alguns minutos)..."
if docker compose exec -T node npm install 2>/dev/null; then
    echo "✅ Dependências frontend instaladas"
else
    echo "⚠️  Erro ao instalar dependências frontend"
    echo "   Execute manualmente depois: docker compose exec node npm install"
fi
echo ""

# Resumo final
echo "═══════════════════════════════════════════════════════════"
echo "✅ SETUP COMPLETO!"
echo "═══════════════════════════════════════════════════════════"
echo ""
echo "🌐 URLs da Aplicação:"
echo "   Backend API:    http://localhost:8082"
echo "   GraphQL:        http://localhost:8082/graphql"
echo "   Frontend:       http://localhost:3002"
echo ""
echo "📝 Próximos Passos:"
echo "   1. Configure as credenciais do Shopify no arquivo backend/.env:"
echo "      SHOPIFY_STORE_URL=https://your-store.myshopify.com"
echo "      SHOPIFY_ACCESS_TOKEN=your-access-token"
echo ""
echo "   2. Sincronize produtos do Shopify:"
echo "      docker compose exec php php artisan shopify:sync-products"
echo ""
echo "   3. Execute os testes (opcional):"
echo "      Backend:  docker compose exec php php artisan test"
echo "      Frontend: docker compose exec node npm run test:e2e"
echo ""
echo "📚 Documentação:"
echo "   - README.md - Documentação completa"
echo "   - PORTAS.md - Configuração de portas"
echo "   - GRAPHQL_EXAMPLES.md - Exemplos de queries GraphQL"
echo ""
