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

namespace App\Models\Setting;

use App\Helpers\Common\Files\Upload;

/*
 * settings.style.option
 */

class StyleSetting
{
	public static function passedValidation($request)
	{
		$mediaOpPath = 'larapen.media.resize.namedOptions';
		$params = [
			[
				'attribute' => 'body_background_image_path',
				'destPath'  => 'app/logo',
				'width'     => (int)config($mediaOpPath . '.bg-body.width', 2500),
				'height'    => (int)config($mediaOpPath . '.bg-body.height', 2500),
				'ratio'     => config($mediaOpPath . '.bg-body.ratio', '1'),
				'upsize'    => config($mediaOpPath . '.bg-body.upsize', '0'),
				'filename'  => 'body-background-',
			],
		];
		
		foreach ($params as $param) {
			$file = $request->hasFile($param['attribute'])
				? $request->file($param['attribute'])
				: $request->input($param['attribute']);
			
			$request->request->set($param['attribute'], Upload::image($file, $param['destPath'], $param));
		}
		
		return $request;
	}
	
	public static function getValues($value, $disk)
	{
		if (empty($value)) {
			
			$value['skin'] = 'default';
			$value['page_width'] = '1200';
			
			$value['header_background_color'] = null; // '#f8f9fA';
			$value['header_border_bottom_width'] = null; // '1px';
			$value['header_border_bottom_color'] = null; // '#dee2e6';
			
			$value['logo_width'] = '216';
			$value['logo_height'] = '40';
			$value['logo_aspect_ratio'] = '1';
			
			$value['footer_background_color'] = null; // '#f8f9fA';
			$value['footer_border_top_width'] = null; // '1px';
			$value['footer_border_top_color'] = null; // '#dee2e6';
			
			$value['admin_logo_bg'] = 'skin3';
			$value['admin_navbar_bg'] = 'skin6';
			$value['admin_sidebar_type'] = 'full';
			$value['admin_sidebar_bg'] = 'skin5';
			$value['admin_sidebar_position'] = '1';
			$value['admin_header_position'] = '1';
			$value['admin_boxed_layout'] = '0';
			$value['admin_dark_theme'] = '0';
			
		} else {
			
			if (!array_key_exists('skin', $value)) {
				$value['skin'] = 'default';
			}
			if (!array_key_exists('page_width', $value)) {
				$value['page_width'] = '1200';
			}
			
			foreach ($value as $key => $item) {
				if ($key == 'body_background_image_path') {
					if (empty($value['body_background_image_path']) || !$disk->exists($value['body_background_image_path'])) {
						$value[$key] = null;
					}
				}
			}
			
			if (!array_key_exists('header_background_color', $value)) {
				$value['header_background_color'] = null; // '#f8f9fA';
			}
			if (!array_key_exists('header_border_bottom_width', $value)) {
				$value['header_border_bottom_width'] = null; // '1px';
			}
			if (!array_key_exists('header_border_bottom_color', $value)) {
				$value['header_border_bottom_color'] = null; // '#dee2e6';
			}
			
			if (!array_key_exists('logo_width', $value)) {
				$value['logo_width'] = '216';
			}
			if (!array_key_exists('logo_height', $value)) {
				$value['logo_height'] = '40';
			}
			if (!array_key_exists('logo_aspect_ratio', $value)) {
				$value['logo_aspect_ratio'] = '1';
			}
			
			if (!array_key_exists('footer_background_color', $value)) {
				$value['footer_background_color'] = null; // '#f8f9fA';
			}
			if (!array_key_exists('footer_border_top_width', $value)) {
				$value['footer_border_top_width'] = null; // '1px';
			}
			if (!array_key_exists('footer_border_top_color', $value)) {
				$value['footer_border_top_color'] = null; // '#dee2e6';
			}
			
			if (!array_key_exists('admin_logo_bg', $value)) {
				$value['admin_logo_bg'] = 'skin3';
			}
			if (!array_key_exists('admin_navbar_bg', $value)) {
				$value['admin_navbar_bg'] = 'skin6';
			}
			if (!array_key_exists('admin_sidebar_type', $value)) {
				$value['admin_sidebar_type'] = 'full';
			}
			if (!array_key_exists('admin_sidebar_bg', $value)) {
				$value['admin_sidebar_bg'] = 'skin5';
			}
			if (!array_key_exists('admin_sidebar_position', $value)) {
				$value['admin_sidebar_position'] = '1';
			}
			if (!array_key_exists('admin_header_position', $value)) {
				$value['admin_header_position'] = '1';
			}
			if (!array_key_exists('admin_boxed_layout', $value)) {
				$value['admin_boxed_layout'] = '0';
			}
			if (!array_key_exists('admin_dark_theme', $value)) {
				$value['admin_dark_theme'] = '0';
			}
			
		}
		
		// Append files URLs
		// body_background_image_url
		$bodyBackgroundImage = $value['body_background_image_path'] ?? $value['body_background_image'] ?? null;
		$value['body_background_image_url'] = thumbService($bodyBackgroundImage, false)->resize('bg-body')->url();
		
		return $value;
	}
	
