require('../components/popular-cards');

jQuery(document).ready(function() {
    var page = 1;
    $('#showMoreCards').click(function(e)
    {
        page = page+1;
        e.preventDefault();
        $('.loadCards:last').html('<p class="text-center">Loading cards...</p>');
       $('.loadCards:last').load('/load-cards?page='+page, function(result)
       {
           console.log(result);
       });
    });
});