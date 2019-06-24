
const firstError = require('../components/first-error') ;
require('../components/avatar-upload') ;
require('../components/address') ;
require('../components/links-collection') ;
require('../components/froala-max') ;

jQuery(document).ready(function() {



    $form = $('form[name="client"]');

    function uploadFile(){

    }


    $(document).on('change', '#client-logo', function () {

        var image = this.files[0];
        var formData = new FormData();
        formData.append("file", image);


        $.ajax({
            url: "/client/upload-logo", type: 'POST', data: formData, cache: false, contentType: false, processData: false
        }).done(function (attachment) {

            $("#client_profilePhoto").val(attachment.file);
            $(".thumb").css("background-image", "url(/uploads/clients/"+attachment.file+")");

        }).fail(function (xhr, status, error) {

        });

    });


    var validator = $form.validate({
        ignore: ".tab-pane:not(.active) :hidden",
        rules: {
            "client[clientInfo][siret]": {maxlength: 14},
        }

    });

    $form.on('submit',function () {
        $form.valid();
    })

});