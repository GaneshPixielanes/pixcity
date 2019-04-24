const Cropper = require('./cropper') ;

//---------------------------------------------
// Avatar
//---------------------------------------------

jQuery(document).ready(function() {

    var routeUpload = $("#api-routes").attr("data-upload-route");
    var $container;
    var currentInput;

    //---------------------------------------------
    // Crop and upload
    //---------------------------------------------

    Cropper.init();
    $(Cropper).on("crop", function(event, image){

        var formData = new FormData();
        formData.append("file", image);
        $container.addClass("uploading");

        $.ajax({
            url: routeUpload, type: 'POST', data: formData, cache: false, contentType: false, processData: false
        }).done(function(attachment) {
            addAvatar($container, attachment);
            $container.removeClass("uploading");
        }).fail(function(xhr, status, error) {
            $container.removeClass("uploading");
            $(currentInput).val("");
        });

    });


    //---------------------------------------------
    // File selection
    //---------------------------------------------

    $(document).on('change', '.upload-zone .file-input', function() {
        var type = $(this).attr("data-type")?$(this).attr("data-type"):"multiple";
        var callback = addAvatar;
        onFileSelection(this, callback);
    });

    function onFileSelection(input, callback){
        currentInput = input;
        $container = $(currentInput).parents(".upload-zone");

        var reader = new FileReader();
        var file = input.files[0];

        resizeImage(file);

        reader.onload = function(e) {
            Cropper.show(e.target.result, 1);
        };
        reader.readAsDataURL(file);
    }

    function resizeImage(file) {
        if (window.File && window.FileReader && window.FileList && window.Blob) {

            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var image = new Image();
                    image.src = e.target.result;

                    image.onload = function() {

                        var img = document.createElement("img");
                        img.src = e.target.result;

                        var canvas = document.createElement("canvas");
                        var ctx = canvas.getContext("2d");
                        ctx.drawImage(img, 0, 0);

                        var MAX_WIDTH = 1024;
                        var MAX_HEIGHT = 1024;
                        var width = this.width;
                        var height = this.height;

                        if (width > height) {
                            if (width > MAX_WIDTH) {
                                height *= MAX_WIDTH / width;
                                width = MAX_WIDTH;
                            }
                        } else {
                            if (height > MAX_HEIGHT) {
                                width *= MAX_HEIGHT / height;
                                height = MAX_HEIGHT;
                            }
                        }
                        canvas.width = width;
                        canvas.height = height;
                        var ctx = canvas.getContext("2d");
                        ctx.drawImage(img, 0, 0, width, height);

                        console.log(img.clientWidth, img.height, width, height);

                        dataurl = canvas.toDataURL(file.type);

                        Cropper.show(dataurl, 1);

                    };

                };
                reader.readAsDataURL(file);

            }

        } else {
            Cropper.show(e.target.result, 1);
        }
    }

    //---------------------------------------------
    // Apply avatar
    //---------------------------------------------

    function addAvatar($container, attachment){
        $collection = $container;
        $collection.find(".thumb").css("background-image", "url("+attachment.url+")");
        $collection.find(".field input").val(attachment.name);
        $collection.find(".field input").valid()
    }

});