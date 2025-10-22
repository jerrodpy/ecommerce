-include docker/.env
# Try to find `docker-compose`; if not found, use `docker compose`.
DOCKER_COMPOSE_CMD := $(shell if command -v docker-compose >/dev/null 2>&1; \
                            then echo "docker-compose"; \
                            else echo "docker compose"; \
                        fi)

# Compose command with common flags
DOCKER_COMPOSE ?= COMPOSE_BAKE=true $(DOCKER_COMPOSE_CMD) --env-file ./docker/.env --project-directory ./docker
DOCKER_COMPOSE_UP ?= up -d

DOCKER_COMPOSE_FRESH_ARGS ?= --build --force-recreate --remove-orphans --renew-anon-volumes
DOCKER_COMPOSE_DOWN ?= $(DOCKER_COMPOSE) down
DOCKER_COMPOSE_EXEC_PHP ?= $(DOCKER_COMPOSE) exec -u www-data php-fpm bash

PHP ?= $(DOCKER_COMPOSE) exec php-fpm php

.PHONY: help
help:
	@echo "Useful targets:"
	@echo ""
	@echo "  cs        > show expected CS fixer changes"
	@echo "  cs-fix    > run CS fixer"
	@echo ""
	@echo "  build     > build application image"
	@echo "  up        > create and start local docker containers"
	@echo ""
	@echo "  down      > stop and remove docker containers"
	@echo ""
	@echo "  ssh       > initialize bash php-fpm console"
	@echo ""
	@echo "For example: make up ARGS=\"-f docker/docker-compose.yaml\" - for production"

.PHONY: cs
cs:
	$(PHP) ./vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.dist.php --dry-run --diff --verbose

.PHONY: cs-fix
cs-fix:
	$(PHP) ./vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.dist.php --verbose

.PHONY: stan
stan:
	$(PHP) vendor/bin/phpstan analyse --memory-limit=4G -c phpstan.neon

.PHONY: build
build:
	$(DOCKER_COMPOSE) build

.PHONY: up
up:
	$(DOCKER_COMPOSE) $(ARGS) $(DOCKER_COMPOSE_UP)

.PHONY: up-fresh
down:
	$(DOCKER_COMPOSE_DOWN) $(ARGS)

.PHONY: ssh
ssh:
	$(DOCKER_COMPOSE_EXEC_PHP)
