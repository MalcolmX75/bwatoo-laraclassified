# 📋 CAHIER DES CHARGES - Bwatoo LaraClassified Evolution

> **Spécifications détaillées du projet de plateforme de petites annonces africaine**
> 
> 📅 Version 1.0.0 - 6 juillet 2025  
> 🔗 Liens : [[README]] | [[META]] | [[TODO]]

---

## 1️⃣ Contexte et Objectifs

### 📊 Analyse du Marché

Le marché africain des petites annonces en ligne présente un potentiel de croissance exponentiel avec :
- **54 pays** aux économies dynamiques et diversifiées
- **1,2 milliard** de personnes dont 60% ont moins de 25 ans
- **Pénétration mobile** > 80% dans les zones urbaines
- **Commerce informel** représentant 50-80% de l'économie selon les pays

### 🎯 Objectifs Stratégiques

1. **Créer LA plateforme de référence** pour les petites annonces en Afrique
2. **Démocratiser l'accès** au commerce digital pour tous les acteurs économiques
3. **Monétiser intelligemment** via un système de promotions premium accessible
4. **Adapter technologiquement** aux réalités africaines (connectivité, devices, paiements)
5. **Construire une communauté** de confiance avec système de réputation robuste

### 📈 Indicateurs de Succès

| KPI | Objectif 6 mois | Objectif 1 an | Objectif 2 ans |
|-----|-----------------|---------------|-----------------|
| Utilisateurs actifs | 50,000 | 250,000 | 1,000,000 |
| Annonces publiées/mois | 10,000 | 50,000 | 200,000 |
| Taux promotions | 10% | 20% | 30% |
| Revenus mensuels | 5,000€ | 25,000€ | 100,000€ |
| Pays actifs | 5 | 15 | 30+ |

---

## 2️⃣ Périmètre Fonctionnel

### 🌟 Module 1 : Plateforme Core LaraClassified

#### Fonctionnalités Principales
- **Gestion des annonces** : CRUD complet avec modération
- **Système utilisateurs** : Inscription, profils, historique
- **Recherche avancée** : Filtres, géolocalisation, catégories
- **Messagerie intégrée** : Communication sécurisée entre utilisateurs
- **Multi-devises** : Support natif monnaies africaines
- **Multilingue** : FR/EN de base, extensible facilement

#### Adaptations Africaines
- **Géolocalisation précise** : Villes, quartiers, zones rurales
- **Catégories locales** : Agriculture, artisanat, services informels
- **Interface simplifiée** : Optimisée connexions lentes et petits écrans
- **Mode offline** : Cache intelligent pour consultation hors ligne

### 💎 Module 2 : Premium Ads System

#### Types de Promotions

##### 🔝 **Bump Up** (Remontée)
- **Fonction** : Replace l'annonce en tête des résultats
- **Durée** : 1-7 jours maximum
- **Prix** : 2€ (adaptable par pays)
- **Usage** : Renouveler la visibilité d'une annonce existante

##### ⭐ **Featured** (Mise en Avant)
- **Fonction** : Badge spécial + position privilégiée
- **Durée** : 7-30 jours
- **Prix** : 10€ (adaptable)
- **Usage** : Annonces importantes nécessitant visibilité continue

##### 🏆 **Top Ad** (Annonce Premium)
- **Fonction** : Zone dédiée homepage + résultats
- **Durée** : 14-30 jours
- **Prix** : 25€ (adaptable)
- **Usage** : Professionnels et annonces commerciales

##### 🚨 **Urgent** (Urgence)
- **Fonction** : Badge rouge "Urgent" + priorité affichage
- **Durée** : 3-14 jours
- **Prix** : 5€ (adaptable)
- **Usage** : Ventes rapides, offres limitées

##### 🎨 **Highlight** (Surbrillance)
- **Fonction** : Fond coloré distinctif dans les listes
- **Durée** : 7-30 jours
- **Prix** : 8€ (adaptable)
- **Usage** : Se démarquer visuellement

#### Mécaniques de Promotion
- **Activation instantanée** après paiement validé
- **Compteurs temps réel** : vues, clics, contacts
- **Notifications expiration** : 3 jours avant échéance
- **Renouvellement facilité** : 1-clic depuis dashboard
- **Cumul possible** : Plusieurs promotions sur même annonce

