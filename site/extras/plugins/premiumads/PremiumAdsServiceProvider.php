<?php

namespace extras\plugins\premiumads;

use Illuminate\Support\ServiceProvider;

class PremiumAdsServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     */
    public function boot(): void
    {
        // Load plugin views
        $viewsPath = realpath(__DIR__ . '/resources/views');
        if ($viewsPath) {
            $this->loadViewsFrom($viewsPath, 'premiumads');
        }
        
        // Load plugin languages files
        $langPath = realpath(__DIR__ . '/lang');
        if ($langPath) {
            $this->loadTranslationsFrom($langPath, 'premiumads');
        }
        
        // Merge plugin config
        $configPath = realpath(__DIR__ . '/config.php');
        if ($configPath) {
            $this->mergeConfigFrom($configPath, 'premiumads');
        }
        
        // Register plugin routes if needed
        $routesPath = __DIR__ . '/routes/web.php';
        if (file_exists($routesPath)) {
            $this->loadRoutesFrom($routesPath);
        }
        
        // Register plugin assets if needed
        $assetsPath = __DIR__ . '/public';
        if (is_dir($assetsPath)) {
            $this->publishes([
                $assetsPath => public_path('vendor/premiumads'),
            ], 'premiumads-assets');
        }
    }
    
    /**
     * Register any package services.
     */
    public function register(): void
    {
        // Register the plugin as a singleton
        $this->app->singleton('premiumads', function ($app) {
            return new PremiumAds();
        });
        
        // Register plugin alias
        $this->app->alias('premiumads', PremiumAds::class);
    }
    
    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return ['premiumads'];
    }
}