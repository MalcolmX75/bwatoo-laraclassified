@php
	$apiResult = $apiResult ?? [];
	
	$default = 0;
	$from = (int)(data_get($apiResult, 'meta.from', $default) ?? $default);
	$to = (int)(data_get($apiResult, 'meta.to', $default) ?? $default);
	$total = (int)(data_get($apiResult, 'meta.total', $default) ?? $default);
	
	$perPage = (int)(data_get($apiResult, 'meta.per_page', $default) ?? $default);
	$hasPagination = ($total > $perPage);
	
	$fromFormatted = '<span class="fw-bold">' . $from . '</span>';
	$toFormatted = '<span class="fw-bold">' . $to . '</span>';
	$totalFormatted = '<span class="fw-bold">' . $total . '</span>';
	
	$mb = $hasPagination ? ' mb-3' : '';
@endphp
@if ($total > 0)
	<div class="text-center text-secondary{{ $mb }}">
		{!! t('pagination_meta', ['from' => $fromFormatted, 'to' => $toFormatted, 'total' => $totalFormatted]) !!}
	</div>
@endif
