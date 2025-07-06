# 🚀 META - Bwatoo LaraClassified Evolution

> **Métadonnées et informations structurelles du projet**
> 
> 📅 Dernière mise à jour : 6 juillet 2025  
> 🔗 Liens : [[README]] | [[TODO]] | [[CAHIER-DES-CHARGES]]

---

## 📋 Informations Générales

### 🏷️ Identité du Projet
- **Nom** : Bwatoo LaraClassified Evolution
- **Version** : 1.0.0
- **Type** : Plateforme de petites annonces avec système de promotion premium
- **Localisation** : Afrique (54 pays)
- **Domaine** : https://bwatoo.fr
- **Développement** : https://dev.bwatoo.fr
- **Repository** : https://github.com/MalcolmX75/bwatoo-laraclassified
- **Status** : Production Ready + Premium Ads Module Operational

### 🎯 Objectifs du Projet
1. **Plateforme d'annonces moderne** pour le marché africain
2. **Système de promotion premium** avec 5 types différents
3. **Interface multilingue** EN/FR avec extensions prévues
4. **Monétisation intelligente** via packages de promotion
5. **Géolocalisation précise** pour 54 pays africains
6. **Analytics avancées** pour optimisation performances
7. **Architecture scalable** pour croissance rapide

---

## 🏗️ Architecture Technique

### 📁 Structure des Dossiers v1.0.0
```
bwatoo-laraclassified/
├── 📄 docs/                        # Documentation projet (ORGANISÉ v1.0.0)
│   ├── META.md                     # Métadonnées (ce fichier)
│   ├── README.md                   # Guide utilisateur principal
│   ├── TODO.md                     # Roadmap et tâches
│   └── CAHIER-DES-CHARGES.md       # Spécifications complètes
├── 🏗️ site/                        # Application LaraClassified
│   ├── app/                        # Code application Laravel
│   │   ├── Models/PostPromotion.php    # Modèle promotions
│   │   ├── Services/PostPromotion*.php # Services métier
│   │   ├── Http/Controllers/Web/Admin/PostPromotionController.php
│   │   └── Observers/PostPromotionObserver.php
│   ├── database/migrations/        # Migrations base de données
│   │   └── 22_00_create_post_promotions_table.php
│   ├── extras/plugins/premiumads/  # Plugin Premium Ads
│   │   ├── Premiumads.php          # Classe principale plugin
│   │   ├── PremiumadsServiceProvider.php
│   │   ├── init.json               # Métadonnées plugin
│   │   ├── config.php              # Configuration plugin
│   │   ├── lang/en/messages.php    # Traductions
│   │   └── resources/views/        # Vues plugin
│   ├── resources/views/front/      # Templates frontend
│   │   └── search/partials/posts/widget/carousel.blade.php (MODIFIÉ)
│   └── routes/web/admin.php        # Routes administration
└── 📊 documentation/               # Documentation originale LaraClassified
```

### 🔧 Stack Technique

#### **Backend**
- **Framework** : Laravel 12.x (Latest LTS)
- **PHP** : 8.3.22 minimum
- **Base de données** : MySQL 8.0+ / MariaDB 10.6+
- **Cache** : Redis (recommandé) / File
- **Queue** : Database / Redis
- **Storage** : Local / S3 compatible

#### **Frontend**
- **CSS Framework** : Bootstrap 5.3
- **JavaScript** : Laravel Mix + Alpine.js
- **Icons** : Font Awesome 6
- **Maps** : OpenStreetMap / Google Maps
- **Upload** : Dropzone.js

#### **Infrastructure**
- **Serveur Web** : Apache 2.4+ / Nginx 1.18+
- **SSL** : Let's Encrypt / Certificat commercial
- **CDN** : Cloudflare (optionnel)
- **Monitoring** : Laravel Telescope + Log Viewer

---

## 🗄️ Base de Données

### 📊 Tables Principales

#### **Table `lc_post_promotions`**
```sql
CREATE TABLE `lc_post_promotions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `post_id` bigint unsigned NOT NULL,
  `package_id` int unsigned NOT NULL,
  `payment_id` bigint unsigned DEFAULT NULL,
  `promotion_type` enum('bump','top','featured','urgent','highlight') NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `currency_code` varchar(3) NOT NULL,
  `status` enum('active','expired','cancelled','pending') DEFAULT 'pending',
  `views_count` int unsigned DEFAULT '0',
  `clicks_count` int unsigned DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_post_promotions_post_id` (`post_id`),
  KEY `idx_post_promotions_status` (`status`),
  KEY `idx_post_promotions_type` (`promotion_type`),
  KEY `idx_post_promotions_dates` (`start_date`,`end_date`)
);
```

#### **Relations Existantes Étendues**
- `lc_posts` → `lc_post_promotions` (1:N)
- `lc_packages` → `lc_post_promotions` (1:N)
- `lc_payments` → `lc_post_promotions` (1:N)

### 🔑 Index et Optimisations
- **Index composés** pour requêtes promotions actives
- **Cache Redis** pour compteurs vues/clics
- **Soft deletes** pour audit trail
- **Timestamps** automatiques Laravel

---

## 🔌 Plugin Premium Ads

### 📋 Structure Plugin LaraClassified
```
extras/plugins/premiumads/
├── Premiumads.php                  # Classe principale plugin
├── PremiumadsServiceProvider.php   # Service Provider Laravel
├── init.json                       # Métadonnées plugin LaraClassified
├── config.php                      # Configuration par défaut
├── app/Traits/InstallTrait.php     # Installation/désinstallation
├── lang/en/messages.php            # Traductions anglaises
└── resources/views/stats.blade.php # Vue statistiques admin
```

