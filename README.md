# Shopify Integration Project

Projeto técnico demonstrando integração com Shopify usando Laravel 11 + PHP 8.2, Laravel Lighthouse (GraphQL), Nuxt.js 3, Docker, com foco em SOLID principles, arquitetura limpa e qualidade de código.

> **📋 Guias Rápidos:**
> - **🚀 Instalação automática:** Execute `./setup.sh` (faz tudo automaticamente!)
> - **✅ Verificar funcionamento:** Execute `./verify.sh` (testa se tudo está OK!)
> - **📊 Exemplos GraphQL:** `GRAPHQL_EXAMPLES.md`

## 📋 Visão Geral

Este projeto implementa uma integração com a API do Shopify para sincronização de produtos, demonstrando:

- **SOLID Principles** aplicados rigorosamente
- **Arquitetura limpa** com separação de responsabilidades
- **Testabilidade** através de interfaces e dependency injection
- **Stack moderna**: Laravel 11, PHP 8.2, Laravel Lighthouse, Nuxt.js 3, TypeScript
- **Ambiente Docker** completo e funcional
- **Testes abrangentes**: Pest (backend) e Playwright (E2E frontend)

## 🏗️ Arquitetura

### Princípios SOLID Aplicados

#### Single Responsibility Principle (SRP)
Cada classe tem uma única responsabilidade bem definida:

- `ShopifyApiClient`: Responsável apenas por comunicação HTTP com Shopify API
- `ShopifyProductService`: Responsável por buscar e transformar produtos da API Shopify
- `ProductSyncService`: Responsável pela lógica de sincronização (comparação, criação, atualização)
- `ProductRepository`: Responsável pela persistência de dados
- `ProductTransformer`: Responsável por transformar dados Shopify para formato interno

#### Open/Closed Principle (OCP)
Interfaces permitem extensão sem modificação:

- `ShopifyApiInterface`: Permite diferentes implementações (REST, GraphQL, Mock para testes)
- `ProductRepositoryInterface`: Permite diferentes storages (Database, Cache, etc)
- `ProductSyncStrategyInterface`: Permite diferentes estratégias de sincronização

#### Liskov Substitution Principle (LSP)
Implementações concretas podem ser substituídas sem quebrar o código cliente. Por exemplo, podemos substituir `ProductRepository` por uma implementação em memória para testes sem alterar o código que a utiliza.

#### Interface Segregation Principle (ISP)
Interfaces específicas e focadas:

- `ShopifyProductApiInterface`: Apenas métodos relacionados a produtos
- `ShopifyApiInterface`: Métodos genéricos de comunicação HTTP

#### Dependency Inversion Principle (DIP)
Services dependem de abstrações (interfaces), não de implementações concretas. Todas as dependências são injetadas via Laravel Service Container.

### Padrões de Design Implementados

O projeto demonstra vários padrões de design além dos princípios SOLID:

- **Strategy Pattern**: Múltiplas estratégias de sincronização (Default, Conservative, Aggressive, Selective)
- **Factory Pattern**: Factory para criar estratégias baseadas em configuração
- **Observer Pattern**: Eventos e listeners para desacoplar ações (logs, notificações)
- **Command Pattern**: Comandos encapsulados para operações de sincronização
- **Decorator Pattern**: Decorators para adicionar funcionalidades (retry, throttling) sem modificar código

### Estrutura de Diretórios

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
│   │   ├── Contracts/ (Interfaces - SOLID)
│   │   │   ├── Shopify/
│   │   │   │   ├── ShopifyApiInterface.php
│   │   │   │   └── ShopifyProductApiInterface.php
│   │   │   └── Product/
│   │   │       ├── ProductRepositoryInterface.php
│   │   │       └── ProductSyncStrategyInterface.php
│   │   ├── Services/
│   │   │   ├── Shopify/
│   │   │   │   ├── ShopifyApiClient.php
│   │   │   │   └── ShopifyProductService.php
│   │   │   └── Product/
│   │   │       ├── ProductSyncService.php
│   │   │       ├── ProductTransformer.php
│   │   │       └── DefaultProductSyncStrategy.php
│   │   ├── Repositories/
│   │   │   └── ProductRepository.php
│   │   ├── Models/
│   │   │   └── Product.php
│   │   ├── GraphQL/
│   │   │   ├── Types/
│   │   │   │   └── ProductType.php
│   │   │   └── Queries/
│   │   │       └── ProductsQuery.php
│   │   ├── Console/Commands/
│   │   │   └── ShopifySyncProductsCommand.php
│   │   └── Providers/
│   │       └── AppServiceProvider.php
│   ├── tests/
│   │   ├── Feature/
│   │   │   ├── Shopify/
│   │   │   │   └── ProductSyncTest.php
│   │   │   └── GraphQL/
│   │   │       └── ProductsQueryTest.php
│   │   └── Unit/
│   │       ├── Services/
│   │       │   └── ProductSyncServiceTest.php
│   │       └── Repositories/
│   │           └── ProductRepositoryTest.php
│   └── graphql/
│       └── schema.graphql
└── frontend/ (Nuxt.js)
    ├── pages/
    │   └── products/
    │       └── index.vue
    ├── composables/
    │   └── useProducts.ts
    └── tests/
        └── e2e/
            └── products.spec.ts
