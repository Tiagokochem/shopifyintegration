#!/bin/bash

echo "🔍 Verificando se tudo está funcionando..."
echo ""

# Cores
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Contador de sucessos e falhas
SUCCESS=0
FAIL=0

# Função para verificar status
check_status() {
    local name=$1
    local command=$2
    local expected=$3
    
    echo -n "Verificando $name... "
    if eval "$command" > /dev/null 2>&1; then
        echo -e "${GREEN}✅ OK${NC}"
        ((SUCCESS++))
        return 0
    else
        echo -e "${RED}❌ FALHOU${NC}"
        ((FAIL++))
        return 1
    fi
}

# 1. Verificar containers Docker
echo "═══════════════════════════════════════════════════════════"
echo "1. Containers Docker"
echo "═══════════════════════════════════════════════════════════"
check_status "Containers rodando" "docker compose ps | grep -q 'Up'"
echo ""

# Verificar cada container individualmente
echo "Containers individuais:"
docker compose ps --format "table {{.Name}}\t{{.Status}}" | grep shopify_integration
echo ""

# 2. Verificar Backend API
echo "═══════════════════════════════════════════════════════════"
echo "2. Backend API (Laravel)"
echo "═══════════════════════════════════════════════════════════"
HTTP_CODE=$(curl -s -o /dev/null -w '%{http_code}' http://localhost:8082 2>/dev/null || echo "000")
if [ "$HTTP_CODE" != "000" ] && [ "$HTTP_CODE" != "" ]; then
    echo -e "${GREEN}✅ Backend API respondendo${NC}"
    echo "   Status HTTP: $HTTP_CODE"
    echo "   URL: http://localhost:8082"
    ((SUCCESS++))
else
    echo -e "${RED}❌ Backend API não está respondendo${NC}"
    echo "   URL: http://localhost:8082"
    echo "   💡 Verifique: docker compose logs nginx php"
    ((FAIL++))
fi
echo ""

# 3. Verificar GraphQL
echo "═══════════════════════════════════════════════════════════"
echo "3. GraphQL Endpoint"
echo "═══════════════════════════════════════════════════════════"
GRAPHQL_RESPONSE=$(curl -s -X POST http://localhost:8082/graphql \
    -H "Content-Type: application/json" \
    -d '{"query":"{ __typename }"}' 2>/dev/null)

if echo "$GRAPHQL_RESPONSE" | grep -q "data\|errors"; then
    echo -e "${GREEN}✅ GraphQL respondendo corretamente${NC}"
    echo "   URL: http://localhost:8082/graphql"
    ((SUCCESS++))
elif [ ! -z "$GRAPHQL_RESPONSE" ]; then
    # Se retornou algo mas não é JSON válido, pode ser HTML (GraphQL Playground)
    HTTP_CODE=$(curl -s -o /dev/null -w '%{http_code}' http://localhost:8082/graphql 2>/dev/null || echo "000")
    if [ "$HTTP_CODE" = "200" ]; then
        echo -e "${GREEN}✅ GraphQL endpoint acessível${NC}"
        echo "   URL: http://localhost:8082/graphql"
        echo "   (Retornando HTML - GraphQL Playground disponível)"
        ((SUCCESS++))
    else
        echo -e "${RED}❌ GraphQL não está respondendo${NC}"
        echo "   URL: http://localhost:8082/graphql"
        ((FAIL++))
    fi
else
    echo -e "${RED}❌ GraphQL não está respondendo${NC}"
    echo "   URL: http://localhost:8082/graphql"
    ((FAIL++))
fi
echo ""

# 4. Verificar Frontend
echo "═══════════════════════════════════════════════════════════"
echo "4. Frontend (Nuxt.js)"
echo "═══════════════════════════════════════════════════════════"
# Verificar se container node está rodando
NODE_STATUS=$(docker compose ps node --format "{{.Status}}" 2>/dev/null | head -n 1)
if [ -z "$NODE_STATUS" ] || ! echo "$NODE_STATUS" | grep -q "Up"; then
    echo -e "${RED}❌ Container Node não está rodando${NC}"
    echo "   Status: $NODE_STATUS"
    echo "   💡 Execute: docker compose up -d node"
    echo "   💡 Verifique logs: docker compose logs node"
    ((FAIL++))
else
    HTTP_CODE=$(curl -s -o /dev/null -w '%{http_code}' http://localhost:3002 2>/dev/null || echo "000")
    if [ "$HTTP_CODE" = "200" ] || [ "$HTTP_CODE" = "302" ]; then
        echo -e "${GREEN}✅ Frontend respondendo${NC}"
        echo "   Status HTTP: $HTTP_CODE"
        echo "   URL: http://localhost:3002"
        ((SUCCESS++))
    else
        echo -e "${YELLOW}⚠️  Frontend container rodando mas não respondendo${NC}"
        echo "   Status Container: $NODE_STATUS"
        echo "   Status HTTP: $HTTP_CODE"
        echo "   URL: http://localhost:3002"
        echo "   💡 Verifique logs: docker compose logs node"
        echo "   💡 Pode estar instalando dependências ainda..."
        ((FAIL++))
    fi
fi
echo ""

# 5. Verificar Banco de Dados
echo "═══════════════════════════════════════════════════════════"
echo "5. Banco de Dados (PostgreSQL)"
echo "═══════════════════════════════════════════════════════════"
if docker compose exec -T postgres pg_isready -U shopify_user -d shopify_integration > /dev/null 2>&1; then
    echo -e "${GREEN}✅ PostgreSQL conectável${NC}"
    echo "   Host: localhost:5433"
    echo "   Database: shopify_integration"
    echo "   User: shopify_user"
    ((SUCCESS++))
else
    echo -e "${RED}❌ PostgreSQL não está conectável${NC}"
    echo "   💡 Verifique: docker compose logs postgres"
    ((FAIL++))
fi
echo ""

# 6. Verificar Redis
echo "═══════════════════════════════════════════════════════════"
echo "6. Redis (Cache)"
echo "═══════════════════════════════════════════════════════════"
check_status "Redis conectável" "docker compose exec -T redis redis-cli ping | grep -q 'PONG'"
if [ $? -eq 0 ]; then
    echo "   Host: localhost:6380"
fi
echo ""

# 7. Verificar Laravel
echo "═══════════════════════════════════════════════════════════"
echo "7. Laravel (Configuração)"
echo "═══════════════════════════════════════════════════════════"
check_status "Laravel configurado" "docker compose exec -T php php artisan --version > /dev/null 2>&1"
if [ $? -eq 0 ]; then
    LARAVEL_VERSION=$(docker compose exec -T php php artisan --version 2>/dev/null | head -n 1)
    echo "   $LARAVEL_VERSION"
    
    # Verificar se APP_KEY está configurada
    APP_KEY=$(docker compose exec -T php php artisan tinker --execute="echo config('app.key');" 2>/dev/null | grep -v "Psy Shell" | grep -v ">>>" | tr -d ' ')
    if [ ! -z "$APP_KEY" ] && [ "$APP_KEY" != "null" ]; then
        echo -e "   ${GREEN}✅ APP_KEY configurada${NC}"
        ((SUCCESS++))
    else
        echo -e "   ${YELLOW}⚠️  APP_KEY não configurada (execute: docker compose exec php php artisan key:generate)${NC}"
        ((FAIL++))
    fi
fi
echo ""

# 8. Verificar dependências
echo "═══════════════════════════════════════════════════════════"
echo "8. Dependências"
echo "═══════════════════════════════════════════════════════════"
if docker compose exec -T php test -d /var/www/html/vendor 2>/dev/null; then
    echo -e "${GREEN}✅ Vendor PHP instalado${NC}"
    ((SUCCESS++))
else
    echo -e "${RED}❌ Vendor PHP não instalado${NC}"
    echo "   💡 Execute: docker compose exec php composer install"
    ((FAIL++))
fi

if docker compose exec -T node test -d /app/node_modules 2>/dev/null; then
    echo -e "${GREEN}✅ Node modules instalado${NC}"
    ((SUCCESS++))
else
    echo -e "${YELLOW}⚠️  Node modules não instalado${NC}"
    echo "   💡 Execute: docker compose exec node npm install"
    echo "   💡 OU reinicie o container: docker compose restart node"
    echo "   (O container node instala automaticamente ao iniciar)"
    ((FAIL++))
fi
echo ""

# Resumo final
echo "═══════════════════════════════════════════════════════════"
echo "📊 RESUMO"
echo "═══════════════════════════════════════════════════════════"
TOTAL=$((SUCCESS + FAIL))
if [ $FAIL -eq 0 ]; then
    echo -e "${GREEN}✅ Tudo funcionando! ($SUCCESS/$TOTAL verificações passaram)${NC}"
    echo ""
    echo "🌐 Acesse:"
    echo "   Backend API:    http://localhost:8082"
    echo "   GraphQL:        http://localhost:8082/graphql"
    echo "   Frontend:       http://localhost:3002"
    exit 0
else
    echo -e "${YELLOW}⚠️  Alguns problemas encontrados ($SUCCESS/$TOTAL verificações passaram)${NC}"
    echo ""
    echo "💡 Próximos passos:"
    echo "   1. Verifique os logs: docker compose logs"
    echo "   2. Execute o setup novamente: ./setup.sh"
    echo "   3. Veja o README.md para troubleshooting"
    exit 1
fi
