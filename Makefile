UID := $(shell id -u)
GID := $(shell id -g)

# Dockerサービス名
SERVICE=laravel-cli

# 共通
VER=

# extract
MOD=
EXTRACT_SRC=mods/$(MOD)
EXTRACT_TMP=src/storage/mods/$(MOD)

# pack
NAME=
JSON_SRC=translated/$(VER)/$(NAME)

# pack-all
JSON_VER_SRC=translated/$(VER)

.PHONY: dump-autoload
dump-autoload:
	docker compose run --rm --user $(UID):$(GID) $(SERVICE) composer dump-autoload

.PHONY: init
init:
	docker compose up -d --build
	docker compose exec $(SERVICE) composer install
	docker compose down
	sudo chown -R $(shell id -u):$(shell id -g) src
	sudo chmod -R 775 src/storage src/bootstrap/cache

.PHONY: extract
extract:
	cp $(EXTRACT_SRC) $(EXTRACT_TMP)

	docker compose run --rm --user $(UID):$(GID) $(SERVICE) php artisan translate:extract --mod $(MOD) --ver $(VER)

	rm -rf tmp/*
	mv -f src/storage/tmp/* tmp/
	rm -rf $(EXTRACT_TMP)

.PHONY: pack
pack:
	cp -r $(JSON_SRC) src/storage/tmp; \
	docker compose run --rm --user $(UID):$(GID) $(SERVICE) php artisan translate:pack --name $(NAME) --ver $(VER); \
	rm -rf src/storage/tmp/*;

.PHONY: pack-all
pack-all:
	@zip="build/resourcepacks/00-translated-all-in-{$VER}.zip"; \
	echo "Packing: 00-translated-all-in-{$VER}.zip"; \
	docker compose run --rm --user $(UID):$(GID) $(SERVICE) php artisan translate:pack-all --ver $(VER); \
	rm -rf src/storage/tmp/*;

.PHONY: diff
diff:
	docker compose run --rm --user $(UID):$(GID) $(SERVICE) php artisan translate:diff --name=$(NAME) --ver=$(VER)

.PHONY: enchant-levels
enchant-levels:
	docker compose run --rm --user $(UID):$(GID) $(SERVICE) php artisan translate:enchant-levels --max=1000 --ver=$(VER)

.PHONY: list-json
list-json:
	docker compose run --rm --user $(UID):$(GID) $(SERVICE) php artisan translate:list-json

.PHONY: build-html
build-html:
	docker compose run --rm --user $(UID):$(GID) $(SERVICE) php artisan translate:build-html