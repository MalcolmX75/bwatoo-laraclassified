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

#### Configuration des Packages Premium
```php
// Configuration via Seeder
$premiumPackages = [
    [
        'type' => 'promotion',
        'name' => '{"en": "Bump Up", "fr": "Remonter"}',
        'short_name' => 'bump',
        'price' => 2.00,
        'promotion_time' => 1, // 1 jour de remontée
        'promotion_features' => json_encode([
            'bump_frequency' => 'once_per_week',
            'notification_subscribers' => true,
            'recently_updated_badge' => true
        ]),
        'description' => '{"en": "Push your ad to the top", "fr": "Remontez votre annonce en tête"}',
        'ribbon' => '#28a745'
    ],
    [
        'type' => 'promotion',
        'name' => '{"en": "Top Ad", "fr": "TOP"}',
        'short_name' => 'top',
        'price' => 15.00,
        'promotion_time' => 7,
        'top_position_guaranteed' => true,
        'promotion_features' => json_encode([
            'fixed_top_position' => true,
            'category_top_display' => true,
            'search_priority' => 'high'
        ]),
        'ribbon' => '#dc3545',
        'has_badge' => true
    ],
    [
        'type' => 'promotion',
        'name' => '{"en": "Featured", "fr": "À la Une"}',
        'short_name' => 'featured',
        'price' => 25.00,
        'promotion_time' => 14,
        'promotion_features' => json_encode([
            'homepage_display' => true,
            'newsletter_inclusion' => true,
            'social_auto_share' => true,
            'priority_search' => 'highest'
        ]),
        'ribbon' => '#ffc107',
        'has_badge' => true
    ],
    [
        'type' => 'promotion',
        'name' => '{"en": "Urgent", "fr": "Urgent"}',
        'short_name' => 'urgent',
        'price' => 5.00,
        'promotion_time' => 3,
        'urgent_badge_color' => '#ff0000',
        'promotion_features' => json_encode([
            'urgent_badge' => true,
            'blink_effect' => true,
            'priority_notifications' => true
        ])
    ],
    [
        'type' => 'promotion',
        'name' => '{"en": "Highlight", "fr": "Surligner"}',
        'short_name' => 'highlight',
        'price' => 3.00,
        'promotion_time' => 5,
        'highlight_color' => '#ffff00',
        'promotion_features' => json_encode([
            'background_highlight' => true,
            'border_glow' => true
        ])
    ]
];
```

### 3. Modèles Laravel

#### Model : `PostPromotion`
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

#### Extension Model : `Post`
```php
// Dans app/Models/Post.php - Ajouter ces méthodes

/**
 * Relations pour les promotions
 */
public function promotions()
{
    return $this->hasMany(PostPromotion::class);
}

public function activePromotions()
{
    return $this->promotions()->active();
}

/**
 * Vérifications des types de promotion
 */
public function isBumped(): bool
{
    return $this->activePromotions()
        ->byType('bump')
        ->exists();
}

public function isTopAd(): bool
{
    return $this->activePromotions()
        ->byType('top')
        ->exists();
}

public function isFeatured(): bool
{
    return $this->activePromotions()
        ->byType('featured')
        ->exists();
}

public function isUrgent(): bool
{
    return $this->activePromotions()
        ->byType('urgent')
        ->exists();
}

public function isHighlighted(): bool
{
    return $this->activePromotions()
        ->byType('highlight')
        ->exists();
}

/**
 * Obtenir la promotion active d'un type
 */
public function getActivePromotion(string $type): ?PostPromotion
{
    return $this->activePromotions()
        ->byType($type)
        ->first();
}

/**
 * Score de priorité pour l'affichage
 */
public function getPromotionPriority(): int
{
    $priority = 0;
    
    if ($this->isFeatured()) $priority += 1000;
    if ($this->isTopAd()) $priority += 500;
    if ($this->isUrgent()) $priority += 100;
    if ($this->isBumped()) $priority += 50;
    if ($this->isHighlighted()) $priority += 25;
    
    return $priority;
}
```

### 4. Services

