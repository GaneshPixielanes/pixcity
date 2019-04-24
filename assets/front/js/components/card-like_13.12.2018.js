jQuery(document).ready(function() {

    var likeCardRoute = $("#api-user-routes").attr("data-like-card");

    $(document).on("click", ".cta-like-card", function(e){
        e.preventDefault();
        likeCard($(this).attr("data-id"));
    });

    $(window).on("like-card", function(event, id){
        likeCard(id);
    });


    function likeCard(id){

        var $cta = $(".cta-like-card[data-id='"+id+"']");

        if($("body").hasClass("logged-in")) {

            if ($cta.hasClass("active")) {
                $cta.removeClass("active");
                $cta.attr("title", "J'aime").tooltip('fixTitle').tooltip('hide');
                if($cta.find("span").length > 0) $cta.find("span").text(parseInt($cta.find("span").text()) - 1);
            }
            else {
                $cta.addClass("active");
                $cta.attr("title", "Je n'aime plus").tooltip('fixTitle').tooltip('hide');
                if($cta.find("span").length > 0) $cta.find("span").text(parseInt($cta.find("span").text()) + 1);
            }

            $.post({
                url: likeCardRoute,
                data: {
                    card: id
                }
            }).done(function (res) {

            }).always(function () {

            });
        }
        else{

            $("input[name='addLikeCard']").val(id);
            $(".modal-alert-like-card").modal("show");

        }
    }


});