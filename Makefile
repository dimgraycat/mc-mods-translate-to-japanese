UID := $(shell id -u)
GID := $(shell id -g)

# Dockerサービス名
SERVICE=laravel-cli

MOD=
VER=
NAME=

init:
	docker compose up -d --build
	docker compose exec $(SERVICE) composer install
	docker compose down
	sudo chown -R $(shell id -u):$(shell id -g) src
	sudo chmod -R 775 src/storage src/bootstrap/cache

extract:
	docker compose run --rm --user $(UID):$(GID) $(SERVICE) php artisan translate:extract $(MOD)

pack:
	docker compose run --rm --user $(UID):$(GID) $(SERVICE) php artisan translate:pack --name $(NAME) --ver $(VER)