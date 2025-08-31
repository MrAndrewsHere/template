ifneq (,$(wildcard .env))
include .env
export
endif

SHELL := bash

APP_NAMESPACE ?= $(APP_NAMESPACE)
CONTAINER ?= $(APP_NAMESPACE)-app
DOCKER_TTY ?= -it
COMPOSE ?= docker-compose

DOCKER_EXEC := docker exec $(DOCKER_TTY) $(CONTAINER)

.DEFAULT_GOAL := echo

# -------------------------------------------------------------------
# Compose / Orchestration
# -------------------------------------------------------------------
.PHONY: compose-build
compose-build:
	$(COMPOSE) up -d --build

.PHONY: compose-up
compose-up:
	$(COMPOSE) up -d --remove-orphans

.PHONY: compose-build-prod
compose-build-prod:
	$(COMPOSE) -f docker-compose.yml -f docker-compose.prod.yml up -d --build

.PHONY: compose-up-prod
compose-up-prod:
	$(COMPOSE) -f docker-compose.yml -f docker-compose.prod.yml up -d --remove-orphans

.PHONY: compose-restart
compose-restart:
	$(COMPOSE) restart

.PHONY: compose-stop
compose-stop:
	$(COMPOSE) stop

.PHONY: compose-down
compose-down:
	$(COMPOSE) down -v

# -------------------------------------------------------------------
# App / Container
# -------------------------------------------------------------------
.PHONY: app-shell
app-shell:
	$(DOCKER_EXEC) /bin/bash

.PHONY: app-composer-install
app-composer-install:
	$(DOCKER_EXEC) composer install --no-interaction --prefer-dist --no-progress

.PHONY: app-key-generate
app-key-generate:
	$(DOCKER_EXEC) php artisan key:generate

.PHONY: app-storage-link
app-storage-link:
	$(DOCKER_EXEC) php artisan storage:link

.PHONY: app-cache-clear
app-cache-clear:
	$(DOCKER_EXEC) php artisan cache:clear

.PHONY: app-horizon-install
app-horizon-install:
	$(DOCKER_EXEC) php artisan horizon:install

# -------------------------------------------------------------------
# Database
# -------------------------------------------------------------------
.PHONY: db-migrate
db-migrate:
	$(DOCKER_EXEC) php artisan migrate

.PHONY: db-seed
db-seed:
	$(DOCKER_EXEC) php artisan db:seed

.PHONY: db-setup
db-setup: db-migrate db-seed
	@true

# -------------------------------------------------------------------
# Quality / CI
# -------------------------------------------------------------------
.PHONY: quality-pint-fix
quality-pint-fix:
	$(DOCKER_EXEC) vendor/bin/pint --config ./pint.json

.PHONY: quality-pint-check
quality-pint-check:
	$(DOCKER_EXEC) vendor/bin/pint --test --config ./pint.json

.PHONY: quality-rector
quality-rector:
	$(DOCKER_EXEC) vendor/bin/rector process

.PHONY: quality-insights
quality-insights:
	$(DOCKER_EXEC) vendor/bin/phpinsights --quiet

.PHONY: quality-stan
quality-stan:
	$(DOCKER_EXEC) vendor/bin/phpstan analyse -c ./phpstan.neon

.PHONY: quality-test
quality-test:
	$(MAKE) app-cache-clear
	$(DOCKER_EXEC) php artisan test --env=testing --parallel

.PHONY: quality-all
quality-all:
	$(MAKE) quality-pint-check
	$(MAKE) quality-rector
	$(MAKE) quality-test
	$(MAKE) quality-insights
	$(MAKE) quality-stan

# -------------------------------------------------------------------
# Composite / Scenarios
# -------------------------------------------------------------------
.PHONY: init
init:
	$(MAKE) compose-build
	$(MAKE) app-composer-install
	$(MAKE) app-key-generate
	$(MAKE) app-storage-link
	$(MAKE) app-horizon-install
	$(MAKE) db-setup
	$(MAKE) swagger
	$(MAKE) compose-restart
	$(MAKE) quality-all

# -------------------------------------------------------------------
# Utils
# -------------------------------------------------------------------
.PHONY: echo
echo:
	@echo Hello World! I am Makefile.
	@echo APP_NAMESPACE: $(APP_NAMESPACE)
	@echo CONTAINER: $(CONTAINER)

.PHONY: tink
tink:
	docker exec -it $(CONTAINER) php artisan tink

.PHONY: swagger
swagger:
	docker exec -it $(CONTAINER) php artisan l5-swagger:generate
# -------------------------------------------------------------------
# Aliases (совместимость со старыми именами)
# -------------------------------------------------------------------
.PHONY: exec
exec: app-shell
	@true

.PHONY: check
check: quality-all
	@true

.PHONY: build
build: compose-build
	@true

.PHONY: up
up: compose-up
	@true

.PHONY: build-prod
build-prod: compose-build-prod
	@true

.PHONY: up-prod
up-prod: compose-up-prod
	@true

.PHONY: restart
restart: compose-restart
	@true

.PHONY: stop
stop: compose-stop
	@true

.PHONY: down
down: compose-down
	@true

.PHONY: composer-install
composer-install: app-composer-install
	@true

.PHONY: key-generate
key-generate: app-key-generate
	@true

.PHONY: storage-link
storage-link: app-storage-link
	@true

.PHONY: pint
pint: quality-pint-fix
	@true

.PHONY: pint-test
pint-test: quality-pint-check
	@true

.PHONY: rector
rector: quality-rector
	@true

.PHONY: insights
insights: quality-insights
	@true

.PHONY: stan
stan: quality-stan
	@true

.PHONY: test
test: quality-test
	@true
