let mix = require('laravel-mix')

require('./nova.mix')

mix
  .setPublicPath('dist')
  .js('resources/js/tool.js', 'js')
    .js('resources/js/field.js','js')
  .vue({ version: 3 })
  .css('resources/css/tool.css', 'css')
  .nova('ispgo/ckeditor')
