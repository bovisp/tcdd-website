let mix = require('laravel-mix')
// const tailwindcss = require('tailwindcss')
require('mix-tailwindcss');
require("dotenv").config()
require('laravel-mix-purgecss')

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js').vue({ version: 2 })
   .sass('resources/sass/app.scss', 'public/css')
   .tailwind()
   // .options({
   //    processCssUrls: false,
   //    postCss:  [
   //       tailwindcss('./tailwind.config.js')
   //    ]
   // })
   .options({
      postCss: [require('tailwindcss')]
    })
   .purgeCss({
      enabled: mix.inProduction(),
   })
   .version()
   // .purgeCss()
   .disableNotifications()