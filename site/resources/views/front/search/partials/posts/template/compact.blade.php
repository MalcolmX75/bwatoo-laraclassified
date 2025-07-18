@php
	use App\Enums\BootstrapColor;
	
	$posts ??= [];
	$totalPosts ??= 0;
	
	$city ??= null;
	$cat ??= null;
@endphp
@if (!empty($posts) && $totalPosts > 0)
	<div class="container px-0 pt-3 compact-view">
		@foreach($posts as $key => $post)
			@php
				$postUrl = urlGen()->post($post);
				$parentCatUrl = null;
				if (!empty(data_get($post, 'category.parent'))) {
					$parentCatUrl = urlGen()->category(data_get($post, 'category.parent'), null, $city);
				}
				$catUrl = urlGen()->category(data_get($post, 'category'), null, $city);
				$locationUrl = urlGen()->city(data_get($post, 'city'), null, $cat);
				
				$borderBottom = !$loop->last ? ' border-bottom pb-3' : '';
			@endphp
			<div class="row{{ $borderBottom }} mb-3 d-flex align-items-stretch item-list item-list">
				<div class="col-sm-9 col-12">
					<div class="items-details">
						{{-- Title --}}
						<h5 class="fs-5 fw-normal px-0">
							@if (data_get($post, 'featured') == 1)
								@if (!empty(data_get($post, 'payment.package')))
									@if (data_get($post, 'payment.package.ribbon') != '')
										@php
											$ribbonColor = data_get($post, 'payment.package.ribbon');
											$ribbonColorClass = BootstrapColor::Badge->getColorClass($ribbonColor);
											$packageShortName = data_get($post, 'payment.package.short_name');
										@endphp
										<span class="badge rounded-pill {{ $ribbonColorClass }}">
											{{ $packageShortName }}
										</span>
									@endif
								@endif
							@endif
							
							<a href="{{ $postUrl }}" class="link-body-emphasis text-decoration-none">
								{{ str(data_get($post, 'title'))->limit(70) }}
							</a>
						</h5>
						
						{{-- Details --}}
						@php
							$showPostInfo = (
								(!config('settings.listings_list.hide_post_type') && config('settings.listing_form.show_listing_type'))
								|| !config('settings.listings_list.hide_date')
								|| !config('settings.listings_list.hide_category')
								|| !config('settings.listings_list.hide_location')
							);
						@endphp
						@if ($showPostInfo)
							<div class="container px-0 text-secondary">
								<ul class="list-inline mb-0">
									@if (
										!config('settings.listings_list.hide_post_type')
										&& config('settings.listing_form.show_listing_type')
									)
										@if (!empty(data_get($post, 'postType')))
											<li class="list-inline-item">
												<span class="badge rounded-pill text-bg-secondary fw-normal"
												      data-bs-toggle="tooltip"
												      data-bs-placement="bottom"
												      title="{{ data_get($post, 'postType.label') }}"
												>
													{{ strtoupper(mb_substr(data_get($post, 'postType.label'), 0, 1)) }}
												</span>
											</li>
										@endif
									@endif
									@if (!config('settings.listings_list.hide_date'))
										<li class="list-inline-item">
											<i class="fa-regular fa-clock"></i> {!! data_get($post, 'created_at_formatted') !!}
										</li>
									@endif
									@if (!config('settings.listings_list.hide_category'))
										<li class="list-inline-item">
											<i class="bi bi-folder"></i>&nbsp;
											@if (!empty(data_get($post, 'category.parent')))
												<a href="{!! $parentCatUrl !!}" class="{{ linkClass() }}">
													{{ data_get($post, 'category.parent.name') }}
												</a>&nbsp;&raquo;&nbsp;
											@endif
											<a href="{!! $catUrl !!}" class="{{ linkClass() }}">
												{{ data_get($post, 'category.name') }}
											</a>
										</li>
									@endif
									@if (!config('settings.listings_list.hide_location'))
										<li class="list-inline-item">
											<i class="bi bi-geo-alt"></i>&nbsp;
											<a href="{!! $locationUrl !!}" class="{{ linkClass() }}">
												{{ data_get($post, 'city.name') }}
											</a> {{ data_get($post, 'distance_info') }}
										</li>
									@endif
								</ul>
							</div>
						@endif
						
						{{-- Reviews Stars --}}
						@if (config('plugins.reviews.installed'))
							@if (view()->exists('reviews::ratings-list'))
								@include('reviews::ratings-list')
							@endif
						@endif
					</div>
				</div>
				
				<div class="col-sm-3 col-12 text-end text-nowrap d-flex flex-column justify-content-between">
					<h5 class="fs-5 fw-bold">
						{!! data_get($post, 'price_formatted') !!}
					</h5>
					<div>
						@if (!empty(data_get($post, 'payment.package')))
							@if (data_get($post, 'payment.package.has_badge') == 1)
								<a class="btn btn-danger btn-xs small make-favorite">
									<i class="fa-solid fa-certificate"></i> <span>{{ data_get($post, 'payment.package.short_name') }}</span>
								</a>&nbsp;
							@endif
						@endif
						@php
							$postId = data_get($post, 'id');
							$savedByLoggedUser = (bool)data_get($post, 'p_saved_by_logged_user');
						@endphp
						@if ($savedByLoggedUser)
							<a class="btn btn-success btn-xs small make-favorite" id="{{ $postId }}">
								<i class="bi bi-heart-fill"></i> <span>{{ t('Saved') }}</span>
							</a>
						@else
							<a class="btn btn-outline-secondary btn-xs small make-favorite" id="{{ $postId }}">
								<i class="bi bi-heart"></i> <span>{{ t('Save') }}</span>
							</a>
						@endif
					</div>
				</div>
			</div>
		@endforeach
	</div>
@else
	<div class="p-4" style="width: 100%;">
		{{ t('no_result_refine_your_search') }}
	</div>
@endif

@section('after_scripts')
	@parent
	<script>
		{{-- Favorites Translation --}}
		var lang = {
			labelSavePostSave: "{!! t('Save listing') !!}",
			labelSavePostRemove: "{!! t('Remove favorite') !!}",
			loginToSavePost: "{!! t('Please log in to save the Listings') !!}",
			loginToSaveSearch: "{!! t('Please log in to save your search') !!}"
		};
	</script>
@endsection
