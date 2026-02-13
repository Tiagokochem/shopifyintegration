# Configuração de Portas

Este documento explica as portas usadas pelo projeto e como alterá-las se necessário.

## Portas Padrão Configuradas

| Serviço | Porta Externa | Porta Interna | Descrição |
|---------|--------------|---------------|-----------|
| Nginx (Backend API) | 8082 | 80 | API Laravel e GraphQL |
| Nuxt.js (Frontend) | 3002 | 3000 | Interface web |
| PostgreSQL | 5433 | 5432 | Banco de dados |
| Redis | 6380 | 6379 | Cache e sessões |

## Por que essas portas?

As portas externas foram escolhidas para evitar conflitos com serviços comuns:
- **8082**: Alternativa comum ao 80/8080 (HTTP padrão)
- **3002**: Evita conflito com outros projetos Node.js (3000, 3001)
- **5433**: Evita conflito com PostgreSQL local (5432)
- **6380**: Evita conflito com Redis local (6379)

## Como Alterar Portas

### 1. Editar docker-compose.yml

Encontre a seção do serviço e altere o primeiro número (porta externa):

```yaml
# Exemplo: Alterar frontend de 3002 para 3005
node:
  ports:
    - "3005:3000"  # 3005 é externo, 3000 é interno (não altere)
```

### 2. Atualizar Referências (se necessário)

**Se mudar porta do Frontend (3002):**
- `README.md` - URLs do frontend
- `playwright.config.ts` - baseURL para testes E2E
- `setup.sh` - mensagens de setup

**Se mudar porta do Backend (8082):**
- `README.md` - URLs da API
- `frontend/nuxt.config.ts` - GRAPHQL_ENDPOINT
- `setup.sh` - mensagens de setup

**Se mudar PostgreSQL ou Redis:**
- Não precisa alterar nada! Eles se comunicam internamente via Docker network.

## Verificar Portas em Uso

```bash
# Verificar portas específicas
# Verificar portas em uso
lsof -i :8082 -i :3002 -i :5433 -i :6380

# OU verificar containers Docker
docker compose ps

# OU manualmente
lsof -i :8082
lsof -i :3002
lsof -i :5433
lsof -i :6380
```

## Exemplo: Portas Alternativas

Se todas as portas padrão estiverem ocupadas, você pode usar:

```yaml
services:
  nginx:
    ports:
      - "8082:80"  # Backend em 8082
  
  node:
    ports:
      - "3005:3000"  # Frontend em 3005
  
  postgres:
    ports:
      - "5434:5432"  # PostgreSQL em 5434
  
  redis:
    ports:
      - "6381:6379"  # Redis em 6381
```

Depois atualize as URLs no README e arquivos de configuração conforme necessário.
