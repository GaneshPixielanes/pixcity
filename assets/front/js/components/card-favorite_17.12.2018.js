jQuery(document).ready(function() {

    var favoriteCardRoute = $("#api-user-routes").attr("data-favorite-card");

    $(document).on("click", ".cta-favorite-card", function(e){
        e.preventDefault();
        favoriteCard($(this).attr("data-id"));
    });

    $(window).on("favorite-card", function(event, id){
        favoriteCard(id);
    });


    function favoriteCard(id){

        var $cta = $(".cta-favorite-card[data-id='"+id+"']");

        if($("body").hasClass("logged-in")) {

            if ($cta.hasClass("active")) {
                $cta.removeClass("active");
                $cta.attr("title", "Ajouter à mes favoris").tooltip('fixTitle').tooltip('hide');
                // if($cta.find("span").length > 0) $cta.find("span").text(parseInt($cta.find("span").text()) - 1);
            }
            else {
                $cta.addClass("active");
                $cta.attr("title", "Retirer de mes favoris").tooltip('fixTitle').tooltip('hide');
                // if($cta.find("span").length > 0) $cta.find("span").text(parseInt($cta.find("span").text()) + 1);
            }

            $.post({
                url: favoriteCardRoute,
                data: {
                    card: id
                }
            }).done(function (res) {
                res = JSON.parse(res);
                if(res.success == true)
                {
                    $('.card-favorite-modal').modal('show');
                }
            }).always(function () {

            });
        }
        else{

            $("input[name='addFavoriteCard']").val(id);
            $(".modal-alert-favorite-card").modal("show");

        }
    }

});