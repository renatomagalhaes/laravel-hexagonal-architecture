# Laravel Hexagonal Architecture - Makefile
# Comandos básicos para desenvolvimento

.PHONY: help install up down test artisan composer shell clean

# Cores para output
GREEN=\033[0;32m
YELLOW=\033[1;33m
RED=\033[0;31m
NC=\033[0m # No Color

help: ## Mostra esta ajuda
	@echo "$(GREEN)Laravel Hexagonal Architecture - Comandos Disponíveis$(NC)"
	@echo ""
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "$(YELLOW)%-20s$(NC) %s\n", $$1, $$2}'

install: ## Instala dependências e configura o ambiente
	@echo "$(GREEN)🚀 Configurando ambiente Laravel...$(NC)"
	@docker-compose build --no-cache
	@docker-compose up -d mysql redis
	@echo "$(YELLOW)⏳ Aguardando MySQL inicializar...$(NC)"
	@sleep 15
	@docker-compose up -d app
	@echo "$(YELLOW)⏳ Aguardando container da aplicação...$(NC)"
	@sleep 10
	@docker-compose exec app git config --global --add safe.directory /var/www/html
	@docker-compose exec app composer install --no-interaction --prefer-dist --optimize-autoloader
	@docker-compose exec app cp .env.example .env
	@docker-compose exec app php artisan key:generate
	@docker-compose exec app php artisan config:cache
	@docker-compose exec app php artisan route:cache
	@docker-compose exec app php artisan view:cache
	@docker-compose exec app php artisan migrate
	@docker-compose exec app npm install
	@docker-compose exec app npm run build
	@echo "$(YELLOW)⏳ Iniciando nginx e phpMyAdmin...$(NC)"
	@docker-compose up -d nginx phpmyadmin
	@echo "$(GREEN)✅ Instalação concluída!$(NC)"
	@echo "$(YELLOW)🌐 Acesse: http://localhost:8080$(NC)"
	@echo "$(YELLOW)🗄️  phpMyAdmin: http://localhost:8081$(NC)"

up: ## Inicia os containers Docker
	@echo "$(GREEN)🚀 Iniciando containers...$(NC)"
	@docker-compose up -d
	@echo "$(GREEN)✅ Containers iniciados!$(NC)"
	@echo "$(YELLOW)🌐 Aplicação: http://localhost:8080$(NC)"
	@echo "$(YELLOW)🗄️  phpMyAdmin: http://localhost:8081$(NC)"

down: ## Para os containers Docker
	@echo "$(YELLOW)🛑 Parando containers...$(NC)"
	@docker-compose down
	@echo "$(GREEN)✅ Containers parados!$(NC)"

restart: ## Reinicia os containers
	@echo "$(YELLOW)🔄 Reiniciando containers...$(NC)"
	@docker-compose restart
	@echo "$(GREEN)✅ Containers reiniciados!$(NC)"

test: ## Executa os testes
	@echo "$(GREEN)🧪 Executando testes...$(NC)"
	@docker-compose exec app php artisan test
	@echo "$(GREEN)✅ Testes concluídos!$(NC)"

artisan: ## Executa comandos Artisan (uso: make artisan cmd="migrate")
	@docker-compose exec app php artisan $(cmd)

composer: ## Executa comandos Composer (uso: make composer cmd="install")
	@docker-compose exec app composer $(cmd)

npm: ## Executa comandos NPM (uso: make npm cmd="run dev")
	@docker-compose exec app npm $(cmd)

shell: ## Acessa o shell do container da aplicação
	@echo "$(GREEN)🐚 Acessando shell do container...$(NC)"
	@docker-compose exec app bash

mysql: ## Acessa o MySQL via linha de comando
	@echo "$(GREEN)🗄️  Acessando MySQL...$(NC)"
	@docker-compose exec mysql mysql -u laravel -plaravel laravel_hexagonal

logs: ## Mostra os logs dos containers
	@docker-compose logs -f

logs-app: ## Mostra logs apenas da aplicação
	@docker-compose logs -f app

logs-mysql: ## Mostra logs apenas do MySQL
	@docker-compose logs -f mysql

clean: ## Limpa containers, volumes e imagens
	@echo "$(RED)🧹 Limpando ambiente...$(NC)"
	@docker-compose down -v --rmi all
	@echo "$(GREEN)✅ Ambiente limpo!$(NC)"

fresh: ## Reinstala tudo do zero
	@echo "$(RED)🔄 Reinstalando do zero...$(NC)"
	@make clean
	@make install
	@echo "$(GREEN)✅ Reinstalação concluída!$(NC)"

status: ## Mostra status dos containers
	@echo "$(GREEN)📊 Status dos containers:$(NC)"
	@docker-compose ps

# Comandos de desenvolvimento específicos
migrate: ## Executa migrations
	@docker-compose exec app php artisan migrate

migrate-fresh: ## Executa migrations do zero
	@docker-compose exec app php artisan migrate:fresh

seed: ## Executa seeders
	@docker-compose exec app php artisan db:seed

tinker: ## Abre o Tinker do Laravel
	@docker-compose exec app php artisan tinker

queue-work: ## Inicia o worker de filas
	@docker-compose exec app php artisan queue:work

# Comandos de build
build: ## Constrói as imagens Docker (desenvolvimento)
	@docker-compose build

build-no-cache: ## Constrói as imagens Docker sem cache (desenvolvimento)
	@docker-compose build --no-cache

build-dev: ## Constrói imagem de desenvolvimento
	@echo "$(GREEN)🐳 Building development environment...$(NC)"
	@docker-compose build --no-cache app
	@echo "$(GREEN)✅ Development build completed!$(NC)"

build-prod: ## Constrói imagem de produção otimizada
	@echo "$(GREEN)🚀 Building production environment...$(NC)"
	@docker build -f docker/Dockerfile.prod -t laravel-hexagonal-prod .
	@echo "$(GREEN)✅ Production build completed!$(NC)"
	@echo "$(YELLOW)🐳 Image tagged as: laravel-hexagonal-prod$(NC)"

fix-permissions: ## Corrige permissões dos diretórios
	@echo "$(GREEN)🔧 Corrigindo permissões...$(NC)"
	@docker-compose exec app chown -R www:www /var/www/html/storage /var/www/html/bootstrap/cache
	@docker-compose exec app chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
	@echo "$(GREEN)✅ Permissões corrigidas!$(NC)"

# Comandos Xdebug
xdebug-status: ## Mostra status e configurações do Xdebug
	@echo "$(GREEN)📊 Status do Xdebug:$(NC)"
	@if docker-compose exec app php -m | grep -i xdebug > /dev/null; then \
		echo "$(GREEN)✅ Xdebug está instalado e ativo$(NC)"; \
		echo "$(YELLOW)🔧 Configurações:$(NC)"; \
		docker-compose exec app php -r "if (extension_loaded('xdebug')) { echo 'Mode: ' . ini_get('xdebug.mode') . PHP_EOL; echo 'Client Host: ' . ini_get('xdebug.client_host') . PHP_EOL; echo 'Client Port: ' . ini_get('xdebug.client_port') . PHP_EOL; }"; \
	else \
		echo "$(RED)❌ Xdebug não está instalado$(NC)"; \
	fi

test-coverage: ## Executa testes com coverage
	@echo "$(GREEN)🧪 Executando testes com coverage...$(NC)"
	@docker-compose exec app php artisan test --coverage
	@echo "$(GREEN)✅ Testes com coverage concluídos!$(NC)"
