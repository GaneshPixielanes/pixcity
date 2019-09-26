const firstError = require('../components/first-error') ;
require('../components/avatar-upload') ;
require('../components/address') ;
require('../components/links-collection') ;
require('../components/froala-max') ;
require('../components/cropper') ;


jQuery(document).ready(function() {
    $('#microentreprenuer_type').on('change', function()
    {
        if($(this).val() == 'without_tva')
        {
            $('#user_pixie_billing_tva').val('');
        }
        checkPixieStatus();

    });

    if($('#user_pixie_billing_status').val() == 'company' || $('#user_pixie_billing_status').val() == 'microentrepreneur')
    {
        $("#user_pixie_billing_status option[value='individualregistration']").remove();
    }
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
            $("#user_pixie_billing_companyName, #user_pixie_billing_rcs").parents(".form-row").show();
            $("#user_pixie_billing_firstname, #user_pixie_billing_lastname").parents(".form-row").hide();
            $("#user_pixie_billing_tva").prop("required", true).parents(".form-row").find("label").first().addClass("oblig");
        }
        else {

            $("#user_pixie_billing_companyName, #user_pixie_billing_rcs").parents(".form-row").hide();
            $("#user_pixie_billing_firstname, #user_pixie_billing_lastname").parents(".form-row").show();
            $("#user_pixie_billing_tva").prop("required", false).parents(".form-row").find("label").first().removeClass("oblig");
        }
        if($('#user_pixie_billing_status').val() != 'microentrepreneur')
        {
            $('#microentreprenuer_type').parents('.form-row').hide();
        }
        else
        {
            $('#microentreprenuer_type').parents('.form-row').show();
        }
        if($("#microentreprenuer_type").val() == 'with_tva' || isPixieStatusCompany())
        {
            $("#user_pixie_billing_tva").parents(".form-row").show();
            $("#user_pixie_billing_tva").prop("required", "required").parents(".form-row").find("label").first().addClass("oblig");
        }
        else
        {
            $("#user_pixie_billing_tva").parents(".form-row").hide();
            $("#user_pixie_billing_tva").prop("required", false).parents(".form-row").find("label").first().removeClass("oblig")
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
        if($("#user_pixie_billing_address_country").val() === "FR" && $("#user_pixie_billing_status").val() === "company")
        {
            return true;
        }
        else if($("#user_pixie_billing_address_country").val() === "FR" && $("#user_pixie_billing_status").val() == "microentrepreneur" && $('#microentreprenuer_type').val() == "with_tva")
        {
            return true;
        }
        else
        {
            return false;
        }
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
            "user[pixie][billing][rcs]": {required: isPixieStatusCompany},

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
        if ($('[name="user[pixie][regions][]"]:checked').length > 2) {
            this.checked = false;
            // $(this).parents(".plCheck").removeClass("clicked");
        }

        $('[name="user[pixie][regions][]"]:checked').each(function () {
            console.log($(this).text());
            $('[data-original-title="'+$(this+" option:selected").text()+'"]').addClass('active');
        });
    });

    $(document).on('change','.region-select:first', function (evt) {
        $('[data-original-title]').removeClass('active');
        $('[data-original-title="'+$(".region-select:first option:selected").text()+'"]').addClass('active');
        $('[data-original-title="'+$(".region-select:last option:selected").text()+'"]').addClass('active');
        // if ($('[name="user[userRegion][]"]:selected').length > 2) {
        //     this.checked = false;
        //     evt.preventDefault();
        // }

        // $('[name="user[userRegion][]"]:selected').each(function () {
        //     console.log($(this).text());
        // });
    });
    $(document).on('change','.region-select:last', function (evt) {
        $('[data-original-title]').removeClass('active');
        $('[data-original-title="'+$(".region-select:first option:selected").text()+'"]').addClass('active');
        $('[data-original-title="'+$(".region-select:last option:selected").text()+'"]').addClass('active');
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

$(document).ready(function () {
    if($('.choose-region-drop').length == 2)
    {
        $("#add-cm-region").hide();
    }

    $('[data-original-title="'+$("[name='user[userRegion][]']:first option:selected").text()+'"]').addClass('active');
    $('[data-original-title="'+$("[name='user[userRegion][]']:last option:selected").text()+'"]').addClass('active');

    $(document).on('click','.remove-region',function (e) {
        e.preventDefault();
        $('[data-original-title]').removeClass('active');
        $('[data-original-title="'+$(".region-select:first option:selected").text()+'"]').addClass('active');
        $('[data-original-title="'+$(".region-select:last option:selected").text()+'"]').addClass('active');

        $('.choose-region-drop:last').remove();
       $(this).remove();
        $("#add-cm-region").show();
    });

    $('#add-cm-region').click(function (e) {
        e.preventDefault();
        $select = $('.choose-region-drop').clone();
        $('.choose-region-drop:last').after($select);
        $('.choose-region-drop:last').after('<div class="col-md-2"><a class="remove-region" href="#"><i class="fa fa-times"></i></a></div>');
        $('[data-original-title="'+$(".region-select:last option:selected").text()+'"]').addClass('active');

        $(this).hide();
    });

    $('li > a').on('click', function () {
        if($(this).attr('href') == '#community-manager-tab')
        {
            $('#user_oldPassword').attr('name','inactivePassword');
            $('#user_oldPassword').removeAttr('required');
            $('.password-protected-form').addClass('hidden');
            $('.save-container').addClass('hidden');
            $('#submitform').addClass('hidden');
        }
        else
        {
            $('#user_oldPassword').attr('name','user[oldPassword]');
            $('#user_oldPassword').attr('required','required');
            $('.password-protected-form').removeClass('hidden');
            $('.save-container').removeClass('hidden');
            $('#submitform').removeClass('hidden');
        }

    });


});

