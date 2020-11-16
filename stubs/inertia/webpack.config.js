const path = require('path');

module.exports = {
    module: {
        rules: [{
            resourceQuery: /blockType=i18n/,
            type: 'javascript/auto',
            loader: '@intlify/vue-i18n-loader',
        }],
    },
    resolve: {
        alias: {
            '@': path.resolve('resources/js'),
        },
    },
};
