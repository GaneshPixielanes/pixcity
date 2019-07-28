require('jquery-validation');

var $form = $('form[name="client"]');

$form.validate({
    rules:{
        "client[firstName]":{required: true, maxlength: 30},
        "client[lastName]":{required: true, maxlength: 30},
        "client[company]":{required: true, maxlength: 50},
        "client[password]":{required: true, maxlength: 30},
        "client[clientInfo][siret]":{required: true, maxlength: 14, minlength:14, number: true},
        "client[clientInfo][address]":{required: true, maxlength: 50},
        "client[clientInfo][postalCode]":{required: true, maxlength: 5, minlength:5, number: true},
        "client[clientInfo][city]":{required: true, maxlength: 50},
        "client[clientInfo][companyType]":{required: true, maxlength: 50},
    },
    messages: {
        "client[firstName]" : {
            required:"Ce champ est requis",
        },
        "client[lastName]" : {
            required:"Ce champ est requis",
        },
        "client[company]" : {
            required:"Ce champ est requis",
        },
        "client[password]" : {
            required:"Ce champ est requis",
        },
        "client[clientInfo][siret]" : {
            required:"Ce champ est requis",
            minlength:"Merci d’insérer 14 caractères maximum",
            maxlength:"Merci d’insérer au moins 14 caractères"
        },
        "client[clientInfo][address]":{
            required:"Ce champ est requis",
            maxlength:"Merci d’insérer au moins 50 caractères"
        },
        "client[clientInfo][postalCode]":{
            required:"Ce champ est requis",
            maxlength:"Merci d’insérer au moins 5 caractères"
        },
        "client[clientInfo][city]":{
            required:"Ce champ est requis",
        },
        "client[clientInfo][companyType]":{
            required:"Ce champ est requis",
            maxlength:"Merci d’insérer au moins 50 caractères"
        }

    }
});

