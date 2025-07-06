@php
	$packages ??= collect();
	$paymentMethods ??= collect();
	
	$selectedPackage ??= null;
	$currentPackagePrice = $selectedPackage->price ?? 0;
	$noPackageOrPremiumOneSelected ??= true;
@endphp
@if ($paymentMethods->count() > 0 && $noPackageOrPremiumOneSelected)
	@if (!empty($selectedPackage))
		
		<div class="col-12 fw-bold fs-5 border-bottom py-2 my-5 mb-4">
			<i class="fa-solid fa-wallet"></i> {{ t('Payment') }}
		</div>
		
		<div class="col-md-12 mb-4">
			<div class="container bg-body rounded p-2">
				
				<div class="row">
					<div class="col-sm-12">
						
						<div class="form-group mb-0">
							<fieldset>
								@include('front.payment.packages.selected')
							</fieldset>
						</div>
					
					</div>
				</div>
				
			</div>
		</div>
		
	@else
	
		@if ($packages->count() > 0)
			<div class="col-12 fw-bold fs-5 border-bottom py-2 my-5 mb-4">
				<i class="fa-solid fa-tags"></i> {{ t('Packages') }}
			</div>
			
			<div class="col-md-12 mb-4">
				<div class="container bg-body rounded p-2">
					
					<div class="row">
						<div class="col-sm-12">
							<fieldset>
								@include('front.payment.packages')
							</fieldset>
						</div>
					</div>
					
				</div>
			</div>
		@endif
		
	@endif
@endif

@section('after_styles')
	@parent
@endsection

@section('after_scripts')
	@parent
	<script>
		const packageType = 'promotion';
		const formType = 'singleStep';
		const isCreationFormPage = {{ request()->segment(1) == 'create' ? 'true' : 'false' }};
	</script>
	@include('front.common.js.payment-js')
@endsection
