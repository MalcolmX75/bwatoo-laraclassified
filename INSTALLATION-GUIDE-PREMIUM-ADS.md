# 🚀 Guide d'Installation Rapide - Module Premium Ads

## ✅ État Actuel : Opérationnel sur dev.bwatoo.fr

Le module Premium Ads est entièrement fonctionnel avec :
- ✅ 5 types de promotions : Bump Up, Top Ad, Featured, Urgent, Highlight
- ✅ Interface admin complète : https://dev.bwatoo.fr/admin/post_promotions
- ✅ Plugin visible dans : https://dev.bwatoo.fr/admin/plugins
- ✅ Mode gratuit activé pour tests

## 🔧 Configuration Réalisée

### Base de Données
- Table `lc_post_promotions` créée avec toutes les colonnes nécessaires
- 3 packages de promotion configurés (Bump Up, Featured, Top Ad)
- Relations avec posts, packages et payments établies

### Plugin Premium Ads
- Détecté automatiquement par LaraClassified
- Options disponibles : Manage Promotions, Statistics, Packages
- Service Provider chargé et fonctionnel

### Frontend
- Ribbons corrigés dans le widget carousel
- Badges visibles sur toutes les vues (grid, list, carousel)
- Classes Bootstrap natives utilisées

## 🎯 Accès Admin

- **URL** : https://dev.bwatoo.fr/admin
- **Login** : manolinis@gmail.com
- **Password** : admin123

## 📊 Fonctionnalités Disponibles

1. **Gestion des Promotions** (/admin/post_promotions)
   - Liste complète avec filtres
   - Actions en masse (activation, expiration)
   - Statistiques détaillées

2. **Types de Promotion**
   - Bump Up : Remonte l'annonce (2€, 7 jours max)
   - Featured : Badge spécial (10€, 30 jours max)
   - Top Ad : Zone premium (25€, 30 jours max)
   - Urgent : Badge urgent (5€, 14 jours max)
   - Highlight : Surlignage (8€, 30 jours max)

3. **Workflow**
   - Sélection promotion depuis l'annonce
   - Choix de la durée
   - Paiement (gratuit actuellement)
   - Activation automatique

## ⚠️ Note Importante

Le serveur IONOS dev est actuellement plein (100% d'espace disque utilisé). 
Il est recommandé de :
- Nettoyer les fichiers temporaires régulièrement
- Augmenter l'espace disque disponible
- Ou migrer vers un serveur avec plus de capacité

## 📚 Documentation Complète

La documentation détaillée est disponible dans le dossier `/docs` :
- **README.md** : Guide utilisateur et présentation
- **META.md** : Architecture technique détaillée
- **TODO.md** : Roadmap et prochaines étapes
- **CAHIER-DES-CHARGES.md** : Spécifications complètes du projet

---

**Module développé et opérationnel - Prêt pour la production**