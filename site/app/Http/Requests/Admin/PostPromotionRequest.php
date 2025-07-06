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

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use App\Models\PostPromotion;

class PostPromotionRequest extends Request
{
	/**
	 * Get the validation rules that apply to the request.
	 */
	public function rules(): array
	{
		$rules = [
			'post_id' => [
				'required',
				'integer',
				'exists:posts,id',
			],
			'package_id' => [
				'required',
				'integer',
				'exists:packages,id',
			],
			'promotion_type' => [
				'required',
				'string',
				'in:' . implode(',', array_keys(PostPromotion::PROMOTION_TYPES)),
			],
			'status' => [
				'required',
				'string',
				'in:' . implode(',', array_keys(PostPromotion::STATUSES)),
			],
			'start_date' => [
				'required',
				'date',
				'after_or_equal:today',
			],
			'end_date' => [
				'required',
				'date',
				'after:start_date',
			],
			'price' => [
				'required',
				'numeric',
				'min:0',
				'max:999999.99',
			],
			'currency_code' => [
				'required',
				'string',
				'size:3',
				'exists:currencies,code',
			],
			'payment_id' => [
				'nullable',
				'integer',
				'exists:payments,id',
			],
		];
		
		// Additional validation for updates
		if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
			// Allow start_date to be in the past for existing promotions
			$rules['start_date'] = [
				'required',
				'date',
			];
		}
		
		return $rules;
	}
	
	/**
	 * Get the validation attributes that apply to the request.
	 */
	public function attributes(): array
	{
		return [
			'post_id' => trans('admin.Post'),
			'package_id' => trans('admin.Package'),
			'promotion_type' => trans('admin.promotion_type'),
			'status' => trans('admin.Status'),
			'start_date' => trans('admin.start_date'),
			'end_date' => trans('admin.end_date'),
			'price' => trans('admin.Price'),
			'currency_code' => trans('admin.Currency'),
			'payment_id' => trans('admin.Payment'),
		];
	}
	
	/**
	 * Get the validation messages that apply to the request.
	 */
	public function messages(): array
	{
		return [
			'post_id.exists' => trans('admin.post_not_found'),
			'package_id.exists' => trans('admin.package_not_found'),
			'promotion_type.in' => trans('admin.invalid_promotion_type'),
			'status.in' => trans('admin.invalid_status'),
			'start_date.after_or_equal' => trans('admin.start_date_must_be_today_or_future'),
			'end_date.after' => trans('admin.end_date_must_be_after_start_date'),
			'currency_code.size' => trans('admin.currency_code_must_be_3_characters'),
			'currency_code.exists' => trans('admin.currency_not_found'),
			'payment_id.exists' => trans('admin.payment_not_found'),
		];
	}
	
	/**
	 * Configure the validator instance.
	 */
	public function withValidator($validator): void
	{
		$validator->after(function ($validator) {
			// Check if post already has active promotion of the same type
			if ($this->post_id && $this->promotion_type && $this->status === 'active') {
				$existingPromotion = PostPromotion::where('post_id', $this->post_id)
					->where('promotion_type', $this->promotion_type)
					->where('status', 'active')
					->where('end_date', '>=', now());
				
				// Exclude current promotion when updating
				if ($this->route('post_promotion')) {
					$existingPromotion->where('id', '!=', $this->route('post_promotion'));
				}
				
				if ($existingPromotion->exists()) {
					$validator->errors()->add(
						'promotion_type',
						trans('admin.post_already_has_active_promotion_of_this_type')
					);
				}
			}
			
			// Validate package type is promotion
			if ($this->package_id) {
				$package = \App\Models\Package::find($this->package_id);
				if ($package && $package->type !== 'promotion') {
					$validator->errors()->add(
						'package_id',
						trans('admin.package_must_be_promotion_type')
					);
				}
			}
			
			// Validate end date is reasonable (not more than 1 year)
			if ($this->start_date && $this->end_date) {
				$startDate = \Carbon\Carbon::parse($this->start_date);
				$endDate = \Carbon\Carbon::parse($this->end_date);
				
				if ($endDate->diffInDays($startDate) > 365) {
					$validator->errors()->add(
						'end_date',
						trans('admin.promotion_duration_cannot_exceed_one_year')
					);
				}
			}
		});
	}
}