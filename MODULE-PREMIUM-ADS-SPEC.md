# 🚀 Module Premium Ads - Spécifications Techniques Détaillées

## 📋 Vue d'ensemble

Le module Premium Ads étend le système de packages existant de LaraClassified pour offrir des options de mise en avant avancées. Ce module se base sur l'architecture existante `lc_packages` et ajoute de nouvelles fonctionnalités de promotion d'annonces.

## 🔍 Analyse du Système Existant

### Différences Souscriptions vs Promotions

#### **Souscriptions (User-level)**
- **Cible** : Utilisateurs (table `lc_users`)
- **Durée** : Facturation récurrente (semaine/mois/année)
- **Fonctionnalités** :
  - `listings_limit` : Nombre d'annonces autorisées par période
  - `pictures_limit` : Photos par annonce
  - `expiration_time` : Durée de vie des annonces
  - Accès à des fonctionnalités utilisateur

#### **Promotions (Post-level)**
- **Cible** : Annonces individuelles (table `lc_posts`)
- **Durée** : Période fixe (`promotion_time` en jours)
- **Fonctionnalités** :
  - `ribbon` : Couleur de mise en évidence
  - `has_badge` : Badge sur l'annonce
  - `pictures_limit` : Limite photos améliorée
  - Intégration réseaux sociaux
  - Mise en avant page d'accueil et catégories

## 🎯 Objectifs du Module Premium Ads

### Nouvelles Fonctionnalités à Implémenter

