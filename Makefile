ifneq (,$(wildcard .env))
include .env
export
endif

SHELL := bash
.DEFAULT_GOAL := help

# --- Environment -----------------------------------------------------------
# Usage:  make up ENV=prod
#         make build  (defaults to dev)
ENV ?= dev

APP_NAMESPACE ?= $(APP_NAMESPACE)
CONTAINER     ?= $(APP_NAMESPACE)-app
DOCKER_TTY    ?= -it

ifeq ($(ENV),prod)
COMPOSE_FILES := -f docker-compose.yml -f docker-compose.prod.yml
else
COMPOSE_FILES :=
endif

COMPOSE    := docker compose $(COMPOSE_FILES)
DOCKER_EXEC := docker exec $(DOCKER_TTY) $(CONTAINER)
ARTISAN     := $(DOCKER_EXEC) php artisan

# ===================================================================
# Compose / Orchestration
# ===================================================================
.PHONY: build
build: ## Build and start containers (respects ENV)
	$(COMPOSE) up -d --build

.PHONY: up
up: ## Start containers (respects ENV)
	$(COMPOSE) up -d --remove-orphans

.PHONY: restart
restart: ## Restart all containers
	$(COMPOSE) restart

.PHONY: stop
stop: ## Stop all containers
	$(COMPOSE) stop

.PHONY: down
down: ## Stop and remove containers and volumes
	$(COMPOSE) down -v

.PHONY: logs
logs: ## Tail container logs (all services)
	$(COMPOSE) logs -f --tail=100

.PHONY: ps
ps: ## Show running containers
	$(COMPOSE) ps

# ===================================================================
# App / Container
# ===================================================================
.PHONY: shell
shell: ## Open bash inside the app container
	$(DOCKER_EXEC) /bin/bash

.PHONY: composer-install
composer-install: ## Install PHP dependencies
	$(DOCKER_EXEC) composer install --no-interaction --prefer-dist --no-progress

.PHONY: npm-install
npm-install: ## Install Node dependencies
	$(DOCKER_EXEC) npm ci

.PHONY: key-generate
key-generate: ## Generate APP_KEY
	$(ARTISAN) key:generate

.PHONY: storage-link
storage-link: ## Create storage symlink
	$(ARTISAN) storage:link

.PHONY: cache-clear
cache-clear: ## Clear all application caches
	$(ARTISAN) cache:clear

.PHONY: optimize
optimize: ## Cache config/routes/views (for prod)
	$(ARTISAN) optimize

.PHONY: optimize-clear
optimize-clear: ## Clear cached config/routes/views
	$(ARTISAN) optimize:clear

# ===================================================================
# Database
# ===================================================================
.PHONY: migrate
migrate: ## Run database migrations
	$(ARTISAN) migrate

.PHONY: migrate-fresh
migrate-fresh: ## Drop all tables and re-run migrations
	$(ARTISAN) migrate:fresh

.PHONY: seed
seed: ## Run database seeders
	$(ARTISAN) db:seed

.PHONY: db-setup
db-setup: migrate seed ## Migrate + seed

.PHONY: db-fresh
db-fresh: migrate-fresh seed ## Fresh migrate + seed

# ===================================================================
# Quality / CI
# ===================================================================
.PHONY: pint
pint: ## Fix code style (Pint)
	$(DOCKER_EXEC) vendor/bin/pint --config ./pint.json

.PHONY: pint-check
pint-check: ## Check code style without fixing
	$(DOCKER_EXEC) vendor/bin/pint --test --config ./pint.json

.PHONY: rector
rector: ## Run Rector refactoring
	$(DOCKER_EXEC) vendor/bin/rector process

.PHONY: rector-dry
rector-dry: ## Rector dry-run
	$(DOCKER_EXEC) vendor/bin/rector process --dry-run

.PHONY: insights
insights: ## Run PHP Insights
	$(DOCKER_EXEC) vendor/bin/phpinsights --summary

.PHONY: stan
stan: ## Run PHPStan static analysis
	$(DOCKER_EXEC) vendor/bin/phpstan analyse -c ./phpstan.neon

.PHONY: test
test: ## Run tests in parallel
	$(ARTISAN) test --env=testing --parallel

.PHONY: check
check: pint-check rector-dry stan test insights ## Run all quality checks (no mutations)

# ===================================================================
# Utilities
# ===================================================================
.PHONY: tinker
tinker: ## Open Laravel Tinker
	$(DOCKER_EXEC) php artisan tinker

.PHONY: swagger
swagger: ## Generate Swagger/OpenAPI docs
	$(ARTISAN) l5-swagger:generate

.PHONY: horizon
horizon: ## Open Horizon status
	$(ARTISAN) horizon:status

# ===================================================================
# Composite scenarios
# ===================================================================
.PHONY: init
init: build composer-install npm-install key-generate storage-link db-setup ## Full project init (dev)
	@echo "--- Init complete. Run 'make up' to start. ---"

.PHONY: deploy
deploy: ## Deploy-like sequence for prod
	$(MAKE) build ENV=prod
	$(MAKE) migrate
	$(MAKE) optimize

# ===================================================================
# Help
# ===================================================================
.PHONY: help
help: ## Show this help
	@printf "\nUsage:  make <target> [ENV=dev|prod]\n\n"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | \
		awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[36m%-20s\033[0m %s\n", $$1, $$2}'
	@printf "\nCurrent: ENV=$(ENV)  CONTAINER=$(CONTAINER)\n\n"
