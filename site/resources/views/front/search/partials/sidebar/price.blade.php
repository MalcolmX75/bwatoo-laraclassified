@php
	$isPriceFilterCanBeDisplayed = (!empty($cat) && data_get($cat, 'type') != 'not-salable');
	
	// Clear Filter Button
	$clearFilterBtn = urlGen()->getPriceFilterClearLink($cat ?? null, $city ?? null);
@endphp
@if ($isPriceFilterCanBeDisplayed)
	{{-- Price --}}
	<div class="container p-0 vstack gap-2">
		<h5 class="border-bottom pb-2 d-flex justify-content-between">
			<span class="fw-bold">
				{{ (!in_array(data_get($cat, 'type'), ['job-offer', 'job-search'])) ? t('price_range') : t('salary_range') }}
			</span> {!! $clearFilterBtn !!}
		</h5>
		<div>
			<form action="{{ request()->url() }}" method="GET" role="form">
				@foreach(request()->except(['page', 'minPrice', 'maxPrice', '_token']) as $key => $value)
					@if (is_array($value))
						@foreach($value as $k => $v)
							@if (is_array($v))
								@foreach($v as $ik => $iv)
									@continue(is_array($iv))
									<input type="hidden" name="{{ $key.'['.$k.']['.$ik.']' }}" value="{{ $iv }}">
								@endforeach
							@else
								<input type="hidden" name="{{ $key.'['.$k.']' }}" value="{{ $v }}">
							@endif
						@endforeach
					@else
						<input type="hidden" name="{{ $key }}" value="{{ $value }}">
					@endif
				@endforeach
				<div class="row px-0 gx-1 gy-1">
					<div class="col-12 mb-3 number-range-slider price-range" id="priceRangeSlider"></div>
					<div class="col-lg-4 col-md-12 col-sm-12">
						<input type="number"
						       min="0"
						       id="minPrice"
						       name="minPrice"
						       class="form-control"
						       placeholder="{{ t('Min') }}"
						       value="{{ request()->query('minPrice') }}"
						>
					</div>
					<div class="col-lg-4 col-md-12 col-sm-12">
						<input type="number"
						       min="0"
						       id="maxPrice"
						       name="maxPrice"
						       class="form-control"
						       placeholder="{{ t('Max') }}"
						       value="{{ request()->query('maxPrice') }}"
						>
					</div>
					<div class="col-lg-4 col-md-12 col-sm-12 d-grid">
						<button class="btn btn-secondary" type="submit">{{ t('go') }}</button>
					</div>
				</div>
			</form>
		</div>
	</div>
@endif

{{-- Styles --}}
{{-- The noUiSlider styles need to be placed in the end of <body> instead of in the <head> --}}
@section('after_scripts')
	@parent
	@if ($isPriceFilterCanBeDisplayed)
		<link href="{{ url('assets/plugins/noUiSlider/15.5.0/nouislider.css') }}" rel="stylesheet">
		<style>
			/* Hide Arrows From Input Number */
			/* Chrome, Safari, Edge, Opera */
			.number-range-slider-wrapper input::-webkit-outer-spin-button,
			.number-range-slider-wrapper input::-webkit-inner-spin-button {
				-webkit-appearance: none;
				margin: 0;
			}
			/* Firefox */
			.number-range-slider-wrapper input[type=number] {
				-moz-appearance: textfield;
			}
		</style>
	@endif
@endsection

{{-- Scripts --}}
@section('after_scripts')
	@parent
	@if ($isPriceFilterCanBeDisplayed)
		<script src="{{ url('assets/plugins/noUiSlider/15.5.0/nouislider.js') }}"></script>
		@php
			$minPrice = (int)config('settings.listings_list.min_price', 0);
			$maxPrice = (int)config('settings.listings_list.max_price', 10000);
			$priceSliderStep = (int)config('settings.listings_list.price_slider_step', 50);
			
			$startPrice = (int)request()->query('minPrice', $minPrice);
			$endPrice = (int)request()->query('maxPrice', $maxPrice);
		@endphp
		<script>
			onDocumentReady((event) => {
				let minPrice = {{ $minPrice }};
				let maxPrice = {{ $maxPrice }};
				let priceSliderStep = {{ $priceSliderStep }};
				
				{{-- Price --}}
				let startPrice = {{ $startPrice }};
				let endPrice = {{ $endPrice }};
				
				let priceRangeSliderEl = document.getElementById(`priceRangeSlider`);
				noUiSlider.create(priceRangeSliderEl, {
					connect: true,
					start: [startPrice, endPrice],
					step: priceSliderStep,
					keyboardSupport: true,     			 /* Default true */
					keyboardDefaultStep: 5,    			 /* Default 10 */
					keyboardPageMultiplier: 5, 			 /* Default 5 */
					keyboardMultiplier: priceSliderStep, /* Default 1 */
					range: {
						'min': minPrice,
						'max': maxPrice
					}
				});
				
				let minPriceEl = document.getElementById(`minPrice`);
				let maxPriceEl = document.getElementById(`maxPrice`);
				
				priceRangeSliderEl.noUiSlider.on('update', (values, handle) => {
					let value = values[handle];
					
					if (handle) {
						maxPriceEl.value = Math.round(value);
					} else {
						minPriceEl.value = Math.round(value);
					}
				});
				minPriceEl.addEventListener('change', (e) => {
					priceRangeSliderEl.noUiSlider.set([e.target.value, null]);
				});
				maxPriceEl.addEventListener('change', (e) => {
					if (e.target.value <= maxPrice) {
						priceRangeSliderEl.noUiSlider.set([null, e.target.value]);
					}
				});
			});
		</script>
	@endif
@endsection
