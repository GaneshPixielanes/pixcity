jQuery(document).ready(function() {

    $modalsOnLoad = $(".modal.show-on-load");
    if($modalsOnLoad.length > 0) {
        $modalsOnLoad.modal("show");
    }

});