```

## 🚀 Como Executar

### ⚡ Instalação Rápida (Recomendado)

**Execute o script de setup que faz tudo automaticamente:**

```bash
# 1. Configure as credenciais do Shopify no backend/.env (veja passo 2 abaixo)

# 2. Execute o setup completo
./setup.sh
```

**Pronto!** O script cria diretórios, ajusta permissões, instala dependências e configura tudo automaticamente.

### Pré-requisitos

- Docker e Docker Compose instalados
- Acesso à internet para baixar dependências

### Configuração Inicial (Detalhada)

1. **Clone o repositório** (se aplicável)

2. **Configure as variáveis de ambiente**:

   **IMPORTANTE**: Crie o arquivo `.env` manualmente no diretório `backend/`:
   
   ```bash
   # Opção 1: Se existir .env.example
   cp backend/.env.example backend/.env
   
   # Opção 2: Criar manualmente
   touch backend/.env
   # Edite o arquivo e adicione as variáveis necessárias (veja backend/env.example para referência)
   ```

   Edite `backend/.env` e configure as credenciais do Shopify:
   ```env
   SHOPIFY_STORE_URL=https://your-store.myshopify.com
   SHOPIFY_ACCESS_TOKEN=your-access-token
   SHOPIFY_API_VERSION=2024-10
   ```
   
   **Nota**: Veja `backend/env.example` para o conteúdo completo do `.env` com todas as variáveis disponíveis.

3. **Execute o script de setup** (recomendado para primeira instalação):

   ```bash
   ./setup.sh
   ```

   Este script faz **TUDO automaticamente** e de forma robusta:
   - ✅ Cria todos os diretórios necessários do Laravel (`bootstrap/cache`, `storage/*`, etc)
   - ✅ Ajusta permissões corretamente ANTES de instalar dependências
   - ✅ Cria arquivo `.env` se não existir
   - ✅ Constrói e inicia os containers Docker
   - ✅ Instala dependências PHP (Laravel) com tratamento de erros
   - ✅ Instala dependências Node (Nuxt.js)
   - ✅ Gera a chave da aplicação Laravel
   - ✅ Executa as migrations do banco de dados
   - ✅ Verifica cada passo e tenta corrigir erros automaticamente

   **🎯 Recomendado:** Use `./setup.sh` para evitar todos os problemas que enfrentamos!
   
   **Se preferir fazer manualmente**, veja os passos detalhados abaixo.

### ✅ Verificar se está funcionando

Após executar o setup, verifique se tudo está funcionando:

```bash
./verify.sh
```

Este script verifica automaticamente:
- ✅ Containers Docker rodando
- ✅ Backend API respondendo (http://localhost:8082)
- ✅ GraphQL endpoint funcionando (http://localhost:8082/graphql)
- ✅ Frontend respondendo (http://localhost:3002)
- ✅ Banco de dados conectável
- ✅ Redis funcionando
- ✅ Laravel configurado corretamente
- ✅ Dependências instaladas

**Ou teste manualmente no navegador/terminal:**
- Backend API: http://localhost:8082
- GraphQL: http://localhost:8082/graphql
- Frontend: http://localhost:3002

**Teste rápido via terminal:**
```bash
# Verificar containers
docker compose ps

# Testar backend
curl http://localhost:8082

# Testar GraphQL
curl -X POST http://localhost:8082/graphql \
  -H "Content-Type: application/json" \
  -d '{"query":"{ __typename }"}'
```

### Executando Manualmente

Se preferir executar manualmente:

```bash
# Build e start dos containers
docker compose build
docker compose up -d

# Instalar dependências PHP
docker compose exec php composer install

# Gerar chave da aplicação
docker compose exec php php artisan key:generate

# Executar migrations
docker compose exec php php artisan migrate

# Instalar dependências frontend
docker compose exec node npm install
```

### Acessando a Aplicação

- **Backend API**: http://localhost:8082
- **GraphQL Endpoint**: http://localhost:8082/graphql
- **Frontend**: http://localhost:3002 (porta alterada para evitar conflitos)

**💡 Dica:** Execute `./verify.sh` para verificar se tudo está funcionando corretamente!

### Comandos Úteis

```bash
# 🔧 DIAGNÓSTICO RÁPIDO - Use este primeiro se tiver problemas!
docker compose exec php php artisan shopify:quick-fix

# Sincronizar produtos do Shopify
docker compose exec php php artisan shopify:sync-products

# Testar conexão com Shopify
docker compose exec php php artisan shopify:test-connection

# Diagnosticar token do Shopify
docker compose exec php php artisan shopify:diagnose-token

# Executar testes backend
docker compose exec php php artisan test

# Executar testes frontend E2E
docker compose exec node npm run test:e2e

# Acessar shell do container PHP
docker compose exec php bash

# Ver logs
docker compose logs -f php
```

## 🔄 Como Testar a Integração com Shopify

### ⚠️ IMPORTANTE: Por que a página está vazia?

A página `http://localhost:3002/products` está vazia porque:

1. **Você ainda não sincronizou produtos do Shopify** - O banco de dados está vazio
2. **Você precisa ter produtos cadastrados na sua loja Shopify** - A integração busca produtos que já existem no Shopify
3. **As credenciais do Shopify precisam estar configuradas** - Sem isso, a sincronização não funciona

### Passo 1: Verificar se você tem produtos no Shopify

**Você precisa ter:**
- Uma loja Shopify ativa (pode ser uma loja de teste)
- Pelo menos 1 produto cadastrado na loja
- Um **Admin API access token** configurado

**Como criar uma loja de teste:**
1. Acesse https://partners.shopify.com
2. Crie uma conta de desenvolvedor
3. Crie uma loja de desenvolvimento
4. Adicione alguns produtos de teste na loja

### Passo 2: Configurar Credenciais do Shopify

**No arquivo `backend/.env`, configure:**

```env
SHOPIFY_STORE_URL=https://sua-loja.myshopify.com
SHOPIFY_ACCESS_TOKEN=seu-token-aqui
SHOPIFY_API_VERSION=2024-10
```

**Como obter o Access Token:**
1. Acesse sua loja Shopify como admin
2. Vá em **Settings > Apps and sales channels > Develop apps**
3. Crie um novo app ou use um existente
4. Vá em **API credentials**
5. Gere um **Admin API access token** com permissões de leitura de produtos
6. Copie o token e cole no `.env`

**⚠️ IMPORTANTE:** O token precisa ter permissão para ler produtos (`read_products`).

### Passo 3: Verificar se as Credenciais Estão Configuradas

```bash
# Verificar se as variáveis estão no .env
docker compose exec php cat .env | grep SHOPIFY

# Deve mostrar algo como:
# SHOPIFY_STORE_URL=https://sua-loja.myshopify.com
# SHOPIFY_ACCESS_TOKEN=shpat_xxxxx
# SHOPIFY_API_VERSION=2024-10
```

**Se não aparecer nada ou estiver vazio:**
- Edite o arquivo `backend/.env` manualmente
- Adicione as 3 variáveis acima
- Certifique-se de que não há espaços extras ou aspas desnecessárias

### Passo 4: Sincronizar Produtos do Shopify

**Execute o comando de sincronização:**

```bash
docker compose exec php php artisan shopify:sync-products
```

**Saída esperada (sucesso):**
```
Starting product synchronization...
Synchronization completed!
+---------+-------+
| Action  | Count |
+---------+-------+
| Created |   10  |
| Updated |    0  |
| Skipped |    0  |
| Errors  |    0  |
+---------+-------+
```

**Se der erro:**
- Verifique se as credenciais estão corretas
- Verifique se você tem produtos na loja Shopify
- Verifique os logs: `docker compose logs php --tail 50`
- Veja a seção "Troubleshooting" abaixo

### Passo 5: Verificar se os Produtos Foram Sincronizados

**Opção 1: Via banco de dados**
```bash
docker compose exec postgres psql -U shopify_user -d shopify_integration -c "SELECT COUNT(*) FROM products;"
```

**Opção 2: Via GraphQL**
```bash
curl -X POST http://localhost:8082/graphql \
  -H "Content-Type: application/json" \
  -d '{"query":"{ products(first: 10) { paginatorInfo { total } } }"}'
```

**Opção 3: Via Frontend**
- Acesse http://localhost:3002/products
- Você deve ver os produtos listados

### Passo 6: Testar a Página de Produtos

1. **Acesse:** http://localhost:3002/products
2. **Você deve ver:**
   - Lista de produtos sincronizados do Shopify
   - Filtros de busca (search, vendor, product_type)
   - Paginação (se houver mais de 10 produtos)
   - Preços, status, e informações de cada produto

**Se ainda estiver vazio:**
- Verifique se a sincronização foi executada com sucesso (Passo 4)
- Verifique se há produtos no banco (Passo 5)
- Verifique o console do navegador (F12) para erros JavaScript
- Verifique os logs do backend: `docker compose logs php --tail 50`

### Troubleshooting da Integração

**Erro: "Shopify credentials are not configured"**
- Verifique se o arquivo `backend/.env` existe
- Verifique se as variáveis `SHOPIFY_STORE_URL` e `SHOPIFY_ACCESS_TOKEN` estão preenchidas
- Execute: `docker compose exec php php artisan config:clear`

**Erro: "Shopify API request failed: 401 Unauthorized"**
- O token de acesso está incorreto ou expirado
- Verifique se o token tem permissão `read_products`
- Gere um novo token no Shopify

**Erro: "Shopify API request failed: 404 Not Found"**
- A URL da loja está incorreta
- Verifique se `SHOPIFY_STORE_URL` está no formato: `https://sua-loja.myshopify.com`
- Não inclua `/admin` ou `/api` na URL

**Sincronização executou mas "Created: 0"**
- Você não tem produtos na loja Shopify
- Adicione produtos na loja primeiro
- Verifique se os produtos estão com status "active" ou "draft"

**Produtos sincronizados mas não aparecem no frontend**
- Verifique se o frontend está rodando: `docker compose ps | grep node`
- Verifique o console do navegador (F12) para erros
- Verifique se o GraphQL está respondendo: `curl http://localhost:8082/graphql`

## 🧪 Guia Passo a Passo para Testes

### Passo 1: Verificar Pré-requisitos

Certifique-se de que você tem:
- Docker e Docker Compose instalados
- Arquivo `.env` criado em `backend/` com as credenciais do Shopify
- Portas configuradas (podem ser alteradas no `docker-compose.yml` se necessário):
  - **8082**: Backend API (Nginx)
  - **3002**: Frontend (Nuxt.js)
  - **5433**: PostgreSQL (mapeado externamente, interno usa 5432)
  - **6380**: Redis (mapeado externamente, interno usa 6379)

### Passo 2: Criar o Arquivo .env

```bash
# Execute o script auxiliar
./create-env.sh

# OU copie manualmente
cp backend/.env.example backend/.env
```

**Configure as credenciais do Shopify no arquivo `backend/.env`:**
```env
SHOPIFY_STORE_URL=https://your-store.myshopify.com
SHOPIFY_ACCESS_TOKEN=your-access-token-here
SHOPIFY_API_VERSION=2024-10
```

### Passo 3: Subir os Containers

```bash
# Construir e iniciar todos os containers
docker compose up -d
```

**Verificar se todos os containers estão rodando:**
```bash
docker compose ps
```

Você deve ver 5 containers com status "Up":
- `shopify_integration_postgres`
- `shopify_integration_redis`
- `shopify_integration_php`
- `shopify_integration_nginx`
- `shopify_integration_node`

### Passo 4: Criar Diretórios e Ajustar Permissões (OBRIGATÓRIO)

**⚠️ IMPORTANTE:** O Laravel precisa de diretórios específicos com permissões corretas.

**O script `setup.sh` já faz isso automaticamente!** Se preferir fazer manualmente:
```bash
# Criar diretórios
mkdir -p backend/bootstrap/cache
mkdir -p backend/storage/{app,framework/{cache,sessions,views},logs}

# Ajustar permissões
sudo chmod -R 775 backend/
sudo chmod -R 777 backend/storage backend/bootstrap/cache
```

Este script irá:
- Criar todos os diretórios necessários do Laravel (`bootstrap/cache`, `storage/*`, etc)
- Ajustar permissões para permitir escrita
- Garantir que o Laravel possa funcionar corretamente

**Se você estiver instalando em outro ambiente:**
- Certifique-se de que os diretórios `backend/bootstrap/cache` e `backend/storage/*` existem
- Execute `./setup.sh` (faz tudo automaticamente) ou ajuste permissões manualmente
- Veja a seção "Troubleshooting" para mais detalhes

### Passo 5: Instalar Dependências do Laravel (OBRIGATÓRIO)

**⚠️ CRÍTICO:** Sem este passo, a aplicação não funcionará! O erro "vendor/autoload.php not found" ocorre porque as dependências do Laravel ainda não foram instaladas.

**Instalar dependências do backend (Laravel):**
```bash
docker compose exec php composer install
```

**Aguarde a instalação completa** (pode levar alguns minutos na primeira vez). Isso instalará todas as dependências do Laravel, incluindo:
- Laravel Framework
- Laravel Lighthouse (GraphQL)
- Guzzle (para API Shopify)
- Pest (testes)
- E todas as outras dependências listadas no `composer.json`

**Se receber erro sobre `bootstrap/cache` ou `storage`:**
```bash
# Execute novamente o script de permissões
# O setup.sh já faz isso automaticamente, mas se precisar fazer manualmente:
mkdir -p backend/bootstrap/cache backend/storage/{app,framework/{cache,sessions,views},logs}
sudo chmod -R 775 backend/
sudo chmod -R 777 backend/storage backend/bootstrap/cache

# Tente instalar novamente
docker compose exec php composer install
```

**Instalar dependências do frontend (Nuxt.js):**
```bash
docker compose exec node npm install
```

**Aguarde a instalação completa** (pode levar alguns minutos na primeira vez).

### Passo 6: Gerar Chave da Aplicação

```bash
docker compose exec php php artisan key:generate
```

Isso preencherá automaticamente a `APP_KEY` no arquivo `.env`.

### Passo 7: Executar Migrations

```bash
docker compose exec php php artisan migrate
```

Isso criará a tabela `products` no banco de dados PostgreSQL.

### Passo 8: Sincronizar Produtos do Shopify

```bash
# Sincronizar produtos (padrão: 250 produtos)
docker compose exec php php artisan shopify:sync-products

# OU sincronizar quantidade específica
docker compose exec php php artisan shopify:sync-products --limit=50
```

**Verificar se os produtos foram sincronizados:**
```bash
docker compose exec php php artisan tinker
# No tinker:
>>> \App\Models\Product::count()
```

### Passo 9: Testar a API Backend

**⚠️ Certifique-se de que executou o Passo 4 (composer install) antes de testar!**

**Testar endpoint raiz:**
```bash
curl http://localhost:8082
```

**Resposta esperada:**
```json
{"message":"Shopify Integration API","version":"1.0.0"}
```

**Se você receber erro sobre "vendor/autoload.php":**
- Execute: `docker compose exec php composer install`
- Aguarde a instalação completa
- Tente novamente

### Passo 10: Testar GraphQL

**Query simples para listar produtos:**
```bash
curl -X POST http://localhost:8082/graphql \
  -H "Content-Type: application/json" \
  -d '{
    "query": "query { products(first: 10) { data { id title price vendor } paginatorInfo { total } } }"
  }'
```

**Query com filtro de busca:**
```bash
curl -X POST http://localhost:8082/graphql \
  -H "Content-Type: application/json" \
  -d '{
    "query": "query { products(first: 10, search: \"test\") { data { id title price } paginatorInfo { total } } }"
  }'
```

**Resposta esperada:**
```json
{
  "data": {
    "products": {
      "data": [
        {
          "id": "1",
          "title": "Nome do Produto",
          "price": 99.99,
          "vendor": "Vendor Name"
        }
      ],
      "paginatorInfo": {
        "total": 10
      }
    }
  }
}
```

### Passo 11: Acessar o Frontend

Abra no navegador: **http://localhost:3002**

Você deve ver:
- Página inicial com link para produtos
- Página de produtos com listagem, filtros e paginação

### Passo 12: Executar Testes Automatizados

**Testes Backend (Pest):**
```bash
docker compose exec php php artisan test
```

**Saída esperada:**
```
PASS  Tests\Unit\Services\ProductSyncServiceTest
PASS  Tests\Unit\Repositories\ProductRepositoryTest
PASS  Tests\Feature\Shopify\ProductSyncTest
PASS  Tests\Feature\GraphQL\ProductsQueryTest

Tests:  8 passed
```

**Testes Frontend E2E (Playwright):**
```bash
docker compose exec node npm run test:e2e
```

## 🔍 Troubleshooting

### Erro: "port is already allocated" (Porta em uso)

**Problema:** Uma porta configurada já está em uso por outro processo.

**Solução:** Alterar a porta no `docker-compose.yml` para uma porta livre.

**Portas configuradas por padrão:**
- **8082**: Backend API (Nginx)
- **3002**: Frontend (Nuxt.js)
- **5433**: PostgreSQL (externo) - interno usa 5432
- **6380**: Redis (externo) - interno usa 6379

**Exemplo de alteração no `docker-compose.yml`:**
```yaml
# Se porta 3002 estiver ocupada, use 3003, 3004, etc
node:
  ports:
    - "3003:3000"  # Altere o primeiro número para porta livre

# Se porta 5433 estiver ocupada, use 5434, 5435, etc
postgres:
  ports:
    - "5434:5432"  # Altere o primeiro número para porta livre

# Se porta 6380 estiver ocupada, use 6381, 6382, etc
redis:
  ports:
    - "6381:6379"  # Altere o primeiro número para porta livre
```

**Importante:** 
- O primeiro número é a porta **externa** (no seu host)
- O segundo número é a porta **interna** do container (não altere)
- Se mudar PostgreSQL ou Redis, não precisa alterar nada no `.env` (eles se comunicam internamente)

**Verificar portas disponíveis:**
```bash
# Verificar portas em uso
lsof -i :8082 -i :3002 -i :5433 -i :6380

# OU verificar containers Docker
docker compose ps
```


### Erro: "Connection refused" ao acessar API

**Verificar se os containers estão rodando:**
```bash
docker compose ps
```

**Verificar logs:**
```bash
docker compose logs php
docker compose logs nginx
```

**Reiniciar containers:**
```bash
docker compose restart
```

### Erro: "Failed to connect to database"

**Verificar se o PostgreSQL está saudável:**
```bash
docker compose ps postgres
```

**Verificar logs do PostgreSQL:**
```bash
docker compose logs postgres
```

**Aguardar o banco estar pronto:**
```bash
# O healthcheck deve mostrar "healthy" após alguns segundos
docker compose ps postgres
```

### Erro ao sincronizar produtos do Shopify

**Verificar credenciais no `.env`:**
```bash
cat backend/.env | grep SHOPIFY
```

**Verificar formato da URL:**
- ✅ Correto: `https://your-store.myshopify.com`
- ❌ Errado: `your-store.myshopify.com` (sem https://)

**Testar conexão manualmente:**
```bash
docker compose exec php php artisan tinker
# No tinker:
>>> $client = app(\App\Contracts\Shopify\ShopifyApiInterface::class);
>>> $client->get('/products/count.json');
```

### Erro: "bootstrap/cache directory must be present and writable"

**Problema:** O Laravel precisa de diretórios específicos com permissões corretas.

**Solução:** O script `setup.sh` já faz isso automaticamente! Se preferir fazer manualmente:
```bash
# Criar diretórios
mkdir -p backend/bootstrap/cache
mkdir -p backend/storage/{app,framework/{cache,sessions,views},logs}

# Ajustar permissões
sudo chmod -R 775 backend/
sudo chmod -R 777 backend/storage backend/bootstrap/cache
```

### Erro: "@nuxtjs/apollo@^5.0.0" não encontrado (Frontend)

**Problema:** O pacote `@nuxtjs/apollo` versão 5.0.0 não existe ainda.

**Solução:** Removemos a dependência `@nuxtjs/apollo` e estamos usando `@vue/apollo-composable` diretamente, que é mais simples e funciona perfeitamente com Nuxt 3. O `package.json` já está atualizado.

**Se ainda houver erro:**
```bash
# Limpar cache do npm e reinstalar
docker compose exec node npm cache clean --force
docker compose exec node npm install
```

### Erro: HTTP 500 no Backend

**Problema:** Backend retornando erro 500.

**Soluções:**
1. **Verificar permissões:**
   ```bash
   # O setup.sh já faz isso automaticamente, mas se precisar fazer manualmente:
   mkdir -p backend/bootstrap/cache backend/storage/{app,framework/{cache,sessions,views},logs}
   sudo chmod -R 775 backend/
   sudo chmod -R 777 backend/storage backend/bootstrap/cache
   ```

2. **Gerar APP_KEY:**
   ```bash
   docker compose exec php php artisan key:generate
   ```

3. **Corrigir configuração de cache (se erro "relation cache does not exist" ou "Class Redis not found"):**
   ```bash
   # Editar backend/.env e mudar:
   # CACHE_STORE=database  ->  CACHE_STORE=redis
   # REDIS_CLIENT=phpredis  ->  REDIS_CLIENT=predis
   # 
   # O predis é uma biblioteca PHP pura que não requer extensão
   ```

4. **Limpar cache do Laravel:**
   ```bash
   docker compose exec php php artisan config:clear
   # Se CACHE_STORE=redis, este comando funcionará sem erro
   docker compose exec php php artisan cache:clear
   ```

5. **Verificar logs:**
   ```bash
   docker compose logs php --tail 50
   tail -n 50 backend/storage/logs/laravel.log
   ```

### Erro: "Class Redis not found" ou "Class Predis\Client not found"

**Problema:** O pacote `predis/predis` não está instalado no projeto.

**Solução:** Instale o pacote Predis:
```bash
# Instalar o pacote Predis
docker compose exec php composer require predis/predis

# Depois limpe o cache:
docker compose exec php php artisan config:clear
docker compose exec php php artisan cache:clear
```

**Nota:** O `composer.json` já foi atualizado para incluir `predis/predis` automaticamente. Se você já tem o `composer.json` atualizado, apenas execute `composer install`.

### Erro: "relation sessions does not exist"

**Problema:** A tabela `sessions` não existe no banco de dados, mas o Laravel está configurado para usar `SESSION_DRIVER=database`.

**Solução:** Execute as migrations para criar a tabela de sessões:
```bash
# Executar todas as migrations (inclui a tabela de sessões)
docker compose exec php php artisan migrate

# OU apenas verificar o status das migrations
docker compose exec php php artisan migrate:status
```

**Nota:** A migration para criar a tabela `sessions` já foi criada no projeto. Basta executar `php artisan migrate`.

### Erro: "Target class [Nuwave\Lighthouse\Support\Http\Middleware\AcceptJson] does not exist"

**Problema:** O middleware `AcceptJson` não existe na versão 6.x do Lighthouse.

**Solução:** O `config/lighthouse.php` já foi atualizado para remover esse middleware. O Laravel já trata JSON por padrão nas rotas API.

**Se ainda houver erro:**
```bash
# Limpar cache de configuração
docker compose exec php php artisan config:clear

# Verificar se o arquivo foi atualizado
cat backend/config/lighthouse.php | grep middleware
```

### Erro: "GraphQL Request must include at least one of those two parameters: query or queryId"

**Problema:** Este erro é **normal e esperado** quando você acessa o endpoint GraphQL sem enviar uma query.

**Explicação:**
- O GraphQL requer uma query para funcionar
- Se você acessar `http://localhost:8082/graphql` no navegador (GET), verá esse erro
- Isso é comportamento esperado do GraphQL

**Solução:** Use POST com uma query válida:
```bash
# Exemplo de query GraphQL
curl -X POST http://localhost:8082/graphql \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"query":"{ __typename }"}'
```

**Ou acesse no navegador:**
- `http://localhost:8082/` - Página inicial da API
- `http://localhost:8082/graphql` (GET) - Informações sobre o endpoint GraphQL
- `http://localhost:8082/graphql` (POST) - Endpoint GraphQL (requer query)

**Nota:** Veja `GRAPHQL_EXAMPLES.md` para exemplos completos de queries.

### Erro: "Found invalid pagination type: paginator"

**Problema:** O tipo de paginação "paginator" não é válido no Lighthouse 6.x.

**Solução:** O schema GraphQL foi atualizado para usar uma query customizada (`@field`) em vez de `@paginate`. A query `ProductsQuery` já está configurada para retornar o formato correto de paginação.

**Se ainda houver erro:**
```bash
# Limpar cache do schema GraphQL
docker compose exec php php artisan lighthouse:clear-cache

# Limpar cache geral
docker compose exec php php artisan config:clear
```

Este script cria automaticamente:
- `backend/bootstrap/cache`
- `backend/storage/app`
- `backend/storage/framework/cache`
- `backend/storage/framework/sessions`
- `backend/storage/framework/views`
- `backend/storage/logs`

E ajusta as permissões corretamente.

**Solução Manual (se o script não funcionar):**
```bash
# Criar diretórios
mkdir -p backend/bootstrap/cache
mkdir -p backend/storage/{app,framework/{cache,sessions,views},logs}

# Ajustar permissões
sudo chmod -R 775 backend/
sudo chmod -R 777 backend/storage backend/bootstrap/cache
```

**⚠️ IMPORTANTE para novos ambientes:**
- Sempre execute `./setup.sh` que faz tudo automaticamente
- Ou execute `./setup.sh` que faz tudo automaticamente (inclui criação de diretórios e permissões)
- Este erro é comum em instalações em novos ambientes

### Erro: "APP_KEY is not set"

**Gerar a chave:**
```bash
docker compose exec php php artisan key:generate
```

### Erro: "Class not found" ou "Service Provider not found"

**Limpar cache e reinstalar:**
```bash
docker compose exec php composer dump-autoload
docker compose exec php php artisan config:clear
docker compose exec php php artisan cache:clear
```

### Ver todos os logs

```bash
# Logs em tempo real de todos os serviços
docker compose logs -f

# Logs de um serviço específico
docker compose logs -f php
docker compose logs -f node
docker compose logs -f nginx
```

### Reiniciar tudo do zero

```bash
# Parar e remover containers
docker compose down

# Remover volumes (CUIDADO: apaga dados do banco)
docker compose down -v

# Reconstruir e iniciar
docker compose build --no-cache
docker compose up -d
```

## 🧪 Testes Detalhados

### Backend (Pest)

O projeto inclui testes unitários e de feature usando Pest:

- **Testes Unitários**: Testam classes isoladamente com mocks
  - `ProductSyncServiceTest`: Testa lógica de sincronização
  - `ProductRepositoryTest`: Testa operações de banco de dados

- **Testes de Feature**: Testam fluxos completos incluindo GraphQL
  - `ProductSyncTest`: Testa sincronização completa com Shopify
  - `ProductsQueryTest`: Testa queries GraphQL

```bash
docker compose exec php php artisan test
```

### Frontend (Playwright)

Testes E2E usando Playwright:

```bash
docker compose exec node npm run test:e2e
```

**Nota:** Certifique-se de que o frontend está rodando antes de executar os testes E2E.

## 📊 Decisões de Design

### 1. Separação de Responsabilidades

Cada camada tem uma responsabilidade clara:
- **Contracts (Interfaces)**: Definem contratos sem implementação
- **Services**: Contêm lógica de negócio
- **Repositories**: Abstraem acesso a dados
- **Models**: Representam entidades do domínio

### 2. Dependency Injection

Todas as dependências são injetadas via construtor, facilitando testes e permitindo substituição de implementações.

### 3. Strategy Pattern

O `ProductSyncStrategyInterface` permite diferentes estratégias de sincronização sem modificar o código existente (OCP).

### 4. Repository Pattern

O `ProductRepositoryInterface` abstrai o acesso a dados, permitindo trocar a implementação (ex: cache, banco diferente) sem afetar os services.

### 5. GraphQL com Lighthouse

Lighthouse foi escolhido por sua integração nativa com Laravel e suporte a paginação, filtros e tipos complexos.

### 6. Nuxt.js com TypeScript

Nuxt.js 3 oferece SSR/SSG, roteamento automático e TypeScript nativo, melhorando a experiência de desenvolvimento e type safety.

## 🔧 Stack Tecnológica

### Backend
- **Laravel 11**: Framework PHP moderno e estável
- **PHP 8.2**: Versão estável do PHP com suporte completo
- **Laravel Lighthouse**: GraphQL para Laravel
- **PostgreSQL**: Banco de dados relacional
- **Redis**: Cache e sessões
- **Pest**: Framework de testes PHP
- **Guzzle**: Cliente HTTP para API Shopify

### Frontend
- **Nuxt.js 3**: Framework Vue.js com SSR
- **TypeScript**: Type safety
- **Apollo Client**: Cliente GraphQL
- **TailwindCSS**: Framework CSS utility-first
- **Playwright**: Testes E2E

### Infraestrutura
- **Docker**: Containerização
- **Docker Compose**: Orquestração de containers
- **Nginx**: Servidor web
- **PHP-FPM**: Processador PHP

## 📝 Funcionalidades

### Sincronização de Produtos

- Busca produtos da API Shopify
- Compara com produtos existentes
- Cria novos produtos ou atualiza existentes
- Registra timestamp de sincronização

### API GraphQL

- Query `products`: Lista produtos com filtros e paginação
- Query `product(id)`: Busca produto específico
- Suporte a filtros: search, vendor, product_type

### Frontend

- Listagem de produtos sincronizados
- Filtros em tempo real (search, vendor, product_type)
- Paginação
- Interface responsiva com TailwindCSS

## 🎯 Diferenciais de Qualidade

1. **SOLID bem aplicado**: Cada classe tem responsabilidade única, interfaces bem definidas, dependências invertidas
2. **Testabilidade**: Código facilmente testável através de interfaces e dependency injection
3. **Arquitetura limpa**: Separação clara entre camadas (Domain, Application, Infrastructure)
4. **Documentação**: README explicando arquitetura e decisões de design
5. **Docker completo**: Ambiente totalmente containerizado e funcional
6. **Testes abrangentes**: Cobertura de testes unitários e E2E

## 📚 Próximos Passos (Melhorias Futuras)

- Implementar webhooks do Shopify para sincronização em tempo real
- Adicionar cache para melhorar performance
- Implementar filas para processamento assíncrono
- Adicionar autenticação e autorização
- Implementar sincronização de variantes e imagens de produtos
- Adicionar métricas e monitoramento

## 📄 Licença

Este projeto foi criado como demonstração técnica para processo seletivo.