#### Service : `PromotionService`
```php
<?php

namespace App\Services\PremiumAds;

use App\Models\Post;
use App\Models\Package;
use App\Models\PremiumAds\PostPromotion;
use App\Services\Payment\PaymentService;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PromotionService
{
    public function __construct(
        private PaymentService $paymentService
    ) {}
    
    /**
     * Acheter une promotion pour une annonce
     */
    public function purchasePromotion(
        Post $post, 
        Package $package, 
        array $paymentData
    ): PostPromotion {
        return DB::transaction(function () use ($post, $package, $paymentData) {
            // Vérifier les conditions
            $this->validatePromotionPurchase($post, $package);
            
            // Calculer les dates
            $startDate = now();
            $endDate = $startDate->copy()->addDays($package->promotion_time);
            
            // Traitement du paiement
            $payment = $this->paymentService->processPayment([
                'amount' => $package->price,
                'currency' => $package->currency_code,
                'description' => "Promotion {$package->short_name} pour annonce #{$post->id}",
                'metadata' => [
                    'post_id' => $post->id,
                    'package_id' => $package->id,
                    'promotion_type' => $package->short_name
                ]
            ] + $paymentData);
            
            // Créer la promotion
            $promotion = PostPromotion::create([
                'post_id' => $post->id,
                'package_id' => $package->id,
                'payment_id' => $payment->id,
                'promotion_type' => $package->short_name,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'price' => $package->price,
                'currency_code' => $package->currency_code,
                'status' => 'active'
            ]);
            
            // Actions spécifiques par type
            $this->executePromotionActions($promotion);
            
            return $promotion;
        });
    }
    
    /**
     * Actions spécifiques par type de promotion
     */
    private function executePromotionActions(PostPromotion $promotion): void
    {
        match ($promotion->promotion_type) {
            'bump' => $this->executeBumpActions($promotion),
            'top' => $this->executeTopActions($promotion),
            'featured' => $this->executeFeaturedActions($promotion),
            'urgent' => $this->executeUrgentActions($promotion),
            'highlight' => $this->executeHighlightActions($promotion),
            default => null
        };
    }
    
    private function executeBumpActions(PostPromotion $promotion): void
    {
        // Actualiser la date de l'annonce
        $promotion->post->update([
            'updated_at' => now(),
            'bumped_at' => now()
        ]);
        
        // Notifier les abonnés de la catégorie
        $this->notifySubscribers($promotion->post);
    }
    
    private function executeTopActions(PostPromotion $promotion): void
    {
        // S'assurer qu'il n'y a pas trop d'annonces TOP simultanées
        $this->manageTopAdsLimit($promotion);
    }
    
    private function executeFeaturedActions(PostPromotion $promotion): void
    {
        // Programmer l'inclusion en newsletter
        $this->scheduleNewsletterInclusion($promotion->post);
        
        // Partage automatique réseaux sociaux
        $this->scheduleSocialSharing($promotion->post);
    }
    
    /**
     * Obtenir les packages de promotion disponibles
     */
    public function getAvailablePromotionPackages(): Collection
    {
        return Package::where('type', 'promotion')
            ->where('active', true)
            ->orderBy('price')
            ->get();
    }
    
    /**
     * Calculer le prix dynamique (optionnel)
     */
    public function calculateDynamicPrice(Post $post, Package $package): float
    {
        $basePrice = $package->price;
        $factors = [];
        
        // Facteur demande catégorie
        $factors['category_demand'] = $this->getCategoryDemandFactor($post->category);
        
        // Facteur heure de la journée
        $factors['time_of_day'] = $this->getTimeOfDayFactor();
        
        // Facteur concurrence
        $factors['competition'] = $this->getCompetitionFactor($post);
        
        // Facteur historique utilisateur
        $factors['user_history'] = $this->getUserHistoryFactor($post->user);
        
        return $basePrice * array_product($factors);
    }
}
```

### 5. Contrôleurs

