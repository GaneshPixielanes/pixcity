//var webpack = require('webpack');
//const CopyWebpackPlugin = require('copy-webpack-plugin');

var Encore = require('@symfony/webpack-encore');

Encore
    // the project directory where compiled assets will be stored
    .setOutputPath('public/build/')

    // the public path used by the web server to access the previous directory
    .setPublicPath('/build')
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())

    // create hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    // uncomment if you use Sass/SCSS files
     .enableSassLoader()

     .enablePostCssLoader()

    // uncomment for legacy applications that require $/jQuery as a global variable
    //.autoProvidejQuery()

    .enableVersioning()


    //--------------------------------------------------------------------------------------
    // CMS
    //--------------------------------------------------------------------------------------

    .addEntry('admin/js/app', './assets/admin/js/app.js')
    .addEntry('admin/js/pages/dashboard', './assets/admin/js/pages/dashboard.js')
    .addEntry('admin/js/pages/users', './assets/admin/js/pages/users.js')
    .addEntry('admin/js/pages/users-list', './assets/admin/js/pages/users-list.js')
    .addEntry('admin/js/pages/cards-templates', './assets/admin/js/pages/cards-templates.js')
    .addEntry('admin/js/pages/categories', './assets/admin/js/pages/categories.js')
    .addEntry('admin/js/pages/cards-projects', './assets/admin/js/pages/cards-projects.js')
    .addEntry('admin/js/pages/cards-projects-list', './assets/admin/js/pages/cards-projects-list.js')
    .addEntry('admin/js/pages/cards', './assets/admin/js/pages/cards.js')
    .addEntry('admin/js/pages/cards-list', './assets/admin/js/pages/cards-list.js')
    .addEntry('admin/js/pages/menus', './assets/admin/js/pages/menus.js')
    .addEntry('admin/js/pages/slide', './assets/admin/js/pages/slide.js')
    .addEntry('admin/js/pages/walls', './assets/admin/js/pages/walls.js')
    .addEntry('admin/js/pages/pagecategory', './assets/admin/js/pages/pagecategory.js')
    .addEntry('admin/js/pages/transactions', './assets/admin/js/pages/transactions.js')

    .addEntry('admin/js/shared/address', './assets/admin/js/shared/address.js')

    .addStyleEntry('admin/css/app', './assets/admin/scss/app.scss')


    //--------------------------------------------------------------------------------------
    // FRONT
    //--------------------------------------------------------------------------------------

    .addEntry('front/js/app', './assets/front/js/app.js')
    .addEntry('front/js/pages/homepage', './assets/front/js/pages/homepage.js')
    .addEntry('front/js/pages/search', './assets/front/js/pages/search.js')
    .addEntry('front/js/pages/card', './assets/front/js/pages/card.js')
    .addEntry('front/js/pages/user-register', './assets/front/js/pages/user-register.js')
    .addEntry('front/js/pages/pixie-register', './assets/front/js/pages/pixie-register.js')
    .addEntry('front/js/pages/client-register', './assets/front/js/pages/client-register.js')
    .addEntry('front/js/pages/pixie-settings', './assets/front/js/pages/pixie-settings.js')
    .addEntry('front/js/pages/pixie-projects-list', './assets/front/js/pages/pixie-projects-list.js')
    .addEntry('front/js/pages/pixie-card-creation', './assets/front/js/pages/pixie-card-creation.js')
    .addEntry('front/js/pages/user-collections', './assets/front/js/pages/user-collections.js')
    .addEntry('front/js/pages/user-calendars', './assets/front/js/pages/user-calendars.js')
    // .addEntry('front/js/pages/pixie-address', './assets/front/js/pages/pixie-address.js')

    .addEntry('js/simplebar', './assets/v2/node_modules/simplebar/dist/simplebar.min.js')
    .addEntry('js/custom', './assets/v2/js/custom.js')
    .addEntry('js/map', './assets/front/js/pages/map.js')
    .addEntry('js/slick', './assets/v2/js/slick.min.js')
    .addEntry('js/collection', './assets/v2/js/collection.js')
    .addEntry('js/bootstrap-select', './assets/v2/js/bootstrap-select.min.js')
    // .addEntry('js/lazy', './assets/v2/js/jquery.lazy.min.js')
    .addEntry('js/dropzone', './assets/v2/js/dropzone.js')
    .addEntry('js/scroll', './assets/front/js/pages/scroll-jax.js')
    .addEntry('js/scrollMapCount', './assets/front/js/pages/scroll-map-count.js')
    .addEntry('js/scrollTopCard', './assets/front/js/pages/scroll-top-cards.js')
    .addEntry('js/scrollBecomeCityMaker', './assets/front/js/pages/become-city-mker.js')
    // B2B

    .addEntry('b2b/js/app', './assets/b2b/js/app.js')
    .addEntry('b2b/js/client_registration', './assets/b2b/js/client_registration.js')
    .addEntry('b2b/js/client_profile', './assets/b2b/js/client_profile.js')
    // .addEntry('b2b/js/mission', './assets/b2b/js/mission.js')
    .addEntry('b2b/js/pack', './assets/b2b/js/pack.js')




    // CSS
    .addStyleEntry('front/css/app', './assets/front/scss/app.scss')



    .addStyleEntry('css/simplebar', './assets/v2/node_modules/simplebar/dist/simplebar.css')
    .addStyleEntry('css/bootstrap-select', './assets/v2/css/bootstrap-select.min.css')
    .addStyleEntry('css/pretty-checkbox', './assets/v2/node_modules/pretty-checkbox/dist/pretty-checkbox.min.css')
    .addStyleEntry('css/slick', './assets/v2/node_modules/slick-carousel/slick/slick.css')
    .addStyleEntry('css/slick-theme', './assets/v2/node_modules/slick-carousel/slick/slick-theme.css')
    .addStyleEntry('css/lightgallery', './assets/v2/node_modules/lightgallery/src/css/lightgallery.css')
    .addStyleEntry('css/style', './assets/v2/css/style.css')
    .addStyleEntry('css/main', './assets/v2/css/main.scss')
    .addStyleEntry('css/header','./assets/front/internal-page-styles.css')
    // .addStyleEntry('css/dropzone', './assets/v2/css/dropzone.css')
    // .addStyleEntry('css/dropzon', './assets/v2/css/dropzon.css')
    // .addStyleEntry('css/simple-sidebar', './assets/v2/css/simple-sidebar.css')




;

module.exports = Encore.getWebpackConfig();
