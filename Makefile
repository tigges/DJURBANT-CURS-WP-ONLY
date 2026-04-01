# DJ UrbanT — Local development helpers
# Usage:  make <target>
#
# Quickstart:
#   make up        # start Docker services
#   make setup     # first-boot WP provisioning (idempotent)
#   make logs      # tail WordPress logs
#   make down      # stop everything

SHELL        := /bin/bash
COMPOSE      := sudo docker compose
CONTAINER_WP := workspace-wordpress-1
WP           := sudo docker exec $(CONTAINER_WP) wp --allow-root

.DEFAULT_GOAL := help

# ── Docker lifecycle ───────────────────────────────────────────────
.PHONY: up down restart logs ps

up:            ## Start all containers (detached)
	$(COMPOSE) up -d

down:          ## Stop and remove containers
	$(COMPOSE) down

restart:       ## Restart containers
	$(COMPOSE) restart

logs:          ## Tail WordPress container logs
	$(COMPOSE) logs -f wordpress

ps:            ## Show running containers
	$(COMPOSE) ps

# ── WordPress provisioning ─────────────────────────────────────────
.PHONY: setup

setup:         ## First-boot: install WP core, themes, pages (idempotent)
	@./wp-setup.sh

# ── WP-CLI shortcuts ──────────────────────────────────────────────
.PHONY: wp-shell wp-themes wp-plugins wp-flush

wp-shell:      ## Open an interactive WP-CLI shell
	$(WP) shell

wp-themes:     ## List installed themes
	$(WP) theme list

wp-plugins:    ## List installed plugins
	$(WP) plugin list

wp-flush:      ## Flush WP object cache & rewrite rules
	$(WP) cache flush
	$(WP) rewrite flush

# ── Development helpers ────────────────────────────────────────────
.PHONY: lint-php shell db-shell clean nuke health

lint-php:      ## Lint child theme PHP (requires php on host)
	@command -v php >/dev/null 2>&1 && php -l djurbant-child-theme/functions.php && echo "✔ No PHP syntax errors" || echo "⚠ php not on host — run: make docker-lint-php"

docker-lint-php: ## Lint child theme PHP inside Docker container
	sudo docker exec $(CONTAINER_WP) bash -c 'find /var/www/html/wp-content/themes/djurbant-child-theme -name "*.php" -exec php -l {} \;'

shell:         ## Open a bash shell inside the WordPress container
	sudo docker exec -it $(CONTAINER_WP) bash

db-shell:      ## Open a MySQL shell
	sudo docker exec -it workspace-db-1 mysql -uwordpress -pwordpress wordpress

health:        ## Check health of all services
	@echo "── Docker containers ──"
	@$(COMPOSE) ps
	@echo ""
	@echo "── WordPress health ──"
	@curl -sf -o /dev/null -w "HTTP %{http_code} in %{time_total}s\n" http://localhost:8080/ || echo "FAIL: WordPress not responding"
	@echo ""
	@echo "── Active theme ──"
	@$(WP) theme list --status=active --fields=name,version 2>/dev/null || echo "WP-CLI unavailable"

clean:         ## Stop containers and remove volumes
	$(COMPOSE) down -v

nuke:          ## Full reset: remove volumes, images, rebuild
	$(COMPOSE) down -v --rmi local
	$(COMPOSE) up -d
	@echo "Run 'make setup' to re-provision WordPress."

# ── Help ───────────────────────────────────────────────────────────
.PHONY: help
help:          ## Show this help
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | \
	  awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m  %-18s\033[0m %s\n", $$1, $$2}'
