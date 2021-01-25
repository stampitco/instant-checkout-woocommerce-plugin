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