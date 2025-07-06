<footer>
	<div class="container-fluid border-top bg-body-tertiary pt-5 pb-5 mt-4">
		<div class="container p-0 my-0">
			<div class="row">
				<div class="col-12">
					
					<div class="text-center">
						© {{ date('Y') }} {{ config('settings.app.name') }}. {{ t('all_rights_reserved') }}.
						@if (!config('settings.footer.hide_powered_by'))
							@if (config('settings.footer.powered_by_info'))
								{{ t('Powered by') }} {!! config('settings.footer.powered_by_info') !!}
							@else
								{{ t('Powered by') }} <a href="https://laraclassifier.com"
								                         title="LaraClassifier"
								                         class="{{ linkClass() }}"
								>LaraClassifier</a>.
							@endif
						@endif
					</div>
					
				</div>
			</div>
		</div>
	</div>
</footer>
