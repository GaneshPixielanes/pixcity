require('jquery-validation');

var $form = $('form[name="client"]');

$form.validate({
    rules:{
        "client[email]":{required: true, email: true, remote: '/client/check-email'},
        "client[firstName]":{required: true, maxlength: 30},
        "client[lastName]":{required: true, maxlength: 30},
        "client[company]":{required: true, maxlength: 50},
        "client[password]":{required: true, maxlength: 30},
        "client[clientInfo][siret]":{required: true, maxlength: 14, minlength:14, number: true},
        "client[clientInfo][address]":{required: true, maxlength: 50},
        "client[clientInfo][postalCode]":{required: true, maxlength: 5, minlength:5, number: true},
        "client[clientInfo][city]":{required: true, maxlength: 50},
        "client[clientInfo][companyType]":{required: true, maxlength: 50},
        "client[plainPassword][first]": {minlength: 8, password: true},
        "client[plainPassword][second]": {minlength: 8, password: true, equalTo: "#client_plainPassword_first"}
    },
    messages: {
        "client[email]": {
            required:"Ce champ est requis",
            remote: "Cet email est déjà utilisé. Merci d’essayer avec un autre email !"
        },
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


jQuery.validator.addMethod("phone", function(value, element) {
    return this.optional( element ) || /^(\+?\d+){9,}$/.test( value );
}, 'Numéro de téléphone invalide');
$(document).ready(function () {

    //Get the SIRET number from the API
    $('#get-company-info').on('click', function(e){
        e.preventDefault();
        $('.enterprise-log').toggle();
        $('.loader-icon').toggle();
        var info = $('#company-info').val();
        var url = ''
        // GET the info using SIRET number
        if(info.length == 14)
        {
            url = 'https://entreprise.data.gouv.fr/api/sirene/v1/siret/'+info;
        }
        else if(info.length == 9)
        {
            url = 'https://entreprise.data.gouv.fr/api/sirene/v1/siren/'+info;
        }
        else
        {
            showManuelRegister();
            return false;
        }

        $.ajax(url,{
            method: 'GET',
            success: function (data) {

                var year = data.etablissement.date_debut_activite.slice(0,4);
                var month = data.etablissement.date_debut_activite.slice(4,6);
                var day = data.etablissement.date_debut_activite.slice(6,8);
                if(info.length == 9)
                {
                    var result = {
                      name:  data.siege_social.nom_raison_sociale,
                      address: data.siege_social.l4_normalisee,
                      siret: data.siege_social.siret,
                      street_address: data.siege_social.l4_normalisee,
                      postal_code: data.siege_social.code_postal,
                      city: data.siege_social.l6_normalisee.match(/[a-zA-Z]+/),
                        creation_date: year+'-'+month+'-'+day

                    };
                }
                else if(info.length == 14)
                {

                    var result = {
                        name:  data.etablissement.l1_normalisee,
                        address: data.etablissement.l4_normalisee,
                        siret: info,
                        street_address: data.etablissement.l4_normalisee,
                        postal_code: data.etablissement.code_postal,
                        city: data.etablissement.l6_normalisee.match(/[a-zA-Z]+/),
                        creation_date: year+'-'+month+'-'+day

                    };
                }
                fillCompanyData(result);
                $('.enterprise-log').toggle();
                $('.loader-icon').toggle();
                $('.registered-log').show();
                $('.unregistered-log').show();
                $('.enterprise-log').hide();
                if(data.total_results == 0)
                {
                    $('#company-not-found').show();
                }
                else{
                    // $('#client_clientInfo_siret').val(result.siret);
                    // $('#client_company').val(result.name);
                    // $('#client_clientInfo_address').val(result.address);
                    // $('#client_clientInfo_postalCode').val(result.postal_code);
                    // $('#client_clientInfo_city').val(result.city);
                    // $('#client_clientInfo_companyCreationDate').val(result.creation_date);
                }

            },
            error: function()
            {
                showManuelRegister();
            }
        });
    });

    function showManuelRegister()
    {
        $('.loader-icon').toggle();
        $('#company-not-found').show();
    }
    $('.reset').on('click', function(e){
        e.preventDefault();
        $('#company-info').val('');
        $('.enterprise-log').show();
        $('#company-not-found').hide();

    });

    $('#manual-registeration-button').on('click', function (e) {
        e.preventDefault();
        $('.company-info').show();
        $('.unregistered-log').show();
        $('#company-not-found').hide();
    });
    $('#company-info').on('keyup', function (e) {
        $.ajax('https://entreprise.data.gouv.fr/api/sirene/v1/suggest/'+$( "#company-info" ).val(), {
            success: function (data) {
                $('#suggestions').html('<ul class="suggestions-list"></ul>');
                $.each(data.suggestions, function(key, value){
                    $('.suggestions-list').append('<li><a href="#" class="suggested-company-name">'+value+'</a></li>');
                });

            }
        });
    });

    $(document).on('click','.suggested-company-name', function(e){
        e.preventDefault();
        var company = $(this).text();

        $.ajax('https://entreprise.data.gouv.fr/api/sirene/v1/full_text/'+company, {
            success: function (data) {
                var index;
                $.each(data.suggestions, function(key, value)
                {
                    if(value == company)
                    {
                        index = key;
                    }
                });
                data = data.etablissement[index];
                var year = data.date_debut_activite.slice(0,4);
                var month = data.date_debut_activite.slice(4,6);
                var day = data.date_debut_activite.slice(6,8);

                var result = {
                    name:  data.l1_normalisee,
                    address: data.l4_normalisee,
                    siret: data.siret,
                    street_address: data.l4_normalisee,
                    postal_code: data.code_postal,
                    city: data.l6_normalisee.match(/[a-zA-Z]+/),
                    creation_date: year+'-'+month+'-'+day

                };

                $('.enterprise-log').toggle();
                $('.loader-icon').hide();
                $('.registered-log').show();
                $('.unregistered-log').show();
                $('.enterprise-log').hide();
                fillCompanyData(result);
            }
        });
    });

    function  fillCompanyData(result) {
        $('.company-name').html(result.name);
        $('.address').html(result.address);
        $('#client_clientInfo_siret').val(result.siret);
        $('#client_company').val(result.name);
        $('#client_clientInfo_address').val(result.address);
        $('#client_clientInfo_postalCode').val(result.postal_code);
        $('#client_clientInfo_city').val(result.city);
        document.getElementById('client_clientInfo_companyCreationDate').valueAsDate = new Date(result.creation_date);

        return true;
    }
});

