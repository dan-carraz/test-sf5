.DEFAULT_GOAL := help

.PHONY: help
help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(firstword $(MAKEFILE_LIST)) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'


##
## Setup
## -----
install: docker-pull docker-build composer-install doctrine-force ## Installation du projet

docker-pull: ## Pull des containers docker
	docker-compose pull

docker-build: ## Build des containers docker
	docker-compose build

doctrine-force: start ## Mise à jour de la base de donnée
	docker exec -it test-sf5-php bin/console d:s:u --force

composer-install: start ## Installation des vendor
	docker exec -it test-sf5-php composer install

composer-update: start  ## Mise à jour des vendors
	docker exec -it test-sf5-php composer update

start: ## Démarrage des containers du projet
	docker-compose up -d

stop: ## Arrêt des containers du projet
	docker-compose stop

docker-exec: ## Connexion au container php
	docker exec -it test-sf5-php sh
