jQuery(document).ready(function() {

    //---------------------------------------------------------
    // Confirm payements

    $(".mark-as-paid-form button").click(function(e){
        e.preventDefault();
        $form = $(this).parents("form");

        swal({
            title: "Attention",
            text: "Toutes les cards liées à cette transaction seront indiquées comme réglées.",
            icon: "warning",
            buttons: [
                'Annuler',
                'Continuer'
            ],
            dangerMode: true,
        }).then(function (isConfirm) {
            if (isConfirm) {
                $form.submit();
            }
        })

    })

    $(".pay-banktransfer-form button").click(function(e){
        e.preventDefault();
        $form = $(this).parents("form");

        swal({
            title: "Attention",
            text: "Le virement bancaire va être effectué et toutes les cards indiquées comme réglées.",
            icon: "warning",
            buttons: [
                'Annuler',
                'Continuer'
            ],
            dangerMode: true,
        }).then(function (isConfirm) {
            if (isConfirm) {
                $form.submit();
            }
        })

    })



});

