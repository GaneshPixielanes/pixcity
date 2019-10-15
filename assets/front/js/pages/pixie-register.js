const firstError = require('../components/first-error') ;
require('../components/avatar-upload') ;
require('../components/address') ;
require('../components/links-collection') ;
require('../components/froala-max') ;

jQuery(document).ready(function() {
    //---------------------------------------------
    // Refresh Froala on tab change (dispatch a window resize event)
    //---------------------------------------------

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        $(window).resize();
        checkPixieBillingMethod();
        checkPixieStatus();

        var slug = $(".tab-pane.active").attr("data-slug");
        if(slug){
            $(".intro-tab").hide();
            $(".intro-tab-"+slug).show();
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

            "user[pixie][billing][companyName]": {required: isPixieStatusCompany, maxlength: 50},
            "user[pixie][billing][firstname]": {required: isPixieStatusIndividual, maxlength: 50},
            "user[pixie][billing][lastname]": {required: isPixieStatusIndividual, maxlength: 50},
            
            "user[links][0][url]": {required: true},

            "user[pixie][billing][tva]": {required: isPixieTva},

            "user[pixie][billing][address][address]": {maxlength: 50},
            "user[pixie][billing][address][zipcode]": {minlength: 5, maxlength: 5},
            "user[pixie][billing][address][city]": {maxlength: 50},
            "user[pixie][billing][address][country]": {maxlength: 50},

            "user[pixie][billing][billingName]": {required: isPixieBillingBankTransfer, maxlength: 50},
            "user[pixie][billing][billingCountry]": {required: isPixieBillingBankTransfer},
            "user[pixie][billing][billingIban]": {required: isPixieBillingBankTransfer},
            "user[pixie][billing][billingBic]": {required: isPixieBillingBankTransfer},
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
            $(this).parents(".plCheck").removeClass("clicked");
        }

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
    // Automatically select user region

    $("#user_birthLocation").change(function(){
        if ($('[name="user[pixie][regions][]"]:checked').length < 2) {
            $('[name="user[pixie][regions][]"][value="'+$(this).val()+'"]').parents(".form-check-label").click();
        }
    });


    //---------------------------------------------
    // Navigation between tabs

    $("#next1").click(function (e) {
        e.preventDefault();

        var formIsValid = $form.valid();

        if (formIsValid) {
            $(".nav-tabs .tab2 a").click();
        }
    });

    $("#next2").click(function (e) {
        e.preventDefault();

        var formIsValid = $form.valid();

        if (formIsValid) {
            $(".nav-tabs .tab3 a").click();
        }
    });

    $("#prev2").click(function (e) {
        e.preventDefault();
        $(".nav-tabs .tab1 a").click();
    });

    $("#next3").click(function (e) {
        e.preventDefault();

        var formIsValid = $form.valid();

        if (formIsValid) {
            $(".nav-tabs .tab4 a").click();
        }
    });

    $("#prev3").click(function (e) {
        e.preventDefault();
        $(".nav-tabs .tab2 a").click();
    });

    $("#prev4").click(function (e) {
        e.preventDefault();
        $(".nav-tabs .tab3 a").click();
    });

    $("#submit-form").click(function (e) {
        e.preventDefault();

        var formIsValid = $form.valid();

        if(formIsValid) {
            $("#modal-rgpd").modal("show");
        }
    });

    $("#form-final-submit").click(function(e){
        e.preventDefault();

        $form.submit();
    });

    $("[name='check_legals']").change(function(){
        $("#form-final-submit").toggleClass("disabled", !$(this).is(":checked"));
    });


    // Activate Instagram input box on load
    $('.add-item').click();

    //Remove the delete option for the first element i.e; Instagram.
    $('a.delete-item:first').html('');

    $( "[name='user[firstname]']" ).change(function() {
        $(".fname").text($( this ).val());
    });
    $( "[name='user[lastname]']" ).change(function() {
        $(".lname").text($( this ).val());
    });
    $( "[name='user[phone]']" ).change(function() {
        $(".telep").text($( this ).val());
    });
    $( "[name='user[birthdate][day]']" ).change(function() {
        $(".dobd").text($( this ).val());
    });
    $( "[name='user[birthdate][month]']" ).change(function() {
        $(".dobm").text($( this ).val());
    });
    $( "[name='user[birthdate][year]']" ).change(function() {
        $(".doby").text($( this ).val());
    });
    $( "[name='user[pixie][billing][address][address]']" ).change(function() {
        $(".address").text($( this ).val());
    });
    $( "[name='user[pixie][billing][address][zipcode]']" ).change(function() {
        $(".zipcode").text($( this ).val());
    });
    $( "[name='user[pixie][billing][address][city]']" ).change(function() {
        $(".city").text($( this ).val());
    });
    $( "[name='user[pixie][billing][address][country]']" ).change(function() {
        $(".country").text($( this ).val());
    });
    $( "[name='user[email]']" ).change(function() {
        $(".email").text($( this ).val());
    });
    $("#user_links_0_url").on('input',function () {
        $(".instagram_txt").html($(this).val()) ;
    });
    $("body").on('input','#user_links_1_url',function () {
        $(".facebook_txt").html($(this).val()) ;
    });
    $("body").on('input','#user_links_2_url',function () {
        $(".twitter_txt").html($(this).val()) ;
    });
    $("body").on('input','#user_links_3_url',function () {
        $(".youtube_txt").html($(this).val()) ;
    });
    $("body").on('input','#user_links_4_url',function () {
        $(".blog_txt").html($(this).val()) ;
    });
    $("body").on('input','#user_links_5_url',function () {
        $(".autre_txt").html($(this).val()) ;
    });
});
