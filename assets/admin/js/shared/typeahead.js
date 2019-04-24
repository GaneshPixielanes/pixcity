jQuery(document).ready(function() {

    $('.type-typeahead .select2').each(function(){
        $(this).select2({
            placeholder: $(this).attr("placeholder"),
            allowClear: true,
            ajax: {
                url: $(this).attr("data-route"),
                dataType: 'json'
            }
        });
    });


});