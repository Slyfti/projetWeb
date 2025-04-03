# projetWeb

## Installer Laravel
https://laravel.com/docs/12.x#installing-php

## Installer les dependencies
```bash
npm install
composer global require laravel/installer
composer install
```

## Build le serveur Laravel
```bash
npm run build
```

## Lancer le serveur Laravel
```bash
php artisan serve
```

## Base de donnée

### Créer la BDD
```
sudo apt-get install php-sqlite3
touch database/database.sqlite
php artisan migrate
```
### Regénerer la BDD (quand on a modifié les fichiers dans database)
Regénerer le schema de BDD présent dans le dossier database/migrations
```
php artisan migrate
```

### Seed la BDD 
Remplir la BDD d'exemples présents dans database/seeders

```
php artisan db:seed
```


#### Accèder à la BDD manuellement (très rare)
```
sqlite3 database/database.sqlite
```
#### Améliorer affichage résultat requetes (dans sqlite)
```
.mode box
```

#### Faire une requete (dans sqlite)
```
SELECT * FROM USERS;
```
