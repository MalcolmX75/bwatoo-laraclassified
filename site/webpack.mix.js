const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

/* SASS Files Compilation  */
mix.sass('resources/sass/auth.scss', 'public/assets/resources/css');
mix.sass('resources/sass/auth.rtl.scss', 'public/assets/resources/css');
mix.sass('resources/sass/app.scss', 'public/assets/resources/css');
mix.sass('resources/sass/app.rtl.scss', 'public/assets/resources/css');
mix.sass('resources/sass/admin.scss', 'public/assets/resources/css');
mix.options({
	processCssUrls: false
});

/* AUTH */
/* CSS & JS Files Combination (Concatenation) */
mix.combine(['public/assets/resources/css/auth.css'], 'public/dist/auth/styles.css');
mix.combine(['public/assets/resources/css/auth.rtl.css'], 'public/dist/auth/styles.rtl.css');
mix.combine([
	'public/assets/js/helpers/vanilla.js',
	'public/assets/plugins/jquery/3.3.1/jquery.min.js',
	'public/assets/bootstrap/5.3.6/js/bootstrap.bundle.min.js',
	'public/assets/js/helpers/bootstrap.js',
	'public/assets/js/helpers/cookieManager.js',
	'public/assets/plugins/dompurify/3.2.5/purify.min.js',
	'public/assets/js/helpers/themeDetector.js',
	'public/assets/js/app/theme-switcher.js',
	'public/assets/js/helpers/unsavedFormGuard/locale.js',
	'public/assets/js/helpers/unsavedFormGuard/unsavedFormGuard.js',
	'public/assets/auth/js/script.js',
	'public/assets/auth/js/two-factor-otp-form.js',
	'public/assets/js/app/auth-fields.js'
], 'public/dist/auth/scripts.js');

/* FRONT */
/* CSS & JS Files Combination (Concatenation) */
mix.combine(['public/assets/resources/css/app.css'], 'public/dist/front/styles.css');
mix.combine(['public/assets/resources/css/app.rtl.css'], 'public/dist/front/styles.rtl.css');
mix.combine([
	'public/assets/js/helpers/vanilla.js',
	'public/assets/js/helpers/uri.js',
	'public/assets/js/helpers/cookieManager.js',
	'public/assets/plugins/dompurify/3.2.5/purify.min.js',
	'public/assets/js/helpers/themeDetector.js',
	'public/assets/js/app/theme-switcher.js',
	'public/assets/js/helpers/unsavedFormGuard/locale.js',
	'public/assets/js/helpers/unsavedFormGuard/unsavedFormGuard.js',
	'public/assets/js/helpers/global.js',
	'public/assets/js/helpers/httpRequest.js',
	
	'public/assets/plugins/jquery/3.3.1/jquery.min.js',
	'public/assets/plugins/jqueryui/1.13.2/jquery-ui.min.js',
	// 'public/assets/plugins/popper.js/2.9.3/popper.min.js',
	// 'public/assets/bootstrap/5.3.6/js/bootstrap.min.js',
	// Bundled JS files (bootstrap.bundle.js and minified bootstrap.bundle.min.js) include Popper, but not jQuery.
	'public/assets/bootstrap/5.3.6/js/bootstrap.bundle.min.js',
	'public/assets/js/helpers/bootstrap.js',
	'public/assets/plugins/jquery.fs.scroller/jquery.fs.scroller.min.js',
	'public/assets/plugins/social-media/js/social-share.js',
	'public/assets/plugins/jquery-parallax/1.1/jquery.parallax-1.1.js',
	'public/assets/plugins/jquery-nice-select/js/jquery.nice-select.min.js',
	'public/assets/plugins/jquery.nicescroll/dist/jquery.nicescroll.min.js',
	'public/assets/plugins/owlcarousel/owl.carousel.js',
	'public/assets/plugins/pnotify/5.2.0/dist/PNotify.js',
	'public/assets/plugins/sweetalert2/11.21.0/sweetalert2.all.min.js',
	'public/assets/plugins/autoComplete.js/10.2.7/autoComplete.min.js',
	'public/assets/plugins/counter-up/2.0.2/dist/index.js',
	'public/assets/plugins/busy-load/0.1.2/app.min.js',
	'public/assets/plugins/larapen/hideMaxListItems/hideMaxListItems.js',
	'public/assets/plugins/larapen/form-validation/form-validation.js',
	
	'public/assets/js/ajaxSetup.js',
	'public/assets/js/script.js',
	'public/assets/js/app/autocomplete.cities.js',
	'public/assets/js/app/auth-fields.js',
	'public/assets/js/app/show.phone.js',
	'public/assets/js/app/make.favorite.js'
], 'public/dist/front/scripts.js');

/* ADMIN PANEL */
/* CSS & JS Files Combination (Concatenation) */
mix.combine(['public/assets/resources/css/admin.css'], 'public/dist/admin/styles.css');
mix.combine([
	'public/assets/js/helpers/vanilla.js',
	'public/assets/js/helpers/uri.js',
	'public/assets/js/helpers/cookieManager.js',
	'public/assets/plugins/dompurify/3.2.5/purify.min.js',
	'public/assets/js/helpers/themeDetector.js',
	'public/assets/js/app/theme-switcher.js',
	'public/assets/js/helpers/unsavedFormGuard/locale.js',
	'public/assets/js/helpers/unsavedFormGuard/unsavedFormGuard.js',
	'public/assets/js/helpers/global.js',
	'public/assets/js/helpers/httpRequest.js',
	
	'public/assets/plugins/jquery/3.3.1/jquery.min.js',
	'public/assets/plugins/popper.js/2.9.3/popper.min.js',
	'public/assets/bootstrap/5.3.6/js/bootstrap.min.js',
	'public/assets/js/helpers/bootstrap.js',
	'public/assets/admin/js/app.js',
	'public/assets/plugins/perfect-scrollbar/0.7.1/perfect-scrollbar.jquery.min.js',
	'public/assets/plugins/sparkline/sparkline.js',
	'public/assets/admin/js/waves.js',
	'public/assets/admin/js/sidebarmenu.js',
	'public/assets/admin/js/feather.min.js',
	'public/assets/admin/js/custom.js',
	'public/assets/plugins/pnotify/5.2.0/dist/PNotify.js',
	'public/assets/plugins/pnotify/5.2.0/modules/font-awesome5/PNotifyFontAwesome5.js',
	'public/assets/plugins/pnotify/5.2.0/modules/font-awesome5-fix/PNotifyFontAwesome5Fix.js',
	'public/assets/plugins/pnotify/5.2.0/modules/confirm/PNotifyConfirm.js',
	'public/assets/plugins/sweetalert2/11.21.0/sweetalert2.all.min.js',
	'public/assets/plugins/pace-js/1.0.2/pace.min.js',
	'public/assets/plugins/busy-load/0.1.2/app.min.js',
	
	'public/assets/js/ajaxSetup.js',
	'public/assets/js/app/auth-fields.js'
], 'public/dist/admin/scripts.js');

/* Disable Compilation Notification */
/* mix.disableNotifications(); */

/*
mix.webpackConfig({
	stats: {
		children: true, // Display child compilations
		warnings: true, // Display warnings
		errors: true    // Display errors
	}
});
*/

/* Cache Busting */
mix.version();
