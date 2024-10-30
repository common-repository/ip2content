const mix = require('laravel-mix');

mix
    .js('resources/js/vue_modules/ip_conditions/app.js', 'assets/js/ip_conditions.js')
    .js('resources/js/shortcode.js', 'assets/js/shortcode.js')
    .js('resources/js/total_views_counter.js', 'assets/js/total_views_counter.js')
    .js('resources/js/data_bridge.js', 'assets/js/data_bridge.js')
    .sass('resources/js/vue_modules/ip_conditions/assets/styles/style.scss', 'assets/styles/ip_conditions.css')
    .copyDirectory('resources/images', 'assets/images')
    .options({
        processCssUrls: false
    })
    .vue()
