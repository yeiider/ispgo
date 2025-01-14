let mix = require('laravel-mix')
let tailwindcss = require("tailwindcss")

require('./nova.mix')

mix
    .setPublicPath('dist')
    .js('resources/js/tool.js', 'js')
    .vue({version: 3})
    .css('resources/css/tool.css', 'css')
    .postCss('resources/css/tailwind.css', 'css', [
        require("tailwindcss")
    ])
    .nova('ispgo/settings-manager')