	public static function setValues($value, $setting)
	{
		return $value;
	}
	
	public static function getFields($diskName): array
	{
		// Get Pre-Defined Skins By Name
		$skins = getCachedReferrerList('skins');
		$skinsByName = collect($skins)
			->mapWithKeys(fn ($item, $key) => [$key => $item['name']])
			->toArray();
		
		$fields = [
			[
				'name'  => 'separator_1',
				'type'  => 'custom_html',
				'value' => trans('admin.style_html_frontend'),
			],
			[
				'name'    => 'skin',
				'label'   => trans('admin.Front Skin'),
				'type'    => 'select2_from_skins',
				'options' => $skinsByName,
				'skins'   => json_encode($skins),
				'wrapper' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'       => 'custom_skin_color',
				'label'      => trans('admin.custom_skin_color_label'),
				'type'       => 'color_picker',
				'attributes' => [
					'placeholder' => '#FFFFFF',
				],
				'hint'       => trans('admin.custom_skin_color_hint'),
				'wrapper'    => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'  => 'separator_2',
				'type'  => 'custom_html',
				'value' => trans('admin.style_html_customize_front'),
			],
			[
				'name'  => 'separator_2_1',
				'type'  => 'custom_html',
				'value' => trans('admin.style_html_customize_front_global'),
			],
			[
				'name'       => 'body_background_color',
				'label'      => trans('admin.Body Background Color'),
				'type'       => 'color_picker',
				'attributes' => [
					'placeholder' => '#FFFFFF',
				],
				'wrapper'    => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'       => 'body_text_color',
				'label'      => trans('admin.Body Text Color'),
				'type'       => 'color_picker',
				'attributes' => [
					'placeholder' => '#292B2C',
				],
				'wrapper'    => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'    => 'body_background_image_path',
				'label'   => trans('admin.Body Background Image'),
				'type'    => 'image',
				'upload'  => true,
				'disk'    => $diskName,
				'default' => null,
				'wrapper' => [
					'class' => 'col-md-6',
				],
				'newline' => true,
			],
			
			[
				'name'    => 'body_background_image_fixed',
				'label'   => trans('admin.Body Background Image Fixed'),
				'type'    => 'checkbox_switch',
				'wrapper' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'    => 'page_width',
				'label'   => trans('admin.Page Width'),
				'type'    => 'number',
				'wrapper' => [
					'class' => 'col-md-6',
				],
				'newline' => true,
			],
			
			[
				'name'       => 'title_color',
				'label'      => trans('admin.Titles Color'),
				'type'       => 'color_picker',
				'attributes' => [
					'placeholder' => '#292B2C',
				],
				'wrapper'    => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'       => 'progress_background_color',
				'label'      => trans('admin.Progress Background Color'),
				'type'       => 'color_picker',
				'attributes' => [
					'placeholder' => '',
				],
				'wrapper'    => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'       => 'link_color',
				'label'      => trans('admin.Links Color'),
				'type'       => 'color_picker',
				'attributes' => [
					'placeholder' => '#4682B4',
				],
				'wrapper'    => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'       => 'link_color_hover',
				'label'      => trans('admin.Links Color Hover'),
				'type'       => 'color_picker',
				'attributes' => [
					'placeholder' => '#FF8C00',
				],
				'wrapper'    => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'  => 'separator_2_2',
				'type'  => 'custom_html',
				'value' => trans('admin.style_html_customize_header'),
			],
			[
				'name'  => 'header_full_width',
				'label' => trans('admin.Header Full Width'),
				'type'  => 'checkbox_switch',
				'wrapper' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'  => 'header_fixed_top',
				'label' => trans('admin.Header Fixed Top'),
				'type'  => 'checkbox_switch',
				'wrapper' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'    => 'header_height',
				'label'   => trans('admin.Header Height'),
				'type'    => 'number',
				'wrapper' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'       => 'header_background_color',
				'label'      => trans('admin.Header Background Color'),
				'type'       => 'color_picker',
				'attributes' => [
					'placeholder' => '#F8F8F8',
				],
				'wrapper'    => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'    => 'header_border_bottom_width',
				'label'   => trans('admin.Header Border Bottom Width'),
				'type'    => 'number',
				'wrapper' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'       => 'header_border_bottom_color',
				'label'      => trans('admin.Header Border Bottom Color'),
				'type'       => 'color_picker',
				'attributes' => [
					'placeholder' => '#E8E8E8',
				],
				'wrapper'    => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'       => 'header_link_color',
				'label'      => trans('admin.Header Links Color'),
				'type'       => 'color_picker',
				'attributes' => [
					'placeholder' => '#333',
				],
				'wrapper'    => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'       => 'header_link_color_hover',
				'label'      => trans('admin.Header Links Color Hover'),
				'type'       => 'color_picker',
				'attributes' => [
					'placeholder' => '#000',
				],
				'wrapper'    => [
					'class' => 'col-md-6',
				],
			],
			
			[
				'name'  => 'separator_logo',
				'type'  => 'custom_html',
				'value' => trans('admin.style_logo_title'),
			],
			[
				'name'    => 'logo_width',
				'label'   => trans('admin.logo_width_label'),
				'type'    => 'number',
				'hint'    => trans('admin.logo_width_hint'),
				'wrapper' => [
					'class' => 'col-md-3',
				],
			],
			[
				'name'    => 'logo_height',
				'label'   => trans('admin.logo_height_label'),
				'type'    => 'number',
				'hint'    => trans('admin.logo_height_hint'),
				'wrapper' => [
					'class' => 'col-md-3',
				],
			],
			[
				'name'    => 'logo_aspect_ratio',
				'label'   => trans('admin.logo_aspect_ratio_label'),
				'type'    => 'checkbox_switch',
				'hint'    => trans('admin.logo_aspect_ratio_hint'),
				'wrapper' => [
					'class' => 'col-md-6',
				],
			],
			
			[
				'name'  => 'separator_2_3',
				'type'  => 'custom_html',
				'value' => trans('admin.style_html_customize_footer'),
			],
			[
				'name'  => 'footer_full_width',
				'label' => trans('admin.Footer Full Width'),
				'type'  => 'checkbox_switch',
				'wrapper' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'       => 'footer_background_color',
				'label'      => trans('admin.Footer Background Color'),
				'type'       => 'color_picker',
				'attributes' => [
					'placeholder' => '#F5F5F5',
				],
				'wrapper'    => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'    => 'footer_border_top_width',
				'label'   => trans('admin.Footer Border Top Width'),
				'type'    => 'number',
				'wrapper' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'       => 'footer_border_top_color',
				'label'      => trans('admin.Footer Border Top Color'),
				'type'       => 'color_picker',
				'attributes' => [
					'placeholder' => '#E8E8E8',
				],
				'wrapper'    => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'       => 'footer_text_color',
				'label'      => trans('admin.Footer Text Color'),
				'type'       => 'color_picker',
				'attributes' => [
					'placeholder' => '#333',
				],
				'wrapper'    => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'       => 'footer_title_color',
				'label'      => trans('admin.Footer Titles Color'),
				'type'       => 'color_picker',
				'attributes' => [
					'placeholder' => '#000',
				],
				'wrapper'    => [
					'class' => 'col-md-6',
				],
				'newline'    => true,
			],
			
			[
				'name'       => 'footer_link_color',
				'label'      => trans('admin.Footer Links Color'),
				'type'       => 'color_picker',
				'attributes' => [
					'placeholder' => '#333',
				],
				'wrapper'    => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'       => 'footer_link_color_hover',
				'label'      => trans('admin.Footer Links Color Hover'),
				'type'       => 'color_picker',
				'attributes' => [
					'placeholder' => '#333',
				],
				'wrapper'    => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'    => 'payment_icon_border_top_width',
				'label'   => trans('admin.Payment Methods Icons Border Top Width'),
				'type'    => 'number',
				'wrapper' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'       => 'payment_icon_border_top_color',
				'label'      => trans('admin.Payment Methods Icons Border Top Color'),
				'type'       => 'color_picker',
				'attributes' => [
					'placeholder' => '#DDD',
				],
				'wrapper'    => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'    => 'payment_icon_border_bottom_width',
				'label'   => trans('admin.Payment Methods Icons Border Bottom Width'),
				'type'    => 'number',
				'wrapper' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'       => 'payment_icon_border_bottom_color',
				'label'      => trans('admin.Payment Methods Icons Border Bottom Color'),
				'type'       => 'color_picker',
				'attributes' => [
					'placeholder' => '#DDD',
				],
				'wrapper'    => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'  => 'separator_2_4',
				'type'  => 'custom_html',
				'value' => trans('admin.style_html_customize_button_al'),
			],
			[
				'name'       => 'btn_listing_bg_top_color',
				'label'      => trans('admin.Gradient Background Top Color'),
				'type'       => 'color_picker',
				'attributes' => [
					'placeholder' => '#ffeb43',
				],
				'wrapper'    => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'       => 'btn_listing_bg_bottom_color',
				'label'      => trans('admin.Gradient Background Bottom Color'),
				'type'       => 'color_picker',
				'attributes' => [
					'placeholder' => '#fcde11',
				],
				'wrapper'    => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'       => 'btn_listing_border_color',
				'label'      => trans('admin.Button Border Color'),
				'type'       => 'color_picker',
				'attributes' => [
					'placeholder' => '#f6d80f',
				],
				'wrapper'    => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'       => 'btn_listing_text_color',
				'label'      => trans('admin.Button Text Color'),
				'type'       => 'color_picker',
				'attributes' => [
					'placeholder' => '#292b2c',
				],
				'wrapper'    => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'       => 'btn_listing_bg_top_color_hover',
				'label'      => trans('admin.Gradient Background Top Color Hover'),
				'type'       => 'color_picker',
				'attributes' => [
					'placeholder' => '#fff860',
				],
				'wrapper'    => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'       => 'btn_listing_bg_bottom_color_hover',
				'label'      => trans('admin.Gradient Background Bottom Color Hover'),
				'type'       => 'color_picker',
				'attributes' => [
					'placeholder' => '#ffeb43',
				],
				'wrapper'    => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'       => 'btn_listing_border_color_hover',
				'label'      => trans('admin.Button Border Color Hover'),
				'type'       => 'color_picker',
				'attributes' => [
					'placeholder' => '#fcde11',
				],
				'wrapper'    => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'       => 'btn_listing_text_color_hover',
				'label'      => trans('admin.Button Text Color Hover'),
				'type'       => 'color_picker',
				'attributes' => [
					'placeholder' => '#1b1d1e',
				],
				'wrapper'    => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'  => 'separator_3',
				'type'  => 'custom_html',
				'value' => trans('admin.style_html_raw_css'),
			],
			[
				'name'  => 'separator_3_1',
				'type'  => 'custom_html',
				'value' => trans('admin.style_html_raw_css_hint'),
			],
			[
				'name'       => 'custom_css',
				'label'      => trans('admin.Custom CSS'),
				'type'       => 'textarea',
				'attributes' => [
					'rows' => '5',
				],
				'hint'       => trans('admin.do_not_include_style_tags'),
			],
			
			[
				'name'  => 'backend_title_separator',
				'type'  => 'custom_html',
				'value' => trans('admin.backend_title_separator'),
			],
			[
				'name'    => 'admin_logo_bg',
				'label'   => trans('admin.admin_logo_bg_label'),
				'type'    => 'select2_from_array',
				'options' => [
					'skin1' => 'Green',
					'skin2' => 'Red',
					'skin3' => 'Blue',
					'skin4' => 'Purple',
					'skin5' => 'Black',
					'skin6' => 'White',
				],
				'wrapper' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'    => 'admin_navbar_bg',
				'label'   => trans('admin.admin_navbar_bg_label'),
				'type'    => 'select2_from_array',
				'options' => [
					'skin1' => 'Green',
					'skin2' => 'Red',
					'skin3' => 'Blue',
					'skin4' => 'Purple',
					'skin5' => 'Black',
					'skin6' => 'White',
				],
				'wrapper' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'    => 'admin_sidebar_type',
				'label'   => trans('admin.admin_sidebar_type_label'),
				'type'    => 'select2_from_array',
				'options' => [
					'full'         => 'Full',
					'mini-sidebar' => 'Mini Sidebar',
					'iconbar'      => 'Icon Bbar',
					'overlay'      => 'Overlay',
				],
				'hint'    => trans('admin.admin_sidebar_type_hint'),
				'wrapper' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'    => 'admin_sidebar_bg',
				'label'   => trans('admin.admin_sidebar_bg_label'),
				'type'    => 'select2_from_array',
				'options' => [
					'skin1' => 'Green',
					'skin2' => 'Red',
					'skin3' => 'Blue',
					'skin4' => 'Purple',
					'skin5' => 'Black',
					'skin6' => 'White',
				],
				'wrapper' => [
					'class' => 'col-md-6',
				],
				'newline' => true,
			],
			
			[
				'name'    => 'admin_sidebar_position',
				'label'   => trans('admin.admin_sidebar_position_label'),
				'type'    => 'checkbox_switch',
				'hint'    => trans('admin.admin_sidebar_position_hint'),
				'wrapper' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'    => 'admin_header_position',
				'label'   => trans('admin.admin_header_position_label'),
				'type'    => 'checkbox_switch',
				'hint'    => trans('admin.admin_header_position_hint'),
				'wrapper' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'    => 'admin_boxed_layout',
				'label'   => trans('admin.admin_boxed_layout_label'),
				'type'    => 'checkbox_switch',
				'hint'    => trans('admin.admin_boxed_layout_hint'),
				'wrapper' => [
					'class' => 'col-md-6',
				],
			],
		];
		
		return $fields;
	}
}
