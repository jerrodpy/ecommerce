-include docker/.env

DOCKER_COMPOSE_CMD := $(shell if command -v docker-compose >/dev/null 2>&1; \
                            then echo "docker-compose"; \
                            else echo "docker compose"; \
                        fi)

DOCKER_COMPOSE ?= COMPOSE_BAKE=true $(DOCKER_COMPOSE_CMD) --env-file ./docker/.env --project-directory ./docker
DOCKER_COMPOSE_DOWN ?= $(DOCKER_COMPOSE) down

EXEC_PHP ?= $(DOCKER_COMPOSE) exec -u www-data -w /var/www php-fpm
PHP ?= $(EXEC_PHP) php

.PHONY: help cs cs-fix stan build up down ssh logs restart

help:
	@echo "Usage: make <target>"
	@echo ""
	@echo "  build     Build Docker images"
	@echo "  up        Start containers"
	@echo "  down      Stop containers"
	@echo "  restart   Restart containers"
	@echo "  logs      Tail container logs"
	@echo "  ssh       Shell into PHP container"
	@echo ""
	@echo "  cs        Code style check (dry-run)"
	@echo "  cs-fix    Apply code style fixes"
	@echo "  stan      PHPStan static analysis"

cs:
	$(PHP) vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.dist.php --dry-run --diff --verbose

cs-fix:
	$(PHP) vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.dist.php --verbose

stan:
	$(PHP) vendor/bin/phpstan analyse --memory-limit=4G -c phpstan.neon

build:
	$(DOCKER_COMPOSE) build

up:
	$(DOCKER_COMPOSE) up -d $(ARGS)

down:
	$(DOCKER_COMPOSE_DOWN) $(ARGS)

restart: down up

logs:
	$(DOCKER_COMPOSE) logs -f $(ARGS)

ssh:
	$(EXEC_PHP) bash
