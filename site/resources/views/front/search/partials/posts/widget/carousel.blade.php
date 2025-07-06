@php
	$widget ??= [];
	$posts = (array)data_get($widget, 'posts');
	$totalPosts = (int)data_get($widget, 'totalPosts', 0);
	
	$sectionOptions ??= [];
	$hideOnMobile = (data_get($sectionOptions, 'hide_on_mobile') == '1') ? ' d-none d-md-block' : '';
	$carouselEl = 'carousel-' . createRandomString(6);
	
	$isFromHome ??= false;
	
	$isReviewsAddonInstalled = config('plugins.reviews.installed');
	$itemHeight = $isReviewsAddonInstalled ? 340 : 320;
	$itemStyle = ' style="height:' . $itemHeight . 'px;"';
	$titleClass = $isReviewsAddonInstalled ? ' fs-6 fw-bold' : ' fs-5';
	$titleLimit = $isReviewsAddonInstalled ? 48 : 42;
@endphp
@if ($totalPosts > 0)
	@if ($isFromHome)
		@include('front.sections.spacer', ['hideOnMobile' => $hideOnMobile])
	@endif
	<div class="container{{ $hideOnMobile }}">
		<div class="card">
			<div class="card-header border-bottom-0">
				<h4 class="mb-0 float-start fw-lighter">
					{!! data_get($widget, 'title') !!}
				</h4>
				<h5 class="mb-0 float-end mt-1 fs-6 fw-lighter text-uppercase">
					<a href="{{ data_get($widget, 'link') }}" class="{{ linkClass() }}">
						{{ t('View more') }} <i class="fa-solid fa-bars"></i>
					</a>
				</h5>
			</div>
			
			<div class="card-body rounded p-3">
				<div class="m-0 featured-list-slider {{ $carouselEl }} owl-carousel owl-theme">
					@foreach($posts as $key => $post)
						@php
							$postUrl = urlGen()->post($post);
						@endphp
						<div class="card me-2 p-0 d-flex justify-content-between flex-column item hover-bg-tertiary"{!! $itemStyle !!}>
							{{-- Main Picture --}}
							<div class="w-100 m-0 position-relative item-carousel-thumb">
								@if (data_get($post, 'featured') == 1)
									@if (!empty(data_get($post, 'payment.package')))
										@if (data_get($post, 'payment.package.ribbon') != '')
											@php
												$ribbonColor = data_get($post, 'payment.package.ribbon');
												$ribbonColorClass = BootstrapColor::Badge->getColorClass($ribbonColor);
												$packageShortName = data_get($post, 'payment.package.short_name');
											@endphp
											<span class="badge rounded-pill {{ $ribbonColorClass }} position-absolute mt-2 ms-2" style="z-index: 10;">
												{{ $packageShortName }}
											</span>
										@endif
									@endif
								@endif
								<div class="position-absolute top-0 end-0 mt-2 me-2 bg-body-secondary opacity-75 rounded px-1">
									<i class="fa-solid fa-camera"></i> {{ data_get($post, 'count_pictures') }}
								</div>
								<a href="{{ $postUrl }}" class="{{ linkClass('body-emphasis') }}">
									@php
										$src = data_get($post, 'picture.url.medium');
										$webpSrc = data_get($post, 'picture.url.webp.medium');
										$alt = str(data_get($post, 'title'))->slug();
										$attr = ['class' => 'lazyload img-fluid rounded-top'];
										echo generateImageHtml($src, $alt, $webpSrc, $attr);
									@endphp
								</a>
							</div>
							
							<div class="card-body h-100 d-flex justify-content-between flex-column">
								{{-- Title --}}
								<h6 class="mb-0{{ $titleClass }} px-0 text-center text-break">
									<a href="{{ $postUrl }}" class="{{ linkClass() }}">
										{{ str(data_get($post, 'title'))->limit($titleLimit) }}
									</a>
								</h6>
								
								<div class="d-flex flex-column">
									{{-- Reviews Stars --}}
									@if ($isReviewsAddonInstalled)
										<div class="text-center">
											@if (view()->exists('reviews::ratings-list'))
												@include('reviews::ratings-list')
											@endif
										</div>
									@endif
									
									{{-- Price --}}
									<h4 class="fs-4 fw-bold mt-3 text-center">
										{!! data_get($post, 'price_formatted') !!}
									</h4>
								</div>
							</div>
						</div>
					@endforeach
				</div>
			</div>
		</div>
	</div>
@endif

@section('after_style')
	@parent
@endsection

@section('after_scripts')
	@parent
	<script>
		onDocumentReady((event) => {
			{{-- Check if RTL or LTR --}}
			let isRTLEnabled = (document.documentElement.getAttribute('dir') === 'rtl');
			
			{{-- Carousel Parameters --}}
			{{-- Documentation: https://owlcarousel2.github.io/OwlCarousel2/ --}}
			let carouselItems = {{ $totalPosts ?? 0 }};
			let carouselAutoplay = {{ data_get($sectionOptions, 'autoplay') ?? 'false' }};
			let carouselAutoplayTimeout = {{ (int)(data_get($sectionOptions, 'autoplay_timeout') ?? 1500) }};
			let carouselLang = {
				'navText': {
					'prev': "{{ t('prev') }}",
					'next': "{{ t('next') }}"
				}
			};
			
			{{-- Featured Listings Carousel --}}
			let carouselObject = $('.featured-list-slider.{{ $carouselEl }}');
			let responsiveObject = {
				0: {
					items: 1,
					nav: true
				},
				576: {
					items: 2,
					nav: false
				},
				768: {
					items: 3,
					nav: false
				},
				992: {
					items: 5,
					nav: false,
					loop: (carouselItems > 5)
				}
			};
			carouselObject.owlCarousel({
				rtl: isRTLEnabled,
				nav: false,
				navText: [carouselLang.navText.prev, carouselLang.navText.next],
				loop: true,
				responsiveClass: true,
				responsive: responsiveObject,
				autoWidth: true,
				autoplay: carouselAutoplay,
				autoplayTimeout: carouselAutoplayTimeout,
				autoplayHoverPause: true
			});
			
			{{-- Items Title Animation --}}
			{{-- https://animate.style --}}
			const elements = document.querySelectorAll('.featured-list-slider .card-body > h6');
			if (elements.length) {
				const animation = 'animate__pulse';
				
				elements.forEach((element) => {
					element.addEventListener('mouseover', (event) => {
						event.target.classList.add('animate__animated', animation);
					});
					element.addEventListener("mouseout", (event) => {
						event.target.classList.remove('animate__animated', animation);
					});
				})
			}
		});
	</script>
@endsection
