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
	@zip="build/resourcepacks/$(NAME)-translate-to-japanese-$(VER).zip"; \
	json="translated/$(VER)/$(NAME)/lang/ja_jp.json"; \
	if [ ! -f $$zip ] || [ $$json -nt $$zip ]; then \
		echo "Packing: $(NAME)-$(VER).zip"; \
		cp -r $(JSON_SRC) src/storage/tmp; \
		docker compose run --rm --user $(UID):$(GID) $(SERVICE) php artisan translate:pack --name $(NAME) --ver $(VER); \
		rm -rf src/storage/tmp/*; \
		mv src/build/resourcepacks/* build/resourcepacks; \
	else \
		echo "No update: skipping pack for $(NAME)-$(VER)"; \
	fi