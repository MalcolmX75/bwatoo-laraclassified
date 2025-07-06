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

namespace App\Services;

use App\Models\Post;
use App\Models\PostPromotion;
use App\Models\Package;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class PostPromotionService extends BaseService
{
	/**
	 * Create a new promotion for a post
	 */
	public function createPromotion(
		int $postId,
		int $packageId,
		string $promotionType,
		?int $paymentId = null
	): PostPromotion {
		$package = Package::findOrFail($packageId);
		$post = Post::findOrFail($postId);
		
		// Calculate promotion dates
		$startDate = now();
		$endDate = $this->calculateEndDate($package, $promotionType);
		
		return PostPromotion::create([
			'post_id' => $postId,
			'package_id' => $packageId,
			'payment_id' => $paymentId,
			'promotion_type' => $promotionType,
			'start_date' => $startDate,
			'end_date' => $endDate,
			'price' => $package->price,
			'currency_code' => $package->currency_code,
			'status' => $paymentId ? 'active' : 'pending',
		]);
	}
	
	/**
	 * Activate a promotion after payment
	 */
	public function activatePromotion(int $promotionId, int $paymentId): bool
	{
		$promotion = PostPromotion::findOrFail($promotionId);
		
		$promotion->update([
			'payment_id' => $paymentId,
			'status' => 'active',
			'start_date' => now(),
		]);
		
		return true;
	}
	
	/**
	 * Bump a post (move to top of listings)
	 */
	public function bumpPost(int $postId, int $packageId, ?int $paymentId = null): PostPromotion
	{
		// Check if post already has active bump
		$this->expireActivePromotions($postId, 'bump');
		
		return $this->createPromotion($postId, $packageId, 'bump', $paymentId);
	}
	
	/**
	 * Make post a top ad
	 */
	public function makeTopAd(int $postId, int $packageId, ?int $paymentId = null): PostPromotion
	{
		// Check if post already has active top promotion
		$this->expireActivePromotions($postId, 'top');
		
		return $this->createPromotion($postId, $packageId, 'top', $paymentId);
	}
	
	/**
	 * Feature a post
	 */
	public function featurePost(int $postId, int $packageId, ?int $paymentId = null): PostPromotion
	{
		// Check if post already has active featured promotion
		$this->expireActivePromotions($postId, 'featured');
		
		return $this->createPromotion($postId, $packageId, 'featured', $paymentId);
	}
	
	/**
	 * Mark post as urgent
	 */
	public function markUrgent(int $postId, int $packageId, ?int $paymentId = null): PostPromotion
	{
		// Check if post already has active urgent promotion
		$this->expireActivePromotions($postId, 'urgent');
		
		return $this->createPromotion($postId, $packageId, 'urgent', $paymentId);
	}
	
	/**
	 * Highlight a post
	 */
	public function highlightPost(int $postId, int $packageId, ?int $paymentId = null): PostPromotion
	{
		// Check if post already has active highlight promotion
		$this->expireActivePromotions($postId, 'highlight');
		
		return $this->createPromotion($postId, $packageId, 'highlight', $paymentId);
	}
	
	/**
	 * Get active promotions for a post
	 */
	public function getActivePromotions(int $postId): Collection
	{
		return PostPromotion::with(['package', 'payment'])
			->byPost($postId)
			->active()
			->get();
	}
	
	/**
	 * Get active promotions by type
	 */
	public function getActivePromotionsByType(string $type): Collection
	{
		return PostPromotion::with(['post', 'package'])
			->byType($type)
			->active()
			->get();
	}
	
	/**
	 * Check if post has active promotion of specific type
	 */
	public function hasActivePromotion(int $postId, string $type): bool
	{
		return PostPromotion::byPost($postId)
			->byType($type)
			->active()
			->exists();
	}
	
	/**
	 * Expire all active promotions of a specific type for a post
	 */
	public function expireActivePromotions(int $postId, string $type): int
	{
		return PostPromotion::byPost($postId)
			->byType($type)
			->active()
			->update([
				'status' => 'expired',
				'end_date' => now(),
			]);
	}
	
	/**
	 * Expire all expired promotions (cron job)
	 */
	public function expireExpiredPromotions(): int
	{
		return PostPromotion::where('status', 'active')
			->where('end_date', '<', now())
			->update(['status' => 'expired']);
	}
	
	/**
	 * Get promotion statistics for a post
	 */
	public function getPromotionStats(int $postId): array
	{
		$promotions = PostPromotion::byPost($postId)->get();
		
		return [
			'total_promotions' => $promotions->count(),
			'active_promotions' => $promotions->where('status', 'active')->count(),
			'total_views' => $promotions->sum('views_count'),
			'total_clicks' => $promotions->sum('clicks_count'),
			'total_spent' => $promotions->sum('price'),
			'by_type' => $promotions->groupBy('promotion_type')->map(function ($group) {
				return [
					'count' => $group->count(),
					'views' => $group->sum('views_count'),
					'clicks' => $group->sum('clicks_count'),
					'spent' => $group->sum('price'),
				];
			}),
		];
	}
	
	/**
	 * Get global promotion statistics
	 */
	public function getGlobalStats(): array
	{
		return [
			'total_promotions' => PostPromotion::count(),
			'active_promotions' => PostPromotion::where('status', 'active')->count(),
			'expired_promotions' => PostPromotion::where('status', 'expired')->count(),
			'total_revenue' => PostPromotion::where('status', '!=', 'cancelled')->sum('price'),
			'by_type' => PostPromotion::select('promotion_type')
				->selectRaw('COUNT(*) as count')
				->selectRaw('SUM(views_count) as total_views')
				->selectRaw('SUM(clicks_count) as total_clicks')
				->selectRaw('SUM(price) as total_revenue')
				->groupBy('promotion_type')
				->get()
				->keyBy('promotion_type'),
		];
	}
	
	/**
	 * Get posts with active promotions for homepage/category display
	 */
	public function getPromotedPosts(string $type, int $limit = 10): Collection
	{
		$postIds = PostPromotion::byType($type)
			->active()
			->pluck('post_id')
			->unique()
			->take($limit);
			
		return Post::whereIn('id', $postIds)
			->with(['promotions' => function ($query) use ($type) {
				$query->byType($type)->active();
			}])
			->orderByDesc('created_at')
			->get();
	}
	
	/**
	 * Calculate end date for promotion based on package and type
	 */
	private function calculateEndDate(Package $package, string $promotionType): Carbon
	{
		$days = $package->promotion_time ?? 30;
		
		// Special rules for different promotion types
		switch ($promotionType) {
			case 'bump':
				// Bump promotions typically last shorter
				$days = min($days, 7);
				break;
			case 'top':
				// Top ads can have the full duration
				break;
			case 'featured':
				// Featured can have the full duration
				break;
			case 'urgent':
				// Urgent typically shorter duration
				$days = min($days, 14);
				break;
			case 'highlight':
				// Highlight can have the full duration
				break;
		}
		
		return now()->addDays($days);
	}
	
	/**
	 * Renew an expired promotion
	 */
	public function renewPromotion(int $promotionId, int $packageId, ?int $paymentId = null): PostPromotion
	{
		$oldPromotion = PostPromotion::findOrFail($promotionId);
		$package = Package::findOrFail($packageId);
		
		// Create new promotion with same settings
		return $this->createPromotion(
			$oldPromotion->post_id,
			$packageId,
			$oldPromotion->promotion_type,
			$paymentId
		);
	}
	
	/**
	 * Cancel a promotion and issue refund if applicable
	 */
	public function cancelPromotion(int $promotionId, bool $issueRefund = false): bool
	{
		$promotion = PostPromotion::findOrFail($promotionId);
		
		$promotion->update(['status' => 'cancelled']);
		
		if ($issueRefund && $promotion->payment) {
			// Handle refund logic here
			// This would integrate with payment gateway
		}
		
		return true;
	}
}