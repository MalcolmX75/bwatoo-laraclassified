# LaraClassifier - Classified Ads Web Application

**Script URL:** https://codecanyon.net/item/laraclassified-geo-classified-ads-cms/16458425

## 🎯 Présentation

LaraClassifier est une application web de petites annonces construite sur Laravel, le framework PHP le plus moderne, puissant et sécurisé.

## 📍 Localisation du Projet

Le code source du projet se trouve dans le répertoire : `/site`

## ✨ Fonctionnalités Principales

### Personnalisation et Flexibilité
- **Champs personnalisés** : Créez vos propres sites d'annonces classées, de vente automobile, d'immobilier, d'annuaire ou de portail d'emploi
- **Personnalisation de la page d'accueil** : Modifiez et organisez les sections depuis le panneau d'administration
- **Support multi-devises**
- **Multilingue**
- **Couleurs illimitées**
- **Support RTL** (de droite à gauche)

### Technologies et Architecture
- **API REST** (RESTful API)
- **Design moderne et épuré**
- **Entièrement responsive** (y compris le panneau d'administration)
- **Frameworks** : Laravel, Bootstrap, jQuery, VueJS
- **Technologies** : PHP/MySQL & HTML5/CSS3

### Géolocalisation et Cartes
- **Géolocalisation supportée** (avec base de données Maxmind gratuite ou pro)
- **Google Maps intégré**

### Gestion des Utilisateurs
- **Système de rôles et permissions** (ACL) intégré
- **Connexion via réseaux sociaux** (Facebook, Google)
- **Protection des numéros de téléphone** contre les crawlers
- **Option d'activation des comptes utilisateurs** (ON/OFF)
- **Possibilité pour les invités de poster** sans être connectés

### Monétisation et Paiements
- **PayPal intégré**
- **Google Adsense intégré**
- **Plans/Packages** pour les annonces premium

### Communication
- **Notifications par email** (SMTP local ou APIs : Amazon SES, Mailgun, Mandrill, Sparkpost)
- **Notifications par SMS** (APIs Twilio ou Nexmo)
- **Système de messagerie** entre vendeurs et acheteurs (seuls les acheteurs peuvent initier)
- **Commentaires Facebook intégrés**
- **Fonction de renvoi** de vérification email/SMS

### Sécurité et Modération
- **ReCaptcha intégré**
- **Option d'activation des annonces** (ON/OFF)
- **Module de liste noire** (panneau d'administration)

### SEO et Marketing
- **Optimisé SEO**
- **Sitemap XML Google**
- **Flux RSS**
- **Page liste des pays** (si activée, SEO supporté)
- **Support du format d'image WebP**

### Gestion du Contenu
- **CMS pour pages statiques** (À propos, FAQ, CGU, etc.)
- **Page de contact** avec formulaire et Google Maps
- **Page sitemap du site**

### Administration et Maintenance
- **Sauvegarde du site** (module panneau d'administration)
- **Soumission d'annonces en front-end**
- **Code bien commenté**
- **Installation rapide** (2 minutes)

## 🔧 Prérequis Techniques

### Base de Données
- **MySQL 5.7+** ou **MariaDB 10.3+**
- L'utilisateur DB doit avoir tous les privilèges (incluant FLUSH TABLES)
- **Collation recommandée** (par ordre de préférence) :
  - utf8mb4_0900_ai_ci
  - utf8mb4_unicode_ci
  - utf8mb4_general_ci
  - utf8mb3_unicode_ci
  - utf8mb3_general_ci
  - utf8_unicode_ci
  - utf8_general_ci
- **Paramètres de performance** :
  - max_user_connections : 30-100
  - max_connections : 150-200

### Serveur
- **PHP 8.2 ou supérieur** (avec toutes les fonctions par défaut activées)
- **Extensions PHP requises** :
  - BCMath
  - Ctype
  - cURL (version 7.34.0+)
  - DOM
  - Fileinfo
  - Filter
  - Hash
  - JSON
  - Mbstring
  - OpenSSL
  - PCRE
  - PDO
  - Session
  - Tokenizer
  - XML
  - GD (ou Imagick)
  - Zip Archive
- **Module Rewrite activé**
- **PHP.ini** : open_basedir doit être désactivé

### Serveurs Web Supportés
- **Apache** (entièrement supporté)
- **Nginx** (entièrement supporté)
- **LiteSpeed/OpenLiteSpeed** (gestion des permissions requise, LSCache non supporté)
- **Varnish** (configuration avancée requise)

## 📝 Notes Importantes
- Images, icônes et plugins (add-ons) non inclus
- **Support bugs** : https://support.laraclassifier.com/hc/tickets/new

## 🔗 Sources et Crédits
- Laravel : https://laravel.com
- Maxmind : https://dev.maxmind.com/geoip/geoip2/geolite2/
- Geonames : https://www.geonames.org
- Simplemaps : https://simplemaps.com/resources/svg-maps

## 💻 Développement Local sur Mac

### Prérequis Système

1. **macOS** avec Homebrew installé
2. **PHP 8.2+** avec extensions : BCMath, cURL, Fileinfo, JSON, PDO, GD/Imagick, Zip
3. **MySQL 5.7+** ou **MariaDB 10.3+**
4. **Node.js** et **npm** (pour les assets front-end)
5. **Composer** (gestionnaire de dépendances PHP)
6. **Redis** (optionnel, pour cache et queues)

### Installation des Prérequis

```bash
# Installation via Homebrew
brew install php@8.2
brew install mysql
brew install node
brew install composer
brew install redis  # Optionnel

# Démarrage des services
brew services start mysql
brew services start redis  # Si installé
```

### Configuration du Projet

```bash
# 1. Se positionner dans le répertoire du projet
cd /path/to/laraclassified/site

# 2. Installation des dépendances
composer install
npm install

# 3. Configuration de l'environnement
cp .env.example .env
php artisan key:generate

# 4. Configuration de la base de données dans .env
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=laraclassified
# DB_USERNAME=root
# DB_PASSWORD=votre_mot_de_passe

# 5. Création de la base de données
mysql -u root -p -e "CREATE DATABASE laraclassified CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 6. Migration et seed de la base de données
php artisan migrate
php artisan db:seed

# 7. Compilation des assets
npm run dev
```

### Configuration VSCode

#### Extensions Recommandées
1. **PHP Intelephense** - Autocomplétion et analyse PHP
2. **Laravel Blade Snippets** - Support des templates Blade
3. **Laravel Snippets** - Snippets Laravel
4. **DotENV** - Coloration syntaxique .env
5. **ESLint** - Linting JavaScript
6. **Prettier** - Formatage du code
7. **Laravel Artisan** - Commandes artisan intégrées
8. **PHP Debug** - Débogage avec XDebug

#### Configuration VSCode (.vscode/settings.json)
```json
{
    "php.validate.executablePath": "/opt/homebrew/bin/php",
    "files.associations": {
        "*.blade.php": "html"
    },
    "emmet.includeLanguages": {
        "blade": "html"
    },
    "blade.format.enable": true,
    "editor.formatOnSave": true,
    "[php]": {
        "editor.defaultFormatter": "bmewburn.vscode-intelephense-client"
    },
    "intelephense.environment.phpVersion": "8.2",
    "intelephense.files.exclude": [
        "**/vendor/**",
        "**/node_modules/**"
    ]
}
```

### Lancement du Serveur de Développement

```bash
# Option 1 : Commande tout-en-un
npm run dev

# Option 2 : Services séparés (dans différents terminaux)
php artisan serve                    # Serveur HTTP (http://localhost:8000)
php artisan queue:listen --tries=1   # Worker pour les queues
npm run watch                         # Watcher pour les assets
```

## 🚀 Stratégie pour Ajouter des Fonctionnalités Sans Casser les Mises à Jour

### 1. Architecture Modulaire via Plugins

LaraClassifier dispose d'un système de plugins robuste permettant d'ajouter des fonctionnalités sans toucher au core :

```
extras/plugins/
├── votre-plugin/
│   ├── plugin.json          # Métadonnées du plugin
│   ├── src/                 # Code source PHP
│   ├── resources/           # Vues, assets
│   ├── database/            # Migrations spécifiques
│   └── routes/              # Routes du plugin
```

### 2. Points d'Extension Recommandés

#### Pour les Nouvelles Fonctionnalités (Bump-up, Featured, Top)

1. **Créer un plugin personnalisé** dans `extras/plugins/premium-features/`
2. **Étendre les modèles existants** via traits ou relations
3. **Utiliser les événements Laravel** pour intercepter les actions
4. **Ajouter des vues personnalisées** via le système de thèmes

#### Structure Suggérée pour le Plugin Premium Features

```php
// extras/plugins/premium-features/src/Models/PremiumFeature.php
namespace Plugins\PremiumFeatures\Models;

class PremiumFeature extends Model {
    // Bump-up, Featured, Top logic
}

// extras/plugins/premium-features/src/Services/AdPromotionService.php
namespace Plugins\PremiumFeatures\Services;

class AdPromotionService {
    public function bumpUp($adId) { }
    public function makeFeatured($adId) { }
    public function makeTop($adId) { }
}
```

### 3. Système de Paiement par Visualisation d'Annonces

```php
// extras/plugins/premium-features/src/Services/AdViewCreditsService.php
namespace Plugins\PremiumFeatures\Services;

class AdViewCreditsService {
    public function earnCredits($userId, $adId) {
        // Logique pour gagner des crédits
    }
    
    public function spendCredits($userId, $feature, $amount) {
        // Logique pour dépenser des crédits
    }
}
```

### 4. Bonnes Pratiques pour la Maintenance

1. **Ne jamais modifier les fichiers du core** dans `app/`
2. **Utiliser les observers** pour étendre le comportement des modèles
3. **Créer des migrations séparées** dans le dossier du plugin
4. **Documenter toutes les modifications** dans un fichier CHANGELOG
5. **Utiliser le versioning sémantique** pour vos plugins
6. **Tester la compatibilité** à chaque mise à jour du core

### 5. Structure de Développement Recommandée

```
laraclassified/
├── site/                          # Core LaraClassified (ne pas modifier)
├── custom/                        # Vos développements personnalisés
│   ├── plugins/                   # Vos plugins
│   ├── themes/                    # Vos thèmes personnalisés
│   └── documentation/             # Documentation de vos modifications
└── mobile-app/                    # Future application mobile
    ├── ios/                       # App iOS
    └── android/                   # App Android
```

## 📱 Planification de l'Application Mobile

### Architecture Recommandée

1. **API REST existante** : LaraClassifier dispose déjà d'une API RESTful
2. **Framework suggéré** : React Native ou Flutter pour le développement cross-platform
3. **Authentification** : Laravel Sanctum déjà intégré

### Structure Proposée pour l'App Mobile

```
mobile-app/
├── src/
│   ├── api/                      # Client API
│   ├── components/               # Composants réutilisables
│   ├── screens/                  # Écrans de l'app
│   │   ├── ads/                  # Listing, détail annonces
│   │   ├── premium/              # Bump-up, Featured, Top
│   │   └── rewards/              # Système de récompenses
│   └── services/                 # Services (notifications, etc.)
├── ios/                          # Configuration iOS
└── android/                      # Configuration Android
```

### Fonctionnalités Spécifiques Mobile

1. **Système de récompenses par visualisation**
   - Intégration avec AdMob ou similaire
   - API endpoint : `/api/rewards/watch-ad`
   - Gestion des crédits côté serveur

2. **Notifications Push**
   - Utiliser Firebase Cloud Messaging
   - Intégration avec les notifications Laravel existantes

3. **Géolocalisation avancée**
   - Utiliser l'API native du téléphone
   - Recherche par proximité

## 🛠️ Commandes Utiles pour le Développement

```bash
# Vider le cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Régénérer les autoloads
composer dump-autoload

# Lancer les tests
php artisan test

# Générer la documentation API
php artisan scribe:generate

# Créer un nouveau plugin
php artisan make:plugin PremiumFeatures

# Vérifier le code
./vendor/bin/pint        # Formatage PHP
npm run lint             # Linting JS/CSS
```

## 📝 Checklist Avant de Commencer le Développement

- [ ] Environnement local configuré et fonctionnel
- [ ] Base de données créée et migrée
- [ ] Assets compilés avec succès
- [ ] VSCode configuré avec les extensions
- [ ] Accès à l'application sur http://localhost:8000
- [ ] Documentation de l'API consultée (/docs)
- [ ] Structure des plugins comprise
- [ ] Plan de développement mobile défini