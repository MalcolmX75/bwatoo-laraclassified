# 📁 Structure du Projet LaraClassifier et Guide de Déploiement

## 🏗️ Rôle des Dossiers Principaux

### Dossiers Core Application (NE PAS MODIFIER)
```
app/                    # 🚫 Code source principal Laravel
├── Console/           # Commandes Artisan
├── Http/              # Contrôleurs, Middleware, Resources API
├── Models/            # Modèles Eloquent
├── Services/          # Logique métier
├── Observers/         # Pattern Observer pour événements
├── Providers/         # Service Providers Laravel
└── Rules/             # Règles de validation personnalisées

bootstrap/             # 🚫 Bootstrap Laravel (ne pas toucher)
config/                # 🚫 Configuration Laravel (peut être étendu)
database/              # 🚫 Migrations et seeders (peut être étendu)
├── migrations/        # Structure de la base de données
├── seeders/          # Données initiales
└── updates/          # Scripts de mise à jour par version

resources/             # 🚫 Vues et assets (peut être étendu)
├── views/            # Templates Blade
├── js/               # JavaScript source
└── sass/             # Styles SCSS

routes/                # 🚫 Définition des routes (peut être étendu)
storage/               # 🚫 Stockage Laravel (logs, cache, uploads)
vendor/                # 🚫 Dépendances Composer (auto-généré)
```

### Dossiers Extension (POINTS D'EXTENSION)
```
extras/                # ✅ Vos extensions personnalisées
├── plugins/          # Plugins tiers et custom
│   └── paypal/       # Exemple: plugin PayPal
└── themes/           # Thèmes personnalisés
    └── customized/   # Votre thème custom

packages/             # ✅ Packages Laravel personnalisés
public/               # ✅ Point d'entrée web + assets publics
lang/                 # ✅ Traductions (peut être étendu)
```

### Fichiers de Configuration et Outils
```
.env.example          # Template configuration environnement
.gitignore           # Fichiers à ignorer par Git
.gitattributes       # Configuration Git
composer.json        # Dépendances PHP
package.json         # Dépendances Node.js
webpack.mix.js       # Configuration Laravel Mix
artisan              # CLI Laravel
index.php            # Point d'entrée principal
phpunit.xml          # Configuration tests
```

## 🚀 Déploiement : Que Mettre sur le Serveur ?

### ❌ ERREUR COURANTE
**Ne jamais uploader tout le dossier `/site` à la racine du serveur !**

### ✅ MÉTHODE CORRECTE

#### Option 1 : Structure Recommandée (Plus Sécurisé)
```
serveur/
├── laraclassified/              # Dossier privé (hors web)
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── database/
│   ├── resources/
│   ├── routes/
│   ├── storage/                 # Permissions 755/775
│   ├── vendor/
│   ├── .env                     # Configuration production
│   ├── artisan
│   └── composer.json
│
└── public_html/ (ou www/)       # Dossier web public
    ├── index.php                # Point d'entrée modifié
    ├── assets/                  # Assets compilés
    ├── images/
    ├── robots.txt
    └── .htaccess               # Configuration Apache
```

#### Option 2 : Structure Simple (Hébergement Partagé)
Si vous ne pouvez pas avoir de dossier privé :
```
public_html/
├── [contenu complet de /site]
└── .env                        # Configuration production
```

## 🔧 Configuration pour le Déploiement

### 1. Modifications Nécessaires dans `public/index.php`
```php
// Ajuster les chemins selon votre structure
$app = require_once __DIR__.'/../laraclassified/bootstrap/app.php';
// ou pour structure simple:
// $app = require_once __DIR__.'/../bootstrap/app.php';
```

### 2. Configuration `.env` Production
```env
APP_NAME="Votre Site"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://votre-domaine.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=votre_db
DB_USERNAME=votre_user
DB_PASSWORD=votre_password

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=database
```

### 3. Permissions Serveur
```bash
# Dossiers de stockage
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chmod -R 755 public/

# Fichiers
chmod 644 .env
chmod 755 artisan
```

