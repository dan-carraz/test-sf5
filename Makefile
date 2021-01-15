install: docker-build composer-install doctrine-force

docker-build:
	docker-compose build

doctrine-force: start
	docker exec -it test-sf5-php bin/console d:s:u --force

composer-install: start
	docker exec -it test-sf5-php composer install

composer-update: start
	docker exec -it test-sf5-php composer update

start:
	docker-compose up -d

stop:
	docker-compose stop