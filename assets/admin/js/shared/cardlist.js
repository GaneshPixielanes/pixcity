jQuery(document).ready(function() {

    $cardList = $(".cardlist");

    if($cardList.length > 0){

        var typeaheadRoute = $cardList.attr("data-route");
        var typeaheadPlaceholder = $cardList.attr("data-typeahead-placeholder");
        var inputName = $cardList.attr("data-input-name");

        var cardProto = $("#cardlist-item-prototype").html();

        $cardList.find(".typeahead select").select2({
            placeholder: typeaheadPlaceholder,
            allowClear: true,
            ajax: {
                url: typeaheadRoute,
                dataType: 'json'
            }
        });

        $cardList.find(".typeahead select").on('select2:select', function (e) {
            var data = e.params.data;
            var newCard = cardProto;
            newCard = newCard
                .replace("__ID__", data.id)
                .replace("__INPUT_NAME__", inputName)
                .replace("__THUMB__", data.thumb)
                .replace("__NAME__", data.text)
                .replace("__REGION__", data.region)
            ;

            if($cardList.find("input[value='"+data.id+"']").length === 0) {
                $cardList.append(newCard);
                $cardList.find(".typeahead select").val("").trigger('change');
            }
        });

        $cardList.on("click", ".delete-item", function(e){
            e.preventDefault();
            $(this).parents(".single-card-preview").fadeOut(300, function() { $(this).remove(); });
        });


    }


});