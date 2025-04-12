# Astrosphère - Gestion de Stade connecté

Ce projet est une application web de gestion de stade développée avec le framework Laravel.

## Prérequis

- PHP 8.1 ou supérieur
- Composer
- Node.js et npm
- SQLite3
- Make (optionnel, pour utiliser les commandes automatisées)

## Comptes utilisateurs de test

Le projet inclut plusieurs comptes utilisateurs de test avec différents niveaux d'accès :

| Pseudo | Email | Mot de passe | Type de membre | Niveau |
|--------|-------|--------------|----------------|---------|
| admin_stade | admin@stade.fr | admin123 | Administratif | Expert |
| tech_martin | martin@stade.fr | tech123 | Personnel technique | Avancé |
| coach_pierre | pierre@stade.fr | coach123 | Entraîneur | Expert |
| secu_marie | marie@stade.fr | secu123 | Sécurité | Intermédiaire |

### Installation de Make

Si vous n'avez pas Make installé, vous pouvez l'installer avec les commandes suivantes :

```bash
# Sur Ubuntu/Debian
sudo apt-get install make
```

```bash
# Sur macOS (avec Homebrew)
brew install make
```

```bash
# Sur Windows (avec Chocolatey)
choco install make
```

## Installation rapide avec Make

Pour une installation et un lancement rapides du projet, vous pouvez utiliser les commandes Make suivantes :

```bash
# Cloner le projet
git clone git@github.com:Slyfti/projetWeb.git
```

```bash
cd projetWeb
```

```bash
# Installation complète et lancement du site
make setup
```

Le site sera accessible à l'adresse : http://localhost:8000

## Installation manuelle

### 1. Cloner le projet

```bash
git clone git@github.com:Slyfti/projetWeb.git
```

```bash
cd projetWeb
```

### 2. Installer les dépendances PHP

```bash
composer install
```

### 3. Installer les dépendances JavaScript

```bash
npm install
```

### 4. Configurer la base de données

```bash
# Installer l'extension SQLite pour PHP
sudo apt-get install php-sqlite3
```

```bash
# Créer le fichier de base de données
touch database/database.sqlite
```

### 5. Migrer la base de données

```bash
php artisan migrate
```

### 6. Remplir la base de données avec des données de test

```bash
php artisan db:seed
```

### 7. Compiler les assets

```bash
npm run build
```

## Lancement du site

### En mode développement

```bash
# Lancer le serveur Laravel
php artisan serve
```

```bash
# Dans un autre terminal, lancer Vite pour le hot reloading (optionnel)
npm run dev
```

Le site sera accessible à l'adresse : http://localhost:8000

## Commandes Make disponibles

Le projet inclut un Makefile avec plusieurs commandes utiles :

```bash
# Installation complète du projet
make install
```

```bash
# Compilation des assets
make build
```

```bash
# Lancement du serveur de développement
make run
```

```bash
# Lancement du serveur avec hot reloading
make dev
```

```bash
# Nettoyage des fichiers générés
make clean
```

```bash
# Réinitialisation complète de la base de données
make fresh-seed
```

```bash
# Installation complète et lancement du site
make setup
```

```bash
# Installation complète et lancement du site avec hot reloading
make setup-dev
```

## Commandes utiles

### Régénérer la base de données

Si vous avez modifié les fichiers de migration dans `database/migrations` :

```bash
php artisan migrate:fresh
```

### Réinitialiser et remplir la base de données

Pour réinitialiser complètement la base de données et la remplir avec les données de test :

```bash
php artisan migrate:fresh --seed
```

## Structure du projet

- `app/` : Contient les modèles, contrôleurs et autres classes PHP
- `database/` : Contient les migrations et les seeders
  - `migrations/` : Définition de la structure de la base de données
  - `seeders/` : Données de test pour remplir la base de données
- `resources/` : Contient les vues, assets CSS/JS, etc.
- `routes/` : Définition des routes de l'application

## Fonctionnalités principales

- Gestion des utilisateurs
- Gestion des événements sportifs
- Gestion des objets connectés
- Gestion des services
- Suivi des actions utilisateurs
- Système de points et de niveaux de compétences

## Groupe ING1 GI2 numéro 6
- BIOUDI Mathéo
- CAPBLANCQ Sylvain
- LESBARRERES Emma
- ORDONEZ SEGURA Angie

