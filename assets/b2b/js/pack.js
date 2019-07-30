$form = '';

$(document).on('click','.pack-save-changes', function (e) {
    e.preventDefault();
    try{
        saveForm($(this));
    }catch (e) {
        //DO NOTHING
    }

});
// Edit the pack
$('body').on('click', '.edit-the-pack, .add-package-to-list > a, .add-package', function () {

    var pack_id = $(this).attr('data-id');
    if ($(this).hasClass('edit-the-pack')) {
        url = '/api/pack/edit/' + pack_id;
    } else {
        url = '/api/pack/create';
    }
    $.ajax(url, {
        type: 'GET',
        success: function (result) {
            $('#pack-form').html(result);
            $('.selectpicker').selectpicker('refresh');
            $.each($('.pack-region-select:checked'), function (key, value) {
                var region = $(value).parents('.choose-region-drop').find('.selectpicker').val();
                $('[data-original-title="'+region+'"]').addClass('active');
            });
            /* Initialize dropzone */
            imageDropzone();

            /* jQuery form validator */
            /* Pack Form Validation*/
            // $form = $("[name='pack']");
            //
            // $form.validate({
            //     ignore: [],
            //     rules: {
            //         "pack[title]": { required: true, maxlength: 64, minlength: 5},
            //         "pack[description]": { required: true, maxlength: 1200, minlength: 5},
            //         "pack[bannerImage]": { required: true},
            //         "pack[userBasePrice]": { required: true, number: true},
            //         "pack[packSkill]":{required:true}
            //     },
            //     errorPlacement: function(error, element) {
            //         element.parents('.edit').append(error);
            //     }
            // });
            //
            // $.extend($.validator.messages, {
            //     required: 'Champs obligatoire',
            //     minlength: jQuery.validator.format("Merci de rédiger au moins {0} caractères"),
            //     maxlength: 'Vous avez atteint le nombre de caractères maximum autorisé'
            // });
            //
            //
            //
            // /* Check if the form is valid on load; it will be valid during edit*/
            // if(($form).valid())
            // {
            //     $('.save-changes').removeClass('disabled');
            // }

            $('[data-bind]').trigger('change');
            // console.log($('.pack-region-select:checked').length);
            if($('.pack-region-select:checked').length > 1)
            {
                $('.add-pack-region').hide();
            }
            else{
                if($('.pack-region-select').length == 1)
                {
                    $('.add-pack-region').hide();
                }
                $('.remove-pack-region').hide();
            }
        }
    })
});
//     $(document).on('change, keyup, keydown, click','input', function () {
// console.log('teasas');
//         try {
//         if($form.valid())
//         {
//             $.event.trigger({
//                 type: "validate"
//             });
//
//         }}catch (e) {
//             // DO NOTHING
//         }
//     });

$(document).on('input', function () {
    try{
        $.event.trigger({
            type: "validate"
        });
    }catch (e) {
        // DO SOMETHING
    }

});

$(document).on('validate', function () {
    /* Pack Form Validation*/
    $form = $("[name='pack']");

    $form.validate({
        ignore: [],
        rules: {
            "pack[title]": { required: true, maxlength: 64, minlength: 5},
            "pack[description]": { required: true, maxlength: 1200, minlength: 5},
            "pack[bannerImage]": { required: true, minlength: 1},
            "pack[userBasePrice]": { required: true, number: true},
            "pack[packSkill]":{required:true}
        },
        errorPlacement: function(error, element) {
            element.parent('div').append(error);
        }
    });

    if($form.valid() && $('#acceptPackAgreement').is(':checked'))
    {
        $('.pack-save-changes').removeClass('disabled');
    }
    else
    {
        $('.pack-save-changes').addClass('disabled');
    }
});


$(document).on('click','.skills-pack', function () {
    $.event.trigger({
        type: "validate"
    });
});

$(document).on('input','#pack_userBasePrice', function () {
    $(this).val($(this).val().replace(/[^a-z0-9]/gi, ''));
});


// Remove region from pack

$(document).on('click','.remove-pack-region', function (e) {
    e.preventDefault();
    $('.remove-pack-region').hide();
    // $('[data-original-title="'+region+'"]').addClass('active');
    // var region  = $(this).attr('data-region').
    // $('.pack-region-select[value="'+$(this).attr('data-id')+'"]').removeProp('checked',false);
    $('.pack-region-select[value="'+$(this).attr('data-id')+'"]').prop('checked',false).trigger('change');
    $('.pack-region-select[value="'+$(this).attr('data-id')+'"]').removeAttr('checked').trigger('change');
    // $('.pack-region-select[value="'+$(this).attr('data-id')+'"]').removeProp('checked',false);

    $(this).parents('.row').find('[data-pack-region-id="'+$(this).attr('data-id')+'"]').hide();
    $('.add-pack-region').show();
    highlightRegionMap();
});

$(document).on('click','.add-pack-region', function (e) {
    e.preventDefault();
    $('.remove-pack-region').show();
    $.each($('[name="pack[packRegions][]"]'), function (key, value) {
        console.log($(this).is(':checked'));
        if(!$(this).is(':checked'))
        {
            $('[name="pack[packRegions][]"]').prop('checked','checked').trigger('change');
        }
    });
    $('[name="pack[packRegions][]"]').prop('checked',true).trigger('change');
    $('[name="pack[packRegions][]"]').attr('checked',true);
    $('.row').find('[data-pack-region-id]').show();
    $(this).hide();
    highlightRegionMap();
});

function highlightRegionMap()
{
    setTimeout(function () {

        $('a[data-original-title]').removeClass('active');
        // console.log($('.pack-region-select:checked'));
        $.each($('.pack-region-select:checked'), function (key, value) {
            var region = $(value).parents('.choose-region-drop').find('.selectpicker').val();
            $('[data-original-title="'+region+'"]').addClass('active');
        },1000);
    });
}


$(document).on('change','#acceptPackAgreement', function () {
    try{
        $.event.trigger({
            type: "validate"
        });
    }catch (e) {
        // DO SOMETHING
    }
});

