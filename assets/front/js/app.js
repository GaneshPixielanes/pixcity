//------------------------------------
// ♥ Pix.City in the console ♥
//------------------------------------

console.log("%c  _____ _         _____ _ _         \n |  __ (_)       / ____(_) |        \n | |__) |__  __ | |     _| |_ _   _ \n |  ___/ \\ \\/ / | |    | | __| | | |\n | |   | |>  <  | |____| | |_| |_| |\n |_|   |_/_/\\_\\  \\_____|_|\\__|\\__, |\n                               __/ |\n                              |___/ \n\n____________________________________\n\n", 'font-family: Monospace; color: #ef5285');

//------------------------------------
// Polyfills
//------------------------------------

require('./polyfills/toblob');

//------------------------------------
// Vendors
//------------------------------------

window.jQuery = window.$ = require('jquery');

require('jquery-validation');
require('jquery-validation/dist/localization/messages_fr');
require('./vendors/bootstrap.js');
require('./vendors/jquery-ui.min.js');
require('./vendors/isotope.pkgd.min.js');
require('./vendors/jquery.flexslider-min');
require('./vendors/slick.min');
require('./vendors/curvedarrow.js') ;
require('./vendors/jquery.matchHeight-min');
require('./vendors/functions');
require('froala-editor/js/froala_editor.pkgd.min.js');
require('froala-editor/js/third_party/embedly.min');


//------------------------------------
// Require images
//------------------------------------

const imagesCtx = require.context('../images', true, /\.(png|jpg|jpeg|gif|ico|svg|webp)$/);
imagesCtx.keys().forEach(imagesCtx);


//------------------------------------
// App
//------------------------------------


function is_touch_device() {
    var prefixes = ' -webkit- -moz- -o- -ms- '.split(' ');
    var mq = function(query) {
        return window.matchMedia(query).matches;
    }

    if (('ontouchstart' in window) || window.DocumentTouch && document instanceof DocumentTouch) {
        return true;
    }

    // include the 'heartz' as a way to have a non matching MQ to help terminate the join
    // https://git.io/vznFH
    var query = ['(', prefixes.join('touch-enabled),('), 'heartz', ')'].join('');
    return mq(query);
}

jQuery(document).ready(function() {
    //$("body").addClass(is_touch_device() ? "touch" : "no-touch");
    $("body").addClass("no-touch");

    window.addEventListener('touchstart', function onFirstTouch() {
        $("body").removeClass("no-touch");
        $("body").addClass("touch");
        $('[data-toggle="tooltip"]').tooltip('destroy');

        window.removeEventListener('touchstart', onFirstTouch, false);
    }, false);

});

/*
jQuery(window).on('load', function() {
    if(is_touch_device()){
        $('[data-toggle="tooltip"]').tooltip('destroy');
     }
});
*/

//------------------------------------
// Validators
//------------------------------------

jQuery.validator.addMethod("password", function(value, element) {
    return this.optional( element ) || /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d$@$!%*#?&]{8,}$/.test( value );
}, 'Votre mot de passe doit contenir au moins 8 caractères dont au moins 1 chiffre');

jQuery.validator.addMethod("phone", function(value, element) {
    return this.optional( element ) || /^(\+?\d+){9,}$/.test( value );
}, 'Numéro de téléphone invalide');

jQuery.validator.addMethod("maxwords",
    function(value, element, params) {
        var count = countWordsIn(value);
        if(count <= params[0]) {
            return true;
        }
    },
    jQuery.validator.format("Maximum {0} mots")
);

jQuery.validator.addMethod("minwords",
    function(value, element, params) {
        var count = countWordsIn(value);
        if(count >= params[0]) {
            return true;
        }
    },
    jQuery.validator.format("Minimum {0} mots")
);


function countWordsIn(text){
    var countWords = 0;
    if(text){
        text = text.replace(/'/g," ");
        text = text.replace(/(^\s*)|(\s*$)/gi,"");//exclude  start and end white-space
        text = text.replace(/[ ]{2,}/gi," ");//2 or more space to 1
        text = text.replace(/\n /,"\n"); // exclude newline with a start spacing
        countWords = text.split(' ').filter(function(str){return str!="";}).length;
    }

    return countWords;
}

//------------------------------------
// App
//------------------------------------

require('./components/search-form') ;
require('./components/modals') ;
require('./components/ajax-form') ;
require('./components/follow-pixie') ;
require('./components/card-like') ;
require('./components/card-favorite') ;
