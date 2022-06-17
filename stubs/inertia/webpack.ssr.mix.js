const mix = require('laravel-mix');
const webpackNodeExternals = require('webpack-node-externals');

mix.js('resources/js/ssr.js', 'public/js')
    .vue({
        version: 3,
        useVueStyleLoader: true,
        options: { optimizeSSR: true },
    })
    .alias({
        '@': 'resources/js',
        ziggy: 'vendor/tightenco/ziggy/dist/vue',
    })
    .webpackConfig({
        target: 'node',
        externals: [webpackNodeExternals()],
    });
