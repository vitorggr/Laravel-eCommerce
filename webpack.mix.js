let mix = require('laravel-mix');

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

mix
    .styles(
        [
            'vendor/twitter/bootstrap/dist/css/bootstrap.css',
            'vendor/fortawesome/font-awesome/css/fontawesome.css',
            'vendor/driftyco/ionicons/css/ionicons.css',
            'vendor/select2/select2/dist/css/select2.css',
            'resources/assets/admin-lte/css/AdminLTE.min.css',
            'resources/assets/admin-lte/css/skins/skin-purple.min.css',
            'vendor/datatables/datatables/media/css/jquery.dataTables.css',
            'resources/assets/css/admin.css'
        ],
        'public/css/admin.min.css'
    )
    .scripts(
        [
            'resources/js/jquery-2.2.3.min.js',
            'vendor/twitter/bootstrap/dist/js/bootstrap.js',
            'vendor/select2/select2/dist/js/select2.js',
            'vendor/datatables/datatables/media/js/jquery.dataTables.js',
            'resources/admin-lte/js/app.js'
        ],
        'public/js/admin.min.js'
    )
    .styles(
        [
            'vendor/twitter/bootstrap/dist/css/bootstrap.css',
            'vendor/fortawesome/font-awesome/css/fontawesome.css',
            'vendor/select2/select2/dist/css/select2.css',
            'resources/css/drift-basic.min.css',
            'resources/css/front.css'
        ],
        'public/css/style.min.css'
    )
    .scripts(
        [
            'vendor/twitter/bootstrap/dist/js/bootstrap.js',
            'vendor/select2/select2/dist/js/select2.js',
            'resources/js/owl.carousel.min.js',
            'resources/js/Drift.min.js'
        ],
        'public/js/front.min.js'
    )
    .copyDirectory('vendor/datatables/datatables/media/images', 'public/images')
    .copyDirectory('vendor/fortawesome/font-awesome/webfonts', 'public/fonts')
    .copyDirectory('resources/admin-lte/img', 'public/img')
    .copyDirectory('resources/images', 'public/images')
    .copy('resources/js/scripts.js', 'public/js/scripts.js')
    .copy('resources/js/custom.js', 'public/js/custom.js');

/*
|-----------------------------------------------------------------------
| BrowserSync
|-----------------------------------------------------------------------
|
| BrowserSync refreshes the Browser if file changes (js, sass, blade.php) are
| detected.
| Proxy specifies the location from where the app is served.
| For more information: https://browsersync.io/docs
*/
mix.browserSync({
    proxy: 'http://localhost:8000',
    host: 'localhost',
    open: true,
    watchOptions: {
        usePolling: false
    },
    files: [
        'app/**/*.php',
        'resources/views/**/*.php',
        'public/js/**/*.js',
        'public/css/**/*.css',
        'resources/docs/**/*.md'
    ]
});