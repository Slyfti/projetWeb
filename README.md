# projetWeb

## Installer Laravel
https://laravel.com/docs/12.x#installing-php

## Installer les dependencies
```bash
npm install
composer global require laravel/installer
composer install
```

## Créer la BDD
```
sudo apt-get install php-sqlite3
touch database/database.sqlite
php artisan migrate
```

## Accèder à la BDD manuellement (une fois dedans on peut 
```
sqlite3 database/database.sqlite
```
### Améliorer affichage résultat requetes (dans sqlite)
```
.mode box
```

### Faire une requete (dans sqlite)
```
SELECT * FROM USERS;
```
 
## Build le serveur Laravel
```bash
npm run build
```

## Lancer le serveur Laravel
```bash
php artisan serve
```
