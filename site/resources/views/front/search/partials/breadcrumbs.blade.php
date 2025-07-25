@php
	$bcTab ??= [];
	$admin ??= null;
	$city ??= null;
	
	$adminType = config('country.admin_type', 0);
	$relAdminType = (in_array($adminType, ['1', '2'])) ? $adminType : 1;
	$adminCode = data_get($city, 'subadmin' . $relAdminType . '_code') ?? data_get($admin, 'code') ?? 0;
	
	// Search base URL
	$searchWithoutQuery = urlGen()->searchWithoutQuery();
	$filterBy = request()->query('filterBy');
	if (!empty($filterBy)) {
		$searchWithoutQuery .=  (str_contains($searchWithoutQuery, '?')) ? '&' : '?';
		$searchWithoutQuery .= 'filterBy=' . $filterBy;
	}
	
	$linkClass = linkClass();
@endphp
<div class="container">
	<nav aria-label="breadcrumb" role="navigation" class="search-breadcrumb">
		<ol class="breadcrumb mb-0 py-3">
			<li class="breadcrumb-item">
				<a href="{{ url('/') }}" class="{{ $linkClass }}">
					<i class="fa-solid fa-house"></i>
				</a>
			</li>
			<li class="breadcrumb-item">
				<a href="{{ $searchWithoutQuery }}" class="{{ $linkClass }}">
					{{ config('country.name') }}
				</a>
			</li>
			@if (is_array($bcTab) && count($bcTab) > 0)
				@foreach($bcTab as $key => $value)
					@if ($value->has('position') && $value->get('position') > count($bcTab)+1)
						<li class="breadcrumb-item active">
							{!! $value->get('name') !!}
							@if (!empty($adminCode))
								&nbsp;<a href="#browseLocations"
								   class="{{ $linkClass }}"
								   data-bs-toggle="modal"
								   data-admin-code="{{ $adminCode }}"
								   data-city-id="{{ data_get($city, 'id', 0) }}"
								>
									<i class="bi bi-chevron-down"></i>
								</a>
							@endif
						</li>
					@else
						<li class="breadcrumb-item">
							<a href="{{ $value->get('url') }}" class="{{ $linkClass }}">
								{!! $value->get('name') !!}
							</a>
						</li>
					@endif
				@endforeach
			@endif
		</ol>
	</nav>
</div>
