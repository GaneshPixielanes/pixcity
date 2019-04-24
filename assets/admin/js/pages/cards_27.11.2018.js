const Cropper = require('../shared/cropper');

jQuery(document).ready(function() {

    var routeDepartmentsList = $("#api-routes").attr("data-departments-route");
    var routePixiesList = $("#api-routes").attr("data-pixies-route");
    var routeUpload = $("#api-routes").attr("data-upload-route");

    //---------------------------------------------
    // Nested selects
    //---------------------------------------------

    var regionSelect = $("#card_region");
    var departmentSelect = $("#card_department");
    var pixieSelect = $("#card_pixie");

    regionSelect.change(function(){
        var regionId = $(this).val();

        $.get(
            routeDepartmentsList,
            {
                regionId: regionId
            },
            function(departments) {
                departmentSelect.find(":gt(0)").remove();
                $.each(departments, function (key, department) {
                    departmentSelect.append('<option value="' + department.id + '">' + department.name + '</option>');
                });
                departmentSelect.val('').selectpicker('refresh');
            }
        );

        $.get(
            routePixiesList,
            {
                regionId: regionId
            },
            function(pixies) {
                pixieSelect.find(":gt(0)").remove();
                $.each(pixies, function (key, pixie) {
                    pixieSelect.append('<option value="' + pixie.id + '">' + pixie.firstname + ' ' + pixie.lastname + '</option>');
                });
                pixieSelect.val('').selectpicker('refresh');
            }
        );

    });


    //---------------------------------------------
    // Card Info Collection
    //---------------------------------------------

    // Store the collection tag
    var collectionSelector = [".card-template-infos-collection", ".card-template-attachments-collection"];
    var $collectionHolder;
    var $collection;

    collectionSelector.forEach(function(selector){

        // Get the tag that holds the collection and the add item button
        $collectionHolder = $(selector);

        // count the collection items we have on load
        $collectionHolder.data('index', $collectionHolder.find('.collection-row').length);

        // Add new collection item
        $(document).on('click', selector+' .add-item', function(e) {
            e.preventDefault();
            $collection = $(this).parents(".repeater-container");
            // Get the data-prototype
            var prototype = $collection.data('prototype');

            // get the new index
            var index = $collection.data('index');

            // Update the name and id
            var newForm = prototype;
            newForm = newForm.replace(/__name__/g, index);

            // Update the index and add the form
            $collection.data('index', index + 1);
            var $newFormLi = $('<li></li>').append(newForm);
            $collection.find(".add-item").parents("li").before($newFormLi.hide().fadeIn(300));
        });

        // Remove an item from the collection
        $(document).on('click', selector+' .delete-item', function(e) {
            e.preventDefault();
            $(this).parents(".collection-row").fadeOut(300, function() { $(this).remove(); });
        });

    });


    //---------------------------------------------
    // Attachments
    //---------------------------------------------

    var currentInput;
    var currentCropCallback;

    $(document).on('change', '.upload-zone .file-input', function() {
        var type = $(this).attr("data-type")?$(this).attr("data-type"):"multiple";
        var callback = addAttachment;
        var crop = false;

        switch(type){
            case "thumb":
                callback = addThumb;
                crop = 240/310;
                break;
            case "masterhead":
                callback = addMasterhead;
                crop = 1600/600;
                break;
        }

        onFileSelection(this, callback, crop);
    });

    function onFileSelection(input, callback, crop){

        if(!crop) {
            uploadImage(input, input.files[0], callback);
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

        var $container = $(input).parents(".upload-zone");

        var formData = new FormData();
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

    function addThumb($container, attachment){
        $collection = $container.parents(".card-template-attachments-collection");
        $collection.find(".thumb").attr("src", attachment.url);
        $collection.find(".field input").val(attachment.name);
    }

    function addMasterhead($container, attachment){
        $collection = $container.parents(".card-template-attachments-collection");
        $collection.find(".thumb").attr("src", attachment.url);
        $collection.find(".field input").val(attachment.name);
    }

    function addAttachment($container, attachment){
        $collection = $container.parents(".card-template-attachments-collection");
        var prototype = $collection.data('prototype');

        var index = $collection.data('index');
        var newForm = prototype;
        newForm = newForm.replace(/__name__/g, index);
        var $newFormRow = $(newForm);
        $collection.data('index', index + 1);

        $newFormRow.addClass("type-"+attachment.type);
        $newFormRow.find(".field input").val(attachment.name);
        $newFormRow.find(".thumb").css({backgroundImage: 'url('+attachment.url+')'});
        $newFormRow.find(".name").text(attachment.name);

        $collection.find(".ajax-upload-item").parents("li").before($newFormRow.hide().fadeIn(300));
    }


    //---------------------------------------------
    // Crop and upload
    //---------------------------------------------

    Cropper.init();
    $(Cropper).on("crop", function(event, image){
        uploadImage(currentInput, image, currentCropCallback);
    });




});

