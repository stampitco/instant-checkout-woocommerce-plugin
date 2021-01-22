const mix = require('laravel-mix');

mix.webpackConfig(webpack => {
    return {
        externals: {
            jquery: 'jQuery'
        }
    };
});

mix.js('assets/admin/settings/js/index.js', 'assets/dist/admin/js/settings.js');