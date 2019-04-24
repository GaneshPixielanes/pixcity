const firstError = require('../components/first-error') ;
require('../components/address') ;
$(document).ready(function() {
    $form = $('form[name="card"]');

    $("[name='card[address][address]']").addClass('gm-address');
    $("[name='card[address][city]']").addClass('gm-city');
    $("[name='card[address][country]']").addClass('gm-country');
    $("[name='card[address][zipcode]']").addClass('gm-address');

    var validator = $form.validate({
        ignore: ".tab-pane:not(.active) :hidden",
        rules: {
            "card[name]": {required: true},
            "card[region]": {required: true},
            "card[address][address]": {maxlength: 50, required: true},
            "card[address][city]": {required: true},
            "card[address][country]": {required: true},
            "card[address][zipcode]": {required: true},
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

    $('#submitCardBtn').click(function () {
        var formIsValid = $form.valid();
        console.log(formIsValid);
    });

});