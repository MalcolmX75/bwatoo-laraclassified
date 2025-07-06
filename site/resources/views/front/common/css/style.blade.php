@include('front.common.css.skin')
@include('front.common.css.dark')

<style>
/* === Body === */

@php
	$isFixedTopHeader = (config('settings.style.header_fixed_top') == '1');
	
	// Logo Max Sizes
	$logoMaxWidth = config('larapen.media.resize.namedOptions.logo-max.width', 430);
	$logoMaxHeight = config('larapen.media.resize.namedOptions.logo-max.height', 80);
	if (!empty(config('settings.style.header_height'))) {
		$logoMaxHeight = forceToInt(config('settings.style.header_height'), $logoMaxHeight);
	}
	
	// Logo Sizes
	$logoWidth = forceToInt(config('settings.style.logo_width'), 216);
	$logoHeight = forceToInt(config('settings.style.logo_height'), 40);
	if (config('settings.style.logo_aspect_ratio')) {
		if ($logoHeight <= $logoWidth) {
			$logoWidth = 'auto';
			$logoHeight = $logoHeight . 'px';
		} else {
			$logoWidth = $logoWidth . 'px';
			$logoHeight = 'auto';
		}
	} else {
		$logoWidth = $logoWidth . 'px';
		$logoHeight = $logoHeight . 'px';
	}
@endphp
.main-logo {
	width: {{ $logoWidth }};
	height: {{ $logoHeight }};
	max-width: {{ $logoMaxWidth }}px !important;
	max-height: {{ $logoMaxHeight }}px !important;
}
@if (!empty(config('settings.style.page_width')))
	@php
		$pageWidth = forceToInt(config('settings.style.page_width')) . 'px';
	@endphp
	@media (min-width: 1200px) {
		.container {
			max-width: {{ $pageWidth }};
		}
	}
@endif
@if (
	!empty(config('settings.style.body_background_color'))
	|| !empty(config('settings.style.body_background_image_path'))
)
	body.bg-body {
	@if (!empty(config('settings.style.body_background_color')))
		background-color: {{ config('settings.style.body_background_color') }} !important;
	@endif
	@if (!empty(config('settings.style.body_background_image_url')))
		background-image: url({{ config('settings.style.body_background_image_url') }});
		background-repeat: repeat;
		@if (!empty(config('settings.style.body_background_image_fixed')))
			background-attachment: fixed;
		@endif
	@endif
	}
@endif
@if (!empty(config('settings.style.body_text_color')))
	body.text-body-emphasis {
		color: {{ config('settings.style.body_text_color') }} !important;
	}
@endif

@if (!empty(config('settings.style.body_background_color')) || !empty(config('settings.style.body_background_image_path')))
	main {
		background-color: rgba(0, 0, 0, 0);
	}
@endif
@if (!empty(config('settings.style.title_color')))
	.skin h1,
	.skin h2,
	.skin h3,
	.skin h4,
	.skin h5,
	.skin h6 {
		color: {{ config('settings.style.title_color') }};
	}
@endif
@if (!empty(config('settings.style.link_color')))
	.skin a,
	.skin .link-color {
		color: {{ config('settings.style.link_color') }};
	}
@endif
@if (!empty(config('settings.style.link_color_hover')))
	.skin a:hover,
	.skin a:focus {
		color: {{ config('settings.style.link_color_hover') }};
	}
@endif
@if (!empty(config('settings.style.progress_background_color')))
	.skin .pace .pace-progress {
		background: {{ config('settings.style.progress_background_color') }} none repeat scroll 0 0;
	}
@endif

