var datatableLang = require('../vendors/i18n/datatable.french.json');

jQuery(document).ready(function() {



    //-----------------------------------------
    // DELETE
    //-----------------------------------------

    var $form;

    $(document).on("click", ".delete-form-cards button", function(e){
        e.preventDefault();
        $form = $(this).parents("form");

        swal({
            title: "Attention",
            text: "Vous allez supprimer une card.\nChoisissez un mode de suppression :",
            icon: "warning",
            buttons: {
                softdelete: {text: "Désactiver", className: "swal-button--cancel"},
                harddelete: "Supprimer",
            },
            dangerMode: true,
        }).then(function(value){

            switch(value){
                case "softdelete":
                    $form.attr("action", $form.attr("data-softdelete"));
                    $form.submit();
                    break;
                case "harddelete":
                    $form.attr("action", $form.attr("data-harddelete"));
                    $form.submit();
                    break;
            }

        });

    });



});

