jQuery(document).ready(function() {

    var routeUpload = $("#api-routes").attr("data-upload-route");

    //---------------------------------------------
    // Attachments
    //---------------------------------------------


    $(document).on('change', '.upload-zone .file-input', function() {
        var callback = addImage;
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

    function addImage($container, attachment){
        $collection = $container.parents(".slide-attachment");
        $collection.find(".thumb").attr("src", attachment.url);
        $collection.find(".field input").val(attachment.name);
    }

});

