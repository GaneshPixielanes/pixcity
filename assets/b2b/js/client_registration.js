var $form = $("form[name='client']");

$form.validate({
    rules:{
        "client[email]": {required: true, maxlength: 30, email: true},
        "client[firstName]": {required: true, maxlength: 30},
        "client[lastName]": {required: true, maxlength: 30},
        "client[company]": {required: true, maxlength: 50},
        "client[clientInfo][siret]": {required: true, maxlength: 14, minlength: 14, number: true},
        "client[clientInfo][address]": {required: true, maxlength: 50},
        "client[clientInfo][city]": {required: true, maxlength: 54},
        "client[clientInfo][companyType]": {required: true, maxlength: 50},
        "client[clientInfo][postalCode]": {required: true, number: true, minlength: 5, maxlength: 5}
    }
});


$('.btn-success').on('click', function(){
    $form.valid();
});