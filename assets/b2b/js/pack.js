



jQuery(document).ready(function() {

    $("#pack_userBasePrice").on('change',function(){

        var actual_price = $("#pack_userBasePrice").val();
        var after_price = parseFloat(actual_price) * 20 / 100;

        var total = parseFloat(actual_price) + parseFloat(after_price);
        $("#actula_value").text('The Value after {{ tax.value }} % added '+total);

    });

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $("#imagePreview").css(
                    "background-image",
                    "url(" + e.target.result + ")"
                );
                $("#imagePreview").hide();
                $("#imagePreview").fadeIn(650);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#imageUpload").change(function() {
        readURL(this);
    });

    $('input[type="file"]').on('change', function(e)
    {
        var type = 'banner';

        var formData = new FormData();
        formData.append("file", $(this)[0].files[0]);
        //Upload the image via AJAX
        uploadFiles(formData, type);

    });
    var banner_file = "{{ pack.bannerImage }}";
    if(banner_file){
        $('#pack_bannerImage').val(banner_file);
    }

    function uploadFiles(formData, type)
    {
        $.ajax('/b2b/pack/upload',
            {
                type: 'POST',
                data: formData,
                cacheable: false,
                processData: false,
                contentType: false,
            }).done(function(result)
        {
            $('#pack_bannerImage').val(result.fileName);

        });
    }

});

var $form = $("form[name='pack']");
try {
    $form.validate({
        rules : {
            "pack[packSkill]":{require:true},
            "pack[title]":{require:true},
        },

    })
}catch (e) {
    //DO NOTHING
}
