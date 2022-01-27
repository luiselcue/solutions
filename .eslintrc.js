module.exports = {
    extends: ['eslint:recommended', 'plugin:prettier/recommended'],
    rules: {
        'prettier/prettier': 'error',
        'no-unused-vars': 'warn',
        'no-useless-escape': 'warn',
        'no-control-regex': 'warn',
    },
    parser: 'babel-eslint',
    env: {
        browser: true,
        es6: true,
    },
}
