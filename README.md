# Galerie d'Art - Application Symfony

Application de gestion de galerie d'art développée avec Symfony 7.4, suivant les méthodologies du cours "Namur Cadets Symfony".

## Prérequis

- PHP 8.2 ou supérieur
- Composer
- MySQL (via MAMP/WAMP)
- Symfony CLI (optionnel)

## Installation

### 1. Cloner le projet et installer les dépendances

```bash
cd /Applications/MAMP/htdocs/symfony/projet_theo
composer install
```

### 2. Configuration de la base de données

Le fichier `.env` est déjà configuré avec :
```
DATABASE_URL="mysql://root:root@127.0.0.1:3306/gallery?serverVersion=8.0&charset=utf8mb4"
```

### 3. Créer la base de données

```bash
php bin/console doctrine:database:create
```

### 4. Créer les migrations et les exécuter

```bash
php bin/console make:migration
php bin/console doctrine:migrations:migrate
```

### 5. Charger les fixtures (données de test)

```bash
php bin/console doctrine:fixtures:load
```

Cette commande va créer :
- 5 catégories (Renaissance, Baroque, Impressionnisme, Moderne, Contemporain)
- 5 techniques (Huile, Fusain, Acrylique, Aquarelle, Pastel)
- 20 peintures avec des données réalistes
- Des commentaires associés aux peintures

## Structure du projet

### Contrôleurs

- `src/Controller/PageController.php` : Pages statiques (Home, About, Team)
- `src/Controller/GalleryController.php` : Galerie publique (liste et détail des peintures)
- `src/Controller/Admin/PaintingController.php` : Administration CRUD des peintures

### Entités

- `Category` : Catégories de peintures
- `Technique` : Techniques artistiques
- `Painting` : Peintures avec upload d'images via VichUploaderBundle
- `Comment` : Commentaires sur les peintures

### Templates

- `templates/base.html.twig` : Template de base avec Bootstrap 5
- `templates/partials/navbar.html.twig` : Navigation principale
- `templates/partials/footer.html.twig` : Pied de page
- `templates/pages/` : Pages statiques
- `templates/gallery/` : Pages de la galerie publique
- `templates/admin/` : Pages d'administration

## Fonctionnalités

### Zone Publique

- **Page d'accueil** (`/`) : Carousel Bootstrap avec 3 images et texte de bienvenue
- **Galerie** (`/gallery`) : Liste des peintures publiées avec cards Bootstrap
- **Détail d'une peinture** (`/gallery/{id}`) : Affichage complet avec formulaire de commentaire
- **À propos** (`/about`) : Page de présentation
- **Équipe** (`/team`) : Présentation de l'équipe

### Zone Administration

- **Liste des peintures** (`/admin/paintings`) : Tableau avec toutes les peintures
  - Toggle publication (icône œil)
  - Actions : Voir, Modifier, Supprimer
- **Créer une peinture** (`/admin/paintings/new`) : Formulaire complet avec upload d'image
- **Modifier une peinture** (`/admin/paintings/{id}/edit`) : Édition avec prévisualisation
- **Gestion des commentaires** (`/admin/comments`) : Modération (toggle visibilité)

## Technologies utilisées

- **Symfony 7.4** : Framework PHP
- **Doctrine ORM** : Gestion de la base de données
- **Twig** : Moteur de templates
- **AssetMapper** : Gestion des assets (Bootstrap 5)
- **VichUploaderBundle** : Upload d'images
- **FakerPHP** : Génération de données de test
- **Bootstrap 5** : Framework CSS

## Commandes utiles

```bash
# Lancer le serveur de développement
symfony server:start
# ou
php -S localhost:8000 -t public

# Vider le cache
php bin/console cache:clear

# Créer une nouvelle migration
php bin/console make:migration

# Exécuter les migrations
php bin/console doctrine:migrations:migrate

# Recharger les fixtures
php bin/console doctrine:fixtures:load
```

## Notes importantes

- Les images sont stockées dans `assets/images/paintings/`
- L'administration est accessible sans authentification (comme demandé)
- Les messages flash sont affichés en haut de chaque page
- Les peintures non publiées (`isPublished = false`) n'apparaissent pas dans la galerie publique

## Auteur

Développé dans le cadre du cours "Namur Cadets Symfony"



