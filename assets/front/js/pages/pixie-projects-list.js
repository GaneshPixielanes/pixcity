require('../components/pagination');


jQuery(document).ready(function() {

    var modalInfosRoute = $("#api-routes").attr("data-modalinfos-route");
    var acceptRoute = $("#api-routes").attr("data-acceptproject-route");
    var refuseRoute = $("#api-routes").attr("data-refuseproject-route");

    var $infosModal = $("#project-infos-modal");
    var $contractModal = $("#project-confirm");

    var currentProjectId;
    var currentProjectContract;

    $(".open-project-infos").click(function(e){
        e.preventDefault();
        currentProjectId = $(this).attr("data-id");
        currentProjectContract = $(this).attr("data-contract");

        $(".open-contract-pop").attr("href", currentProjectContract);

        $.post(modalInfosRoute, {id: currentProjectId},
            function(infos) {
                if(infos.result){
                    $infosModal.html(infos.html);
                    $infosModal.modal("show");
                }
            }
        );
    });

    $(document).on("click", "#confirm-accept-project", function(e){
        e.preventDefault();
        currentProjectId = $(this).attr("data-id");
        $infosModal.modal("hide");
        $contractModal.modal("show");
    });

    $(document).on("click", "#accept-project", function(e){
        e.preventDefault();
        currentProjectId = ($(this).attr("data-id"))?$(this).attr("data-id"):currentProjectId;

        $.post(acceptRoute, {id: currentProjectId},
            function(infos) {
                location.reload();
            }
        );
    });

    $(document).on("click", "#refuse-project", function(e){
        e.preventDefault();
        currentProjectId = ($(this).attr("data-id"))?$(this).attr("data-id"):currentProjectId;

        $.post(refuseRoute, {id: currentProjectId},
            function(infos) {
                location.reload();
            }
        );
    });

    $(".open-contract-pop").click(function(e){
        e.preventDefault();

        window.open($(this).attr("href"), "Contrat","width=800, height=500");
    });

});