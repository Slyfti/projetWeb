.PHONY: install build run clean fresh-seed

# Installation complète du projet
install:
	composer install
	npm install
	sudo apt-get install -y php-sqlite3
	touch database/database.sqlite
	php artisan migrate:fresh
	php artisan db:seed

# Compilation des assets
build:
	npm run build

# Lancement du serveur de développement
run:
	php artisan serve

# Lancement du serveur avec hot reloading
dev:
	php artisan serve & npm run dev

# Nettoyage des fichiers générés
clean:
	rm -rf node_modules
	rm -rf vendor
	rm -rf public/build
	rm -rf public/hot
	rm -f database/database.sqlite

# Réinitialisation complète de la base de données
fresh-seed:
	php artisan migrate:fresh --seed

# Installation complète et lancement du site
setup: install build run

# Installation complète et lancement du site avec hot reloading
setup-dev: install build dev 