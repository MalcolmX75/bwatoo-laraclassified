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

use App\Models\Payment;
use App\Models\Post;
use App\Models\User;
use App\Notifications\PaymentApproved;
use App\Notifications\SubscriptionApproved;
use App\Observers\Traits\PaymentTrait;
use Throwable;

class PaymentObserver extends BaseObserver
{
	use PaymentTrait;
	
	/**
	 * Listen to the Entry updating event.
	 *
	 * @param Payment $payment
	 * @return void
	 */
	public function updating(Payment $payment)
	{
		// Get the original object values
		$original = $payment->getOriginal();
		
		$isPromoting = (str_ends_with($payment->payable_type, 'Post'));
		$isSubscripting = (str_ends_with($payment->payable_type, 'User'));
		
		// The Payment was not approved
		if ($original['active'] != 1) {
			if ($payment->active == 1) {
				$payable = null;
				if ($isPromoting) {
					$payable = Post::find($payment->payable_id);
				}
				if ($isSubscripting) {
					$payable = User::find($payment->payable_id);
				}
				if (!empty($payable)) {
					try {
						if ($isPromoting) {
							$payable->notify(new PaymentApproved($payment, $payable));
						}
						if ($isSubscripting) {
							$payable->notify(new SubscriptionApproved($payment, $payable));
						}
					} catch (Throwable $e) {
						if (!isFromApi()) {
							flash($e->getMessage())->error();
						}
					}
				}
			}
		}
	}
	
	/**
	 * Listen to the Entry saved event.
	 *
	 * @param Payment $payment
	 * @return void
	 */
	public function saved(Payment $payment)
	{
		// Removing Entries from the Cache
		$this->clearCache($payment);
	}
	
	/**
	 * Listen to the Entry deleting event.
	 *
	 * @param Payment $payment
	 * @return void
	 */
	public function deleting(Payment $payment)
	{
		$isPromoting = (str_ends_with($payment->payable_type, 'Post'));
		$isSubscripting = (str_ends_with($payment->payable_type, 'User'));
		
		// Un-feature the payment's payable (Post|User) if it does not have other payments
		$postOtherPayments = Payment::query()
			->where('payable_type', $payment->payable_type)
			->where('payable_id', $payment->payable_id);
		
		if ($postOtherPayments->count() <= 0) {
			$payable = null;
			if ($isPromoting) {
				$payable = Post::find($payment->payable_id);
			}
			if ($isSubscripting) {
				$payable = User::find($payment->payable_id);
			}
			if (!empty($payable)) {
				$payable->featured = 0;
				$payable->save();
			}
		}
	}
	
	/**
	 * Listen to the Entry deleted event.
	 *
	 * @param Payment $payment
	 * @return void
	 */
	public function deleted(Payment $payment)
	{
		// Removing Entries from the Cache
		$this->clearCache($payment);
	}
	
	/**
	 * Removing the Entity's Entries from the Cache
	 *
	 * @param $payment
	 * @return void
	 */
	private function clearCache($payment): void
	{
		if (empty($payment->payable_type)) {
			return;
		}
		
		$this->clearCacheRelatedToPromotionPayment($payment);
		$this->clearCacheRelatedToSubscriptionPayment($payment);
	}
}
