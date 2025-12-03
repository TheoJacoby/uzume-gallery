# Instructions d'Installation - Galerie d'Art

## Étapes d'installation complètes

### 1. Vérifier que MAMP/WAMP est démarré
Assurez-vous que MySQL est en cours d'exécution sur le port 3306.

### 2. Créer la base de données
```bash
php bin/console doctrine:database:create
```

### 3. Générer et exécuter les migrations
```bash
php bin/console make:migration
php bin/console doctrine:migrations:migrate
```

### 4. Charger les données de test (fixtures)
```bash
php bin/console doctrine:fixtures:load
```

Cette commande va créer :
- 5 catégories
- 5 techniques
- 20 peintures (80% publiées)
- Des commentaires associés

### 5. Lancer le serveur de développement

**Option 1 : Avec Symfony CLI**
```bash
symfony server:start
```

**Option 2 : Avec PHP intégré**
```bash
php -S localhost:8000 -t public
```

### 6. Accéder à l'application

- **Page d'accueil** : http://localhost:8000/
- **Galerie** : http://localhost:8000/gallery
- **Administration** : http://localhost:8000/admin/paintings

## Structure des routes

### Routes publiques
- `/` : Page d'accueil
- `/gallery` : Liste des peintures
- `/gallery/{id}` : Détail d'une peinture
- `/about` : À propos
- `/team` : Équipe

### Routes d'administration
- `/admin/paintings` : Liste des peintures
- `/admin/paintings/new` : Créer une peinture
- `/admin/paintings/{id}/edit` : Modifier une peinture
- `/admin/paintings/{id}/delete` : Supprimer une peinture
- `/admin/paintings/{id}/toggle-publish` : Publier/Masquer
- `/admin/comments` : Gestion des commentaires
- `/admin/comments/{id}/toggle-visibility` : Afficher/Masquer un commentaire

## Notes importantes

1. **Images** : Les images uploadées sont stockées dans `assets/images/paintings/`
2. **Sécurité** : L'administration est accessible sans authentification (comme demandé)
3. **Fixtures** : Les peintures générées n'ont pas d'images réelles (placeholders)
4. **Cache** : En cas de problème, vider le cache avec `php bin/console cache:clear`

## Dépannage

### Erreur de connexion à la base de données
- Vérifier que MySQL est démarré
- Vérifier les identifiants dans `.env` (root/root)
- Vérifier que la base de données `gallery` existe

### Erreur de permissions sur les fichiers
```bash
chmod -R 777 var/
chmod -R 777 assets/images/
```

### Réinitialiser la base de données
```bash
php bin/console doctrine:database:drop --force
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
```



