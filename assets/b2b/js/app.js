
window.jQuery = window.$ = require('jquery');

require('jquery-validation');
require('jquery-validation/dist/localization/messages_fr');


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

