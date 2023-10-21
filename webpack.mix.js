const path = require('path');
const glob = require('glob-all');
const PurgecssPlugin = require('purgecss-webpack-plugin');
const mix = require('laravel-mix');
require('laravel-mix-purgecss');

mix.config.webpackConfig.output = {
    chunkFilename: 'js/[name].[contenthash].bundle.js',
    publicPath: '/',
};

mix.babelConfig({
    plugins: ['@babel/plugin-syntax-dynamic-import'],
});

mix.webpackConfig({
    resolve: {
        alias: {
            ziggy: path.resolve('vendor/tightenco/ziggy/dist/js/route.js'),
        },
    },
    module: {
        rules: [
            {
                test: /\.js?$/,
                use: [{
                    loader: 'babel-loader',
                    options: mix.config.babel()
                }]
            }
        ]
    },
    plugins: [
        new PurgecssPlugin({
            paths: glob.sync([
                path.join(__dirname, "resources/views/**/*.blade.php"),
                path.join(__dirname, "resources/views/vendor/**/*.blade.php"),
                path.join(__dirname, "resources/js/components/**/*.vue"),
                path.join(__dirname, "resources/js/*.js"),
            ]),
            whitelistPatterns: ['pl-*', 'jp-card*']
        })
    ]
})
.options({
    cssNano: {
        discardComments: {
            removeAll: true
        },
        discardDuplicates: true,
        discardEmpty: true,
    }
})

mix.autoload({
    jquery: ['$', 'window.jQuery', 'jQuery'],
    vue: ['Vue','window.Vue']
})

// then this part
mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .purgeCss({
        whitelistPatterns: [/modal-backdrop/,  /modal-open/, /fade/, /show/, /pl-*/, /jp-card*/],
    })
    .version()