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

namespace App\Models\Traits;

use App\Helpers\Common\Date;
use App\Models\Permission;
use Illuminate\Support\Carbon;

trait UserTrait
{
	// ===| ADMIN PANEL METHODS |===
	
	public function getNameHtml(): string
	{
		// Get the user's possible payment info
		$paymentInfo = '';
		if (!empty($this->payment)) {
			$info = ' (' . $this->payment->expiry_info . ')';
			$class = 'text-' . $this->payment->css_class_variant;
			$packageName = $this->payment->package?->short_name ?? t('unknown_package');
			
			$paymentInfo = ' <i class="fa-solid fa-circle-check ' . $class . '"
                    data-bs-placement="bottom" data-bs-toggle="tooltip"
                    type="button" title="' . $packageName . $info . '">
                </i>';
		}
		
		$noName = 'No Name';
		$name = $this->name ?? $noName;
		if (!empty($this->username)) {
			$title = trans('auth.username') . ': ' . $this->username;
			$name = '<span data-bs-toggle="tooltip" title="' . $title . '">';
			$name .= $this->name ?? $noName;
			$name .= '</span>';
		}
		
		return $name . $paymentInfo;
	}
	
	public function getEmailHtml(): string
	{
		$email = !empty($this->email) ? $this->email : null;
		
		$out = $email ?? '-';
		$out = '<span class="float-start">' . $out . '</span>';
		
		$authField = !empty($this->auth_field) ? $this->auth_field : getAuthField();
		if ($authField == 'email') {
			$infoIcon = trans('auth.notifications_channel') . ' (' . trans('settings.mail') . ')';
			$out .= '<span class="float-end d-inline-block">';
			$out .= ' <i class="bi bi-bell" data-bs-toggle="tooltip" title="' . $infoIcon . '"></i>';
			$out .= '</div>';
		}
		
		return $out;
	}
	
	public function getPhoneHtml(): string
	{
		$out = '';
		
		$country = $this->country ?? null;
		$countryCode = $country->code ?? $this->country_code ?? null;
		$countryName = $country->name ?? $countryCode;
		
		$phoneCountry = $this->phone_country ?? $countryCode;
		$phone = !empty($this->phone) ? $this->phone : null;
		$phoneCountryFlagUrl = getCountryFlagUrl($phoneCountry);
		
		if (!empty($phoneCountryFlagUrl)) {
			if (!empty($phone)) {
				$out .= '<img src="' . $phoneCountryFlagUrl . '" data-bs-toggle="tooltip" title="' . $countryName . '">';
				$out .= '&nbsp;';
				$out .= $phone;
			} else {
				$out .= '-';
			}
		} else {
			$out .= $phone ?? '-';
		}
		$out = '<span class="float-start">' . $out . '</span>';
		
		$authField = !empty($this->auth_field) ? $this->auth_field : getAuthField();
		if ($authField == 'phone') {
			$infoIcon = trans('auth.notifications_channel') . ' (' . trans('settings.sms') . ')';
			$out .= '<span class="float-end d-inline-block">';
			$out .= ' <i class="bi bi-bell" data-bs-toggle="tooltip" title="' . $infoIcon . '"></i>';
			$out .= '</div>';
		}
		
		return $out;
	}
	
	public function getFeaturedHtml(): string
	{
		$out = '-';
		if (config('plugins.offlinepayment.installed')) {
			$opTool = '\extras\plugins\offlinepayment\app\Helpers\OpTools';
			if (class_exists($opTool)) {
				$out = $opTool::featuredCheckboxDisplay(
					$this->{$this->primaryKey},
					$this->getTable(),
					'featured',
					($this->featured ?? null)
				);
			}
		}
		
		return $out;
	}
	
	public function impersonateButton($xPanel = false): string
	{
		$out = '';
		
		// Get all the User's attributes
		$user = self::findOrFail($this->getKey());
		
		// Get impersonate URL
		$impersonateUrl = dmUrl($this->country_code, 'impersonate/take/' . $this->getKey(), false, false);
		
		// If the Domain Mapping plugin is installed,
		// Then, the impersonate feature need to be disabled
		if (config('plugins.domainmapping.installed')) {
			return $out;
		}
		
		// Generate the impersonate link
		if ($user->getKey() == auth()->user()->getAuthIdentifier()) {
			$tooltip = '" data-bs-toggle="tooltip" title="' . t('Cannot impersonate yourself') . '"';
			$out .= '<a class="btn btn-xs btn-warning" ' . $tooltip . '><i class="fa-solid fa-lock"></i></a>';
		} else if ($user->can(Permission::getStaffPermissions())) {
			$tooltip = '" data-bs-toggle="tooltip" title="' . t('Cannot impersonate admin users') . '"';
			$out .= '<a class="btn btn-xs btn-warning" ' . $tooltip . '><i class="fa-solid fa-lock"></i></a>';
		} else if (!isVerifiedUser($user)) {
			$tooltip = '" data-bs-toggle="tooltip" title="' . t('Cannot impersonate unactivated users') . '"';
			$out .= '<a class="btn btn-xs btn-warning" ' . $tooltip . '><i class="fa-solid fa-lock"></i></a>';
		} else {
			$tooltip = '" data-bs-toggle="tooltip" title="' . t('Impersonate this user') . '"';
			$out .= '<a class="btn btn-xs btn-light" href="' . $impersonateUrl . '" ' . $tooltip . '><i class="fa-solid fa-right-to-bracket"></i></a>';
		}
		
		return $out;
	}
	
	public function deleteButton($xPanel = false): string
	{
		$out = '';
		
		if (auth()->check()) {
			if ($this->id == auth()->user()->id) {
				return $out;
			}
			if (isDemoDomain() && $this->id == 1) {
				return $out;
			}
		}
		
		$url = urlGen()->adminUrl('users/' . $this->id);
		
		$out .= '<a href="' . $url . '" class="btn btn-xs btn-danger" data-button-type="delete">';
		$out .= '<i class="fa-regular fa-trash-can"></i> ';
		$out .= trans('admin.delete');
		$out .= '</a>';
		
		return $out;
	}
	
	// ===| OTHER METHODS |===
	
	/**
	 * Get the user's preferred locale.
	 *
	 * @return string
	 */
	public function preferredLocale()
	{
		return $this->language_code ?? 'en';
	}
	
	public function canImpersonate(): bool
	{
		// Cannot impersonate from Demo website,
		// Non admin users cannot impersonate
		if (isDemoDomain() || !$this->can(Permission::getStaffPermissions())) {
			return false;
		}
		
		return true;
	}
	
	public function canBeImpersonated(): bool
	{
		$canBeImpersonated = $this->can_be_impersonated ?? null;
		
		// Admin users cannot be impersonated
		// Users with the 'can_be_impersonated' attribute != 1 cannot be impersonated
		// Cannot be impersonated from Demo website
		if ($this->can(Permission::getStaffPermissions()) || $canBeImpersonated != 1 || isDemoDomain()) {
			return false;
		}
		
		return true;
	}
	
	public function isOnline(): bool
	{
		$timezone = Date::getAppTimeZone();
		
		$lastActivity = $this->last_activity ?? now($timezone);
		$isOnline = ($lastActivity > Carbon::now($timezone)->subMinutes(5));
		
		// Allow only logged users to get the other users status
		return auth()->check() ? $isOnline : false;
	}
}