1. **Bump Up (Remonter l'annonce)**
2. **Top Ad (Annonce en tête)**
3. **Featured (Mise en avant)**
4. **Urgent (Annonce urgente)**
5. **Highlight (Surbrillance)**

## 🏗️ Architecture Technique

### 1. Extensions Base de Données

#### Table : `lc_post_promotions`
```sql
CREATE TABLE lc_post_promotions (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    post_id BIGINT UNSIGNED NOT NULL,
    package_id INT UNSIGNED NOT NULL,
    payment_id BIGINT UNSIGNED NULL,
    
    -- Type de promotion
    promotion_type ENUM('bump', 'top', 'featured', 'urgent', 'highlight') NOT NULL,
    
    -- Dates
    start_date DATETIME NOT NULL,
    end_date DATETIME NOT NULL,
    
    -- Métadonnées
    price DECIMAL(10,2) NOT NULL,
    currency_code VARCHAR(3) NOT NULL,
    
    -- Statut
    status ENUM('active', 'expired', 'cancelled', 'pending') DEFAULT 'pending',
    
    -- Tracking
    views_count INT UNSIGNED DEFAULT 0,
    clicks_count INT UNSIGNED DEFAULT 0,
    
    -- Audit
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Index et contraintes
    FOREIGN KEY (post_id) REFERENCES lc_posts(id) ON DELETE CASCADE,
    FOREIGN KEY (package_id) REFERENCES lc_packages(id),
    FOREIGN KEY (payment_id) REFERENCES lc_payments(id),
    
    INDEX idx_post_promotion (post_id, promotion_type),
    INDEX idx_status_dates (status, start_date, end_date),
    INDEX idx_promotion_type (promotion_type),
    INDEX idx_active_promotions (status, end_date)
);
```

#### Extension Table : `lc_packages`
```sql
-- Ajouter de nouveaux champs pour les promotions avancées
ALTER TABLE lc_packages ADD COLUMN promotion_features JSON DEFAULT NULL;
ALTER TABLE lc_packages ADD COLUMN bump_limit INT DEFAULT 1;
ALTER TABLE lc_packages ADD COLUMN top_position_guaranteed BOOLEAN DEFAULT FALSE;
ALTER TABLE lc_packages ADD COLUMN urgent_badge_color VARCHAR(7) DEFAULT '#ff0000';
ALTER TABLE lc_packages ADD COLUMN highlight_color VARCHAR(7) DEFAULT '#ffff00';
ALTER TABLE lc_packages ADD COLUMN auto_renewal BOOLEAN DEFAULT FALSE;
```

#### Table : `lc_promotion_analytics`
```sql
CREATE TABLE lc_promotion_analytics (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    promotion_id BIGINT UNSIGNED NOT NULL,
    
    -- Métriques par jour
    date DATE NOT NULL,
    impressions INT UNSIGNED DEFAULT 0,
    clicks INT UNSIGNED DEFAULT 0,
    conversions INT UNSIGNED DEFAULT 0,
    
    -- Sources de trafic
    source_home INT UNSIGNED DEFAULT 0,
    source_category INT UNSIGNED DEFAULT 0,
    source_search INT UNSIGNED DEFAULT 0,
    source_direct INT UNSIGNED DEFAULT 0,
    
    FOREIGN KEY (promotion_id) REFERENCES lc_post_promotions(id) ON DELETE CASCADE,
    UNIQUE KEY unique_promotion_date (promotion_id, date),
    INDEX idx_date (date),
    INDEX idx_promotion_analytics (promotion_id, date)
);
```

### 2. Nouveaux Packages Premium

#### Configuration des Packages Premium avec Support Multilingue
```php
// Configuration via Seeder - Compatible système LaraClassified
$premiumPackages = [
    [
        'type' => 'promotion',
        'name' => json_encode([
            'en' => 'Bump Up',
            'fr' => 'Remonter',
            'es' => 'Subir',
            'ar' => 'رفع الإعلان',
            'pt' => 'Promover',
            'de' => 'Nach oben',
            'it' => 'Promuovi',
            'ru' => 'Поднять',
            'zh' => '置顶',
            'ja' => 'アップ'
        ]),
        'short_name' => json_encode([
            'en' => 'Bump',
            'fr' => 'Boost',
            'es' => 'Subir',
            'ar' => 'رفع',
            'pt' => 'Subir',
            'de' => 'Hoch',
            'it' => 'Su',
            'ru' => 'Вверх',
            'zh' => '顶',
            'ja' => 'アップ'
        ]),
        'price' => 2.00,
        'promotion_time' => 1,
        'promotion_features' => json_encode([
            'bump_frequency' => 'once_per_week',
            'notification_subscribers' => true,
            'recently_updated_badge' => true
        ]),
        'description' => json_encode([
            'en' => 'Push your ad back to the top of listings as if it was just posted',
            'fr' => 'Remontez votre annonce en tête des listes comme si elle venait d\'être publiée',
            'es' => 'Sube tu anuncio a la parte superior de las listas como si acabara de ser publicado',
            'ar' => 'ادفع إعلانك إلى أعلى القوائم كما لو تم نشره للتو',
            'pt' => 'Empurre seu anúncio de volta ao topo das listagens como se tivesse acabado de ser postado',
            'de' => 'Schieben Sie Ihre Anzeige an die Spitze der Listen, als wäre sie gerade veröffentlicht worden',
            'it' => 'Spingi il tuo annuncio in cima agli elenchi come se fosse stato appena pubblicato',
            'ru' => 'Поднимите свое объявление в топ списков, как будто оно только что было размещено',
            'zh' => '将您的广告推回列表顶部，就像刚刚发布一样',
            'ja' => '投稿したばかりのように、広告をリストのトップに押し上げます'
        ]),
        'ribbon' => '#28a745'
    ],
    [
        'type' => 'promotion',
        'name' => json_encode([
            'en' => 'Top Ad',
            'fr' => 'Annonce TOP',
            'es' => 'Anuncio TOP',
            'ar' => 'إعلان مميز',
            'pt' => 'Anúncio TOP',
            'de' => 'Top-Anzeige',
            'it' => 'Annuncio TOP',
            'ru' => 'Топ объявление',
            'zh' => '顶级广告',
            'ja' => 'トップ広告'
        ]),
        'short_name' => json_encode([
            'en' => 'TOP',
            'fr' => 'TOP',
            'es' => 'TOP',
            'ar' => 'الأول',
            'pt' => 'TOP',
            'de' => 'TOP',
            'it' => 'TOP',
            'ru' => 'ТОП',
            'zh' => '顶级',
            'ja' => 'トップ'
        ]),
        'price' => 15.00,
        'promotion_time' => 7,
        'top_position_guaranteed' => true,
        'promotion_features' => json_encode([
            'fixed_top_position' => true,
            'category_top_display' => true,
            'search_priority' => 'high'
        ]),
        'description' => json_encode([
            'en' => 'Guarantee your ad appears at the top of search results and category pages',
            'fr' => 'Garantissez que votre annonce apparaisse en haut des résultats de recherche et des pages de catégories',
            'es' => 'Garantiza que tu anuncio aparezca en la parte superior de los resultados de búsqueda y páginas de categorías',
            'ar' => 'اضمن ظهور إعلانك في أعلى نتائج البحث وصفحات الفئات',
            'pt' => 'Garanta que seu anúncio apareça no topo dos resultados de pesquisa e páginas de categoria',
            'de' => 'Garantieren Sie, dass Ihre Anzeige oben in den Suchergebnissen und Kategorieseiten erscheint',
            'it' => 'Garantisci che il tuo annuncio appaia in cima ai risultati di ricerca e alle pagine delle categorie',
            'ru' => 'Гарантируйте, что ваше объявление появится вверху результатов поиска и страниц категорий',
            'zh' => '保证您的广告出现在搜索结果和分类页面的顶部',
            'ja' => '検索結果とカテゴリページの上部に広告が表示されることを保証します'
        ]),
        'ribbon' => '#dc3545',
        'has_badge' => true
    ],
    [
        'type' => 'promotion',
        'name' => json_encode([
            'en' => 'Featured',
            'fr' => 'À la Une',
            'es' => 'Destacado',
            'ar' => 'مميز',
            'pt' => 'Em Destaque',
            'de' => 'Empfohlen',
            'it' => 'In Evidenza',
            'ru' => 'Рекомендуемое',
            'zh' => '精选',
            'ja' => '注目'
        ]),
        'short_name' => json_encode([
            'en' => 'Featured',
            'fr' => 'Une',
            'es' => 'Destacado',
            'ar' => 'مميز',
            'pt' => 'Destaque',
            'de' => 'Empfohlen',
            'it' => 'Evidenza',
            'ru' => 'Рекомендуемое',
            'zh' => '精选',
            'ja' => '注目'
        ]),
        'price' => 25.00,
        'promotion_time' => 14,
        'promotion_features' => json_encode([
            'homepage_display' => true,
            'newsletter_inclusion' => true,
            'social_auto_share' => true,
            'priority_search' => 'highest'
        ]),
        'description' => json_encode([
            'en' => 'Maximum exposure with homepage display, newsletter inclusion and social media sharing',
            'fr' => 'Exposition maximale avec affichage en page d\'accueil, inclusion newsletter et partage réseaux sociaux',
            'es' => 'Máxima exposición con visualización en página de inicio, inclusión en boletín y compartir en redes sociales',
            'ar' => 'أقصى تعرض مع عرض الصفحة الرئيسية وإدراج النشرة الإخبارية ومشاركة وسائل التواصل الاجتماعي',
            'pt' => 'Máxima exposição com exibição na página inicial, inclusão na newsletter e compartilhamento em redes sociais',
            'de' => 'Maximale Belichtung mit Homepage-Anzeige, Newsletter-Aufnahme und Social-Media-Sharing',
            'it' => 'Massima esposizione con visualizzazione homepage, inclusione newsletter e condivisione social media',
            'ru' => 'Максимальная экспозиция с отображением на главной странице, включением в рассылку и публикацией в социальных сетях',
            'zh' => '最大程度的曝光，包括主页显示、新闻通讯包含和社交媒体分享',
            'ja' => 'ホームページ表示、ニュースレター掲載、ソーシャルメディア共有による最大限の露出'
        ]),
        'ribbon' => '#ffc107',
        'has_badge' => true
    ],
    [
        'type' => 'promotion',
        'name' => json_encode([
            'en' => 'Urgent',
            'fr' => 'Urgent',
            'es' => 'Urgente',
            'ar' => 'عاجل',
            'pt' => 'Urgente',
            'de' => 'Dringend',
            'it' => 'Urgente',
            'ru' => 'Срочно',
            'zh' => '紧急',
            'ja' => '緊急'
        ]),
        'short_name' => json_encode([
            'en' => 'Urgent',
            'fr' => 'Urgent',
            'es' => 'Urgente',
            'ar' => 'عاجل',
            'pt' => 'Urgente',
            'de' => 'Dringend',
            'it' => 'Urgente',
            'ru' => 'Срочно',
            'zh' => '紧急',
            'ja' => '緊急'
        ]),
        'price' => 5.00,
        'promotion_time' => 3,
        'urgent_badge_color' => '#ff0000',
        'promotion_features' => json_encode([
            'urgent_badge' => true,
            'blink_effect' => true,
            'priority_notifications' => true
        ]),
        'description' => json_encode([
            'en' => 'Add an urgent badge to grab immediate attention from potential buyers',
            'fr' => 'Ajoutez un badge urgent pour attirer immédiatement l\'attention des acheteurs potentiels',
            'es' => 'Agrega una insignia urgente para llamar la atención inmediata de compradores potenciales',
            'ar' => 'أضف شارة عاجل لجذب الانتباه الفوري من المشترين المحتملين',
            'pt' => 'Adicione um distintivo urgente para chamar atenção imediata de compradores em potencial',
            'de' => 'Fügen Sie ein dringendes Abzeichen hinzu, um sofortige Aufmerksamkeit von potenziellen Käufern zu erhalten',
            'it' => 'Aggiungi un badge urgente per attirare l\'attenzione immediata di potenziali acquirenti',
            'ru' => 'Добавьте значок срочности, чтобы привлечь немедленное внимание потенциальных покупателей',
            'zh' => '添加紧急徽章以立即吸引潜在买家的注意',
            'ja' => '潜在的な購入者からの即座の注意を引くために緊急バッジを追加'
        ])
    ],
    [
        'type' => 'promotion',
        'name' => json_encode([
            'en' => 'Highlight',
            'fr' => 'Surligner',
            'es' => 'Destacar',
            'ar' => 'تمييز',
            'pt' => 'Destacar',
            'de' => 'Hervorheben',
            'it' => 'Evidenziare',
            'ru' => 'Выделить',
            'zh' => '突出显示',
            'ja' => 'ハイライト'
        ]),
        'short_name' => json_encode([
            'en' => 'Highlight',
            'fr' => 'Surligner',
            'es' => 'Destacar',
            'ar' => 'تمييز',
            'pt' => 'Destacar',
            'de' => 'Hervorheben',
            'it' => 'Evidenziare',
            'ru' => 'Выделить',
            'zh' => '高亮',
            'ja' => 'ハイライト'
        ]),
        'price' => 3.00,
        'promotion_time' => 5,
        'highlight_color' => '#ffff00',
        'promotion_features' => json_encode([
            'background_highlight' => true,
            'border_glow' => true
        ]),
        'description' => json_encode([
            'en' => 'Make your ad stand out with background highlighting and border glow effects',
            'fr' => 'Faites ressortir votre annonce avec un arrière-plan surligné et des effets de bordure brillante',
            'es' => 'Haz que tu anuncio destaque con resaltado de fondo y efectos de brillo en el borde',
            'ar' => 'اجعل إعلانك يبرز مع تمييز الخلفية وتأثيرات توهج الحدود',
            'pt' => 'Faça seu anúncio se destacar com destaque de fundo e efeitos de brilho na borda',
            'de' => 'Lassen Sie Ihre Anzeige mit Hintergrund-Hervorhebung und Randglühen-Effekten auffallen',
            'it' => 'Fai risaltare il tuo annuncio con evidenziazione dello sfondo e effetti di bagliore del bordo',
            'ru' => 'Выделите свое объявление с помощью подсветки фона и эффектов свечения границ',
            'zh' => '通过背景突出显示和边框发光效果让您的广告脱颖而出',
            'ja' => '背景のハイライトとボーダーグロー効果で広告を目立たせる'
        ])
    ]
];
```

### 3. Fichiers de Traduction

#### Fichier : `/lang/{locale}/premium_ads.php`
```php
<?php
// Traductions pour le module Premium Ads
return [
    'premium_ads' => 'Premium Ads',
    'promotion_options' => 'Options de promotion',
    'boost_your_ad' => 'Boostez votre annonce',
    'select_promotion' => 'Choisissez votre promotion',
    
    // Types de promotion
    'bump_up' => 'Remonter',
    'top_ad' => 'Annonce TOP',
    'featured' => 'À la Une',
    'urgent' => 'Urgent',
    'highlight' => 'Surligner',
    
    // Actions
    'activate_for' => 'Activer pour :price€',
    'purchase_promotion' => 'Acheter la promotion',
    'extend_promotion' => 'Prolonger la promotion',
    'cancel_promotion' => 'Annuler la promotion',
    
    // Statuts
    'promotion_active' => 'Promotion active',
    'promotion_expired' => 'Promotion expirée',
    'promotion_pending' => 'Promotion en attente',
    'expires_in' => 'Expire dans :days jour(s)',
    'expired_on' => 'Expiré le :date',
    
    // Caractéristiques
    'duration' => 'Durée',
    'benefits' => 'Avantages',
    'price' => 'Prix',
    'features_included' => 'Fonctionnalités incluses',
    
    // Messages
    'promotion_activated' => 'Votre promotion a été activée avec succès !',
    'promotion_error' => 'Erreur lors de l\'activation de la promotion',
    'insufficient_funds' => 'Fonds insuffisants pour cette promotion',
    'already_promoted' => 'Cette annonce a déjà une promotion active de ce type',
    
    // Analytics
    'views_count' => 'Nombre de vues',
    'clicks_count' => 'Nombre de clics',
    'conversion_rate' => 'Taux de conversion',
    'performance' => 'Performance',
];
```

#### Intégration aux fichiers existants
```php
// Ajouter à /lang/{locale}/admin.php
'premium_ads' => [
    'menu_title' => 'Premium Ads',
    'promotion_types' => 'Types de promotion',
    'active_promotions' => 'Promotions actives',
    'revenue_report' => 'Rapport des revenus',
    'manage_packages' => 'Gérer les packages',
],

// Ajouter à /lang/{locale}/global.php  
'promotion_features' => [
    'fixed_top_position' => 'Position fixe en tête',
    'homepage_display' => 'Affichage page d\'accueil', 
    'newsletter_inclusion' => 'Inclusion newsletter',
    'social_auto_share' => 'Partage automatique réseaux sociaux',
    'urgent_badge' => 'Badge urgent',
    'background_highlight' => 'Surbrillance arrière-plan',
    'border_glow' => 'Effet de bordure brillante',
    'bump_frequency' => 'Fréquence de remontée',
    'notification_subscribers' => 'Notification abonnés',
    'recently_updated_badge' => 'Badge récemment mis à jour',
],
```

### 4. Modèles Laravel

#### Model : `PostPromotion` avec Support Multilingue
```php
<?php

namespace App\Models\PremiumAds;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Post;
use App\Models\Package;
use App\Models\Payment;

class PostPromotion extends Model
{
    protected $table = 'lc_post_promotions';
    
    protected $fillable = [
        'post_id', 'package_id', 'payment_id', 'promotion_type',
        'start_date', 'end_date', 'price', 'currency_code',
        'status', 'views_count', 'clicks_count'
    ];
    
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'price' => 'decimal:2',
        'views_count' => 'integer',
        'clicks_count' => 'integer'
    ];
    
    // Relations
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'post_id');
    }
    
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class, 'package_id');
    }
    
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }
    
    // Méthodes de traduction
    public function getTypeLabel(): string
    {
        return t("premium_ads.{$this->promotion_type}");
    }
    
    public function getStatusLabel(): string
    {
        return t("premium_ads.promotion_{$this->status}");
    }
    
    public function getTimeRemainingLabel(): string
    {
        if ($this->status !== 'active') {
            return $this->getStatusLabel();
        }
        
        $days = $this->getDaysRemaining();
        
        if ($days > 0) {
            return t('premium_ads.expires_in', ['days' => $days]);
        }
        
        return t('premium_ads.expired_on', ['date' => $this->end_date->format('d/m/Y')]);
    }
    
    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                    ->where('start_date', '<=', now())
                    ->where('end_date', '>=', now());
    }
    
    public function scopeByType($query, string $type)
    {
        return $query->where('promotion_type', $type);
    }
    
    public function scopeExpired($query)
    {
        return $query->where('end_date', '<', now())
                    ->where('status', 'active');
    }
    
    // Méthodes
    public function isActive(): bool
    {
        return $this->status === 'active' 
            && $this->start_date <= now() 
            && $this->end_date >= now();
    }
    
    public function getDaysRemaining(): int
    {
        return max(0, $this->end_date->diffInDays(now()));
    }
    
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }
    
    public function incrementClicks(): void
    {
        $this->increment('clicks_count');
    }
}
```

#### Extension Model : `Package` avec Support Multilingue
```php
// Dans app/Models/Package.php - Ajouter ces méthodes pour gérer les traductions

/**
 * Obtenir la description avec caractéristiques traduites
 */
public function getDescriptionArrayAttribute(): string
{
    $locale = app()->getLocale();
    $baseDescription = data_get($this, "description.{$locale}") 
        ?? data_get($this, "description.en");
    
    $features = json_decode($this->promotion_features, true) ?? [];
    $translatedFeatures = [];
    
    foreach ($features as $feature => $value) {
        if ($value) {
            $translatedFeatures[] = t("promotion_features.{$feature}");
        }
    }
    
    if (!empty($translatedFeatures)) {
        $baseDescription .= '<br><br><strong>' . t('premium_ads.features_included') . ':</strong><br>';
        $baseDescription .= '• ' . implode('<br>• ', $translatedFeatures);
    }
    
    if ($this->promotion_time) {
        $baseDescription .= '<br><br><strong>' . t('premium_ads.duration') . ':</strong> ';
        $baseDescription .= trans_choice('premium_ads.days_duration', $this->promotion_time, [
            'count' => $this->promotion_time
        ]);
    }
    
    return $baseDescription;
}

/**
 * Obtenir le nom traduit du package
 */
public function getLocalizedName(): string
{
    $locale = app()->getLocale();
    return data_get($this, "name.{$locale}") ?? data_get($this, "name.en") ?? $this->short_name;
}

/**
 * Obtenir le nom court traduit
 */
public function getLocalizedShortName(): string
{
    $locale = app()->getLocale();
    return data_get($this, "short_name.{$locale}") ?? data_get($this, "short_name.en") ?? '';
}
```

### 5. Affichage Frontend avec Traductions

#### Vue : Interface de Sélection Multilingue
```blade
{{-- resources/views/front/pages/promotion/show.blade.php --}}
@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <h1>🚀 {{ t('premium_ads.boost_your_ad') }}</h1>
            <p class="lead">{{ t('premium_ads.select_promotion_for_ad', ['title' => $post->title]) }}</p>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-8">
            {{-- Options de promotion --}}
            <div class="promotion-packages">
                @foreach($packages as $package)
                <div class="card promotion-card mb-3" data-package="{{ $package->id }}">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            @if($package->short_name === 'featured')
                                ⭐ {{ $package->getLocalizedName() }}
                            @elseif($package->short_name === 'top')
                                🔝 {{ $package->getLocalizedName() }}
                            @elseif($package->short_name === 'bump')
                                🔄 {{ $package->getLocalizedName() }}
                            @elseif($package->short_name === 'urgent')
                                🚨 {{ $package->getLocalizedName() }}
                            @elseif($package->short_name === 'highlight')
                                ✨ {{ $package->getLocalizedName() }}
                            @endif
                        </h5>
                        <span class="price">{{ $package->price }}€</span>
                    </div>
                    
                    <div class="card-body">
                        <div class="description">
                            {!! $package->description_array !!}
                        </div>
                        
                        <div class="features mt-3">
                            <strong>{{ t('premium_ads.duration') }}:</strong> 
                            {{ trans_choice('premium_ads.days_duration', $package->promotion_time, ['count' => $package->promotion_time]) }}
                        </div>
                        
                        <button class="btn btn-primary btn-block purchase-btn mt-3" 
                                data-package="{{ $package->id }}"
                                data-price="{{ $package->price }}">
                            {{ t('premium_ads.activate_for', ['price' => $package->price]) }}
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        
        <div class="col-md-4">
            {{-- Aperçu de l'annonce --}}
            <div class="card">
                <div class="card-header">
                    <h5>{{ t('premium_ads.ad_preview') }}</h5>
                </div>
                <div class="card-body">
                    <div id="post-preview" class="post-preview">
                        @include('front.partials.post-card', ['post' => $post])
                    </div>
                </div>
            </div>
            
            {{-- Promotions actives --}}
            @if($activePromotions->count() > 0)
            <div class="card mt-3">
                <div class="card-header">
                    <h5>{{ t('premium_ads.active_promotions') }}</h5>
                </div>
                <div class="card-body">
                    @foreach($activePromotions as $promotion)
                    <div class="active-promotion mb-2">
                        <strong>{{ $promotion->getTypeLabel() }}</strong><br>
                        <small class="text-muted">{{ $promotion->getTimeRemainingLabel() }}</small>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Modal de paiement --}}
<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ t('premium_ads.finalize_purchase') }}</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="payment-form">
                    {{-- Formulaire de paiement sera injecté ici --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.purchase-btn').click(function() {
        const packageId = $(this).data('package');
        const price = $(this).data('price');
        
        // Charger le formulaire de paiement
        loadPaymentForm(packageId, price);
        
        $('#paymentModal').modal('show');
    });
    
    function loadPaymentForm(packageId, price) {
        $.get(`/posts/{{ $post->id }}/promote/payment-form`, {
            package_id: packageId
        }).done(function(html) {
            $('#payment-form').html(html);
        }).fail(function() {
            alert('{{ t('premium_ads.error_loading_payment') }}');
        });
    }
    
    // Gestion des réponses Ajax avec traductions
    window.showPromotionSuccess = function(message) {
        toastr.success(message || '{{ t('premium_ads.promotion_activated') }}');
    };
    
    window.showPromotionError = function(message) {
        toastr.error(message || '{{ t('premium_ads.promotion_error') }}');
    };
});
</script>
@endpush
```

#### Composant de Badge Multilingue
```blade
{{-- resources/views/front/partials/promotion-badges.blade.php --}}
@if($post->activePromotions->count() > 0)
    <div class="promotion-badges">
        @foreach($post->activePromotions as $promotion)
            @switch($promotion->promotion_type)
                @case('featured')
                    <span class="badge badge-featured">
                        ⭐ {{ t('premium_ads.featured') }}
                    </span>
                    @break
                    
                @case('top')
                    <span class="badge badge-top">
                        🔝 {{ t('premium_ads.top_ad') }}
                    </span>
                    @break
                    
                @case('urgent')
                    <span class="badge badge-urgent">
                        🚨 {{ t('premium_ads.urgent') }}
                    </span>
                    @break
                    
                @case('bump_up')
                    <span class="badge badge-bumped">
                        🔄 {{ t('premium_ads.recently_updated') }}
                    </span>
                    @break
                    
                @case('highlight')
                    <span class="badge badge-highlight">
                        ✨ {{ t('premium_ads.highlight') }}
                    </span>
                    @break
            @endswitch
        @endforeach
    </div>
@endif
```

### 6. Notifications et Emails Multilingues

#### Notification : `PromotionExpiringNotification`
```php
<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\PremiumAds\PostPromotion;

class PromotionExpiringNotification extends Notification
{
    public function __construct(
        private PostPromotion $promotion
    ) {}
    
    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }
    
    public function toMail($notifiable): MailMessage
    {
        // Utiliser la langue de l'utilisateur
        app()->setLocale($notifiable->preferred_locale ?? 'en');
        
        return (new MailMessage)
            ->subject(t('premium_ads.promotion_expiring_subject'))
            ->greeting(t('premium_ads.greeting', ['name' => $notifiable->name]))
            ->line(t('premium_ads.promotion_expiring_message', [
                'type' => $this->promotion->getTypeLabel(),
                'title' => $this->promotion->post->title,
                'days' => $this->promotion->getDaysRemaining()
            ]))
            ->action(t('premium_ads.extend_promotion'), route('posts.promote', $this->promotion->post))
            ->line(t('premium_ads.promotion_expiring_footer'));
    }
    
    public function toArray($notifiable): array
    {
        app()->setLocale($notifiable->preferred_locale ?? 'en');
        
        return [
            'title' => t('premium_ads.promotion_expiring_title'),
            'message' => t('premium_ads.promotion_expiring_message', [
                'type' => $this->promotion->getTypeLabel(),
                'title' => $this->promotion->post->title,
                'days' => $this->promotion->getDaysRemaining()
            ]),
            'promotion_id' => $this->promotion->id,
            'post_id' => $this->promotion->post_id,
        ];
    }
}
```

#### Service : `MultilingualPromotionService`
```php
<?php

namespace App\Services\PremiumAds;

use App\Models\Post;
use App\Models\Package;
use App\Models\PremiumAds\PostPromotion;
use Illuminate\Support\Facades\DB;

class MultilingualPromotionService extends PromotionService
{
    /**
     * Obtenir les packages avec traductions pour l'utilisateur
     */
    public function getLocalizedPromotionPackages($user = null): Collection
    {
        $locale = $user?->preferred_locale ?? app()->getLocale();
        
        return Package::where('type', 'promotion')
            ->where('active', true)
            ->get()
            ->map(function ($package) use ($locale) {
                // Préparer les données traduites
                $package->localized_name = data_get($package, "name.{$locale}") 
                    ?? data_get($package, "name.en");
                $package->localized_description = data_get($package, "description.{$locale}") 
                    ?? data_get($package, "description.en");
                $package->localized_short_name = data_get($package, "short_name.{$locale}") 
                    ?? data_get($package, "short_name.en");
                
                return $package;
            })
            ->sortBy('price');
    }
    
    /**
     * Générer une description de promotion traduite
     */
    public function generatePromotionDescription(Package $package, string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        app()->setLocale($locale);
        
        $description = data_get($package, "description.{$locale}") 
            ?? data_get($package, "description.en");
        
        $features = json_decode($package->promotion_features, true) ?? [];
        $translatedFeatures = [];
        
        foreach ($features as $feature => $value) {
            if ($value) {
                $translatedFeatures[] = t("promotion_features.{$feature}");
            }
        }
        
        if (!empty($translatedFeatures)) {
            $description .= "\n\n" . t('premium_ads.features_included') . ":\n";
            $description .= "• " . implode("\n• ", $translatedFeatures);
        }
        
        if ($package->promotion_time) {
            $description .= "\n\n" . t('premium_ads.duration') . ": ";
            $description .= trans_choice('premium_ads.days_duration', $package->promotion_time, [
                'count' => $package->promotion_time
            ]);
        }
        
        return $description;
    }
    
    /**
     * Obtenir les options de promotion pour l'API
     */
    public function getPromotionOptionsForApi(Post $post, string $locale = null): array
    {
        $locale = $locale ?? app()->getLocale();
        app()->setLocale($locale);
        
        $packages = $this->getLocalizedPromotionPackages($post->user);
        
        return $packages->map(function ($package) use ($post, $locale) {
            return [
                'id' => $package->id,
                'type' => $package->short_name,
                'name' => $package->localized_name,
                'short_name' => $package->localized_short_name,
                'description' => $this->generatePromotionDescription($package, $locale),
                'price' => $package->price,
                'duration_days' => $package->promotion_time,
                'features' => json_decode($package->promotion_features, true),
                'currency' => $package->currency_code,
                'dynamic_price' => $this->calculateDynamicPrice($post, $package),
                'available' => !$post->activePromotions()
                    ->byType($package->short_name)
                    ->exists(),
                'labels' => [
                    'activate_button' => t('premium_ads.activate_for', ['price' => $package->price]),
                    'duration_label' => trans_choice('premium_ads.days_duration', $package->promotion_time, [
                        'count' => $package->promotion_time
                    ]),
                    'features_title' => t('premium_ads.features_included'),
                    'price_label' => t('premium_ads.price')
                ]
            ];
        })->toArray();
    }
}
```

### 7. Tests Multilingues

#### Test : `PromotionMultilingualTest`
```php
<?php

namespace Tests\Feature\PremiumAds;

use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use App\Models\Package;
use App\Services\PremiumAds\MultilingualPromotionService;

class PromotionMultilingualTest extends TestCase
{
    private MultilingualPromotionService $service;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(MultilingualPromotionService::class);
    }
    
    /** @test */
    public function packages_are_returned_in_user_preferred_language()
    {
        $user = User::factory()->create(['preferred_locale' => 'fr']);
        $package = Package::factory()->create([
            'name' => json_encode([
                'en' => 'Bump Up',
                'fr' => 'Remonter',
                'es' => 'Subir'
            ])
        ]);
        
        $packages = $this->service->getLocalizedPromotionPackages($user);
        
        $this->assertEquals('Remonter', $packages->first()->localized_name);
    }
    
    /** @test */
    public function promotion_description_includes_translated_features()
    {
        app()->setLocale('fr');
        
        $package = Package::factory()->create([
            'promotion_features' => json_encode([
                'fixed_top_position' => true,
                'homepage_display' => true
            ])
        ]);
        
        $description = $this->service->generatePromotionDescription($package, 'fr');
        
        $this->assertStringContainsString('Position fixe en tête', $description);
        $this->assertStringContainsString('Affichage page d\'accueil', $description);
    }
    
    /** @test */
    public function api_returns_localized_promotion_options()
    {
        app()->setLocale('es');
        
        $post = Post::factory()->create();
        $options = $this->service->getPromotionOptionsForApi($post, 'es');
        
        $this->assertIsArray($options);
        $this->assertArrayHasKey('labels', $options[0]);
        $this->assertStringContainsString('€', $options[0]['labels']['activate_button']);
    }
}
```

## 🌍 Résumé de l'Intégration Multilingue

### **Fonctionnalités Multilingues Intégrées :**

1. **Packages avec JSON multilingue** - Noms, descriptions et caractéristiques traduits
2. **Fichiers de traduction** - Support complet des 10 langues principales  
3. **Modèles avec méthodes de traduction** - Getters automatiques selon la locale
4. **Vues entièrement traduites** - Interface utilisateur adaptée à chaque langue
5. **Notifications multilingues** - Emails et notifications dans la langue de l'utilisateur
6. **API localisée** - Réponses traduites selon la langue demandée
7. **Tests multilingues** - Validation du bon fonctionnement des traductions

### **Langues Supportées :**
- 🇬🇧 Anglais (en) - Langue par défaut
- 🇫🇷 Français (fr) 
- 🇪🇸 Espagnol (es)
- 🇸🇦 Arabe (ar)
- 🇵🇹 Portugais (pt)
- 🇩🇪 Allemand (de)
- 🇮🇹 Italien (it)
- 🇷🇺 Russe (ru)
- 🇨🇳 Chinois (zh)
- 🇯🇵 Japonais (ja)

Le module Premium Ads est maintenant **parfaitement intégré au système multilingue de LaraClassified** ! 🌍

---

*Document mis à jour le 2025-07-06 avec l'intégration complète du système multilingue*
