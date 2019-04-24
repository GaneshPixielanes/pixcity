const firstError = require('../components/first-error') ;
require('../components/avatar-upload') ;

jQuery(document).ready(function() {

    $form = $('form[name="user"]');



    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        var slug = $(".tab-pane.active").attr("data-slug");
        if(slug){
            $(".intro-tab").hide();
            $(".intro-tab-"+slug).show();
        }

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


    //---------------------------------------------
    // Submit form
    //---------------------------------------------

    $form.validate({
        ignore: ":hidden",
        rules: {
            "user[firstname]": {maxlength: 50},
            "user[lastname]": {maxlength: 50},
            "user[currentLocation]": {digits: true, minlength: 5, maxlength: 5 },
            "user[plainPassword][first]": {minlength: 8, password: true},
            "user[plainPassword][second]": {minlength: 8, password: true, equalTo: "#user_plainPassword_first"},
            "user[phone]": {minlength: 10, maxlength: 20, phone: true},
            "user[favoriteCategories][]": {required: true, minlength: 3},
            "user[gender]": {required: true}
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
        invalidHandler: function(event, validator){
            firstError.show(false);
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




    $("#next1").click(function(e){
        e.preventDefault();

        var formIsValid = $form.valid();

        if(formIsValid) {
            $(".nav-tabs .tab2 a").click();
        }
    });


    $("#prev2").click(function(e){
        e.preventDefault();
        $(".nav-tabs .tab1 a").click();
    });

    $("#submit-form").click(function(e){
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




});