#### Controller : `PromotionController`
```php
<?php

namespace App\Http\Controllers\Web\Front\Account;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Package;
use App\Services\PremiumAds\PromotionService;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function __construct(
        private PromotionService $promotionService
    ) {}
    
    /**
     * Afficher les options de promotion pour une annonce
     */
    public function show(Post $post)
    {
        $this->authorize('update', $post);
        
        $packages = $this->promotionService->getAvailablePromotionPackages();
        $activePromotions = $post->activePromotions;
        
        return view('front.pages.promotion.show', compact('post', 'packages', 'activePromotions'));
    }
    
    /**
     * Traiter l'achat d'une promotion
     */
    public function purchase(Request $request, Post $post)
    {
        $this->authorize('update', $post);
        
        $validated = $request->validate([
            'package_id' => 'required|exists:lc_packages,id',
            'payment_method' => 'required|string',
            'payment_data' => 'required|array'
        ]);
        
        $package = Package::findOrFail($validated['package_id']);
        
        try {
            $promotion = $this->promotionService->purchasePromotion(
                $post,
                $package,
                $validated['payment_data']
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Promotion activée avec succès',
                'promotion' => $promotion->load('package'),
                'redirect' => route('posts.show', $post)
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'activation : ' . $e->getMessage()
            ], 422);
        }
    }
    
    /**
     * API : Obtenir les options de promotion
     */
    public function getOptions(Post $post)
    {
        $packages = $this->promotionService->getAvailablePromotionPackages();
        
        return response()->json([
            'packages' => $packages->map(function ($package) use ($post) {
                return [
                    'id' => $package->id,
                    'name' => $package->name,
                    'short_name' => $package->short_name,
                    'price' => $package->price,
                    'duration' => $package->promotion_time,
                    'features' => json_decode($package->promotion_features, true),
                    'dynamic_price' => $this->promotionService->calculateDynamicPrice($post, $package)
                ];
            })
        ]);
    }
    
    /**
     * Statistiques de promotion
     */
    public function stats(Post $post)
    {
        $this->authorize('view', $post);
        
        $promotions = $post->promotions()
            ->with('package')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return response()->json([
            'promotions' => $promotions->map(function ($promotion) {
                return [
                    'type' => $promotion->promotion_type,
                    'package_name' => $promotion->package->name,
                    'start_date' => $promotion->start_date,
                    'end_date' => $promotion->end_date,
                    'days_remaining' => $promotion->getDaysRemaining(),
                    'views' => $promotion->views_count,
                    'clicks' => $promotion->clicks_count,
                    'status' => $promotion->status
                ];
            })
        ]);
    }
}
```

### 6. Affichage Frontend

#### Vue : Interface de Sélection
```blade
{{-- resources/views/front/pages/promotion/show.blade.php --}}
@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <h1>🚀 Boostez votre annonce</h1>
            <p class="lead">Donnez plus de visibilité à votre annonce "{{ $post->title }}"</p>
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
                                ⭐ {{ $package->name }}
                            @elseif($package->short_name === 'top')
                                🔝 {{ $package->name }}
                            @elseif($package->short_name === 'bump')
                                🔄 {{ $package->name }}
                            @elseif($package->short_name === 'urgent')
                                🚨 {{ $package->name }}
                            @elseif($package->short_name === 'highlight')
                                ✨ {{ $package->name }}
                            @endif
                        </h5>
                        <span class="price">{{ $package->price }}€</span>
                    </div>
                    
                    <div class="card-body">
                        <p>{{ $package->description }}</p>
                        
                        <div class="features">
                            <strong>Durée :</strong> {{ $package->promotion_time }} jour(s)<br>
                            
                            @php $features = json_decode($package->promotion_features, true) @endphp
                            @if($features)
                                <strong>Avantages :</strong>
                                <ul class="list-unstyled mt-2">
                                    @foreach($features as $feature => $value)
                                        @if($value)
                                            <li>✓ {{ ucfirst(str_replace('_', ' ', $feature)) }}</li>
                                        @endif
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                        
                        <button class="btn btn-primary btn-block purchase-btn" 
                                data-package="{{ $package->id }}"
                                data-price="{{ $package->price }}">
                            Activer pour {{ $package->price }}€
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
                    <h5>Aperçu de votre annonce</h5>
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
                    <h5>Promotions actives</h5>
                </div>
                <div class="card-body">
                    @foreach($activePromotions as $promotion)
                    <div class="active-promotion mb-2">
                        <strong>{{ ucfirst($promotion->promotion_type) }}</strong><br>
                        <small>Expire le {{ $promotion->end_date->format('d/m/Y') }}</small>
                        <small>({{ $promotion->getDaysRemaining() }} jours restants)</small>
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
                <h5 class="modal-title">Finaliser l'achat</h5>
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
        });
    }
});
</script>
@endpush
```

