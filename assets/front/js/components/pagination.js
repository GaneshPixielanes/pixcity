const helpers = require('../components/helpers');

jQuery(document).ready(function() {

    var $pagination = $(".list-pagination");
    var isAjax = $pagination.hasClass("ajax");
    var $btnFirstPage = $pagination.find(".btn-first-page");
    var $btnPrevPage = $pagination.find(".btn-prev-page");
    var $btnNextPage = $pagination.find(".btn-next-page");
    var $btnLastPage = $pagination.find(".btn-last-page");
    var $pages = $pagination.find(".pages ul");
    var $pageLiTpl = $('<li class="btn-circle" data-index=""><a href="#"></a></li>');
    var index, limit, total;

    var update = function(){

        index = parseInt($pagination.attr("data-index"));
        limit = parseInt($pagination.attr("data-limit"));
        total = parseInt($pagination.attr("data-total"));

        $pagination.toggle(total > 1);

        $btnFirstPage.toggle(total > 3);
        $btnLastPage.toggle(total > 3);

        $btnFirstPage.toggleClass("disabled", index === 1);
        $btnPrevPage.toggleClass("disabled", index === 1);
        $btnNextPage.toggleClass("disabled", index === total);
        $btnLastPage.toggleClass("disabled", index === total);

        $pages.html("");
        var firstPage = (total <= 3)?1:(index - 1 < 1)?1:(index + 1 > total)?total - 2:index - 1;
        var pagesLimit = (total >= 3)?3:total;
        var $pageLi;
        for(var i = 0; i < pagesLimit; i++){
            $pageLi = $pageLiTpl.clone();
            $pageLi.attr("data-index", firstPage + i).find("a").text(firstPage + i);
            $pageLi.appendTo($pages);
        }

        $pages.find("li[data-index='"+index+"']").addClass("active");
    };

    var changePage = function(newIndex){
        if(newIndex < 1) newIndex = 1;
        else if(newIndex > total) newIndex = total;

        $pagination.attr("data-index", newIndex);

        $(window).trigger("pagination-updated", [newIndex]);

        if(!isAjax){
            // If not ajax, reload the page
            window.location.href = helpers.replaceUrlParam(window.location.href, "page", newIndex);
        }

        update();
    };

    $btnFirstPage.click(function(e){
        e.preventDefault();
        changePage(1);
    });

    $btnPrevPage.click(function(e){
        e.preventDefault();
        changePage(index-1);
    });

    $btnNextPage.click(function(e){
        e.preventDefault();
        changePage(index+1);
    });

    $btnLastPage.click(function(e){
        e.preventDefault();
        changePage(total);
    });

    $pagination.on("click", ".pages a", function(e){
        e.preventDefault();
        changePage($(this).parent().attr("data-index"));
    });


    //-----------------------------------------------------
    // Update on list reset

    $(window).on("card-list-reset", function(e, res){

        $pagination.attr("data-total", res.totalPages);
        $pagination.attr("data-index", 1);
        update();

    });

    //-----------------------------------------------------
    // Update on start

    if($pagination.length > 0){

        update();

    }

});