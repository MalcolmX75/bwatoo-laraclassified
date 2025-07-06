# 🚀 BWatoo - LaraClassified Enhanced

Site de petites annonces basé sur LaraClassified avec modules premium personnalisés.

## 📋 Informations Projet

- **Plateforme** : LaraClassified v18.0.0
- **Environnement de dev** : https://dev.bwatoo.fr
- **Base de données** : MySQL (bw_dev)
- **PHP** : 8.3.22

## 🎯 Fonctionnalités Principales

### Système Existant
- ✅ Annonces classifiées multilingues
- ✅ Système de packages (souscriptions/promotions)
- ✅ Paiements intégrés
- ✅ Interface admin complète
- ✅ Support multi-pays (Afrique activée)

### Modules Premium en Développement
- 🔄 **Premium Ads Module** - Options de mise en avant avancées
  - Bump Up (Remonter l'annonce)
  - Top Ad (Position garantie en tête)
  - Featured (Mise en avant homepage)
  - Urgent (Badge urgent)
  - Highlight (Surbrillance)

## 🛠️ Technologies

- **Backend** : Laravel 12.x, PHP 8.3
- **Frontend** : Blade Templates, jQuery, Bootstrap
- **Base de données** : MySQL/MariaDB
- **Serveur** : IONOS avec Plesk
- **Paiements** : Multi-gateway (Stripe, PayPal, etc.)

## 📁 Structure Projet

```
/
├── documentation/           # Documentation projet complète
├── MODULE-PREMIUM-ADS-SPEC.md  # Spécifications module premium
├── DEPLOYMENT.md           # Infos déploiement
└── site/                   # Code LaraClassified (à extraire)
```

## 🚀 Installation & Déploiement

Voir le fichier [DEPLOYMENT.md](DEPLOYMENT.md) pour les instructions détaillées.

### Accès Admin
- **URL** : https://dev.bwatoo.fr/auth/login
- **Email** : manolinis@gmail.com
- **Mot de passe** : admin123

### Base de Données
```bash
# Connexion MySQL
mysql -u bw_mano -p'AQW123ok,' bw_dev
```

## 📈 Feuille de Route

### Phase 0: PRIORITÉ IMMÉDIATE ⚡
- **Module Premium Ads** (6 semaines)
- Budget : 8-12k€
- ROI attendu : 2-3 mois

### Phase 1: Foundation (Mois 2-4)
- Modern UI Kit
- Smart Search Pro
- RealTime Chat Plus

### Phase 2: Growth (Mois 4-6)
- Payment Gateway Pro
- Trust & Safety Suite
- Analytics Dashboard Pro

## 📊 Objectifs Business

- **Revenus additionnels** : +20-30% du CA
- **Adoption promotions** : 15-25% des vendeurs
- **Prix moyen** : 5-50€ selon l'option
- **Récurrence** : 40% de renouvellement

## 🔧 Développement

### Configuration VSCode Remote
```bash
# Connexion SSH
ssh bwatoo-dev

# Répertoire de travail
/var/www/vhosts/bwatoo.fr/DEMOS/dev
```

### Commands Utiles
```bash
# Cache Laravel
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Migrations
php artisan migrate
php artisan db:seed
```

## 📝 Documentation

- [MODULE-PREMIUM-ADS-SPEC.md](MODULE-PREMIUM-ADS-SPEC.md) - Spécifications détaillées du module Premium Ads
- [DEPLOYMENT.md](DEPLOYMENT.md) - Informations de déploiement et serveur
- [documentation/](documentation/) - Documentation complète du projet

## 🤝 Équipe

- **Développement** : MalcolmX75
- **Admin** : manolinis@gmail.com

## 📄 Licence

Projet commercial privé - Tous droits réservés.

---

*Dernière mise à jour : Juillet 2025*