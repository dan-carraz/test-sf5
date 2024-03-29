.DEFAULT_GOAL := help

.PHONY: help
help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(firstword $(MAKEFILE_LIST)) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

DOCKER_EXEC  ?= docker exec -it --user www-data test-sf5-php

##
## Setup
## -----
install: docker-pull docker-build composer-install doctrine-force ## Installation du projet

docker-pull: ## Pull des containers docker
	docker-compose pull

docker-build: ## Build des containers docker
	docker-compose build

doctrine-force: start ## Mise à jour de la base de donnée
	$(DOCKER_EXEC) bin/console d:s:u --force

doctrine-fixtures: ## Lancement des fixtures
	$(DOCKER_EXEC) bin/console d:f:l -q

composer-install: start ## Installation des vendor
	$(DOCKER_EXEC) composer install

composer-update: start  ## Mise à jour des vendors
	docker exec -it test-sf5-php composer update

start: ## Démarrage des containers du projet
	docker-compose up -d

stop: ## Arrêt des containers du projet
	docker-compose stop

php: ## Connexion au container php
	$(DOCKER_EXEC) sh
phpcs: ## Lancement du sniffer
	$(DOCKER_EXEC) vendor/bin/php-cs-fixer fix

tests: ## Lancement des tests
	$(DOCKER_EXEC) vendor/bin/phpunit
