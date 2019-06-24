

const firstError = require('../components/first-error') ;
require('../components/avatar-upload') ;
require('../components/address') ;
require('../components/links-collection') ;
require('../components/froala-max') ;
require('../components/cropper') ;

jQuery(document).ready(function() {
    //---------------------------------------------
    // Refresh Froala on tab change (dispatch a window resize event)
    //---------------------------------------------

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        $(window).resize();
        checkPixieBillingMethod();
        checkPixieStatus();

        var id = $(".tab-pane.active").attr("id");
        switch(id){
            case "security-tab":
                $(".password-protected-form, .save-container").hide();
                break;
            default:
                $(".password-protected-form, .save-container").show();

        }

        $("html, body").animate({ scrollTop: 0 }, 600);
    });


    $form = $('form[name="user"]');

    //---------------------------------------------
    // Update the form depending on the pixie status
    //---------------------------------------------

    function isPixieStatusCompany() {
        return $("#user_pixie_billing_status").val() === "company";
    }

    function isPixieStatusIndividual() {
        return $("#user_pixie_billing_status").val() !== "company";
    }

    function checkPixieStatus() {
        if (isPixieStatusCompany()) {
            $("#user_pixie_billing_companyName").parents(".form-row").show();
            $("#user_pixie_billing_firstname, #user_pixie_billing_lastname").parents(".form-row").hide();
            $("#user_pixie_billing_tva").prop("required", true).parents(".form-row").find("label").first().addClass("oblig");
        }
        else {
            $("#user_pixie_billing_companyName").parents(".form-row").hide();
            $("#user_pixie_billing_firstname, #user_pixie_billing_lastname").parents(".form-row").show();
            $("#user_pixie_billing_tva").prop("required", false).parents(".form-row").find("label").first().removeClass("oblig");
        }
    }

    $("#user_pixie_billing_status").change(checkPixieStatus);
    checkPixieStatus();


    //---------------------------------------------
    // Update the form depending on the pixie billing method
    //---------------------------------------------

    function isPixieBillingBankTransfer() {
        return $("#user_pixie_billing_billingType").val() === "banktransfer";
    }

    function checkPixieBillingMethod() {

        if (isPixieBillingBankTransfer()) {
            $("#user_pixie_billing_billingCountry, #user_pixie_billing_billingIban, #user_pixie_billing_billingBic, #user_pixie_billing_rib").parents(".form-row").show();
        }
        else {
            $("#user_pixie_billing_billingCountry, #user_pixie_billing_billingIban, #user_pixie_billing_billingBic, #user_pixie_billing_rib").parents(".form-row").hide();
        }
    }

    $("#user_pixie_billing_billingType").change(checkPixieBillingMethod);
    checkPixieBillingMethod();


    //---------------------------------------------
    // Update the form depending on Pixie country
    // Display TVA only for France
    //---------------------------------------------

    function isPixieTva() {
        return ($("#user_pixie_billing_address_country").val() === "FR" && $("#user_pixie_billing_status").val() === "company");
    }

    function checkPixieCountry() {

        if($("#user_pixie_billing_address_country").val() === "FR"){
            $("#user_pixie_billing_tva").parents(".form-row").show();
        }
        else{
            $("#user_pixie_billing_tva").parents(".form-row").hide();
        }

    }

    $("#user_pixie_billing_address_country").change(checkPixieCountry);
    checkPixieCountry();


    //---------------------------------------------
    // Submit form
    //---------------------------------------------

    var validator = $form.validate({
        ignore: ".tab-pane:not(.active) :hidden",
        rules: {
            "user[currentLocation]": {digits: true, minlength: 5, maxlength: 5},
            "user[plainPassword][first]": {minlength: 8, password: true},
            "user[plainPassword][second]": {minlength: 8, password: true, equalTo: "#user_plainPassword_first"},
            "user[phone]": {minlength: 10, maxlength: 20, phone: true},
            "user[favoriteCategories][]": {required: true, minlength: 3},
            "user[pixie][regions][]": {required: true},
            "user[gender]": {required: true},
            "user[pixie][likeText]": {maxwords: ["500"]},
            "user[pixie][dislikeText]": {maxwords: ["500"]},

            "user[pixie][billing][companyName]": {required: isPixieStatusCompany, maxlength: 50},
            "user[pixie][billing][firstname]": {required: isPixieStatusIndividual, maxlength: 50},
            "user[pixie][billing][lastname]": {required: isPixieStatusIndividual, maxlength: 50},

            "user[pixie][billing][tva]": {required: isPixieTva},

            "user[pixie][billing][phone]": {minlength: 10, maxlength: 20, phone: true},

            "user[pixie][billing][address][address]": {maxlength: 50},
            "user[pixie][billing][address][zipcode]": {minlength: 5, maxlength: 5},
            "user[pixie][billing][address][city]": {maxlength: 50},
            "user[pixie][billing][address][country]": {maxlength: 50},

            "user[pixie][billing][billingName]": {required: isPixieBillingBankTransfer, maxlength: 50},
            "user[pixie][billing][billingCountry]": {required: isPixieBillingBankTransfer},
            "user[pixie][billing][billingIban]": {required: isPixieBillingBankTransfer},
            "user[pixie][billing][billingBic]": {required:  isPixieBillingBankTransfer},
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


    //---------------------------------------------
    // Limit the number of selected regions

    $('[name="user[pixie][regions][]"]').on('change', function (evt) {
        console.log('hi');
        if ($('[name="user[pixie][regions][]"]:checked').length > 2) {
            this.checked = false;
            // $(this).parents(".plCheck").removeClass("clicked");
        }

        $('[name="user[pixie][regions][]"]:checked').each(function () {
            console.log($(this).text());
            $('[data-original-title="'+$(this+" option:selected").text()+'"]').addClass('active');
        });
    });

    $('[name="user[userRegion][0]"]').on('change', function (evt) {
        console.log($("[name='user[userRegion][0]'] option:selected").text());
        $('[data-original-title]').removeClass('active');
        $('[data-original-title="'+$("[name='user[userRegion][0]'] option:selected").text()+'"]').addClass('active');
        $('[data-original-title="'+$("[name='user[userRegion][1]'] option:selected").text()+'"]').addClass('active');
        // if ($('[name="user[userRegion][]"]:selected').length > 2) {
        //     this.checked = false;
        //     evt.preventDefault();
        // }

        // $('[name="user[userRegion][]"]:selected').each(function () {
        //     console.log($(this).text());
        // });
    });
    $('[name="user[userRegion][1]"]').on('change', function (evt) {
        console.log($("[name='user[userRegion][0]'] option:selected").text());
        $('[data-original-title]').removeClass('active');
        $('[data-original-title="'+$("[name='user[userRegion][0]'] option:selected").text()+'"]').addClass('active');
        $('[data-original-title="'+$("[name='user[userRegion][1]'] option:selected").text()+'"]').addClass('active');
        // if ($('[name="user[userRegion][]"]:selected').length > 2) {
        //     this.checked = false;
        //     evt.preventDefault();
        // }

        // $('[name="user[userRegion][]"]:selected').each(function () {
        //     console.log($(this).text());
        // });
    });
    //---------------------------------------------
    // Limit the number of selected categories

    var countPhotos;
    var minCategories = 3;
    var maxCategories = 5;

    $('[name="user[favoriteCategories][]"]').on('change', function (evt) {
        if (countCategoriesSelected() > maxCategories) {
            this.checked = false;
            $(this).parents(".plCheck").removeClass("clicked");
        }

        updatePhotoCounter();
    });

    function countCategoriesSelected(){
        return $('[name="user[favoriteCategories][]"]:checked').length;
    }

    function updatePhotoCounter(){
        countPhotos = countCategoriesSelected();
        $(".count-categories-status .current").text(countPhotos);
        $(".count-categories-status").toggleClass("valid", countPhotos >= minCategories);
    }

    updatePhotoCounter();

    //---------------------------------------------
    // Navigation between tabs


    $("#btn-save").click(function (e) {
        e.preventDefault();

        var formIsValid = $form.valid();

        if (formIsValid) {
            $form.submit();
        }
    });

    // $('.region-select').on('select', function () {
    //     console.log('asasdsad');
    //     $('[data-original-title="'+$(this+" option:selected").text()+'"]').addClass('active');
    // });


});
