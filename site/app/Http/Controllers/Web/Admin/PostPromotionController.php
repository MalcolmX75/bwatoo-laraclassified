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

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Web\Admin\Panel\PanelController;
use App\Http\Requests\Admin\PostPromotionRequest as StoreRequest;
use App\Http\Requests\Admin\PostPromotionRequest as UpdateRequest;
use App\Models\PostPromotion;
use App\Models\Post;
use App\Models\Package;
use App\Services\PostPromotionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PostPromotionController extends PanelController
{
	protected PostPromotionService $promotionService;
	
	public function __construct()
	{
		parent::__construct();
		$this->promotionService = app(PostPromotionService::class);
	}
	
	public function setup()
	{
		/*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/
		$this->xPanel->setModel(PostPromotion::class);
		$this->xPanel->setRoute(urlGen()->adminUri('post-promotions'));
		$this->xPanel->setEntityNameStrings(trans('admin.post_promotion'), trans('admin.post_promotions'));
		$this->xPanel->enableReorder('start_date', 1);
		$this->xPanel->allowAccess(['reorder']);
		if (!request()->input('order')) {
			$this->xPanel->orderBy('start_date', 'desc');
		}
		
		$this->xPanel->addButtonFromModelFunction('top', 'bulk_activation_button', 'bulkActivationButton', 'end');
		$this->xPanel->addButtonFromModelFunction('top', 'bulk_deactivation_button', 'bulkDeactivationButton', 'end');
		$this->xPanel->addButtonFromModelFunction('top', 'bulk_deletion_button', 'bulkDeletionButton', 'end');
		
		// Filters
		// -----------------------
		$this->xPanel->disableSearchBar();
		// -----------------------
		$this->xPanel->addFilter(
			[
				'name'  => 'promotion_type',
				'type'  => 'dropdown',
				'label' => trans('admin.promotion_type'),
			],
			PostPromotion::getPromotionTypes(),
			function ($value) {
				$this->xPanel->addClause('where', 'promotion_type', $value);
			}
		);
		// -----------------------
		$this->xPanel->addFilter(
			[
				'name'  => 'status',
				'type'  => 'dropdown',
				'label' => trans('admin.Status'),
			],
			PostPromotion::getStatuses(),
			function ($value) {
				$this->xPanel->addClause('where', 'status', $value);
			}
		);
		// -----------------------
		$this->xPanel->addFilter(
			[
				'name'  => 'date_range',
				'type'  => 'date_range',
				'label' => trans('admin.date_range'),
			],
			false,
			function ($value) {
				$dates = json_decode($value);
				$this->xPanel->addClause('where', 'start_date', '>=', $dates->from);
				$this->xPanel->addClause('where', 'end_date', '<=', $dates->to);
			}
		);
		
		/*
		|--------------------------------------------------------------------------
		| COLUMNS AND FIELDS
		|--------------------------------------------------------------------------
		*/
		// COLUMNS
		$this->xPanel->addColumn([
			'name'      => 'id',
			'label'     => '',
			'type'      => 'checkbox',
			'orderable' => false,
		]);
		$this->xPanel->addColumn([
			'name'          => 'post',
			'label'         => trans('admin.Post'),
			'type'          => 'model_function',
			'function_name' => 'getPostTitleHtml',
		]);
		$this->xPanel->addColumn([
			'name'          => 'promotion_type',
			'label'         => trans('admin.promotion_type'),
			'type'          => 'model_function',
			'function_name' => 'getPromotionTypeHtml',
		]);
		$this->xPanel->addColumn([
			'name'          => 'package',
			'label'         => trans('admin.Package'),
			'type'          => 'model_function',
			'function_name' => 'getPackageHtml',
		]);
		$this->xPanel->addColumn([
			'name'  => 'price',
			'label' => trans('admin.Price'),
			'type'  => 'number',
			'suffix' => ' USD',
		]);
		$this->xPanel->addColumn([
			'name'          => 'status',
			'label'         => trans('admin.Status'),
			'type'          => 'model_function',
			'function_name' => 'getStatusHtml',
		]);
		$this->xPanel->addColumn([
			'name'  => 'start_date',
			'label' => trans('admin.start_date'),
			'type'  => 'datetime',
		]);
		$this->xPanel->addColumn([
			'name'  => 'end_date',
			'label' => trans('admin.end_date'),
			'type'  => 'datetime',
		]);
		$this->xPanel->addColumn([
			'name'  => 'views_count',
			'label' => trans('admin.Views'),
			'type'  => 'number',
		]);
		$this->xPanel->addColumn([
			'name'  => 'clicks_count',
			'label' => trans('admin.Clicks'),
			'type'  => 'number',
		]);
		
		// FIELDS
		$this->xPanel->addField([
			'label'     => trans('admin.Post'),
			'name'      => 'post_id',
			'model'     => Post::class,
			'entity'    => 'post',
			'attribute' => 'title',
			'type'      => 'select2',
			'options'   => function ($query) {
				return $query->orderBy('title', 'ASC')->get();
			},
			'wrapper'   => [
				'class' => 'col-md-6',
			],
		]);
		$this->xPanel->addField([
			'label'     => trans('admin.Package'),
			'name'      => 'package_id',
			'model'     => Package::class,
			'entity'    => 'package',
			'attribute' => 'name',
			'type'      => 'select2',
			'options'   => function ($query) {
				return $query->promotion()->orderBy('name', 'ASC')->get();
			},
			'wrapper'   => [
				'class' => 'col-md-6',
			],
		]);
		$this->xPanel->addField([
			'name'        => 'promotion_type',
			'label'       => trans('admin.promotion_type'),
			'type'        => 'select2_from_array',
			'options'     => PostPromotion::getPromotionTypes(),
			'allows_null' => false,
			'wrapper'     => [
				'class' => 'col-md-6',
			],
		]);
		$this->xPanel->addField([
			'name'        => 'status',
			'label'       => trans('admin.Status'),
			'type'        => 'select2_from_array',
			'options'     => PostPromotion::getStatuses(),
			'allows_null' => false,
			'default'     => 'pending',
			'wrapper'     => [
				'class' => 'col-md-6',
			],
		]);
		$this->xPanel->addField([
			'name'       => 'start_date',
			'label'      => trans('admin.start_date'),
			'type'       => 'datetime_picker',
			'default'    => now()->format('Y-m-d H:i:s'),
			'wrapper'    => [
				'class' => 'col-md-6',
			],
		]);
		$this->xPanel->addField([
			'name'       => 'end_date',
			'label'      => trans('admin.end_date'),
			'type'       => 'datetime_picker',
			'wrapper'    => [
				'class' => 'col-md-6',
			],
		]);
		$this->xPanel->addField([
			'name'       => 'price',
			'label'      => trans('admin.Price'),
			'type'       => 'number',
			'attributes' => [
				'placeholder' => trans('admin.Price'),
				'step'        => '0.01',
			],
			'wrapper'    => [
				'class' => 'col-md-6',
			],
		]);
		$this->xPanel->addField([
			'name'       => 'currency_code',
			'label'      => trans('admin.Currency'),
			'type'       => 'text',
			'default'    => 'USD',
			'attributes' => [
				'maxlength' => 3,
			],
			'wrapper'    => [
				'class' => 'col-md-6',
			],
		]);
	}
	
	public function store(StoreRequest $request): RedirectResponse
	{
		// Custom logic for creating promotion
		$data = $request->validated();
		
		try {
			$promotion = $this->promotionService->createPromotion(
				$data['post_id'],
				$data['package_id'],
				$data['promotion_type']
			);
			
			// Update additional fields
			$promotion->update([
				'start_date' => $data['start_date'],
				'end_date' => $data['end_date'],
				'price' => $data['price'],
				'currency_code' => $data['currency_code'],
				'status' => $data['status'],
			]);
			
			alert()->success(trans('admin.promotion_created_successfully'))->flash();
			
		} catch (\Exception $e) {
			alert()->error('Error: ' . $e->getMessage())->flash();
		}
		
		return redirect()->back();
	}
	
	public function update(UpdateRequest $request): RedirectResponse
	{
		return parent::updateCrud($request);
	}
	
	/**
	 * Activate promotion
	 */
	public function activate(Request $request, $id): RedirectResponse
	{
		$promotion = PostPromotion::findOrFail($id);
		
		try {
			$promotion->activate();
			alert()->success(trans('admin.promotion_activated_successfully'))->flash();
		} catch (\Exception $e) {
			alert()->error('Error: ' . $e->getMessage())->flash();
		}
		
		return redirect()->back();
	}
	
	/**
	 * Expire promotion
	 */
	public function expire(Request $request, $id): RedirectResponse
	{
		$promotion = PostPromotion::findOrFail($id);
		
		try {
			$promotion->expire();
			alert()->success(trans('admin.promotion_expired_successfully'))->flash();
		} catch (\Exception $e) {
			alert()->error('Error: ' . $e->getMessage())->flash();
		}
		
		return redirect()->back();
	}
	
	/**
	 * Cancel promotion
	 */
	public function cancel(Request $request, $id): RedirectResponse
	{
		$promotion = PostPromotion::findOrFail($id);
		
		try {
			$this->promotionService->cancelPromotion($id);
			alert()->success(trans('admin.promotion_cancelled_successfully'))->flash();
		} catch (\Exception $e) {
			alert()->error('Error: ' . $e->getMessage())->flash();
		}
		
		return redirect()->back();
	}
	
	/**
	 * Show promotion statistics
	 */
	public function stats(): \Illuminate\View\View
	{
		$stats = $this->promotionService->getGlobalStats();
		
		return view('admin.post-promotions.stats', compact('stats'));
	}
	
	/**
	 * Expire all expired promotions (manual trigger)
	 */
	public function expireExpired(): RedirectResponse
	{
		try {
			$count = $this->promotionService->expireExpiredPromotions();
			alert()->success(trans('admin.expired_promotions_processed', ['count' => $count]))->flash();
		} catch (\Exception $e) {
			alert()->error('Error: ' . $e->getMessage())->flash();
		}
		
		return redirect()->back();
	}
}