## 📋 Checklist de Déploiement

### Avant Upload
- [ ] Compiler les assets : `npm run production`
- [ ] Optimiser autoload : `composer install --optimize-autoloader --no-dev`
- [ ] Configurer `.env` pour production
- [ ] Tester en local avec `APP_ENV=production`

### Après Upload
- [ ] Vérifier permissions (755 pour dossiers, 644 pour fichiers)
- [ ] Lancer migrations : `php artisan migrate --force`
- [ ] Vider caches : `php artisan config:cache`
- [ ] Générer clé app : `php artisan key:generate`
- [ ] Créer lien stockage : `php artisan storage:link`
- [ ] Tester le site

### Optimisations Production
```bash
# Cache des configurations
php artisan config:cache

# Cache des routes
php artisan route:cache

# Cache des vues
php artisan view:cache

# Optimisation Composer
composer install --optimize-autoloader --no-dev
```

## 🔄 Git : Suivi de Version

### Stratégie Git Recommandée

#### Configuration Git (fichiers présents)
- `.gitignore` : Ignore `.env` et `public/webcron.php`
- `.gitattributes` : Configuration des types de fichiers

#### Branches Suggérées
```
main/master          # Version stable production
develop             # Développement en cours  
feature/nom         # Nouvelles fonctionnalités
hotfix/nom          # Corrections urgentes
```

#### Workflow Recommandé
```bash
# 1. Développement local
git checkout develop
git checkout -b feature/premium-ads

# 2. Vos modifications dans extras/
# 3. Test et validation

# 4. Merge vers develop
git checkout develop
git merge feature/premium-ads

# 5. Déploiement staging
git checkout main
git merge develop

# 6. Déploiement production (tag)
git tag v1.1.0
git push origin v1.1.0
```

## 🛡️ Sécurité et Bonnes Pratiques

### Fichiers à NE JAMAIS Committer
```
.env                    # Secrets de production
storage/logs/          # Logs applicatifs
storage/framework/     # Cache Laravel
node_modules/          # Dépendances Node
vendor/                # Dépendances Composer (optionnel)
```

### Structure Recommandée pour Vos Développements
```
bwatoo-laraclassified/
├── site/                      # Core LaraClassifier (suivi Git)
├── custom/                    # Vos développements (Git séparé)
│   ├── .git/                 # Repo Git séparé
│   ├── plugins/
│   │   └── premium-features/
│   ├── themes/
│   │   └── bwatoo-theme/
│   └── mobile-app/
└── deployment/               # Scripts de déploiement
    ├── deploy.sh
    ├── backup.sh
    └── rollback.sh
```

## 🚀 Automatisation du Déploiement

### Script de Déploiement Simple
```bash
#!/bin/bash
# deploy.sh

echo "🚀 Déploiement LaraClassifier..."

# 1. Sauvegarde
php artisan backup:run

# 2. Mise à jour code
git pull origin main

# 3. Dépendances
composer install --optimize-autoloader --no-dev
npm ci --production

# 4. Compilation assets
npm run production

# 5. Migrations
php artisan migrate --force

# 6. Cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 7. Restart services
php artisan queue:restart

echo "✅ Déploiement terminé!"
```

## 📊 Monitoring Post-Déploiement

### Vérifications Automatiques
```bash
# Vérifier l'état de l'application
php artisan app:health-check

# Vérifier les queues
php artisan queue:monitor

# Vérifier l'espace disque
df -h

# Vérifier les logs d'erreur
tail -f storage/logs/laravel.log
```

---

**Résumé Important :**
- 🚫 **Ne jamais modifier** les dossiers `app/`, `config/`, `database/migrations/` du core
- ✅ **Étendre via** `extras/plugins/` et `extras/themes/`
- 🌐 **Déployer** uniquement le contenu nécessaire avec la bonne structure
- 🔒 **Sécuriser** avec permissions correctes et `.env` adapté
- 📋 **Suivre** la checklist de déploiement pour éviter les erreurs