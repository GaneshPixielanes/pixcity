require('jquery-validation');

var $form = $('form[name="client"]');

$form.validate({
    rules:{
        "client[email]":{required: true, email: true, remote: '/client/check-email'},
        "client[firstName]":{required: true, maxlength: 30},
        "client[lastName]":{required: true, maxlength: 30},
        "client[company]":{required: true, maxlength: 50},
        "client[password]":{required: true, maxlength: 30},
        "client[clientInfo][siret]":{required: true, maxlength: 14, minlength:14, number: true,remote:''},
        "client[clientInfo][address]":{required: true, maxlength: 50},
        "client[clientInfo][postalCode]":{required: true, maxlength: 5, minlength:5, number: true},
        "client[clientInfo][city]":{required: true, maxlength: 50},
        "client[clientInfo][companyType]":{required: true, maxlength: 50},
        "client[plainPassword][first]": {minlength: 8, password: true},
        "client[plainPassword][second]": {minlength: 8, password: true, equalTo: "#client_plainPassword_first"}
    },
    messages: {
        "client[email]": {
            remote: "Cet email est déjà utilisé. Merci d’essayer avec un autre email !"
        },
        "client[clientInfo][siret]":{
            minlength:"Merci d’insérer 14 caractères maximum",
            maxlength:"Merci d’insérer au moins 14 caractères"
        }

    }
});

