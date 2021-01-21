const mix = require('laravel-mix');

mix.webpackConfig(webpack => {
    return {
        externals: {
            jquery: 'jQuery'
        }
    };
});