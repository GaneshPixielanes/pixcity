var datatableLang = require('../vendors/i18n/datatable.french.json');

jQuery(document).ready(function() {


    //---------------------------------------------------------
    // Ajax datatable

    $datatable = $(".datatable-ajax");

    var ajax = $datatable.attr("data-ajax");

    var table = $datatable.DataTable({
        responsive: true,
        serverSide: true,
        ajax: {
            url: ajax,
            type: "POST"
        },
        columns: [
            { "data": "id" },
            { "data": "firstname" },
            { "data": "lastname" },
            { "data": "email" },
            { "data": "roles" },
            { "data": "created_at" },
            { "data": "visible" },
            // { "data": "Auto-login" },
            { "data": "userRegistrationCheck" },
            { "data": "actions" },
        ],
        language: datatableLang,
        createdRow: function ( row, data, index ) {
            if(data.deleted === true){
                $(row).addClass('deleted');
            }
        },
        "order": [[ 0, "desc" ]]
    });

    $(window).resize(function () {

    });


    var $form;

    $(document).on("click", ".delete-form-users button", function(e){
        e.preventDefault();
        $form = $(this).parents("form");

        swal({
            title: "Attention",
            text: "Vous allez supprimer un utilisateur et ses données (cards, assignations, favoris, collections, etc...).\nChoisissez un mode de suppression :",
            icon: "warning",
            buttons: {
                softdelete: {text: "Désactiver le compte", className: "swal-button--cancel"},
                harddelete: "Supprimer définitivement",
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

