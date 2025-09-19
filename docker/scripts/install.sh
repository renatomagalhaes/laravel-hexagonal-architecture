#!/bin/bash

# Cores para output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

echo -e "${GREEN}🚀 Instalando Laravel 11...${NC}"

# Construir e iniciar containers
echo -e "${YELLOW}📦 Construindo containers...${NC}"
docker-compose build --no-cache

echo -e "${YELLOW}🚀 Iniciando MySQL e Redis...${NC}"
docker-compose up -d mysql redis

echo -e "${YELLOW}⏳ Aguardando MySQL inicializar...${NC}"
sleep 15

echo -e "${YELLOW}🚀 Iniciando container da aplicação...${NC}"
docker-compose up -d app

echo -e "${YELLOW}⏳ Aguardando container da aplicação...${NC}"
sleep 10

# Criar Laravel em diretório temporário
echo -e "${YELLOW}📦 Criando projeto Laravel...${NC}"
docker-compose exec app composer create-project laravel/laravel /tmp/laravel-temp --prefer-dist

# Mover arquivos do Laravel para o diretório principal
echo -e "${YELLOW}📁 Movendo arquivos do Laravel...${NC}"
docker-compose exec app sh -c "cp -r /tmp/laravel-temp/* /var/www/html/ && cp -r /tmp/laravel-temp/.* /var/www/html/ 2>/dev/null || true"

# Limpar diretório temporário
docker-compose exec app rm -rf /tmp/laravel-temp

# Configurar Laravel
echo -e "${YELLOW}⚙️  Configurando Laravel...${NC}"
docker-compose exec app cp .env.example .env
docker-compose exec app php artisan key:generate

# Executar migrations
echo -e "${YELLOW}🗄️  Executando migrations...${NC}"
docker-compose exec app php artisan migrate

# Instalar dependências do Node.js
echo -e "${YELLOW}📦 Instalando dependências do Node.js...${NC}"
docker-compose exec app npm install
docker-compose exec app npm run build

# Configurar permissões
echo -e "${YELLOW}🔐 Configurando permissões...${NC}"
docker-compose exec app chown -R www-data:www-data /var/www/html
docker-compose exec app chmod -R 755 /var/www/html/storage
docker-compose exec app chmod -R 755 /var/www/html/bootstrap/cache

echo -e "${GREEN}✅ Instalação concluída!${NC}"
echo -e "${YELLOW}🌐 Acesse: http://localhost:8080${NC}"
echo -e "${YELLOW}🗄️  phpMyAdmin: http://localhost:8081${NC}"
