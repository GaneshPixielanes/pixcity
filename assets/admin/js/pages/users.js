jQuery(document).ready(function() {


    //---------------------------------------------
    // Search the first error and switch tab
    //---------------------------------------------

    function showFirstError(onLoad) {
        var firstErrorTab = $((onLoad)?".error-message":".form-line.error").first().parents(".tab-pane").attr("id");
        if (firstErrorTab && !$("a[href='#" + firstErrorTab + "']").parent().hasClass("active")) {
            $("a[href='#" + firstErrorTab + "']").click();
        }
    }
    showFirstError(true);



    //---------------------------------------------
    // Update the form depending on the user role
    //---------------------------------------------

    function isPixie(){
        return $("#user_roles input[value='ROLE_PIXIE']").is(":checked");
    }

    function checkUserRole(){
        if(isPixie()){
            $(".only-pixie").fadeIn();
        }
        else{
            $(".only-pixie").fadeOut();
        }

        checkPixieStatus();
    }

    $("#user_roles input").change(checkUserRole);

    checkUserRole();


    //---------------------------------------------
    // Update the form depending on the pixie status
    //---------------------------------------------

    function isPixieStatusCompany(){
        return isPixie() && $("#user_pixie_billing_status").val() === "company";
    }

    function isPixieStatusIndividual(){
        return isPixie() && $("#user_pixie_billing_status").val() !== "company";
    }

    function checkPixieStatus(){

        if(isPixieStatusCompany()){
            $("#user_pixie_billing_companyName").parents(".form-row").fadeIn();
            $("#user_pixie_billing_firstname, #user_pixie_billing_lastname").parents(".form-row").fadeOut();
        }
        else{
            $("#user_pixie_billing_companyName").parents(".form-row").fadeOut();
            $("#user_pixie_billing_firstname, #user_pixie_billing_lastname").parents(".form-row").fadeIn();
        }
    }

    $("#user_pixie_billing_status").change(checkPixieStatus);
    checkPixieStatus();


    //---------------------------------------------
    // Update the form depending on the pixie billing method
    //---------------------------------------------

    function isPixieBillingBankTransfer(){
        return isPixie() && $("#user_pixie_billing_billingType").val() === "banktransfer";
    }

    function checkPixieBillingMethod(){

        if(isPixieBillingBankTransfer()){
            $("#user_pixie_billing_billingCountry, #user_pixie_billing_billingIban, #user_pixie_billing_billingBic, #user_pixie_billing_rib").parents(".form-row").fadeIn();
        }
        else{
            $("#user_pixie_billing_billingCountry, #user_pixie_billing_billingIban, #user_pixie_billing_billingBic, #user_pixie_billing_rib").parents(".form-row").fadeOut();
        }
    }

    $("#user_pixie_billing_billingType").change(checkPixieBillingMethod);
    checkPixieBillingMethod();


    //---------------------------------------------
    // Update the form depending on Pixie country
    // Display TVA only for France
    //---------------------------------------------

    function isPixieTva() {
        return (isPixie() && $("#user_pixie_billing_address_country").val() === "FR" && $("#user_pixie_billing_status").val() === "company");
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
    // Form validation
    //---------------------------------------------

    $('form[name="user"]').validate({
        ignore: "",
        rules: {
            "user[plainPassword][first]": {minlength: 8, password: true},
            "user[plainPassword][second]": {minlength: 8, password: true, equalTo: "#user_plainPassword_first"},

            //"user[pixie][likeText]": {required: isPixie},
            //"user[pixie][dislikeText]": {required: isPixie},
            "user[pixie][billing][status]": {required: isPixie},
            "user[pixie][billing][companyName]": {required: isPixieStatusCompany},
            "user[pixie][billing][firstname]": {required: isPixieStatusIndividual},
            "user[pixie][billing][lastname]": {required: isPixieStatusIndividual},
            "user[pixie][billing][tva]": {required: isPixieTva},
            "user[pixie][billing][address]": {required: isPixie},
            "user[pixie][billing][zipcode]": {required: isPixie},
            "user[pixie][billing][country]": {required: isPixie},
            "user[pixie][billing][phone]": {required: isPixie},
            "user[pixie][billing][billingType]": {required: isPixie},
            "user[pixie][billing][billingName]": {required: isPixieBillingBankTransfer},
            "user[pixie][billing][billingCountry]": {required: isPixieBillingBankTransfer},
            "user[pixie][billing][billingIban]": {required: isPixieBillingBankTransfer},
            "user[pixie][billing][billingBic]": {required: isPixieBillingBankTransfer},
        },
        highlight: function (input) {
            $(input).parents('.form-line').addClass('error');
        },
        unhighlight: function (input) {
            $(input).parents('.form-line').removeClass('error');
        },
        errorPlacement: function (error, element) {
            $(element).parents('.form-group').append(error);
            showFirstError(false);
        },
        invalidHandler: function(event, validator){
            showFirstError(false);
        }
    });


    //---------------------------------------------
    // Users Links Collection
    //---------------------------------------------

    // Store the collection tag
    var collectionSelector = ".user-links-collection";
    var $collectionHolder;

    // Get the tag that holds the collection and the add item button
    $collectionHolder = $(collectionSelector);

    // count the collection items we have on load
    $collectionHolder.data('index', $collectionHolder.find('.collection-row').length);

    // Add new collection item
    $(document).on('click', collectionSelector+' .add-item', function(e) {
        e.preventDefault();

        // Get the data-prototype
        var prototype = $collectionHolder.data('prototype');

        // get the new index
        var index = $collectionHolder.data('index');

        // Update the name and id
        var newForm = prototype;
        newForm = newForm.replace(/__name__/g, index);

        // Update the index and add the form
        $collectionHolder.data('index', index + 1);
        var $newFormLi = $('<li></li>').append(newForm);
        $collectionHolder.find(".add-item").parents("li").before($newFormLi.hide().fadeIn(300));
    });

    // Remove an item from the collection
    $(document).on('click', collectionSelector+' .delete-item', function(e) {
        e.preventDefault();
        $(this).parents(".collection-row").fadeOut(300, function() { $(this).remove(); });
    });


    //---------------------------------------------
    // Refresh Froala on tab change (dispatch a window resize event)
    //---------------------------------------------

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        $(window).resize();
        checkPixieBillingMethod();
        checkPixieStatus();
    });


    //---------------------------------------------
    // Attachments
    //---------------------------------------------

    var routeUpload = $("#api-routes").attr("data-upload-route");

    $(document).on('change', '.upload-zone .file-input', function() {
        var type = $(this).attr("data-type")?$(this).attr("data-type"):"multiple";
        var callback = addAvatar;
        onFileSelection(this, callback);
    });

    function onFileSelection(input, callback){
        var file = input.files[0];
        var formData = new FormData();
        var $container = $(input).parents(".upload-zone");
        formData.append("file", file);

        $container.addClass("uploading");

        $.ajax({
            url: routeUpload,
            type: 'POST',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            xhr: function() {
                var myXhr = $.ajaxSettings.xhr();
                if (myXhr.upload) {
                    myXhr.upload.addEventListener('progress', function(e) {
                        if (e.lengthComputable) {
                            $container.find('.progress-bar').width((e.loaded / e.total) * 100 + "%");
                        }
                    } , false);
                }
                return myXhr;
            }
        }).done(function(attachment) {
            callback($container, attachment);
            $container.removeClass("uploading");
        }).fail(function(xhr, status, error) {
            $container.removeClass("uploading");
            swal({icon: "error", text: "Votre fichier n'a pas pu être envoyé"});
            $(input).val("");
        });
    }

    function addAvatar($container, attachment){
        $collection = $container.parents(".user-avatar-collection");
        $collection.find(".thumb").attr("src", attachment.url);
        $collection.find(".field input").val(attachment.name);
    }

});

