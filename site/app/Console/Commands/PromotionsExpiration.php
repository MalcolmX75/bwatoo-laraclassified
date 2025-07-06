<?php
/*
 * LaraClassifier - Classified Ads Web Application
 * Copyright (c) BeDigit. All Rights Reserved
 *
 * Website: https://laraclassifier.com
 * Author: Mayeul Akpovi (BeDigit - https://bedigit.com)
 *
 * LICENSE
 * -------
 * This software is provided under a license agreement and may only be used or copied
 * in accordance with its terms, including the inclusion of the above copyright notice.
 * As this software is sold exclusively on CodeCanyon,
 * please review the full license details here: https://codecanyon.net/licenses/standard
 */

namespace App\Console\Commands;

use App\Models\PostPromotion;
use App\Services\PostPromotionService;
use Illuminate\Console\Command;

class PromotionsExpiration extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'promotions:expire {--dry-run : Show what would be expired without actually doing it}';
	
	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Expire promotions that have reached their end date';
	
	protected PostPromotionService $promotionService;
	
	public function __construct()
	{
		parent::__construct();
		$this->promotionService = app(PostPromotionService::class);
	}
	
	/**
	 * Execute the console command.
	 */
	public function handle(): int
	{
		$this->info('Starting promotions expiration process...');
		
		// Get all active promotions that should be expired
		$expiredPromotions = PostPromotion::where('status', 'active')
			->where('end_date', '<', now())
			->with(['post', 'package'])
			->get();
		
		if ($expiredPromotions->isEmpty()) {
			$this->info('No promotions to expire.');
			return self::SUCCESS;
		}
		
		$this->info("Found {$expiredPromotions->count()} promotion(s) to expire.");
		
		if ($this->option('dry-run')) {
			$this->warn('DRY RUN MODE - No actual changes will be made');
			$this->displayPromotionsTable($expiredPromotions);
			return self::SUCCESS;
		}
		
		$expiredCount = 0;
		$errorCount = 0;
		
		foreach ($expiredPromotions as $promotion) {
			try {
				$promotion->expire();
				$expiredCount++;
				
				$this->line("✓ Expired promotion #{$promotion->id} for post \"{$promotion->post->title}\"");
				
			} catch (\Exception $e) {
				$errorCount++;
				$this->error("✗ Failed to expire promotion #{$promotion->id}: {$e->getMessage()}");
			}
		}
		
		$this->info("Expiration process completed:");
		$this->info("- Successfully expired: {$expiredCount} promotion(s)");
		
		if ($errorCount > 0) {
			$this->error("- Errors encountered: {$errorCount} promotion(s)");
			return self::FAILURE;
		}
		
		// Clean up old expired promotions (optional)
		if ($this->confirm('Do you want to clean up old expired promotions (older than 30 days)?')) {
			$this->cleanupOldPromotions();
		}
		
		return self::SUCCESS;
	}
	
	/**
	 * Display promotions in a table format
	 */
	private function displayPromotionsTable($promotions): void
	{
		$headers = ['ID', 'Post Title', 'Type', 'Package', 'End Date', 'Days Overdue'];
		$rows = [];
		
		foreach ($promotions as $promotion) {
			$daysOverdue = now()->diffInDays($promotion->end_date);
			
			$rows[] = [
				$promotion->id,
				str_limit($promotion->post->title ?? 'N/A', 30),
				$promotion->promotion_type_label,
				$promotion->package->name ?? 'N/A',
				$promotion->end_date->format('Y-m-d H:i'),
				$daysOverdue . ' day(s)',
			];
		}
		
		$this->table($headers, $rows);
	}
	
	/**
	 * Clean up old expired promotions
	 */
	private function cleanupOldPromotions(): void
	{
		$cutoffDate = now()->subDays(30);
		
		$oldPromotions = PostPromotion::where('status', 'expired')
			->where('updated_at', '<', $cutoffDate)
			->get();
		
		if ($oldPromotions->isEmpty()) {
			$this->info('No old promotions to clean up.');
			return;
		}
		
		$this->info("Found {$oldPromotions->count()} old promotion(s) to clean up.");
		
		$deletedCount = 0;
		$errorCount = 0;
		
		foreach ($oldPromotions as $promotion) {
			try {
				// Only delete if no associated payments or if payments are also old
				if (!$promotion->payment || $promotion->payment->created_at < $cutoffDate) {
					$promotion->delete();
					$deletedCount++;
				}
			} catch (\Exception $e) {
				$errorCount++;
				$this->error("Failed to delete promotion #{$promotion->id}: {$e->getMessage()}");
			}
		}
		
		$this->info("Cleanup completed:");
		$this->info("- Deleted: {$deletedCount} old promotion(s)");
		
		if ($errorCount > 0) {
			$this->error("- Errors: {$errorCount} promotion(s)");
		}
	}
}