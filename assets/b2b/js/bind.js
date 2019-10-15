/**
 *  DATE: 14-06-2019
 *
 *  The following code binds form input elements with the html elements
 *  Usage:
 *
 *  Use "data-bind" attribute to attach the form element with the classname as parameter
 *  For eg: <input type="text" data-bind="classname" value="" />
 */

$(document).ready(function () {
    $('input').trigger('change');

    $(document).on('input, change','[data-bind]', function () {

        if($($(this).has('attr','data-bind-callback')))
        {
            if($(this).attr('data-bind-callback') == 'calculateClientPrice')
            {
                calculateClientPrice($(this));
            }
            else
            {
                $('.'+$(this).attr('data-bind')).val($(this).val()).trigger('change');
                $('.'+$(this).attr('data-bind')).html($(this).val()).trigger('change');
                // $.each($('.'+$(this).attr('data-bind')),function (key, value) {
                //     console.log($(value).is('input'));
                //     if($(value).is('input'))
                //     {
                //         $(value).val($(this).val()).trigger('change');
                //     }
                //     else
                //     {
                //         $(value).html($(this).val()).trigger('change');
                //     }
                // });
            }
        }
        else
        {
            $('.'+$(this).attr('data-bind')).val($(this).val()).trigger('change');
            $('.'+$(this).attr('data-bind')).html($(this).val()).trigger('change');
            // $.each(function (key, value) {
            //     if($(value).is('input'))
            //     {
            //         $('.'+$(this).attr('data-bind')).val($(this).val()).trigger('change');
            //     }
            //     else
            //     {
            //         $('.'+$(this).attr('data-bind')).html($(this).val()).trigger('change');
            //     }
            //
            // });


        }


    });


    function calculateClientPrice(element) {
        var basePrice = element.val();
        var clientPrice = (100 * basePrice)/(100 - $('#api-box').attr('data-margin'));
        clientPrice = Math.round(clientPrice);

        $.each($('.'+element.attr('data-bind')),function (key, value) {
            if($(value).is('input'))
            {
                $('.'+element.attr('data-bind')).val(clientPrice.toFixed(2)).trigger('change');
            }
            else
            {
                $('.'+element.attr('data-bind')).html(clientPrice.toFixed(2)+'€').trigger('change');
            }

        });
    }
});