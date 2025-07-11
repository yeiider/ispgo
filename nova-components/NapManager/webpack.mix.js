let mix = require('laravel-mix')
let NovaExtension = require('laravel-nova-devtool')
const webpack = require('webpack')

mix.extend('nova', new NovaExtension())

mix
  .setPublicPath('dist')
  .js('resources/js/tool.js', 'js')
  .vue({ version: 3 })
  .postCss('resources/css/tool.css', 'css', [
    require('tailwindcss'),
    require('autoprefixer'),
  ])
  .nova('ispgo/nap-manager')
  .webpackConfig({
    plugins: [
      new webpack.DefinePlugin({
        'process.env': {
          MIX_GOOGLE_MAPS_API_KEY: JSON.stringify(process.env.MIX_GOOGLE_MAPS_API_KEY)
        }
      })
    ]
  })
  .version()