### 7. Algorithme d'Affichage

#### Service : `PostDisplayService`
```php
<?php

namespace App\Services\PremiumAds;

use App\Models\Post;
use Illuminate\Database\Eloquent\Builder;

class PostDisplayService
{
    /**
     * Appliquer l'ordre de priorité aux annonces
     */
    public function applyPromotionOrdering(Builder $query): Builder
    {
        return $query->addSelect([
            // Calculer le score de priorité
            'promotion_priority' => function ($subQuery) {
                $subQuery->selectRaw('
                    COALESCE(
                        (SELECT 
                            CASE 
                                WHEN pp.promotion_type = "featured" THEN 1000
                                WHEN pp.promotion_type = "top" THEN 500  
                                WHEN pp.promotion_type = "urgent" THEN 100
                                WHEN pp.promotion_type = "bump" THEN 50
                                WHEN pp.promotion_type = "highlight" THEN 25
                                ELSE 0
                            END
                        FROM lc_post_promotions pp 
                        WHERE pp.post_id = lc_posts.id 
                        AND pp.status = "active"
                        AND pp.start_date <= NOW() 
                        AND pp.end_date >= NOW()
                        ORDER BY 
                            CASE pp.promotion_type
                                WHEN "featured" THEN 1
                                WHEN "top" THEN 2  
                                WHEN "urgent" THEN 3
                                WHEN "bump" THEN 4
                                WHEN "highlight" THEN 5
                            END
                        LIMIT 1), 0)
                ');
            }
        ])->orderByDesc('promotion_priority')
          ->orderByDesc('created_at');
    }
    
    /**
     * Obtenir les annonces pour la page d'accueil
     */
    public function getHomepagePosts(int $limit = 20)
    {
        return Post::with(['activePromotions', 'category', 'user'])
            ->where('reviewed_at', '>', now()->subDays(30))
            ->when(true, function ($query) {
                return $this->applyPromotionOrdering($query);
            })
            ->limit($limit)
            ->get();
    }
    
    /**
     * Obtenir les annonces d'une catégorie
     */
    public function getCategoryPosts(int $categoryId, array $filters = [])
    {
        $query = Post::with(['activePromotions', 'category', 'user'])
            ->where('category_id', $categoryId);
            
        // Appliquer les filtres
        foreach ($filters as $field => $value) {
            if ($value !== null) {
                $query->where($field, $value);
            }
        }
        
        return $this->applyPromotionOrdering($query)->paginate(20);
    }
}
```

### 8. Jobs et Tâches Automatiques

#### Job : `ExpirePromotionsJob`
```php
<?php

namespace App\Jobs\PremiumAds;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\PremiumAds\PostPromotion;
use App\Notifications\PromotionExpiringNotification;
use App\Notifications\PromotionExpiredNotification;

class ExpirePromotionsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public function handle(): void
    {
        // Notifier les promotions qui expirent dans 24h
        $expiringPromos = PostPromotion::where('status', 'active')
            ->whereDate('end_date', now()->addDay())
            ->with(['post.user', 'package'])
            ->get();
            
        foreach ($expiringPromos as $promo) {
            $promo->post->user->notify(
                new PromotionExpiringNotification($promo)
            );
        }
        
        // Expirer les promotions échues
        $expiredPromos = PostPromotion::where('status', 'active')
            ->where('end_date', '<', now())
            ->with(['post.user', 'package'])
            ->get();
            
        foreach ($expiredPromos as $promo) {
            $promo->update(['status' => 'expired']);
            
            $promo->post->user->notify(
                new PromotionExpiredNotification($promo)
            );
        }
    }
}
```

### 9. Analytics et Rapports

