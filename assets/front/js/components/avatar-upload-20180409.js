const Cropper = require('./cropper') ;

//---------------------------------------------
// Avatar
//---------------------------------------------

jQuery(document).ready(function() {
    $('.delete-user-image:last').addClass('hidden');
    var routeUpload = $("#api-routes").attr("data-upload-route");
    var $container;
    var currentInput;

    //---------------------------------------------
    // Crop and upload
    //---------------------------------------------

    Cropper.init();
    $(Cropper).on("crop", function(event, image){
        var $emptyContainer = $('.user-media-list:last').wrapAll('<div>').parent().html();
        console.log($emptyContainer);
        var formData = new FormData();
        formData.append("file", image);
        // $container.addClass("uploading");
        $imageContainer = $(".user-media-list:last").attr('data-prototype');
        $.ajax({
            url: routeUpload, type: 'POST', data: formData, cache: false, contentType: false, processData: false
        }).done(function(attachment) {

            var newForm = $imageContainer.replace(/__name__/g,length + $('.user-media-list').length);
            $(".user-media-list:last").append(newForm);
            addAvatar($container, attachment);
            $('.user-media-list:last').find('input[type="text"]').val(attachment.name).parent().parent().addClass('hidden');
            $('input[type="file"]').remove();
            $('.delete-user-image').removeClass('hidden');
            $('.user-media-list:last').after($emptyContainer);
            console.log($('.portraitCompte').length );
            if($('.portraitCompte').length <= 3)
            {
                $('.delete-user-image:last').addClass('hidden');

            }
            else
            {
                $('.user-media-list:last').addClass('hidden');
            }
            // $('.user-media-list:last').findaddClass('hidden');
            $container.removeClass("uploading");
            $('.user-media-list').removeClass('center-block').removeClass('remove-float')
            // $container.after($emptyContainer);
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
        // $collection.find(".field input").val(attachment.name);
        // $collection.find(".field input").valid()
    }
    //---------------------------------------------
    // Upload multiple avatars
    //---------------------------------------------
    $('[name="file-user-avatar[]"]').on('change', function()
    {
        var formData = new FormData($(this).parents('form')[0]);
        $.ajax({
            url: '/city-maker/compte/upload-avatar',
            type: 'POST',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(result)
            {
                var imageLen = $('.avatar-image-cont').length;
                $.each(result.url.url, function(key, image)
                {
                    $('.upload-container').append('<div class="col-md-3 avatar-image-cont" style="height:125px; width: 125px; background:url(\''+image+'\');margin:5px; color:hotpink;">' +
                        '<div class="pull-right delete-image" style="cursor:pointer">' +
                        '<i class="fa fa-trash" aria-hidden="true"></i>' +
                        '<input type="hidden" name="user[userAvatars]['+imageLen+key+'][name]" value="'+result.url.name[key]+'"/>'+
                        '</div></div>');
                });
                console.log(result.url);
            }
        });
    });

    $('body').on('click','.delete-image', function()
    {
       $(this).parent().remove();
    });

    $('body').on('click','.delete-user-image', function(e)
    {
        e.preventDefault();
        var id = $(this).attr('data-id');
        if(confirm('Please click OK to confirm deleting of the image'))
        {
            if('NA' != id)
            {
                $.ajax({
                    url:'/city-maker/compte/delete-avatar/'+id,
                    method:'GET',
                    success: function(result)
                    {
                        // DO NOTHING
                    }
                });
            }
            $(this).parents('.input-container').remove();
            $('.user-media-list').removeClass('hidden');
        }

        // if($('.portraitCompte').length < 3)
        // {
        //     $('.user-media-list').removeClass('hidden');
        // }
    });
});
