jQuery(document).ready(function() {

    var followPixieRoute = $("#api-user-routes").attr("data-follow-pixie");

    $(document).on("click", ".cta-follow-pixie", function(e){
        e.preventDefault();
        var $cta = $(this);
        var pixieId = $cta.attr("data-id");

        if($("body").hasClass("logged-in")) {

            if ($cta.hasClass("active")) {
                $cta.removeClass("active");
                $cta.attr("title", "Suivre le Pixie").tooltip('fixTitle').tooltip('show');
            }
            else {
                $cta.addClass("active");
                $cta.attr("title", "Ne plus suivre").tooltip('fixTitle').tooltip('show');
            }

            $.post({
                url: followPixieRoute,
                data: {
                    pixie: pixieId
                }
            }).done(function (res) {

            }).always(function () {

            });
        }
        else{

            $("input[name='addFavoritePixie']").val(pixieId);
            $(".modal-alert-favorite-pixies").modal("show");

        }

    })

});