#### Service : `PromotionAnalyticsService`
```php
<?php

namespace App\Services\PremiumAds;

use App\Models\PremiumAds\PostPromotion;
use App\Models\PremiumAds\PromotionAnalytics;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class PromotionAnalyticsService
{
    /**
     * Enregistrer une vue de promotion
     */
    public function recordView(PostPromotion $promotion, string $source = 'direct'): void
    {
        $promotion->incrementViews();
        
        $this->updateDailyAnalytics($promotion, [
            'impressions' => 1,
            "source_{$source}" => 1
        ]);
    }
    
    /**
     * Enregistrer un clic sur promotion
     */
    public function recordClick(PostPromotion $promotion, string $source = 'direct'): void
    {
        $promotion->incrementClicks();
        
        $this->updateDailyAnalytics($promotion, [
            'clicks' => 1,
            "source_{$source}" => 1
        ]);
    }
    
    /**
     * Obtenir les statistiques d'une promotion
     */
    public function getPromotionStats(PostPromotion $promotion): array
    {
        $analytics = PromotionAnalytics::where('promotion_id', $promotion->id)
            ->orderBy('date')
            ->get();
            
        return [
            'total_impressions' => $promotion->views_count,
            'total_clicks' => $promotion->clicks_count,
            'ctr' => $promotion->views_count > 0 
                ? round(($promotion->clicks_count / $promotion->views_count) * 100, 2) 
                : 0,
            'daily_data' => $analytics->map(function ($day) {
                return [
                    'date' => $day->date,
                    'impressions' => $day->impressions,
                    'clicks' => $day->clicks,
                    'sources' => [
                        'home' => $day->source_home,
                        'category' => $day->source_category,
                        'search' => $day->source_search,
                        'direct' => $day->source_direct
                    ]
                ];
            })
        ];
    }
    
    /**
     * Rapport global des performances
     */
    public function getGlobalReport(Carbon $startDate, Carbon $endDate): array
    {
        $promotions = PostPromotion::whereBetween('start_date', [$startDate, $endDate])
            ->with(['post', 'package'])
            ->get();
            
        return [
            'total_promotions' => $promotions->count(),
            'total_revenue' => $promotions->sum('price'),
            'by_type' => $promotions->groupBy('promotion_type')->map(function ($group) {
                return [
                    'count' => $group->count(),
                    'revenue' => $group->sum('price'),
                    'avg_price' => $group->avg('price')
                ];
            }),
            'performance' => [
                'total_views' => $promotions->sum('views_count'),
                'total_clicks' => $promotions->sum('clicks_count'),
                'avg_ctr' => $promotions->avg(function ($promo) {
                    return $promo->views_count > 0 
                        ? ($promo->clicks_count / $promo->views_count) * 100 
                        : 0;
                })
            ]
        ];
    }
    
    private function updateDailyAnalytics(PostPromotion $promotion, array $increments): void
    {
        PromotionAnalytics::updateOrCreate(
            [
                'promotion_id' => $promotion->id,
                'date' => now()->toDateString()
            ],
            []
        )->increment($increments);
    }
}
```

## 🚀 Plan d'Implémentation

### Phase 1: Foundation (Semaines 1-2)
1. ✅ Création des migrations de base de données
2. ✅ Modèles Laravel et relations
3. ✅ Configuration des packages premium
4. ✅ Interface utilisateur basique

### Phase 2: Core Features (Semaines 3-4)
1. ✅ Service de promotion et logique métier
2. ✅ Contrôleurs et routes API
3. ✅ Intégration paiement
4. ✅ Algorithme d'affichage prioritaire

### Phase 3: Advanced Features (Semaines 5-6)
1. ✅ Analytics et tracking
2. ✅ Jobs automatiques et notifications
3. ✅ Interface admin
4. ✅ Tests et optimisations

## 📊 Métriques de Succès

### KPIs Techniques
- Temps de réponse API < 200ms
- Taux d'erreur < 0.1%
- Disponibilité > 99.9%

### KPIs Business
- Taux d'adoption promotions > 15%
- Revenus additionnels > 20% du CA
- Satisfaction utilisateurs > 4.5/5

### KPIs Utilisateur
- Augmentation visibilité annonces +200%
- Taux de conversion promotions > 3%
- Réactivations > 40%

---

*Ce document sera mis à jour au fur et à mesure de l'avancement du développement.*