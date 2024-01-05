const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */
  mix.combine(
      [
          "public/assets/vendor/css/rtl/core.css",
          "public/assets/vendor/css/rtl/theme-semi-dark.css",
          "public/assets/css/demo.css",
          "public/assets/css/jquery-filestyle.min.css",
          "public/commonjs/jquery.filer.css",

          "public/assets/vendor/libs/select2/select2.css",
          "public/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css",
          "public/assets/vendor/libs/apex-charts/apex-charts.css",
          "public/assets/vendor/libs/sweetalert2/sweetalert2.css",
          "public/assets/css/lightbox.min.css",
          "public/assets/vendor/libs/flatpickr/flatpickr.css",
         
      ],
      "public/css/app.css"
  ).options({
      processCssUrls: false,
  });
   mix.minify("public/css/app.css");
    mix.combine(
        [
            "public/assets/vendor/libs/jquery/jquery.js",
            "public/assets/vendor/js/bootstrap.js",
            "public/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js",
            "public/assets/vendor/js/menu.js",
            "public/commonjs/jquery.filer.min.js",
            "public/assets/vendor/libs/sweetalert2/sweetalert2.js",
            "public/assets/vendor/libs/typeahead-js/typeahead.js",
            "public/assets/vendor/libs/block-ui/block-ui.js",
            "public/assets/js/main.js",
            "public/assets/vendor/libs/apex-charts/apexcharts.js",
            "public/assets/js/dashboards-analytics.js",
            "public/assets/js/cards-analytics.js",
            "public/commonjs/jquery.validate.min.js",
            "public/assets/vendor/libs/select2/select2.js",
            "public/assets/js/bootstrap-filestyle.min.js",
            "public/assets/vendor/libs/flatpickr/flatpickr.js",
            
        ],
        "public/js/app.js"
    );

  mix.minify("public/js/app.js");