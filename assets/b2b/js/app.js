
window.jQuery = window.$ = require('jquery');

require('jquery-validation');
require('jquery-validation/dist/localization/messages_fr');

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
