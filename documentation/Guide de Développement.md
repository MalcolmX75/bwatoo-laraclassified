# 🛠️ Guide de Développement LaraClassifier

## 🚀 Démarrage Rapide

### Lancement du Serveur de Développement

**Option 1 : Script recommandé (sans avertissements)**
```bash
./dev-serve.sh
```

**Option 2 : Commande manuelle**
```bash
php -d error_reporting="E_ALL & ~E_DEPRECATED" artisan serve --host=127.0.0.1 --port=8000
```

**Option 3 : Standard Laravel**
```bash
php artisan serve
```

### Accès à l'Application

- **Site web** : http://127.0.0.1:8000
- **Panel admin** : http://127.0.0.1:8000/admin
- **API docs** : http://127.0.0.1:8000/docs

## ⚠️ Problèmes Courants

### Avertissements PHP 8.4

**Symptôme** : Messages "Deprecated: ... parameter ... nullable is deprecated"

**Cause** : LaraClassifier utilise du code compatible PHP 8.2, mais PHP 8.4 est plus strict

**Solutions** :
1. ✅ **Utiliser le script `dev-serve.sh`** (recommandé)
2. ✅ **Configurer .env** avec `LOG_DEPRECATIONS_CHANNEL=null`
3. ⚡ **Downgrade vers PHP 8.3** : `brew install php@8.3 && brew unlink php && brew link php@8.3`

### Base de Données

**Configuration dans .env** :
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laraclassified
DB_USERNAME=root
DB_PASSWORD=
```

**Commandes utiles** :
```bash
# Créer la base
mysql -u root -e "CREATE DATABASE laraclassified CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Migrations
php artisan migrate

# Reset complet
php artisan migrate:refresh --seed
```

## 🔧 Configuration de Développement

### Variables d'Environnement (.env)

```env
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

# Masquer dépréciation PHP 8.4
LOG_DEPRECATIONS_CHANNEL=null

# Base de données
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=laraclassified
DB_USERNAME=root
DB_PASSWORD=

# Cache en développement
CACHE_DRIVER=array
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

### Optimisations de Développement

```bash
# Vider tous les caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# Régénérer autoload
composer dump-autoload

# Mode debug avancé
php artisan route:list          # Voir toutes les routes
php artisan config:show app     # Voir la config
```

## 📂 Structure pour Vos Développements

### Architecture Recommandée

```
bwatoo-laraclassified/
├── site/                          # ❌ Core LaraClassifier (ne pas modifier)
├── custom/                        # ✅ Vos développements
│   ├── plugins/                   # Plugins personnalisés
│   │   ├── premium-features/      # Bump-up, Featured, Top
│   │   ├── rewards-system/        # Système de récompenses
│   │   └── mobile-api/            # API pour app mobile
│   ├── themes/                    # Thèmes personnalisés
│   │   └── bwatoo-theme/          # Votre thème
│   └── mobile-app/                # Application mobile
│       ├── ios/
│       └── android/
└── documentation/                 # Documentation du projet
```

### Bonnes Pratiques

1. **Ne jamais modifier** les fichiers dans `/site/app/`, `/site/config/`, `/site/database/migrations/`
2. **Toujours étendre via** `/site/extras/plugins/` et `/site/extras/themes/`
3. **Git séparé** pour vos développements dans `/custom/`
4. **Tests systématiques** après chaque modification
5. **Backup** avant les mises à jour du core

## 🔌 Développement de Plugins

### Créer un Plugin Custom

```bash
# Structure d'un plugin
mkdir -p site/extras/plugins/premium-features
cd site/extras/plugins/premium-features

# Fichiers requis
touch plugin.json
mkdir -p src/{Models,Services,Controllers}
mkdir -p resources/{views,assets}
mkdir -p database/{migrations,seeders}
```

### Plugin.json Template

```json
{
    "name": "Premium Features",
    "version": "1.0.0",
    "author": "Bwatoo",
    "description": "Bump-up, Featured et Top ads",
    "category": "enhancement",
    "installed": false,
    "activated": false,
    "settings": {
        "bump_price": 5,
        "featured_price": 10,
        "top_price": 20
    }
}
```

### Service Provider du Plugin

```php
<?php
// src/PremiumFeaturesServiceProvider.php

namespace Plugins\PremiumFeatures;

use Illuminate\Support\ServiceProvider;

class PremiumFeaturesServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Enregistrer les services
    }

    public function boot()
    {
        // Charger routes, vues, migrations
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadViewsFrom(__DIR__.'/resources/views', 'premium-features');
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }
}
```

## 🎨 Développement Frontend

### Assets (CSS/JS)

**Laravel Mix Configuration** :
```javascript
// webpack.mix.js
mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css')
   .js('custom/plugins/premium-features/resources/js/premium.js', 'public/js')
   .sass('custom/plugins/premium-features/resources/sass/premium.scss', 'public/css');
```

**Compilation** :
```bash
npm run dev          # Développement
npm run watch        # Watch mode
npm run production   # Production
```

## 📱 API pour Application Mobile

### Endpoints Custom

```php
// custom/plugins/mobile-api/routes/api.php

Route::prefix('mobile')->group(function () {
    Route::post('/ads/bump', 'AdPromotionController@bumpUp');
    Route::post('/ads/feature', 'AdPromotionController@makeFeatured');
    Route::post('/ads/top', 'AdPromotionController@makeTop');
    Route::post('/rewards/watch-ad', 'RewardsController@watchAd');
    Route::get('/user/credits', 'RewardsController@getCredits');
});
```

### Authentication Mobile

```php
// Utiliser Laravel Sanctum (déjà installé)
Route::post('/mobile/login', 'Auth\MobileAuthController@login');
Route::middleware('auth:sanctum')->group(function () {
    // Routes protégées
});
```

## 🧪 Tests et Débogage

### Tests

```bash
# Tests unitaires
php artisan test

# Tests spécifiques
php artisan test --filter=PremiumFeaturesTest

# Coverage
php artisan test --coverage
```

### Débogage

```bash
# Logs en temps réel
tail -f storage/logs/laravel.log

# Debug mode avancé
composer require --dev barryvdh/laravel-debugbar

# Ray debugging (déjà installé)
ray('Debug message');
```

## 🚀 Déploiement

### Pré-déploiement

```bash
# Optimisations
composer install --optimize-autoloader --no-dev
npm run production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Checklist

- [ ] Tests passent : `php artisan test`
- [ ] Assets compilés : `npm run production`
- [ ] Config optimisée : `php artisan config:cache`
- [ ] Migrations à jour : `php artisan migrate:status`
- [ ] Backup effectué
- [ ] .env production configuré

---

**Commandes de Démarrage Rapide** :

```bash
# Environnement propre
./dev-serve.sh

# Avec tous les services
php artisan serve &
php artisan queue:work &
npm run watch &
```