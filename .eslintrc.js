module.exports = {
    extends: [
        'plugin:vue/strongly-recommended',
    ],
    rules: {
        eqeqeq: 0,
        indent: ['error', 4],
        'vue/component-tags-order': ['error', {
            'order': [
                ['template', 'script'],
                'style',
            ],
        }],
        'vue/eqeqeq': 0,
        'vue/html-indent': ['error', 4, {
            'attribute': 1,
            'baseIndent': 1,
            'closeBracket': 0,
            'alignAttributesVertically': false,
            'ignores': [],
        }],
        'vue/max-attributes-per-line': ['error', {
            'singleline': 1,
            'multiline': {
                'max': 1,
                'allowFirstLine': false,
            },
        }],
        'vue/no-duplicate-attributes': ['error', {
            'allowCoexistClass': true,
            'allowCoexistStyle': true,
        }],
        'vue/require-prop-types': 0,
    },
};