### 💳 Module 3 : Système de Paiement

#### Moyens de Paiement Supportés

##### Phase 1 (Actuelle)
- **Mode gratuit** : Tests et démonstrations
- **Cartes bancaires** : Via Stripe/PayPal (à activer)
- **Virement bancaire** : Validation manuelle admin

##### Phase 2 (Planifiée)
- **Mobile Money** : Orange Money, MTN Money, Airtel Money
- **Portefeuilles digitaux** : PaySika, Flutterwave, Paystack
- **Cryptomonnaies** : Bitcoin, USDT pour diaspora

#### Workflow de Paiement
1. Sélection promotion depuis l'annonce
2. Choix durée et visualisation prix
3. Sélection moyen de paiement
4. Redirection gateway sécurisé
5. Validation et activation automatique
6. Email confirmation avec facture

### 👥 Module 4 : Administration

#### Dashboard Principal
- **Vue d'ensemble** : Métriques temps réel
- **Graphiques** : Évolution revenus, utilisateurs, annonces
- **Alertes** : Anomalies, modération requise
- **Actions rapides** : Validation, suspension, contact

#### Gestion Premium Ads
- **Liste complète** : Toutes promotions avec filtres
- **Actions masse** : Activer, expirer, rembourser
- **Statistiques** : Par type, période, géographie
- **Export données** : CSV, Excel pour comptabilité

#### Modération
- **Queue modération** : Annonces signalées ou automatiques
- **Outils IA** : Détection contenu inapproprié (futur)
- **Templates réponses** : Messages types utilisateurs
- **Historique actions** : Audit trail complet

### 🌍 Module 5 : Géolocalisation Africaine

#### Structure Géographique
```
Continent → Région → Pays → État/Province → Ville → Quartier
```

#### Données Incluses
- **54 pays** avec codes ISO et indicatifs
- **Capitales** et villes principales (>50k habitants)
- **Monnaies locales** avec taux change
- **Langues officielles** et régionales
- **Fuseaux horaires** pour notifications

#### Fonctionnalités Géo
- **Recherche proximité** : Rayon personnalisable
- **Suggestions intelligentes** : Basées sur IP/GPS
- **Multi-localisation** : Annonces visibles plusieurs zones
- **Cartes interactives** : OpenStreetMap intégré

---

## 3️⃣ Spécifications Techniques

### 🏗️ Architecture Logicielle

#### Backend Architecture
```
┌─────────────────┐     ┌─────────────────┐     ┌─────────────────┐
│   Load Balancer │────▶│   Web Servers   │────▶│    Database     │
│    (Nginx/HAP)  │     │   (PHP-FPM)     │     │  (MySQL/Redis)  │
└─────────────────┘     └─────────────────┘     └─────────────────┘
         │                       │                        │
         ▼                       ▼                        ▼
┌─────────────────┐     ┌─────────────────┐     ┌─────────────────┐
│      CDN        │     │  Queue Workers  │     │  File Storage   │
│  (Cloudflare)   │     │    (Laravel)    │     │    (S3/Local)   │
└─────────────────┘     └─────────────────┘     └─────────────────┘
```

#### Stack Technique Détaillé

