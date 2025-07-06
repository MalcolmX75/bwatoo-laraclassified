<?php

namespace extras\plugins\premiumads;

use App\Models\PostPromotion;
use App\Models\Package;
use App\Models\Post;
use App\Models\User;
use extras\plugins\premiumads\app\Traits\InstallTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PremiumAds
{
    use InstallTrait;
    
    /**
     * Plugin configuration and options
     */
    public static function getOptions(): array
    {
        return [
            (object)[
                'name'     => 'Manage Promotions',
                'url'      => urlGen()->adminUrl('post_promotions'),
                'btnClass' => 'btn-info',
                'iClass'   => 'fa-solid fa-gear',
            ],
            (object)[
                'name'     => 'Statistics',
                'url'      => urlGen()->adminUrl('post_promotions/stats'),
                'btnClass' => 'btn-success',
                'iClass'   => 'fa-solid fa-chart-bar',
            ],
            (object)[
                'name'     => 'Promotion Packages',
                'url'      => urlGen()->adminUrl('packages/promotion'),
                'btnClass' => 'btn-warning',
                'iClass'   => 'fa-solid fa-box',
            ],
        ];
    }
    
    /**
     * Get promotion analytics data
     */
    public static function getAnalyticsData(): array
    {
        try {
            return [
                'total_promotions' => PostPromotion::count(),
                'active_promotions' => PostPromotion::where('status', 'active')->count(),
                'expired_promotions' => PostPromotion::where('status', 'expired')->count(),
                'pending_promotions' => PostPromotion::where('status', 'pending')->count(),
                'cancelled_promotions' => PostPromotion::where('status', 'cancelled')->count(),
                'total_revenue' => PostPromotion::where('status', 'active')->sum('price'),
                'monthly_revenue' => PostPromotion::where('status', 'active')
                    ->whereMonth('created_at', now()->month)
                    ->sum('price'),
                'promotion_types' => PostPromotion::selectRaw('promotion_type, COUNT(*) as count')
                    ->groupBy('promotion_type')
                    ->pluck('count', 'promotion_type')
                    ->toArray(),
                'status_distribution' => PostPromotion::selectRaw('status, COUNT(*) as count')
                    ->groupBy('status')
                    ->pluck('count', 'status')
                    ->toArray(),
                'monthly_stats' => PostPromotion::selectRaw('MONTH(created_at) as month, COUNT(*) as count, SUM(price) as revenue')
                    ->whereYear('created_at', now()->year)
                    ->groupBy(DB::raw('MONTH(created_at)'))
                    ->get()
                    ->keyBy('month')
                    ->toArray(),
            ];
        } catch (\Throwable $e) {
            return [
                'total_promotions' => 0,
                'active_promotions' => 0,
                'expired_promotions' => 0,
                'pending_promotions' => 0,
                'cancelled_promotions' => 0,
                'total_revenue' => 0,
                'monthly_revenue' => 0,
                'promotion_types' => [],
                'status_distribution' => [],
                'monthly_stats' => [],
            ];
        }
    }
    
    /**
     * Get promotion settings for admin panel
     */
    public static function getPromotionSettings(): array
    {
        return config('premiumads.promotion_types', []);
    }
    
    /**
     * Get plugin version
     */
    public static function getVersion(): string
    {
        $initFile = __DIR__ . '/init.json';
        if (file_exists($initFile)) {
            $init = json_decode(file_get_contents($initFile), true);
            return $init['version'] ?? '1.0.0';
        }
        return '1.0.0';
    }
    
    /**
     * Get plugin display name
     */
    public static function getDisplayName(): string
    {
        return 'Premium Ads';
    }
    
    /**
     * Get plugin description
     */
    public static function getDescription(): string
    {
        return 'Advanced promotion system for posts with multiple promotion types (Bump Up, Top Ad, Featured, Urgent, Highlight)';
    }
    
    /**
     * Check if plugin has any active promotions
     */
    public static function hasActivePromotions(): bool
    {
        try {
            return PostPromotion::where('status', 'active')->exists();
        } catch (\Throwable $e) {
            return false;
        }
    }
    
    /**
     * Get recent promotions for dashboard widget
     */
    public static function getRecentPromotions(int $limit = 5): array
    {
        try {
            return PostPromotion::with(['post', 'package'])
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get()
                ->toArray();
        } catch (\Throwable $e) {
            return [];
        }
    }
    
    /**
     * Get expiring promotions count
     */
    public static function getExpiringPromotionsCount(int $days = 3): int
    {
        try {
            return PostPromotion::where('status', 'active')
                ->where('end_date', '<=', now()->addDays($days))
                ->count();
        } catch (\Throwable $e) {
            return 0;
        }
    }
}