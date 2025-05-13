# Dockerサービス名
SERVICE=laravel-cli

# MOD jarファイル名（拡張子 .jar 付き）
MOD=

# Minecraftバージョン（例: 1.20.1）
VER=

# 初回セットアップ（composer install）
init:
	docker-compose up -d --build
	docker-compose exec $(SERVICE) composer install
	docker-compose down

# MODから en_us.json を抽出
extract:
	docker-compose run --rm $(SERVICE) php artisan translate:extract $(MOD)

# ja_jp.json を zip にパック
pack:
	docker-compose run --rm $(SERVICE) php artisan translate:pack --name $(MOD) --ver $(VER)