### ⚙️ Configuration Plugin
```php
'premiumads' => [
    'promotion_types' => [
        'bump' => ['enabled' => true, 'max_duration' => 7, 'default_price' => 2.00],
        'featured' => ['enabled' => true, 'max_duration' => 30, 'default_price' => 10.00],
        'top' => ['enabled' => true, 'max_duration' => 30, 'default_price' => 25.00],
        'urgent' => ['enabled' => true, 'max_duration' => 14, 'default_price' => 5.00],
        'highlight' => ['enabled' => true, 'max_duration' => 30, 'default_price' => 8.00],
    ],
    'currencies' => ['USD', 'EUR', 'XOF', 'XAF'],
    'notifications' => ['admin_new_promotion' => true, 'user_promotion_activated' => true],
]
```

### 🎯 Types de Promotion Détaillés

| Type | Description | Badge | Couleur | Durée Max | Prix Défaut |
|------|-------------|-------|---------|-----------|-------------|
| **Bump Up** | Remonte en haut des résultats | `Bump` | Primary | 7 jours | 2€ |
| **Featured** | Mise en avant visuelle | `Featured` | Warning | 30 jours | 10€ |
| **Top Ad** | Zone premium homepage | `Top` | Success | 30 jours | 25€ |
| **Urgent** | Badge urgent rouge | `Urgent` | Danger | 14 jours | 5€ |
| **Highlight** | Surlignage annonce | `Highlight` | Info | 30 jours | 8€ |

---

## 🌍 Géolocalisation Africaine

### 🗺️ Pays Supportés (54 pays)
- **Afrique du Nord** : Algérie, Égypte, Libye, Maroc, Soudan, Tunisie
- **Afrique de l'Ouest** : Bénin, Burkina Faso, Côte d'Ivoire, Ghana, Mali, Niger, Nigeria, Sénégal, Togo, etc.
- **Afrique Centrale** : Cameroun, RCA, Tchad, Congo, RDC, Gabon, Guinée Équatoriale
- **Afrique de l'Est** : Éthiopie, Kenya, Ouganda, Rwanda, Tanzanie, etc.
- **Afrique Australe** : Afrique du Sud, Botswana, Namibie, Zambie, Zimbabwe, etc.

### 💰 Monnaies Locales
- **CFA Franc Ouest** (XOF) : UEMOA
- **CFA Franc Central** (XAF) : CEMAC  
- **USD** : Monnaie de référence
- **EUR** : Marché européen
- **Monnaies nationales** : En planification

---

## 🔐 Sécurité et Performance

### 🛡️ Mesures de Sécurité
- **Validation complète** des données promotions
- **Protection CSRF** sur toutes actions admin
- **Rate limiting** API et actions utilisateur
- **Audit trail** complet des modifications
- **Permissions granulaires** par rôle utilisateur

### ⚡ Optimisations Performance
- **Cache intelligent** requêtes promotions
- **Eager loading** relations posts/packages
- **Index database** optimisés pour géo-recherche
- **Queue jobs** pour tâches asynchrones (emails, stats)
- **Lazy loading** images et assets front

### 📊 Monitoring
- **Laravel Telescope** : Debug et profiling
- **Log rotation** automatique
- **Health checks** automatisés
- **Metrics** exportées pour Grafana/Prometheus

---

## 🚀 Déploiement

### 🏗️ Environnements

#### **Développement** (dev.bwatoo.fr)
- **Serveur** : IONOS Plesk
- **PHP** : 8.3 via Plesk
- **Database** : MySQL Plesk intégré
- **SSH** : Clé RSA configurée
- **SSL** : Let's Encrypt automatique

#### **Production** (bwatoo.fr) - À configurer
- **Architecture** : Load balancer + app servers
- **Database** : MySQL cluster / RDS
- **Cache** : Redis cluster
- **Storage** : S3 compatible
- **Monitoring** : Full stack observability

### 📝 Variables d'Environnement Critiques
```bash
# Database
DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=bw_dev
DB_USERNAME=bw_mano
DB_PASSWORD=***

# Premium Ads Plugin
PREMIUMADS_DEFAULT_DURATION=30
PREMIUMADS_AUTO_ACTIVATE=false
PREMIUMADS_ANALYTICS=true
PREMIUMADS_MAX_PER_POST=5

# Cache & Performance
CACHE_STORE=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
```

---

## 📈 Analytics et Métriques

### 📊 KPIs Premium Ads
- **Taux d'adoption** : % utilisateurs utilisant promotions
- **Revenue per User** : Revenus moyens par utilisateur
- **Conversion rate** : % annonces normales → promues
- **Retention** : Utilisateurs récurrents promotions
- **Geographic performance** : Performances par pays/région

### 🎯 Métriques Techniques
- **Response time** : API et pages < 200ms
- **Uptime** : 99.9% minimum
- **Database queries** : < 50 queries/page
- **Memory usage** : < 512MB par process
- **Error rate** : < 0.1% des requêtes

---

## 🔗 Intégrations Planifiées

### 💳 Systèmes de Paiement
- **Mobile Money** : Orange Money, MTN Money, etc.
- **Banques locales** : Ecobank, UBA, etc.
- **Cryptomonnaies** : Bitcoin, stablecoins
- **Cartes de crédit** : Visa, Mastercard via Stripe

### 📱 Applications Mobiles
- **Progressive Web App** : Installation native-like
- **API REST** : Prête pour apps mobiles
- **Push notifications** : Promotions expirantes
- **Offline support** : Cache intelligent

### 🤖 Intelligence Artificielle
- **Recommandations** : Promotions personnalisées
- **Fraud detection** : Détection annonces frauduleuses
- **Auto-pricing** : Prix optimaux par géolocalisation
- **Content moderation** : Validation automatique contenu

---

**Architecture conçue pour la scalabilité africaine - Bwatoo Development Team**