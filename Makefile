PROJECT_ROOT := $(abspath $(dir $(lastword $(MAKEFILE_LIST))))
DC := docker compose
BACKEND_PHP := $(DC) exec constru-backend-php php

.PHONY: backend-install frontend-install up down logs migrate seed-db \
        lint-frontend lint-backend \
        lint-phpstan lint-cs-fix lint-cs-check fix-cs

backend-install:
	@cd backend && docker run --rm -v $$PWD:/app -w /app php:8.4-cli sh -lc "apt-get update >/dev/null && apt-get install -y git unzip libzip-dev >/dev/null && docker-php-ext-install zip >/dev/null && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer >/dev/null && composer install"

frontend-install:
	cd frontend && npm install

up:
	$(DC) up -d --build

down:
	$(DC) down

logs:
	$(DC) logs -f

migrate:
	$(DC) exec constru-backend-php php bin/console doctrine:migrations:migrate -n

# Frontend linting
lint-frontend:
	cd frontend && npm run lint

# Backend linting - legacy simple syntax check
lint-backend:
	$(BACKEND_PHP) -l src

# PHPStan - static analysis
lint-phpstan:
	$(DC) exec constru-backend-php vendor/bin/phpstan analyse --memory-limit=1G

# PHP CS Fixer - check code style
lint-cs-check:
	$(DC) exec constru-backend-php vendor/bin/php-cs-fixer fix --dry-run --diff

# PHP CS Fixer - fix code style
fix-cs:
	$(DC) exec constru-backend-php vendor/bin/php-cs-fixer fix

# Run all backend linting
lint-backend-all: lint-phpstan lint-cs-check
