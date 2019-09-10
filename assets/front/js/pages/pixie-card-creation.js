const Cropper = require('../components/cropper');
const firstError = require('../components/first-error') ;
require('../components/address') ;

jQuery(document).ready(function() {

    var modalInfosRoute = $("#api-routes").attr("data-modalinfos-route");
    var routeDepartmentsList = $("#api-routes").attr("data-departments-route");
    var routeUpload = $("#api-routes").attr("data-upload-route");

    $form = $('form[name="card"]');

    //---------------------------------------------
    // Brief modal
    //---------------------------------------------

    var $infosModal = $("#project-infos-modal");

    var currentProjectId;

    $(".open-project-infos").click(function(e){
        e.preventDefault();
        currentProjectId = $(this).attr("data-id");

        $.post(modalInfosRoute, {id: currentProjectId},
            function(infos) {
                if(infos.result){
                    $infosModal.html(infos.html);
                    $infosModal.modal("show");
                }
            }
        );
    });


    //---------------------------------------------
    // Nested selects
    //---------------------------------------------

    var $regionSelect = $("#card_region");
    var $departmentSelect = $("#card_department");

    $regionSelect.change(function(){
        var regionId = $(this).val();

        $.get(
            routeDepartmentsList,
            {
                regionId: regionId
            },
            function(departments) {
                $departmentSelect.find(":gt(0)").remove();
                $.each(departments, function (key, department) {
                    $departmentSelect.append('<option value="' + department.id + '">' + department.name + '</option>');
                });
                $departmentSelect.val('');
            }
        );


    });

    //---------------------------------------------
    // Refresh Froala on tab change (dispatch a window resize event)
    //---------------------------------------------

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        $(window).resize();
    });

    //---------------------------------------------
    // Submit form
    //---------------------------------------------



    var validator = $form.validate({
        ignore: ".tab-pane:not(.active) :hidden",
        rules: {
            "card[name]": {maxlength: 255},
            "card[categories][]": {required: true}
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


    $(".card-info-row").each(function () {
        if($(this).hasClass("type-price")){
            $(this).find("input").rules('add', {
                number: true
            });
        }
        else if($(this).hasClass("type-email")){
            $(this).find("input").rules('add', {
                email: true
            });
        }
        else if($(this).hasClass("type-link")){
            $(this).find("input").rules('add', {
                url: true
            });
        }
        else if($(this).hasClass("type-time") || $(this).hasClass("type-text")){
            $(this).find("input").rules('add', {
                maxlength: 255
            });
        }
        else if($(this).hasClass("type-date")){
            $(this).find("input").datepicker();
        }

    });

    //---------------------------------------------
    // Uploads
    //---------------------------------------------

    var currentInput;
    var currentCropCallback;

    $mediasList = $("#card-medias-list");
    $mediasList.data('index', $mediasList.find('li').length);

    $(document).on('change', '#thumb-upload input[type="file"]', function() {
        onFileSelection(this, addThumb, 240/310);
    });

    $(document).on('change', '#masterhead-upload input[type="file"]', function() {
        onFileSelection(this, addMasterhead, 1600/600);
    });

    $(document).on('change', '#media-upload input[type="file"]', function() {
        onFileSelection(this, addMedia, false);
    });

    function addThumb($container, attachment){
        $container.css("background-image", "url("+attachment.url+")");
        $container.addClass("has-image");
        $container.find("#card_thumb_name").val(attachment.name);
    }

    function addMasterhead($container, attachment){
        $container.css("background-image", "url("+attachment.url+")");
        $container.addClass("has-image");
        $container.find("#card_masterhead_name").val(attachment.name);

        console.log(attachment);
    }

    function addMedia($container, attachment){
        $collection =  $mediasList;
        var prototype = $collection.data('prototype');

        var index = $collection.data('index');
        var newForm = prototype;
        newForm = newForm.replace(/__name__/g, index);
        var $newFormRow = $(newForm);
        $collection.data('index', index + 1);

        $newFormRow.addClass("type-"+attachment.type);
        $newFormRow.find(".field input").val(attachment.name);
        $.each($newFormRow,function (key, obj) {
           if(!$(this).hasClass('no-bg'))
           {
               $(this).css({backgroundImage: 'url('+attachment.url+')'});
           }
        });
        // console.log($newFormRow);
        $collection.append($newFormRow.hide().fadeIn(300));
        updatePhotoCounter();
    }

    function onFileSelection(input, callback, crop){

        if(!crop) {
            $.each(input.files, function(key, file)
            {
                var extension = file.name.substr(file.name.lastIndexOf('.') + 1).toUpperCase();
                if($.inArray(extension, ['JPG', 'JPEG', 'PNG']) != '-1')
                {
                    uploadImage(input, input.files[key], callback);
                }
                else
                {
                    alert(file.name +' n\'est pas dans le bon format');
                }
            });
        }
        else{
            currentInput = input;
            currentCropCallback = callback;

            var reader = new FileReader();
            reader.onload = function(e) {
                Cropper.show(e.target.result, crop);
            };
            reader.readAsDataURL(input.files[0]);
        }

    }

    function uploadImage(input, file, callback){
        var $container = $(input).parents(".uploadFormControl");
        $('.upload-on-progress').removeClass('hidden');
        $container.addClass("uploading");

        var formData = new FormData();
        formData.append("file", file);

        $.ajax({
            url: routeUpload,
            type: 'POST',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            xhr: function() {
                $('.upload-on-progress').removeClass('hidden');
                var myXhr = $.ajaxSettings.xhr();
                return myXhr;
            }
        }).done(function(attachment) {
            $('.upload-on-progress').addClass('hidden');
            callback($container, attachment);
            $container.removeClass("uploading");
            $('.upload-on-progress').addClass('hidden');
        }).fail(function(xhr, status, error) {

            $('.upload-on-progress').addClass('hidden');
            $container.removeClass("uploading");
            alert("Votre fichier n'a pas pu être envoyé, veuillez vérifier qu'il s'agit bien d'un .jpg ou .png pesant moins de 14Mo");
            $(input).val("");
        });

    }

    // Remove an item from the collection
    $(document).on('click', '#card-medias-list .delete-item', function(e) {
        e.preventDefault();
        // $(this).parents("li").siblings().find(".media-bundle").prevObject[0].remove();
        $(this).parents("li").fadeOut(300, function() { $(this).remove(); updatePhotoCounter(); });
    });

    //---------------------------------------------
    // Crop and upload
    //---------------------------------------------

    Cropper.init();
    $(Cropper).on("crop", function(event, image){
        uploadImage(currentInput, image, currentCropCallback);
    });


    //---------------------------------------------
    // Project
    //---------------------------------------------

    $project = $("#project-mandatory-values");

    if($project.attr("data-region")){
        $regionSelect.addClass('readonly');
    }

    var projectCategories = $project.attr('data-categories');
    var projectMinWords = $project.attr('data-min-words');
    var projectMinPhotos = $project.attr('data-min-photos');

    //---------------------------------------------
    // Limit the number of categories selected

    var categoriesSelector = '[name="card[categories][]"]';

    $(categoriesSelector).on('change', function (evt) {
        if ($(categoriesSelector+':checked').length > 3) {
            this.checked = false;
            $(this).parents(".plCheck").removeClass("clicked");
        }
    });

    // Disable mandatory categories
    if(projectCategories){
        projectCategories = projectCategories.split(";");
        projectCategories.forEach(function(cat){
            $(categoriesSelector+"[value='"+cat+"']").parents(".plCheck").addClass('readonly');
        });
    }

    // Count words

    $cardContent = $("#card_content");
    var cardContentText;
    var countWords;

    $cardContent.on('froalaEditor.contentChanged', function (e, editor) {
        updateWordCounter();
    });

    $cardContent.on('froalaEditor.initialized', function (e, editor) {
        updateWordCounter();
    });

    function updateWordCounter(){
        cardContentText = $($cardContent.froalaEditor('html.get')).text();

        if(cardContentText){
            cardContentText = cardContentText.replace(/'/g," ");
            cardContentText = cardContentText.replace(/(^\s*)|(\s*$)/gi,"");//exclude  start and end white-space
            cardContentText = cardContentText.replace(/[ ]{2,}/gi," ");//2 or more space to 1
            cardContentText = cardContentText.replace(/\n /,"\n"); // exclude newline with a start spacing
            countWords = cardContentText.split(' ').filter(function(str){return str!="";}).length;
        }
        else{
            countWords = 0;
        }

        $(".count-words-status").toggleClass("valid", countWords >= (projectMinWords - projectMinWords*0.1));

        $(".count-words-status .current").text(countWords);
    }

    // Count photos

    var countPhotos;

    function updatePhotoCounter(){
        countPhotos = $("#card-medias-list>li").length;

        $(".count-photos-status .current").text(countPhotos);

        $(".count-photos-status").toggleClass("valid", countPhotos >= projectMinPhotos);
    }

    updatePhotoCounter();


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

    $("#submit-form").click(function (e) {
        e.preventDefault();

        var formIsValid = $form.valid();
        if (formIsValid) {
            $("#card_submit_type").val("publish");
            $form.submit();
        }
    });

    $("#prev4").click(function (e) {
        e.preventDefault();
        $(".nav-tabs .tab3 a").click();
    });

    $(".save-form").click(function (e) {
        e.preventDefault();

        var formIsValid = $form.valid();

        if (formIsValid) {
            $("#card_submit_type").val("save");
            $form.submit();
        }
    });


});

$(document).ready(function()
{
    $('.image-upload-btn').bind('change', function()
    {
        $('.upload-on-progress').removeClass('hidden');
    })
});

$(function(){
    $(document.body).curvedArrow({
        p0x: 400,
        p0y: 600,
        p1x: 200,
        p1y: 800,
        p2x: 200,
        p2y: 100
    });

    $('.tab2, .tab3, .tab4').on('click', function()
    {   
        $('.curved_arrow, .instructions-text').hide();
    });
    $('.tab1').on('click', function()
    {
        $('.curved_arrow, .instructions-text').show();
    });
   
});