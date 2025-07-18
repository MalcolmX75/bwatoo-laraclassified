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

namespace App\Enums;

/**
 * // Get button primary color, property, and value
 * $buttonClass = BootstrapColor::Button->getColorClass('primary');
 * // Returns: 'btn-primary'
 * $buttonProperty = BootstrapColor::Button->getColorProperty();
 * // Returns: 'background-color'
 * $buttonValue = BootstrapColor::Button->getColorValue('primary');
 * // Returns: 'var(--bs-primary)!important'
 *
 * // Get outline button primary color, property, and value
 * $outlineClass = BootstrapColor::Button->getColorClass('primary', true);
 * // Returns: 'btn-outline-primary'
 * $outlineProperty = BootstrapColor::Button->getColorProperty(true);
 * // Returns: 'border-color'
 * $outlineValue = BootstrapColor::Button->getColorValue('primary');
 * // Returns: 'var(--bs-primary)!important'
 *
 * // Get invalid color with default
 * $invalidClass = BootstrapColor::Button->getColorClass('invalid', false, 'secondary');
 * // Returns: 'btn-secondary'
 * $invalidValue = BootstrapColor::Button->getColorValue('invalid', 'secondary');
 * // Returns: 'var(--bs-secondary)!important'
 *
 * // Get null color with no default
 * $nullClass = BootstrapColor::Button->getColorClass(null);
 * // Returns: 'btn-primary'
 * $nullValue = BootstrapColor::Button->getColorValue(null);
 * // Returns: 'var(--bs-primary)!important'
 */
enum BootstrapColor: string
{
	case Button = 'button';
	case Badge = 'badge';
	case Alert = 'alert';
	case Background = 'bg';
	case Text = 'text';
	case Link = 'link';
	case Border = 'border';
	
	/**
	 * Get the base class for the component
	 */
	public function base(): string
	{
		return match ($this) {
			self::Button => 'btn',
			self::Badge  => 'badge',
			self::Alert  => 'alert',
			self::Background,
			self::Text,
			self::Link   => '',
			self::Border => 'border',
		};
	}
	
	/**
	 * Get the alternative base class for the component
	 */
	public function altBase(): string
	{
		return match ($this) {
			self::Button => 'btn',
			self::Badge  => 'badge rounded-pill',
			self::Alert  => 'alert',
			self::Background,
			self::Text   => '',
			self::Link   => 'text-decoration-none link-opacity-75-hover',
			self::Border => 'border',
		};
	}
	
	/**
	 * Get the CSS property for the component
	 *
	 * @param bool $outline
	 * @return string
	 */
	public function property(bool $outline = false): string
	{
		if ($this === self::Button && $outline) {
			return 'border-color';
		}
		
		return match ($this) {
			self::Button,
			self::Badge,
			self::Alert,
			self::Background => 'background-color',
			self::Text,
			self::Link       => 'color',
			self::Border     => 'border-color',
		};
	}
	
	/**
	 * Get the color classes for the component
	 *
	 * @return array<string, array{class: string, value: string}>
	 */
	public function colors(): array
	{
		$bsColors = getCachedReferrerList('bootstrap-css');
		
		if (!isset($bsColors[$this->value])) {
			return [];
		}
		
		$color = $bsColors[$this->value] ?? [];
		
		// Use the custom primary color value
		$skinsColors = collect(getCachedReferrerList('skins'))
			->mapWithKeys(fn ($item, $key) => [$key => $item['color']])
			->toArray();
		$selectedSkin = getFrontSkin();
		$primaryColor = $skinsColors[$selectedSkin] ?? null;
		$primaryColor = !empty($primaryColor) ? "{$primaryColor}!important" : null;
		if (!empty($primaryColor)) {
			$color['primary']['value'] = $primaryColor;
		}
		
		return $color;
	}
	
	/**
	 * Get a specific color class for the component, with a default if not found
	 *
	 * @param string|null $color
	 * @param bool $outline Whether to use outline style (only for Button component)
	 * @param string|null $default Optional default class to return if color is not found
	 * @return string
	 */
	public function getColorClass(?string $color, bool $outline = false, ?string $default = null): string
	{
		$colors = $this->colors();
		$colorClass = '';
		
		if (!empty($color) && isset($colors[$color])) {
			$colorClass = $colors[$color]['class'];
		}
		
		if (empty($colorClass)) {
			if (!empty($default) && isset($colors[$default])) {
				$colorClass = $colors[$default]['class'];
			}
		}
		
		if (empty($colorClass)) {
			$firstColor = reset($colors);
			$colorClass = $firstColor ? $firstColor['class'] : '';
		}
		
		if (!empty($colorClass)) {
			if ($this === self::Button && $outline && $color !== 'link') {
				// Handle outline buttons (except for 'link')
				$outlineClass = str_replace('btn-', 'btn-outline-', $colorClass);
				if ($outlineClass !== 'btn-outline-') {
					return $outlineClass;
				}
			}
		}
		
		return $colorClass;
	}
	
	/**
	 * Get the CSS property for a specific color, with a default if not found
	 *
	 * @param bool $outline Whether to use outline style (only for Button component)
	 * @return string
	 */
	public function getColorProperty(bool $outline = false): string
	{
		return $this->property($outline);
	}
	
	/**
	 * Get the CSS value for a specific color, with a default if not found
	 *
	 * @param string|null $color
	 * @param string|null $default Optional default color to use if color is not found
	 * @return string
	 */
	public function getColorValue(?string $color, ?string $default = null): string
	{
		$colors = $this->colors();
		$colorValue = '';
		
		if (!empty($color) && isset($colors[$color])) {
			$colorValue = $colors[$color]['value'];
		}
		
		if (empty($colorValue)) {
			if (!empty($default) && isset($colors[$default])) {
				$colorValue = $colors[$default]['value'];
			}
		}
		
		if (empty($colorValue)) {
			$firstColor = reset($colors);
			$colorValue = $firstColor ? $firstColor['value'] : '';
		}
		
		return $colorValue;
	}
}
