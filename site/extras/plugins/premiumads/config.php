<?php

return [
    'premiumads' => [
        'default_promotion_duration' => env('PREMIUMADS_DEFAULT_DURATION', 30), // days
        'auto_activate_payments' => env('PREMIUMADS_AUTO_ACTIVATE', false),
        'enable_analytics' => env('PREMIUMADS_ANALYTICS', true),
        'max_promotions_per_post' => env('PREMIUMADS_MAX_PER_POST', 5),
        
        // Configuration des types de promotion disponibles
        'promotion_types' => [
            'bump' => [
                'enabled' => true,
                'max_duration' => 7,
                'default_price' => 2.00,
                'display_order' => 1,
            ],
            'featured' => [
                'enabled' => true,
                'max_duration' => 30,
                'default_price' => 10.00,
                'display_order' => 2,
            ],
            'top' => [
                'enabled' => true,
                'max_duration' => 30,
                'default_price' => 25.00,
                'display_order' => 3,
            ],
            'urgent' => [
                'enabled' => true,
                'max_duration' => 14,
                'default_price' => 5.00,
                'display_order' => 4,
            ],
            'highlight' => [
                'enabled' => true,
                'max_duration' => 30,
                'default_price' => 8.00,
                'display_order' => 5,
            ],
        ],
        
        // Configuration des devises supportées
        'currencies' => [
            'USD' => 'US Dollar',
            'EUR' => 'Euro',
            'GBP' => 'British Pound',
            'XOF' => 'West African CFA Franc',
            'XAF' => 'Central African CFA Franc',
        ],
        
        // Configuration des notifications
        'notifications' => [
            'admin_new_promotion' => env('PREMIUMADS_NOTIFY_ADMIN', true),
            'user_promotion_activated' => env('PREMIUMADS_NOTIFY_USER', true),
            'promotion_expiring_soon' => env('PREMIUMADS_NOTIFY_EXPIRING', true),
            'days_before_expiry' => env('PREMIUMADS_NOTIFY_DAYS', 3),
        ],
    ],
];