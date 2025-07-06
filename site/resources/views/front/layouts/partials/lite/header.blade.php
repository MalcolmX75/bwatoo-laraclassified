@php
	// Logo
	$logoFactoryUrl = config('larapen.media.logo-factory');
	$logoDarkUrl = config('settings.app.logo_dark_url', $logoFactoryUrl);
	$logoLightUrl = config('settings.app.logo_light_url', $logoFactoryUrl);
	$logoAlt = strtolower(config('settings.app.name'));
	$logoWidth = (int)config('settings.upload.img_resize_logo_width', 454);
	$logoHeight = (int)config('settings.upload.img_resize_logo_height', 80);
	
	// Theme Preference (light/dark/system)
	$showIconOnly ??= false;
	
	$isFixedTopHeader = (config('settings.style.header_fixed_top') == '1');
	$fixedTopClass = $isFixedTopHeader ? ' fixed-top' : '';
	
	$isFullWidthHeader = (config('settings.style.header_full_width') == '1');
	$containerClass = $isFullWidthHeader ? 'container-fluid' : 'container';
@endphp
<div class="header">
	<nav class="navbar{{ $fixedTopClass }} navbar-expand-md bg-body-tertiary border-bottom navbar-website" role="navigation">
		<div class="{{ $containerClass }}">
			
			{{-- Logo --}}
			<a href="{{ url('/') }}" class="navbar-brand logo logo-title">
				<img src="{{ $logoDarkUrl }}"
				     alt="{{ $logoAlt }}"
				     class="main-logo light-logo"
				     style="max-width: {{ $logoWidth }}px; max-height: {{ $logoHeight }}px; width:auto;"
				/>
				<img src="{{ $logoLightUrl }}"
				     alt="{{ $logoAlt }}"
				     class="main-logo dark-logo"
				     style="max-width: {{ $logoWidth }}px; max-height: {{ $logoHeight }}px; width:auto;"
				/>
			</a>
			
			{{-- Toggle Nav (Mobile) --}}
			<button class="navbar-toggler float-end"
			        type="button"
			        data-bs-toggle="collapse"
			        data-bs-target="#navbarDefault"
			        aria-controls="navbarDefault"
			        aria-expanded="false"
			        aria-label="Toggle navigation"
			>
				<span class="navbar-toggler-icon"></span>
			</button>
			
			<div class="collapse navbar-collapse" id="navbarDefault">
				<ul class="navbar-nav me-md-auto">
					{{----}}
				</ul>
				
				<ul class="navbar-nav ms-auto">
					@if (isSettingsAppDarkModeEnabled())
						@include('front.layouts.partials.navs.themes', [
							'dropdownTag'   => 'li',
							'dropdownClass' => 'nav-item',
							'buttonClass'   => 'nav-link',
							'menuAlignment' => 'dropdown-menu-end',
							'showIconOnly'  => $showIconOnly,
						])
					@endif
					@include('front.layouts.partials.navs.languages')
				</ul>
			</div>
			
			
		</div>
	</nav>
</div>