/* === Header === */
{{--
@if (!empty(config('settings.style.header_fixed_top')))
	header .navbar {
		position: fixed !important;
	}
@else
	header .navbar {
		position: absolute !important;
	}
@endif
--}}
@if (!empty(config('settings.style.header_height')))
	@php
		// Default values
		$defaultHeight = 80;
		$defaultPadding = 20;
		$defaultMargin = 0;
		
		// Get known value from Settings
		$headerHeight = forceToInt(config('settings.style.header_height'));
		
		$headerBottomBorderSize = 0;
		if (!empty(config('settings.style.header_border_bottom_width'))) {
			$headerBottomBorderSize = forceToInt(config('settings.style.header_border_bottom_width'));
		}
		$wrapperPaddingTop = $headerHeight + $headerBottomBorderSize;
		
		// Calculate unknown values
		$calculatedPadding = floor(($headerHeight * $defaultPadding) / $defaultHeight);
		$calculatedMargin = floor(($headerHeight * $defaultMargin) / $defaultHeight);
		$padding = abs(($calculatedPadding - ($defaultPadding / 2)) * 2);
		$margin = abs(($calculatedMargin - ($defaultMargin / 2)) * 2);
		
		/* $wrapperPaddingTop + 4 for default margin/padding values */
		$wrapperPaddingTop = $wrapperPaddingTop + ($padding - 4);
	@endphp
	header .navbar {
		min-height: {{ $headerHeight }}px;
		padding-top: {{ $padding }}px;
		padding-bottom: {{ $padding }}px;
	}
	@if ($isFixedTopHeader)
		main {
			{{-- https://getbootstrap.com/docs/5.3/components/navbar/#placement --}}
			padding-top: {{ $wrapperPaddingTop }}px;
		}
   @endif
@endif
@if (!empty(config('settings.style.header_background_color')))
	header .navbar {
		background-color: {{ config('settings.style.header_background_color') }} !important;
	}
@endif
@if (!empty(config('settings.style.header_border_bottom_width')))
	@php
		$headerBottomBorderSize = forceToInt(config('settings.style.header_border_bottom_width')) . 'px';
	@endphp
	header .navbar {
		border-bottom-width: {{ $headerBottomBorderSize }} !important;
		border-bottom-style: solid !important;
	}
@endif
@if (!empty(config('settings.style.header_border_bottom_color')))
	header .navbar {
		border-bottom-color: {{ config('settings.style.header_border_bottom_color') }} !important;
	}
@endif
@if (!empty(config('settings.style.header_link_color')))
	@media (min-width: 768px) {
		header .navbar ul.navbar-nav > li > a {
			color: {{ config('settings.style.header_link_color') }} !important;
		}
	}
	
	header .navbar ul.navbar-nav > .open > a,
	header .navbar ul.navbar-nav > .open > a:focus,
	header .navbar ul.navbar-nav > .open > a:hover {
		color: {{ config('settings.style.header_link_color') }} !important;
	}
@endif
@if (!empty(config('settings.style.header_link_color_hover')))
	@media (min-width: 768px) {
		header .navbar ul.navbar-nav > li > a:hover,
		header .navbar ul.navbar-nav > li > a:focus {
			color: {{ config('settings.style.header_link_color_hover') }} !important;
		}
	}
@endif

/* === Footer === */
@if (!empty(config('settings.style.footer_background_color')))
	footer > div.bg-body-tertiary {
		background: {{ config('settings.style.footer_background_color') }} !important;
	}
@endif
@if (!empty(config('settings.style.footer_border_top_width')))
	@php
		$footerBorderTopSize = forceToInt(config('settings.style.footer_border_top_width')) . 'px';
	@endphp
	footer > div.bg-body-tertiary {
		border-top-width: {{ $footerBorderTopSize }} !important;
		border-top-style: solid !important;
	}
@endif
@if (!empty(config('settings.style.footer_border_top_color')))
	footer > div.bg-body-tertiary {
		border-top-color: {{ config('settings.style.footer_border_top_color') }} !important;
	}
@endif
@if (!empty(config('settings.style.footer_text_color')))
	footer > div {
		color: {{ config('settings.style.footer_text_color') }};
	}
@endif
@if (!empty(config('settings.style.footer_title_color')))
	footer h1, footer h2, footer h3, footer h4, footer h5, footer h6 {
		color: {{ config('settings.style.footer_title_color') }};
	}
@endif
@if (!empty(config('settings.style.footer_link_color')))
	footer a.link-body-emphasis,
	footer a.link-primary {
		color: {{ config('settings.style.footer_link_color') }} !important;
	}
@endif
@if (!empty(config('settings.style.footer_link_color_hover')))
	footer a.link-body-emphasis:hover,
	footer a.link-body-emphasis:focus,
	footer a.link-primary:hover,
	footer a.link-primary:focus {
		color: {{ config('settings.style.footer_link_color_hover') }} !important;
	}
@endif
@if (!empty(config('settings.style.payment_icon_border_top_width')))
	@php
		$paymentIconTopBorderSize = forceToInt(config('settings.style.payment_icon_border_top_width')) . 'px';
	@endphp
	.payment-method-logo {
		border-top-width: {{ $paymentIconTopBorderSize }};
	}
	.footer-content hr {
		border-top-width: {{ $paymentIconTopBorderSize }};
	}
@endif
@if (!empty(config('settings.style.payment_icon_border_top_color')))
	.payment-method-logo {
		border-top-color: {{ config('settings.style.payment_icon_border_top_color') }};
	}
	.footer-content hr {
		border-top-color: {{ config('settings.style.payment_icon_border_top_color') }};
	}
@endif
@if (!empty(config('settings.style.payment_icon_border_bottom_width')))
	@php
		$paymentIconBottomBorderSize = forceToInt(config('settings.style.payment_icon_border_bottom_width')) . 'px';
	@endphp
	.payment-method-logo {
		border-bottom-width: {{ $paymentIconBottomBorderSize }};
	}
@endif
@if (!empty(config('settings.style.payment_icon_border_bottom_color')))
	.payment-method-logo {
		border-bottom-color: {{ config('settings.style.payment_icon_border_bottom_color') }};
	}
@endif

/* === Button: Add Listing === */
@if (!empty(config('settings.style.btn_listing_bg_top_color')) || !empty(config('settings.style.btn_listing_bg_bottom_color')))
	@php
		$btnBackgroundTopColor = '#ffeb43';
		$btnBackgroundBottomColor = '#fcde11';
		if (!empty(config('settings.style.btn_listing_bg_top_color'))) {
			$btnBackgroundTopColor = config('settings.style.btn_listing_bg_top_color');
		}
		if (!empty(config('settings.style.btn_listing_bg_bottom_color'))) {
			$btnBackgroundBottomColor = config('settings.style.btn_listing_bg_bottom_color');
		}
	@endphp
	a.btn-listing,
	button.btn-listing,
	header .navbar ul.navbar-nav > li.postadd > a.btn-listing,
	#homepage a.btn-listing {
		background-image: linear-gradient(to bottom, {{ $btnBackgroundTopColor }} 0,{{ $btnBackgroundBottomColor }} 100%);
	}
@endif
@if (!empty(config('settings.style.btn_listing_border_color')))
	a.btn-listing,
	button.btn-listing,
	header .navbar ul.navbar-nav > li.postadd > a.btn-listing,
	#homepage a.btn-listing {
		border-color: {{ config('settings.style.btn_listing_border_color') }};
	}
