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
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PostPromotionPaymentService extends BaseService
{
	protected PostPromotionService $promotionService;
	protected PaymentService $paymentService;
	
	public function __construct()
	{
		$this->promotionService = app(PostPromotionService::class);
		$this->paymentService = app(PaymentService::class);
	}
	
	/**
	 * Process promotion payment workflow
	 */
	public function processPromotionPayment(
		int $postId,
		int $packageId,
		string $promotionType,
		array $paymentData = []
	): array {
		$post = Post::findOrFail($postId);
		$package = Package::promotion()->findOrFail($packageId);
		
		DB::beginTransaction();
		
		try {
			// Step 1: Create pending promotion
			$promotion = $this->promotionService->createPromotion(
				$postId,
				$packageId,
				$promotionType
			);
			
			// Step 2: Create payment if package has cost
			$payment = null;
			if ($package->price > 0) {
				$payment = $this->createPromotionPayment($post, $package, $promotion, $paymentData);
				
				// Link payment to promotion
				$promotion->update(['payment_id' => $payment->id]);
			} else {
				// Free promotion - activate immediately
				$promotion->activate();
			}
			
			DB::commit();
			
			return [
				'success' => true,
				'promotion' => $promotion,
				'payment' => $payment,
				'requires_payment' => $package->price > 0,
				'payment_url' => $payment ? $this->getPaymentUrl($payment) : null,
			];
			
		} catch (\Exception $e) {
			DB::rollBack();
			
			return [
				'success' => false,
				'error' => $e->getMessage(),
				'promotion' => null,
				'payment' => null,
			];
		}
	}
	
	/**
	 * Create payment for promotion
	 */
	private function createPromotionPayment(
		Post $post,
		Package $package,
		PostPromotion $promotion,
		array $paymentData = []
	): Payment {
		$paymentData = array_merge([
			'country_code' => $post->country_code,
			'package_id' => $package->id,
			'payable_id' => $promotion->id,
			'payable_type' => PostPromotion::class,
			'period_start' => $promotion->start_date,
			'period_end' => $promotion->end_date,
			'amount' => $package->price,
			'currency_code' => $package->currency_code,
			'payment_method_id' => null, // Will be set by payment gateway
			'transaction_id' => null, // Will be set by payment gateway
			'active' => 0, // Will be activated after successful payment
		], $paymentData);
		
		return Payment::create($paymentData);
	}
	
	/**
	 * Handle successful payment callback
	 */
	public function handleSuccessfulPayment(int $paymentId): bool
	{
		$payment = Payment::findOrFail($paymentId);
		
		if ($payment->payable_type !== PostPromotion::class) {
			throw new \InvalidArgumentException('Payment is not for a post promotion');
		}
		
		$promotion = $payment->payable;
		
		if (!$promotion) {
			throw new \RuntimeException('Promotion not found for payment');
		}
		
		DB::beginTransaction();
		
		try {
			// Activate payment
			$payment->update(['active' => 1]);
			
			// Activate promotion
			$this->promotionService->activatePromotion($promotion->id, $payment->id);
			
			DB::commit();
			
			return true;
			
		} catch (\Exception $e) {
			DB::rollBack();
			throw $e;
		}
	}
	
	/**
	 * Handle failed payment callback
	 */
	public function handleFailedPayment(int $paymentId, string $reason = ''): bool
	{
		$payment = Payment::findOrFail($paymentId);
		
		if ($payment->payable_type !== PostPromotion::class) {
			throw new \InvalidArgumentException('Payment is not for a post promotion');
		}
		
		$promotion = $payment->payable;
		
		if (!$promotion) {
			throw new \RuntimeException('Promotion not found for payment');
		}
		
		DB::beginTransaction();
		
		try {
			// Mark payment as failed
			$payment->update([
				'active' => 0,
				'notes' => $reason,
			]);
			
			// Cancel promotion
			$this->promotionService->cancelPromotion($promotion->id);
			
			DB::commit();
			
			return true;
			
		} catch (\Exception $e) {
			DB::rollBack();
			throw $e;
		}
	}
	
	/**
	 * Process refund for cancelled promotion
	 */
	public function processRefund(int $promotionId, bool $partial = false, float $refundAmount = null): array
	{
		$promotion = PostPromotion::findOrFail($promotionId);
		$payment = $promotion->payment;
		
		if (!$payment) {
			return [
				'success' => false,
				'error' => 'No payment found for this promotion',
			];
		}
		
		if (!$payment->active) {
			return [
				'success' => false,
				'error' => 'Payment is not active',
			];
		}
		
		$refundAmount = $refundAmount ?? $payment->amount;
		
		if ($partial && $refundAmount >= $payment->amount) {
			return [
				'success' => false,
				'error' => 'Partial refund amount cannot be greater than or equal to payment amount',
			];
		}
		
		try {
			// Process refund through payment gateway
			$refundResult = $this->processPaymentGatewayRefund($payment, $refundAmount);
			
			if ($refundResult['success']) {
				// Update payment record
				$payment->update([
					'active' => $partial ? 1 : 0,
					'notes' => ($payment->notes ?? '') . "\nRefund: {$refundAmount} {$payment->currency_code}",
				]);
				
				// Cancel promotion if full refund
				if (!$partial) {
					$this->promotionService->cancelPromotion($promotionId);
				}
				
				return [
					'success' => true,
					'refund_amount' => $refundAmount,
					'transaction_id' => $refundResult['transaction_id'] ?? null,
				];
			}
			
			return $refundResult;
			
		} catch (\Exception $e) {
			return [
				'success' => false,
				'error' => $e->getMessage(),
			];
		}
	}
	
	/**
	 * Get payment URL for gateway redirection
	 */
	private function getPaymentUrl(Payment $payment): ?string
	{
		// This would integrate with your payment gateway
		// Return the URL where user should be redirected to complete payment
		return route('payment.process', ['payment' => $payment->id]);
	}
	
	/**
	 * Process refund through payment gateway
	 */
	private function processPaymentGatewayRefund(Payment $payment, float $amount): array
	{
		// This would integrate with your payment gateway's refund API
		// For now, return a mock success response
		return [
			'success' => true,
			'transaction_id' => 'refund_' . uniqid(),
			'amount' => $amount,
		];
	}
	
	/**
	 * Calculate promotion pricing with potential discounts
	 */
	public function calculatePromotionPricing(
		int $packageId,
		string $promotionType,
		?int $userId = null,
		array $options = []
	): array {
		$package = Package::promotion()->findOrFail($packageId);
		$basePrice = $package->price;
		$discounts = [];
		$finalPrice = $basePrice;
		
		// Apply user-specific discounts
		if ($userId) {
			$user = User::find($userId);
			if ($user) {
				// Example: Premium users get 10% discount
				if ($user->isPremium()) {
					$discounts[] = [
						'type' => 'premium_user',
						'description' => 'Premium user discount',
						'percentage' => 10,
						'amount' => $basePrice * 0.1,
					];
					$finalPrice -= $basePrice * 0.1;
				}
			}
		}
		
		// Apply bulk promotion discounts
		if (isset($options['quantity']) && $options['quantity'] > 1) {
			$bulkDiscount = min(20, $options['quantity'] * 2); // Max 20% discount
			$discounts[] = [
				'type' => 'bulk_promotion',
				'description' => 'Bulk promotion discount',
				'percentage' => $bulkDiscount,
				'amount' => $basePrice * ($bulkDiscount / 100),
			];
			$finalPrice -= $basePrice * ($bulkDiscount / 100);
		}
		
		// Apply seasonal/promotional discounts
		if (isset($options['promo_code'])) {
			// Handle promo codes here
		}
		
		$finalPrice = max(0, $finalPrice); // Ensure price doesn't go negative
		
		return [
			'base_price' => $basePrice,
			'discounts' => $discounts,
			'total_discount' => array_sum(array_column($discounts, 'amount')),
			'final_price' => $finalPrice,
			'currency_code' => $package->currency_code,
			'savings' => $basePrice - $finalPrice,
		];
	}
}