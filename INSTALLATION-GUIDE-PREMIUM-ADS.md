# 🚀 Guide d'Installation - Module Premium Ads

## ✅ Statut Actuel
- ✅ **Fichiers créés** : Tous les fichiers du module sont en place
- ✅ **Routes ajoutées** : Routes admin configurées
- ✅ **Services enregistrés** : Services injectés dans le container
- ⚠️ **Migration en attente** : Base de données à configurer
- ⚠️ **Interface visible** : Après configuration DB

## 📋 Étapes d'Installation

### Étape 1: Configuration Base de Données

#### 1.1 Créer le fichier .env
```bash
cd /Users/xdream/projets/baoprod/bwatoo-laraclassified/site
cp .env.example .env
```

#### 1.2 Configurer la base de données dans .env
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bwatoo_laraclassified
DB_USERNAME=root
DB_PASSWORD=votre_mot_de_passe
```

#### 1.3 Créer la base de données
```sql
-- Dans MySQL/PhpMyAdmin
CREATE DATABASE bwatoo_laraclassified CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Étape 2: Exécuter les Migrations

```bash
cd /Users/xdream/projets/baoprod/bwatoo-laraclassified/site

# Générer la clé d'application
php artisan key:generate

# Exécuter toutes les migrations
php artisan migrate

# Vérifier que la table post_promotions existe
php artisan tinker
>>> DB::select("SHOW TABLES LIKE 'post_promotions'");
```

### Étape 3: Alimenter la Base avec des Données Test

```bash
php artisan tinker
```

```php
// Créer des packages de promotion de base
use App\Models\Package;

$packages = [
    [
        'type' => 'promotion',
        'name' => json_encode([
            'en' => 'Bump Up',
            'fr' => 'Remonter'
        ]),
        'short_name' => json_encode([
            'en' => 'Bump',
            'fr' => 'Remonter'
        ]),
        'price' => 2.00,
        'currency_code' => 'USD',
        'promotion_time' => 7,
        'pictures_limit' => 5,
        'expiration_time' => 30,
        'active' => 1,
        'lft' => 1,
        'rgt' => 2,
        'depth' => 0,
    ],
    [
        'type' => 'promotion',
        'name' => json_encode([
            'en' => 'Featured Listing',
            'fr' => 'Annonce Mise en Avant'
        ]),
        'short_name' => json_encode([
            'en' => 'Featured',
            'fr' => 'Featured'
        ]),
        'price' => 10.00,
        'currency_code' => 'USD',
        'promotion_time' => 14,
        'ribbon' => 'warning',
        'has_badge' => 1,
        'pictures_limit' => 10,
        'expiration_time' => 60,
        'recommended' => 1,
        'active' => 1,
        'lft' => 3,
        'rgt' => 4,
        'depth' => 0,
    ],
    [
        'type' => 'promotion',
        'name' => json_encode([
            'en' => 'Top Ad',
            'fr' => 'Annonce en Tête'
        ]),
        'short_name' => json_encode([
            'en' => 'Top',
            'fr' => 'Tête'
        ]),
        'price' => 25.00,
        'currency_code' => 'USD',
        'promotion_time' => 30,
        'ribbon' => 'success',
        'has_badge' => 1,
        'pictures_limit' => 15,
        'expiration_time' => 120,
        'recommended' => 1,
        'active' => 1,
        'lft' => 5,
        'rgt' => 6,
        'depth' => 0,
    ]
];

foreach ($packages as $package) {
    Package::create($package);
}

echo "Packages créés avec succès !";
```

### Étape 4: Vider le Cache

```bash
# Vider tous les caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Reconstruire les caches
php artisan config:cache
php artisan route:cache
```

### Étape 5: Accéder à l'Interface Admin

1. **Démarrer le serveur** (si pas déjà fait) :
   ```bash
   php artisan serve
   ```

2. **Se connecter à l'admin** :
   - URL : `http://localhost:8000/admin`
   - Login : `manolinis@gmail.com`
   - Password : `admin123` (ou le mot de passe configuré)

3. **Chercher le menu "Post Promotions"** :
   - Dans le menu latéral admin
   - Ou via l'URL directe : `http://localhost:8000/admin/post_promotions`

## 🔍 Vérification de l'Installation

### Vérifier que les Routes Fonctionnent
```bash
# Lister toutes les routes admin
php artisan route:list | grep post_promotions
```

### Tester le Service
```bash
php artisan tinker
```

```php
use App\Services\PostPromotionService;
use App\Models\Post;
use App\Models\Package;

// Vérifier que le service fonctionne
$service = app(PostPromotionService::class);
echo "Service PostPromotionService chargé !";

// Si vous avez des posts, tester la création d'une promotion
$post = Post::first();
$package = Package::where('type', 'promotion')->first();

if ($post && $package) {
    $promotion = $service->createPromotion($post->id, $package->id, 'featured');
    echo "Promotion test créée : ID " . $promotion->id;
}
```

### Vérifier les Fichiers
```bash
# Vérifier que tous les fichiers sont bien en place
ls -la app/Models/PostPromotion.php
ls -la app/Services/PostPromotionService.php
ls -la app/Http/Controllers/Web/Admin/PostPromotionController.php
ls -la database/migrations/22_00_create_post_promotions_table.php
```

## 🛠️ Configuration Avancée (Optionnel)

### Ajouter la Commande Cron
Dans `app/Console/Kernel.php`, méthode `schedule()` :

```php
protected function schedule(Schedule $schedule)
{
    // Expirer les promotions automatiquement toutes les heures
    $schedule->command('promotions:expire')
        ->hourly()
        ->withoutOverlapping();
}
```

### Tester la Commande
```bash
php artisan promotions:expire --dry-run
```

## 📊 Interface Admin Disponible

Une fois l'installation terminée, vous aurez accès à :

- **Liste des promotions** avec filtres avancés
- **Création/édition** de promotions
- **Actions en masse** (activation, expiration)
- **Statistiques** et analytics
- **Gestion des états** des promotions

## 🐛 Dépannage

### Problème de Base de Données
```bash
# Vérifier la connexion DB
php artisan tinker
>>> DB::connection()->getPdo();
```

### Problème de Cache
```bash
# Forcer le vidage complet
php artisan optimize:clear
```

### Problème de Permissions
```bash
# Vérifier les permissions fichiers
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

---

**Le module sera visible dans l'admin après l'exécution de ces étapes !** 🎉