@endif
@if (!empty(config('settings.style.btn_listing_text_color')))
	a.btn-listing,
	button.btn-listing,
	header .navbar ul.navbar-nav > li.postadd > a.btn-listing,
	#homepage a.btn-listing {
		color: {{ config('settings.style.btn_listing_text_color') }} !important;
	}
@endif
@if (!empty(config('settings.style.btn_listing_bg_top_color_hover')) || !empty(config('settings.style.btn_listing_bg_bottom_color_hover')))
	@php
		$btnBackgroundTopColorHover = '#fff860';
		$btnBackgroundBottomColorHover = '#ffeb43';
		if (!empty(config('settings.style.btn_listing_bg_top_color_hover'))) {
			$btnBackgroundTopColorHover = config('settings.style.btn_listing_bg_top_color_hover');
		}
		if (!empty(config('settings.style.btn_listing_bg_bottom_color_hover'))) {
			$btnBackgroundBottomColorHover = config('settings.style.btn_listing_bg_bottom_color_hover');
		}
	@endphp
	a.btn-listing:hover,
	a.btn-listing:focus,
	button.btn-listing:hover,
	button.btn-listing:focus,
	li.postadd > a.btn-listing:hover,
	li.postadd > a.btn-listing:focus,
	#homepage a.btn-listing:hover,
	#homepage a.btn-listing:focus {
		background-image: linear-gradient(to bottom, {{ $btnBackgroundTopColorHover }} 0,{{ $btnBackgroundBottomColorHover }} 100%) !important;
	}
@endif
@if (!empty(config('settings.style.btn_listing_border_color_hover')))
	a.btn-listing:hover,
	a.btn-listing:focus,
	button.btn-listing:hover,
	button.btn-listing:focus,
	header .navbar ul.navbar-nav > li.postadd > a.btn-listing:hover,
	header .navbar ul.navbar-nav > li.postadd > a.btn-listing:focus,
	#homepage a.btn-listing:hover,
	#homepage a.btn-listing:focus {
		border-color: {{ config('settings.style.btn_listing_border_color_hover') }} !important;
	}
@endif
@if (!empty(config('settings.style.btn_listing_text_color_hover')))
	a.btn-listing:hover,
	a.btn-listing:focus,
	button.btn-listing:hover,
	button.btn-listing:focus,
	header .navbar ul.navbar-nav > li.postadd > a.btn-listing:hover,
	header .navbar ul.navbar-nav > li.postadd > a.btn-listing:focus,
	#homepage a.btn-listing:hover,
	#homepage a.btn-listing:focus {
		color: {{ config('settings.style.btn_listing_text_color_hover') }} !important;
	}
@endif
</style>
