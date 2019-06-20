
const firstError = require('../components/first-error') ;
require('../components/avatar-upload') ;
require('../components/address') ;
require('../components/links-collection') ;
require('../components/froala-max') ;

jQuery(document).ready(function() {

    $form = $('form[name="client"]');


    var validator = $form.validate({
        ignore: ".tab-pane:not(.active) :hidden",
        rules: {
            "user[firstname]": {maxlength: 50},
            "user[lastname]": {maxlength: 50},
            "user[currentLocation]": {digits: true, minlength: 5, maxlength: 5},
            "user[plainPassword][first]": {minlength: 8, password: true},
            "user[plainPassword][second]": {minlength: 8, password: true, equalTo: "#user_plainPassword_first"},
            "user[phone]": {minlength: 10, maxlength: 20, phone: true},
            "user[favoriteCategories][]": {required: true, minlength: 3},
            "user[pixie][regions][]": {required: true},
            "user[gender]": {required: true},
            "user[pixie][likeText]": {maxwords: ["500"]},
            "user[pixie][dislikeText]": {maxwords: ["500"]},

            "user[pixie][billing][phone]": {minlength: 10, maxlength: 20, phone: true},



            "user[links][0][url]": {required: true},



            "user[pixie][billing][address][address]": {maxlength: 50},
            "user[pixie][billing][address][zipcode]": {minlength: 5, maxlength: 5},
            "user[pixie][billing][address][city]": {maxlength: 50},
            "user[pixie][billing][address][country]": {maxlength: 50},


        },
        messages: {
            "user[favoriteCategories][]": {
                minlength: "Vous devez sélectionner au moins {0} catégories"
            }
        },
        highlight: function (input) {
            $(input).parents('.form-line').addClass('error');
        },
        unhighlight: function (input) {
            $(input).parents('.form-line').removeClass('error');
        },
        errorPlacement: function (error, element) {
            $(element).parents('.input-container:first').find(".error-message").remove();
            $(element).parents('.input-container:first').append(error);
            firstError.show(false);
        },
        invalidHandler: function (event, validator) {

            for (var i = 0; i < validator.errorList.length; i++) {
                console.log(validator.errorList[i]);
            }

            firstError.show(false);
        }
    });

});