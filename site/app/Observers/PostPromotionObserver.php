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

namespace App\Observers;

use App\Models\PostPromotion;

class PostPromotionObserver extends BaseObserver
{
	/**
	 * Listen to the PostPromotion created event.
	 */
	public function created(PostPromotion $postPromotion): void
	{
		// Clear cache when a new promotion is created
		$this->clearCache($postPromotion);
		
		// Auto-activate promotion if payment is confirmed
		if ($postPromotion->payment && $postPromotion->payment->isVerified()) {
			$postPromotion->activate();
		}
	}
	
	/**
	 * Listen to the PostPromotion updated event.
	 */
	public function updated(PostPromotion $postPromotion): void
	{
		// Clear cache when promotion is updated
		$this->clearCache($postPromotion);
		
		// Handle status changes
		if ($postPromotion->wasChanged('status')) {
			$this->handleStatusChange($postPromotion);
		}
	}
	
	/**
	 * Listen to the PostPromotion deleted event.
	 */
	public function deleted(PostPromotion $postPromotion): void
	{
		// Clear cache when promotion is deleted
		$this->clearCache($postPromotion);
	}
	
	/**
	 * Clear related cache
	 */
	private function clearCache(PostPromotion $postPromotion): void
	{
		try {
			// Clear post-related cache
			if ($postPromotion->post_id) {
				$cacheIds = [
					'posts.show.' . $postPromotion->post_id,
					'posts.promotions.' . $postPromotion->post_id,
					'posts.similar.' . $postPromotion->post_id,
				];
				
				foreach ($cacheIds as $cacheId) {
					cache()->forget($cacheId);
				}
			}
			
			// Clear listing pages cache
			$cacheIds = [
				'posts.home',
				'posts.featured',
				'posts.top',
				'posts.urgent',
				'search.results',
			];
			
			foreach ($cacheIds as $cacheId) {
				cache()->forget($cacheId);
			}
			
		} catch (\Exception $e) {
			// Silently handle cache clearing errors
		}
	}
	
	/**
	 * Handle promotion status changes
	 */
	private function handleStatusChange(PostPromotion $postPromotion): void
	{
		// Log status changes for tracking
		try {
			if ($postPromotion->status === 'active') {
				// Promotion activated - could trigger notifications
				$this->onPromotionActivated($postPromotion);
			} elseif ($postPromotion->status === 'expired') {
				// Promotion expired - could trigger renewal notifications
				$this->onPromotionExpired($postPromotion);
			}
		} catch (\Exception $e) {
			// Handle errors silently to avoid breaking the main flow
		}
	}
	
	/**
	 * Handle promotion activation
	 */
	private function onPromotionActivated(PostPromotion $postPromotion): void
	{
		// Could send notification to post owner
		// Could log analytics event
		// Could trigger related business logic
	}
	
	/**
	 * Handle promotion expiration
	 */
	private function onPromotionExpired(PostPromotion $postPromotion): void
	{
		// Could send renewal reminder to post owner
		// Could log analytics event
		// Could clean up related data
	}
}