##### Backend
- **Framework** : Laravel 12.x (LTS jusqu'en 2027)
- **PHP** : 8.3+ avec OPcache activé
- **API** : RESTful + GraphQL (futur)
- **Authentication** : Laravel Sanctum + 2FA
- **Validation** : Form Requests + Rules custom

##### Frontend
- **Template Engine** : Blade avec composants
- **CSS Framework** : Bootstrap 5.3 + custom theme
- **JavaScript** : Alpine.js pour interactivité
- **Build Tools** : Laravel Mix (Vite migration prévue)
- **PWA** : Service Worker pour offline

##### Base de Données
- **SGBD Principal** : MySQL 8.0 avec partitioning
- **Cache** : Redis pour sessions et cache
- **Search** : MySQL Fulltext + Elasticsearch (futur)
- **Backup** : Réplication Master-Slave + snapshots

##### Infrastructure
- **Hébergement** : Cloud scalable (AWS/GCP/Azure)
- **Containers** : Docker pour environnements
- **CI/CD** : GitHub Actions + auto-deploy
- **Monitoring** : New Relic / Datadog

### 🔒 Sécurité

#### Mesures Implémentées
- **HTTPS obligatoire** avec HSTS
- **Protection CSRF** sur tous formulaires
- **Rate limiting** : 60 req/min anonymous, 120 authenticated
- **SQL injection** : Requêtes préparées systématiques
- **XSS protection** : Échappement automatique Blade
- **File upload** : Validation MIME + antivirus
- **Password policy** : Minimum 8 chars, complexité
- **2FA disponible** : TOTP pour comptes sensibles

#### Conformité
- **RGPD** : Consent management, data export
- **PCI DSS** : Via providers certifiés (Stripe)
- **Législations locales** : Adaptable par pays

### ⚡ Performance

#### Objectifs
- **Time to First Byte** : < 200ms
- **Page Load Time** : < 2s (3G connection)
- **API Response** : < 100ms (cached)
- **Concurrent Users** : 10,000+
- **Database Queries** : < 50/page

#### Optimisations
- **Query optimization** : Eager loading, indexes
- **Cache stratégique** : Pages, queries, objects
- **Image optimization** : WebP, lazy loading, CDN
- **Code splitting** : Chargement JS asynchrone
- **Database sharding** : Par région géographique

### 📱 Compatibilité

#### Navigateurs
- **Desktop** : Chrome 90+, Firefox 88+, Safari 14+, Edge 90+
- **Mobile** : Chrome Android, Safari iOS, Samsung Internet
- **Legacy** : Graceful degradation IE11 (lecture seule)

#### Devices
- **Smartphones** : 320px minimum width
- **Tablets** : Optimisé portrait/paysage
- **Desktop** : Jusqu'à 4K displays
- **Feature phones** : Version lite HTML (futur)

#### Connectivité
- **2G/EDGE** : Version allégée automatique
- **3G** : Expérience complète optimisée
- **4G/5G** : Toutes fonctionnalités HD
- **Offline** : Mode lecture cache intelligent

---

## 4️⃣ Interfaces Utilisateur

### 🎨 Charte Graphique

#### Couleurs
- **Primaire** : #2563EB (Bleu confiance)
- **Secondaire** : #10B981 (Vert succès)
- **Accent** : #F59E0B (Orange énergie)
- **Danger** : #EF4444 (Rouge alerte)
- **Neutre** : #6B7280 (Gris élégance)

#### Typographie
- **Titres** : Inter (sans-serif) - Bold
- **Corps** : Inter - Regular/Medium
- **Monospace** : Fira Code (code/prix)
- **Tailles** : 14px base, 16px mobile

#### Composants UI
- **Cards** : Ombres douces, coins arrondis 8px
- **Boutons** : 3 variantes (primary, outline, ghost)
- **Forms** : Labels flottants, validation inline
- **Modals** : Overlay sombre, animations fluides
- **Toasts** : Notifications non-intrusives

### 📱 Responsive Design

#### Breakpoints
- **Mobile** : 320-767px (mobile-first)
- **Tablet** : 768-1023px (2 colonnes)
- **Desktop** : 1024-1279px (3 colonnes)
- **Wide** : 1280px+ (4 colonnes max)

#### Adaptations Mobile
- **Navigation** : Hamburger menu + bottom tabs
- **Formulaires** : Full-width, grands boutons
- **Images** : Swipe galleries, zoom tactile
- **Actions** : Swipe pour actions rapides
- **Offline** : Indicateur état connexion

### ♿ Accessibilité

#### Standards
- **WCAG 2.1** : Niveau AA minimum
- **ARIA labels** : Navigation screen readers
- **Contraste** : Ratio 4.5:1 minimum
- **Keyboard nav** : Toutes actions clavier
- **Focus visible** : Indicateurs clairs

#### Fonctionnalités
- **Text sizing** : Zoom jusqu'à 200%
- **Alt texts** : Toutes images décrites
- **Formulaires** : Labels explicites
- **Erreurs** : Messages contextuels
- **Loading** : États explicites

---

## 5️⃣ Plan de Déploiement

### 📅 Phases de Lancement

#### Phase 1 : Soft Launch (Juillet 2025)
- **Pays pilotes** : Côte d'Ivoire, Sénégal, Cameroun
- **Utilisateurs** : 1,000 early adopters invités
- **Features** : Core + Premium Ads gratuits
- **Objectif** : Valider UX et corriger bugs

#### Phase 2 : Launch Officiel (Août 2025)
- **Pays** : +Ghana, Nigeria, Kenya
- **Marketing** : Campagnes digitales ciblées
- **Features** : Paiements activés
- **Objectif** : 10,000 utilisateurs actifs

#### Phase 3 : Expansion (Septembre-Décembre 2025)
- **Pays** : Progressive vers 15 pays
- **Partenariats** : Médias locaux, influenceurs
- **Features** : Mobile Money, API publique
- **Objectif** : 100,000 utilisateurs

### 🚀 Stratégie Go-to-Market

#### Marketing Digital
- **SEO local** : Contenu par pays/ville
- **Social Media** : Facebook, WhatsApp, TikTok
- **Influenceurs** : Micro-influenceurs locaux
- **Content Marketing** : Guides, success stories
- **Referral Program** : Parrainage récompensé

#### Partenariats
- **Opérateurs télécom** : Bundles data dédiés
- **Médias locaux** : Échanges visibilité
- **Associations** : Commerçants, artisans
- **Universités** : Programme campus ambassadeurs
- **ONG** : Empowerment économique

#### Support Client
- **Chat intégré** : Réponse <5min heures bureau
- **WhatsApp Business** : Canal préféré Afrique
- **FAQ dynamique** : Auto-suggestion contexte
- **Tutoriels vidéo** : Langues locales
- **Community management** : Modération proactive

---

## 6️⃣ Budget et Ressources

### 💰 Budget Prévisionnel

#### Développement (Fait)
- **Développement initial** : 15,000€ ✅
- **Plugin Premium Ads** : 5,000€ ✅
- **Tests et débugage** : 2,000€ ✅
- **Documentation** : 1,000€ ✅

#### Infrastructure (Année 1)
- **Hébergement cloud** : 500€/mois → 6,000€
- **CDN + Sécurité** : 200€/mois → 2,400€
- **Backup + Monitoring** : 100€/mois → 1,200€
- **Noms domaine + SSL** : 500€

#### Marketing (Année 1)
- **Campagnes digitales** : 15,000€
- **Influenceurs** : 10,000€
- **Content creation** : 5,000€
- **Events/Meetups** : 5,000€

#### Opérations (Année 1)
- **Support client** : 2 temps plein → 24,000€
- **Community manager** : 1 temps plein → 12,000€
- **Traductions** : 5,000€
- **Légal/Comptabilité** : 3,000€

**TOTAL ANNÉE 1** : ~110,000€

### 👥 Équipe Requise

#### Technique (Actuelle)
- **Lead Developer** : Architecture et développement ✅
- **Frontend Developer** : UI/UX et intégrations ✅
- **DevOps** : Infrastructure et déploiement ✅

#### Business (À recruter)
- **Country Managers** : 1 par pays prioritaire
- **Marketing Manager** : Stratégie acquisition
- **Customer Success** : Support et formation
- **Business Developer** : Partenariats locaux

#### Croissance (6+ mois)
- **Data Analyst** : Insights et optimisations
- **Product Manager** : Roadmap et features
- **QA Engineer** : Tests automatisés
- **Content Creators** : Contenu localisé

---

## 7️⃣ Maintenance et Évolution

### 🔧 Maintenance

#### Préventive
- **Updates sécurité** : Hebdomadaire
- **Backup tests** : Bi-mensuel
- **Performance audit** : Mensuel
- **Code review** : Continu
- **Monitoring 24/7** : Alertes automatiques

#### Corrective
- **Bug fixes** : SLA 24h critiques, 72h mineurs
- **Hotfixes** : Processus d'urgence défini
- **Rollback** : Stratégie de retour arrière
- **Communication** : Status page publique

### 🚀 Roadmap Évolutions

#### Court Terme (3-6 mois)
- **Mobile apps** : iOS/Android natifs
- **API publique** : Intégrations tierces
- **Verification système** : KYC lite
- **Reviews/Ratings** : Système notation
- **Saved searches** : Alertes personnalisées

#### Moyen Terme (6-12 mois)
- **AI recommendations** : Suggestions intelligentes
- **Video ads** : Support vidéos courtes
- **Auction system** : Enchères sur articles
- **Pro accounts** : Features entreprises
- **Analytics dashboard** : Insights vendeurs

#### Long Terme (12+ mois)
- **Blockchain** : Transactions décentralisées
- **AR/VR** : Visualisation produits
- **Voice search** : Recherche vocale
- **IoT integration** : Objets connectés
- **Marketplace B2B** : Section professionnelle

---

## 8️⃣ Risques et Mitigation

### ⚠️ Risques Identifiés

#### Techniques
| Risque | Probabilité | Impact | Mitigation |
|--------|-------------|---------|------------|
| Surcharge serveurs | Moyenne | Élevé | Auto-scaling cloud |
| Faille sécurité | Faible | Critique | Audits réguliers |
| Perte données | Faible | Critique | Backups redondants |
| API tierces down | Moyenne | Moyen | Fallbacks locaux |

#### Business
| Risque | Probabilité | Impact | Mitigation |
|--------|-------------|---------|------------|
| Adoption lente | Moyenne | Élevé | Marketing agressif |
| Concurrence | Élevée | Moyen | Innovation continue |
| Réglementation | Moyenne | Élevé | Veille juridique |
| Fraude utilisateurs | Élevée | Moyen | Modération AI |

#### Marché
| Risque | Probabilité | Impact | Mitigation |
|--------|-------------|---------|------------|
| Instabilité pays | Moyenne | Variable | Diversification géo |
| Changes devises | Élevée | Moyen | Multi-devises natif |
| Connectivité | Élevée | Faible | Mode offline |
| Pouvoir d'achat | Moyenne | Moyen | Pricing adaptatif |

### 🛡️ Plan de Continuité

#### Disaster Recovery
- **RPO** : 1 heure maximum perte données
- **RTO** : 4 heures maximum interruption
- **Backups** : 3-2-1 strategy (3 copies, 2 mediums, 1 offsite)
- **Failover** : Basculement automatique multi-zones
- **Tests** : Simulation incidents trimestrielle

#### Crisis Management
- **Équipe crise** : Rôles et responsabilités définis
- **Communication** : Templates et canaux prêts
- **Escalation** : Processus de décision rapide
- **Post-mortem** : Analyse systématique incidents
- **Amélioration** : Intégration retours expérience

---

## 9️⃣ Critères de Succès

### 📊 Métriques de Performance

#### Techniques
- **Uptime** : > 99.9% mensuel
- **Response time** : < 200ms P95
- **Error rate** : < 0.1%
- **Deployment frequency** : 2+ par semaine
- **MTTR** : < 1 heure

#### Business
- **MAU** : Monthly Active Users croissance 20%
- **Conversion** : 30% visiteurs → inscrits
- **Retention** : 70% utilisateurs actifs à J30
- **ARPU** : Average Revenue Per User 2€+
- **NPS** : Net Promoter Score > 50

#### Marché
- **Market share** : Top 3 par pays cible
- **Brand awareness** : 30% population urbaine
- **Geographic coverage** : 15+ pays actifs
- **User satisfaction** : 4.5+ étoiles stores
- **Media coverage** : 100+ mentions/mois

### 🏆 Facteurs Clés de Succès

1. **Adaptation locale** : UI/UX et features par marché
2. **Performance technique** : Rapidité sur réseaux lents
3. **Trust & Safety** : Plateforme sécurisée et fiable
4. **Pricing accessible** : Monétisation non-exclusive
5. **Community building** : Engagement utilisateurs fort
6. **Partenariats locaux** : Ancrage terrain solide
7. **Innovation continue** : Avance sur concurrence
8. **Excellence opérationnelle** : Support réactif
9. **Data-driven** : Décisions basées métriques
10. **Vision long terme** : Construction durable

---

## 🔚 Conclusion

Bwatoo LaraClassified Evolution représente bien plus qu'une simple plateforme de petites annonces. C'est un outil de transformation économique pour l'Afrique, démocratisant l'accès au commerce digital pour des millions de personnes.

Avec son système Premium Ads innovant et accessible, son adaptation fine aux réalités africaines et sa vision d'expansion continentale, le projet est positionné pour devenir **LE** leader incontesté des classifieds en Afrique.

Le succès reposera sur l'exécution rigoureuse de ce cahier des charges, l'écoute constante des utilisateurs et l'adaptation agile aux opportunités et défis du marché africain.

---

**"Connecter l'Afrique, une annonce à la fois"** 🌍

*Document évolutif - Version 1.0.0 - Juillet 2025*