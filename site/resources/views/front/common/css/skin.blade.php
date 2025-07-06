<style>
@if (!empty($primaryBgColor))
/* === Skin === */
	{{-- ========= START BTN ========= --}}
	{{-- .btn-primary --}}
	.skin .btn-primary {
		color: {{ $primaryColor }};
		background-color: {{ $primaryBgColor }};
		border-color: {{ $primaryBgColor }};
	}
	.skin .btn-primary:hover,
	.skin .btn-primary:focus,
	.skin .btn-primary:active,
	.skin .btn-primary:active:focus,
	.skin .btn-primary.active,
	.skin .btn-primary.active:focus,
	.skin .show > .btn-primary.dropdown-toggle,
	.skin .open .dropdown-toggle.btn-primary {
		color: {{ $primaryColor }};
		background-color: {{ $primaryBgColor10 }};
		border-color: {{ $primaryBgColor10 }};
		background-image: none;
	}
	.skin .btn-check:focus+.btn-primary,
	.skin .btn-primary:focus,
	.skin .btn-primary.focus {
		box-shadow: none;
	}
	
	/* .link-primary */
	.skin a.link-primary,
	.skin .maxlist-more > a,
	.skin .text-primary {
		color: {{ $primaryBgColor10d }} !important;
	}
	.skin a.link-primary:hover, .skin a.link-primary:focus,
	.skin .maxlist-more > a:hover, .skin .maxlist-more > a:focus {
		color: #fa7722 !important;
	}
	
	/* .dropdown-item */
	.skin .dropdown-item.active, .skin .dropdown-item:active {
		background-color: {{ $primaryBgColor }};
	}

	/* .list-group-item */
	.skin .list-group-item.active {
		background-color: {{ $primaryBgColor }};
		border-color: {{ $primaryBgColor }};
		color: var(--bs-list-group-active-color);
	}
	
	{{-- .pagination --}}
	.skin .pagination > li > a,
	.skin .pagination > li > span {
		color: {{ $primaryBgColor }};
	}
	.skin .pagination > li > a:hover,
	.skin .pagination > li > span:hover,
	.skin .pagination > li > a:focus,
	.skin .pagination > li > span:focus {
		color: {{ $primaryBgColor20d }};
	}
	.skin .pagination > li.page-item.active .page-link,
	.skin .pagination > .active > a,
	.skin .pagination > .active > span,
	.skin .pagination > .active > a:hover,
	.skin .pagination > .active > span:hover,
	.skin .pagination > .active > a:focus,
	.skin .pagination > .active > span:focus {
		background-color: {{ $primaryBgColor }};
		border-color: {{ $primaryBgColor }};
		color: #fff;
	}
	
	/* .form-check-input */
	.skin .form-check-input:checked {
		background-color: {{ $primaryBgColor }};
		border-color: {{ $primaryBgColor }};
	}
	
	/* .form-control */
	.skin .form-control:focus,
	.skin .form-check-input:focus,
	.skin .select2-container--bootstrap-5 .select2-dropdown .select2-search .select2-search__field:focus {
		border-color: {{ $primaryBgColor }};
		box-shadow: 0 0 0 .25rem {{ rgbToCss(hexToRgba($primaryBgColor, 0.25)) }};
	}
	
	{{-- .text-bg-primary --}}
	.skin .text-bg-primary {
		background-color: {{ $primaryBgColor }} !important;
		color: #fff;
	}
	
	/* .border-primary */
	.skin .border-primary {
		border-color: {{ $primaryBgColor }} !important;
	}
	
	{{-- .btn-primary-dark --}}
	.skin .btn-primary-dark {
		color: {{ $primaryDarkColor }};
		background-color: {{ $primaryDarkBgColor }};
		border-color: {{ $primaryDarkBgColor }};
	}
	.skin .btn-primary-dark:hover,
	.skin .btn-primary-dark:focus,
	.skin .btn-primary-dark:active,
	.skin .btn-primary-dark:active:focus,
	.skin .btn-primary-dark.active,
	.skin .btn-primary-dark.active:focus,
	.skin .show > .btn-primary-dark.dropdown-toggle,
	.skin .open .dropdown-toggle.btn-primary-dark {
		color: {{ $primaryDarkColor }};
		background-color: {{ $primaryDarkBgColor10 }};
		border-color: {{ $primaryDarkBgColor10 }};
		background-image: none;
	}
	.skin .btn-check:focus+.btn-primary-dark,
	.skin .btn-primary-dark:focus,
	.skin .btn-primary-dark.focus {
		box-shadow: none;
	}
	
	{{-- .btn-outline-primary --}}
	.skin .btn-outline-primary {
		color: {{ $primaryBgColor }};
		background-color: {{ $primaryColor }};
		border-color: {{ $primaryBgColor }};
	}
	.skin .btn-outline-primary:hover,
	.skin .btn-outline-primary:focus,
	.skin .btn-outline-primary:active,
	.skin .btn-outline-primary:active:focus,
	.skin .btn-outline-primary.active,
	.skin .btn-outline-primary.active:focus,
	.skin .show > .btn-outline-primary.dropdown-toggle,
	.skin .open .dropdown-toggle.btn-outline-primary {
		color: {{ $primaryColor }};
		background-color: {{ $primaryBgColor }};
		border-color: {{ $primaryBgColor }};
		background-image: none;
	}
	.skin .btn-check:focus+.btn-outline-primary,
	.skin .btn-outline-primary:focus,
	.skin .btn-outline-primary.focus {
		box-shadow: none;
	}
	
	{{-- .btn-primary.btn-gradient --}}
	.skin .btn-primary.btn-gradient {
		color: {{ $primaryColor }};
		background: -webkit-linear-gradient(292deg, {{ $primaryBgColor20d }} 44%, {{ $primaryBgColor }} 85%);
		background: -moz-linear-gradient(292deg, {{ $primaryBgColor20d }} 44%, {{ $primaryBgColor }} 85%);
		background: -o-linear-gradient(292deg, {{ $primaryBgColor20d }} 44%, {{ $primaryBgColor }} 85%);
		background: linear-gradient(158deg, {{ $primaryBgColor20d }} 44%, {{ $primaryBgColor }} 85%);
		border-color: {{ $primaryBgColor20d }};
		-webkit-transition: all 0.25s linear;
		-moz-transition: all 0.25s linear;
		-o-transition: all 0.25s linear;
		transition: all 0.25s linear;
	}
	.skin .btn-primary.btn-gradient:hover,
	.skin .btn-primary.btn-gradient:focus,
	.skin .btn-primary.btn-gradient:active,
	.skin .btn-primary.btn-gradient:active:focus,
	.skin .btn-primary.btn-gradient.active,
	.skin .btn-primary.btn-gradient.active:focus,
	.skin .show > .btn-primary.btn-gradient.dropdown-toggle,
	.skin .open .dropdown-toggle.btn-primary.btn-gradient {
		color: {{ $primaryColor }};
		background-color: {{ $primaryBgColor }};
		border-color: {{ $primaryBgColor }};
		background-image: none;
	}
	.skin .btn-check:focus+.btn-primary.btn-gradient,
	.skin .btn-primary.btn-gradient:focus,
	.skin .btn-primary.btn-gradient.focus {
		box-shadow: 0 0 0 2px {{ $primaryBgColor50 }};
	}
	
	{{-- ========= END BTN ========= --}}
	
	.skin .footer-nav-inline.social-list-color li a:hover,
	.skin .footer-nav-inline.social-list-color li a:focus {
		color: #fff;
		opacity: .6;
	}
	
	.skin ::selection {
		color: {{ $primaryColor }};
		background: {{ $primaryBgColor }};
	}
	
	.skin ::-moz-selection {
		color: {{ $primaryColor }};
		background: {{ $primaryBgColor }};
	}

	/* pace-js */
	.skin .pace .pace-progress {
		background: {{ $primaryBgColor }} none repeat scroll 0 0;
	}
	
	.skin .search-row .search-col:first-child > div,
	.skin .search-row .search-col > div,
	.skin .search-row .search-col button {
		border-color: {{ $primaryBgColor }};
	}
	
	.skin .p-price-tag {
		background: {{ $primaryBgColor10 }};
	}
	.skin .p-price-tag::before {
		border-top-color: {{ $primaryBgColor }};
	}
	
	.skin .bg-primary {
		background-color: {{ $primaryBgColor }} !important;
	}
	.skin .border-color-primary {
		border-color: {{ $primaryBgColor }} !important;
	}
	
	.skin .badge-primary {
		background-color: {{ $primaryBgColor }};
		color: {{ $primaryColor }};
	}
	.skin .badge-primary[href]:focus, .badge-primary[href]:hover {
		background-color: {{ $primaryDarkBgColor }};
		color: {{ $primaryDarkColor }};
	}
	
	.skin .select2-container--bootstrap-5.select2-container--focus .select2-selection,
	.skin .select2-container--bootstrap-5.select2-container--open .select2-selection {
		border-color: {{ $primaryBgColor }} !important;
		box-shadow: 0 0 0 .25rem {{ rgbToCss(hexToRgba($primaryBgColor, 0.25)) }} !important;
	}
	.skin .select2-container--bootstrap-5 .select2-dropdown {
		border-color: {{ $primaryBgColor }} !important;
	}
	
	.skin div:not(.input-group) > .form-control.is-invalid,
	.skin div:not(.input-group) > .form-control.was-validated,
	.skin .select2-container--default.is-invalid .select2-selection--multiple.is-invalid {
		border-color: #dc3545;
	}

	.skin div:not(.input-group) > .form-control.is-invalid:focus,
	.skin div:not(.input-group) > .form-control.was-validated:focus,
	.skin .select2-container--default.is-invalid.select2-container--focus {
		border-color: #dc3545;
		box-shadow: 0 1px 0 #dc3545, 0 -1px 0 #dc3545, -1px 0 0 #dc3545, 1px 0 0 #dc3545;
	}

	.skin .input-group.is-invalid,
	.skin .input-group.was-validated {
		border: 1px solid #dc3545;
		border-radius: 6px;
	}
	.skin .input-group.is-invalid:focus-within,
	.skin .input-group.was-validated:focus-within {
		box-shadow: 0 1px 0 #dc3545, 0 -1px 0 #dc3545, -1px 0 0 #dc3545, 1px 0 0 #dc3545;
	}
	
	.skin .logo,
	.skin .logo-title {
		color: {{ $primaryBgColor }};
	}
	
	.skin .cat-list h3 a,
	.skin .cat-list h3 {
		color: {{ $primaryBgColor }};
	}
	.skin .cat-list h3 a:hover,
	.skin .cat-list h3 a:focus {
		color: #ff8c00;
	}
	
	.skin .cat-list ul li a:not(.btn):hover,
	.skin .cat-list a:not(.btn):hover {
		text-decoration: underline;
		color: {{ $primaryBgColor20d }};
	}
	
	.skin .list-filter ul li p.maxlist-more a {
		color: {{ $primaryBgColor10d }};
	}
	
	.skin ul.list-link li a:not(.btn):hover {
		color: {{ $primaryBgColor20d }};
	}
	
	.skin .bxslider-pager .bx-thumb-item:focus {
		-webkit-box-shadow: 0 0 2px {{ $primaryBgColor10d }};
		-moz-box-shadow: 0 0 2px {{ $primaryBgColor10d }};
		box-shadow: 0 0 2px {{ $primaryBgColor10d }};
		outline: none;
	}
	.skin .bxslider-pager .bx-thumb-item.active,
	.skin .thumbs-gallery .swiper-slide.swiper-slide-thumb-active {
		border: 1px solid {{ $primaryBgColor10d }};
		border-bottom: 4px solid {{ $primaryBgColor10d }} !important;
	}
	
	.skin .page-bottom-info {
		background: {{ $primaryBgColor }};
	}
	
	.skin button.btn-search {
		text-shadow: 0 2px 2px {{ $primaryBgColor }};
		-webkit-text-shadow: 0 2px 2px {{ $primaryBgColor }};
	}
	
	.skin .nav-link:focus,
	.skin .nav-link:hover {
		color: {{ $primaryBgColor }};
	}
	
	.skin .nav-pills > li.active > a:not(.btn),
	.skin .nav-pills > li.active > a:not(.btn):focus,
	.skin .nav-pills > li.active > a:not(.btn):hover {
		background-color: {{ $primaryBgColor10 }};
		color: #fff;
	}
	
	.skin .nav-pills .nav-link.active,
	.skin .nav-pills .nav-link.active:focus,
	.skin .nav-pills .nav-link.active:hover {
		background-color: {{ $primaryBgColor10 }};
		color: #fff;
	}
	
	.skin .select2-container--bootstrap-5 .select2-dropdown .select2-results__options .select2-results__option.select2-results__option--selected,
	.skin .select2-container--bootstrap-5 .select2-dropdown .select2-results__options .select2-results__option[aria-selected=true]:not(.select2-results__option--highlighted) {
		color: #fff;
		background-color: {{ $primaryBgColor10 }};
	}
	
	.skin .number-range-slider .noUi-connect {
		background: {{ $primaryBgColor }};
	}
	
	{{-- Cookie Consent --}}
	.skin .cookie-consent__agree {
		background-color: {{ $primaryBgColor }};
		box-shadow: 0 2px 5px rgba(70, 130, 180, 0.15);
	}
	.skin .cookie-consent__agree:hover {
		background-color: {{ $primaryBgColor20d }};
	}
	
	{{-- Customizations --}}
	@if (in_array($selectedSkin, ['yellow', 'sunFlower']))
	.skin .breadcrumb-item a,
	.skin .category-links a,
	.skin .backtolist a,
	.skin .card-user-info .to a,
	.skin .items-details-info .detail-line-lite a,
	.skin #postType .nav-item a {
		font-weight: bold;
	}
	@endif
	
	{{-- Header --}}
	@if (in_array($selectedSkin, ['yellow', 'sunFlower']))
	.skin header .navbar {
		background-color: {{ $primaryBgColor }} !important;
		border: 1px solid {{ $primaryBgColor }} !important;
	}
	.skin header .navbar .navbar-nav .nav-link {
		color: {{ $primaryColor }} !important;
	}
	@endif
	
	.skin .custom-checkbox .custom-control-input:checked ~ .custom-control-label::before {
		background-color: {{ $primaryBgColor10 }};
	}
	
	.skin .custom-checkbox .custom-control-input:indeterminate ~ .custom-control-label::before {
		background-color: {{ $primaryBgColor10 }};
	}
	
	.skin .custom-radio .custom-control-input:checked ~ .custom-control-label::before {
		background-color: {{ $primaryBgColor10 }};
	}
	
	.skin .custom-radio .custom-control-input:disabled:checked ~ .custom-control-label::before {
		background-color: {{ $primaryBgColor10d }};
	}
	
	{{-- Swal Alert --}}
	.skin .swal2-styled.swal2-confirm {
		background-color: {{ $primaryBgColor }} !important;
		color: #fff !important;
	}
	.skin .swal2-actions:not(.swal2-loading) .swal2-styled:active {
		background-image: linear-gradient(rgba(0,0,0,.2),rgba(0,0,0,.2)) !important;
	}
	.skin .swal2-styled.swal2-confirm:focus {
		box-shadow: 0 0 0 3px {{ $primaryBgColor50 }} !important;
	}
@endif
</style>
