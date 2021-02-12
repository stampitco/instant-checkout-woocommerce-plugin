const mix = require('laravel-mix');

mix.webpackConfig(webpack => {
    return {
        externals: {
            jquery: 'jQuery'
        }
    };
});

mix.sass('assets/admin/settings/scss/settings.scss', 'assets/dist/admin/css/settings.css');
mix.js('assets/admin/settings/js/index.js', 'assets/dist/admin/js/settings.js');

mix.js('assets/public/checkout/js/index.js', 'assets/dist/public/js/checkout.js');
mix.sass('assets/public/checkout/scss/checkout.scss', 'assets/dist/public/css/checkout.css');
mix.copyDirectory( 'assets/public/checkout/images', 'assets/dist/public/images/checkout');
