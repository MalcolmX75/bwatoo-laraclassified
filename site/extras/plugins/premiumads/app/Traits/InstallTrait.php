<?php

namespace extras\plugins\premiumads\app\Traits;

use App\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Throwable;

trait InstallTrait
{
    /**
     * Get plugin options for admin panel
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
     * Check if plugin is installed
     */
    public static function installed(): bool
    {
        $cacheExpiration = 86400; // Cache for 1 day
        
        return cache()->remember('plugins.premiumads.installed', $cacheExpiration, function () {
            try {
                // Check if PostPromotion table exists
                return Schema::hasTable('post_promotions');
            } catch (Throwable $e) {
                return false;
            }
        });
    }
    
    /**
     * Install the plugin
     */
    public static function install(): bool
    {
        try {
            // Create plugin setting if needed
            $pluginSetting = [
                'key'         => 'premiumads',
                'name'        => 'Premium Ads Settings',
                'description' => 'Settings for the Premium Ads plugin',
                'value'       => json_encode([
                    'enabled' => true,
                    'auto_activate' => false,
                    'analytics_enabled' => true,
                ]),
                'field'       => json_encode([
                    'name' => 'value',
                    'type' => 'textarea',
                    'label' => 'Premium Ads Configuration (JSON)',
                ]),
                'active'      => 1,
            ];
            
            // Use the createPluginSetting helper if available
            if (function_exists('createPluginSetting')) {
                createPluginSetting($pluginSetting);
            }
            
            // Clear cache
            cache()->forget('plugins.premiumads.installed');
            
            return true;
        } catch (Throwable $e) {
            return false;
        }
    }
    
    /**
     * Uninstall the plugin
     */
    public static function uninstall(): bool
    {
        try {
            // Remove plugin setting
            if (function_exists('dropPluginSetting')) {
                dropPluginSetting('premiumads');
            }
            
            // Clear cache
            cache()->forget('plugins.premiumads.installed');
            
            return true;
        } catch (Throwable $e) {
            return false;
        }
    }
}