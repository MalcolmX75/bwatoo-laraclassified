<?php

return [
    // Plugin information
    'plugin_name' => 'Premium Ads',
    'plugin_description' => 'Advanced promotion system for posts with multiple promotion types',
    
    // General
    'management' => 'Premium Ads Management',
    'statistics' => 'Premium Ads Statistics',
    'settings' => 'Premium Ads Settings',
    'packages' => 'Promotion Packages',
    
    // Promotion types
    'promotion_types' => [
        'bump' => 'Bump Up',
        'top' => 'Top Ad',
        'featured' => 'Featured',
        'urgent' => 'Urgent',
        'highlight' => 'Highlight',
    ],
    
    // Statuses
    'statuses' => [
        'pending' => 'Pending',
        'active' => 'Active',
        'expired' => 'Expired',
        'cancelled' => 'Cancelled',
    ],
    
    // Actions
    'activate' => 'Activate',
    'expire' => 'Expire',
    'cancel' => 'Cancel',
    'view_stats' => 'View Statistics',
    'manage' => 'Manage',
    
    // Messages
    'no_promotions' => 'No promotions found',
    'promotion_activated' => 'Promotion activated successfully',
    'promotion_expired' => 'Promotion expired successfully',
    'promotion_cancelled' => 'Promotion cancelled successfully',
    'bulk_actions_completed' => 'Bulk actions completed successfully',
    
    // Statistics
    'total_promotions' => 'Total Promotions',
    'active_promotions' => 'Active Promotions',
    'expired_promotions' => 'Expired Promotions',
    'pending_promotions' => 'Pending Promotions',
    'cancelled_promotions' => 'Cancelled Promotions',
    'total_revenue' => 'Total Revenue',
    'monthly_revenue' => 'Monthly Revenue',
    'revenue_by_type' => 'Revenue by Type',
    'promotions_by_status' => 'Promotions by Status',
    'monthly_stats' => 'Monthly Statistics',
    
    // Configuration
    'default_duration' => 'Default Promotion Duration (days)',
    'auto_activate' => 'Auto-activate after payment',
    'enable_analytics' => 'Enable Analytics',
    'max_per_post' => 'Maximum promotions per post',
    'notification_settings' => 'Notification Settings',
    'admin_notifications' => 'Admin Notifications',
    'user_notifications' => 'User Notifications',
    'expiry_notifications' => 'Expiry Notifications',
    'days_before_expiry' => 'Days before expiry to notify',
    
    // Installation
    'installation_success' => 'Premium Ads plugin installed successfully',
    'installation_failed' => 'Premium Ads plugin installation failed',
    'uninstallation_success' => 'Premium Ads plugin uninstalled successfully',
    'uninstallation_failed' => 'Premium Ads plugin uninstallation failed',
    'already_installed' => 'Premium Ads plugin is already installed',
    'not_installed' => 'Premium Ads plugin is not installed',
    
    // Errors
    'table_not_found' => 'Premium Ads table not found. Please run migrations.',
    'service_not_available' => 'Premium Ads service is not available',
    'invalid_promotion_type' => 'Invalid promotion type',
    'invalid_status' => 'Invalid promotion status',
    'promotion_not_found' => 'Promotion not found',
    'post_not_found' => 'Post not found',
    'package_not_found' => 'Package not found',
];