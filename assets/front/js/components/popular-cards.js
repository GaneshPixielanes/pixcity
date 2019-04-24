jQuery(document).ready(function() {

    //-------------------------------------------
    // Paginated list of cards
    //-------------------------------------------

    var $listCardsContainer = $(".list-of-cards");
    var $listCardsLoadMore = $listCardsContainer.find(".load-more");
    var $listCardsCategories = $listCardsContainer.find(".categories");
    var $listCardsRegions = $listCardsContainer.find(".regions-list");
    var $listCardsList = $listCardsContainer.find(".cards-grid");
    var $listCardsLoader = $listCardsContainer.find(".loader");
    var $listCardsNoResults = $listCardsContainer.find(".no-results");

    var $searchResultTitle = $(".search-result-title");
    var $searchContainer = $(".search-form-container");
    var $searchForm = $searchContainer.find("form");

    var apiRouteListCards = $listCardsContainer.attr("data-api-route");

    var selectable = false;
    var userFavorite = null;
    var users = [];
    var categories = [];
    var regions = [];

    if($listCardsContainer.attr("data-selectable")){
        selectable = $listCardsContainer.attr("data-selectable");
    }

    if($listCardsContainer.attr("data-user-favorite")){
        userFavorite = $listCardsContainer.attr("data-user-favorite");
    }

    if($listCardsContainer.attr("data-user")){
        users = [$listCardsContainer.attr("data-user")];
    }

    if($listCardsContainer.attr("data-region")){
        regions = [parseInt($listCardsContainer.attr("data-region"))];
    }
    else{
        $("input[name='regions[]']:checked").each(function(){
            regions.push($(this).val());
        });
    }

    $("input[name='categories[]']:checked").each(function(){
        categories.push($(this).val());
    });

    if($listCardsList.find(".card").length === 0){
        $listCardsLoadMore.hide();
        $listCardsNoResults.show();
    }
    else if($listCardsList.find(".card").length < 10){
        $listCardsLoadMore.hide();
    }

    //-----------------------------------------------------------
    // Ajax call the reload the cards list
    //-----------------------------------------------------------

    var updateList = function(replaceContent){

        $listCardsLoader.addClass("active");

        $.ajax({
            type: "POST",
            url: apiRouteListCards,
            data: {
                page: getPage(),
                limit: $(".list-pagination").attr("data-limit")?$(".list-pagination").attr("data-limit"):10,
                categories: categories,
                regions: regions,
                users: users,
                userFavorite: userFavorite,
                orderby: $("input[name='orderby']").val(),
                pixie: $("input[name='search_pixie']").val(),
                selectable: selectable
            }
        }).done(function(res){
            if(replaceContent){
                $listCardsList.html(res.html);
                if(parseInt(getPage()) === 1) $(window).trigger("card-list-reset", [res]);

                if(!$listCardsContainer.hasClass("no-autoscroll")) {
                    $('html, body').animate({
                        scrollTop: $listCardsList.offset().top - $(".navbar-fixed-top").height()
                    }, 1000);
                }
            }
            else{
                $listCardsList.append(res.html);
            }

            $listCardsLoadMore.toggle(res.datas.length === 10);
            $listCardsNoResults.toggle(res.datas.length === 0 && getPage() === 1);

            $(window).trigger("card-list-updated");

            if($searchResultTitle.length > 0){
                $searchResultTitle.html("Les Pixies vous proposent <span>"+res.totalItems+"</span> Cards !")
            }

        }).always(function(){$listCardsLoader.removeClass("active");});
    };

    var setPage = function(index){
        $listCardsContainer.attr("data-page", index);
    };

    var getPage = function(index){
        return parseInt($listCardsContainer.attr("data-page"));
    };

    $listCardsLoadMore.click(function(){
       setPage(getPage()+1);
       updateList(false);
    });

    $(window).on("pagination-updated", function(e, index){
        setPage(index);
        updateList(true);
    });


    //-----------------------------------------------------------
    // Categories
    //-----------------------------------------------------------

    $listCardsCategories.on('click', 'li', function (){
        var $target = $(this);

        setPage(1);

        //-----------------------------------------------------------
        // If the `all` button was selected, uncheck all other categories

        if($target.hasClass("all")){
            $listCardsCategories.find("li").removeClass("is-checked");
        }else{
            $target.toggleClass('is-checked');
        }

        //-----------------------------------------------------------
        // Update categories list

        updateSelectedCategories();


        //-----------------------------------------------------------
        // If at least one category was selected, uncheck the `all` button

        $listCardsCategories.find("li.all").toggleClass("is-checked", categories.length === 0);


        //-----------------------------------------------------------
        // Update the main search

        //if($target.attr("data-id")) {
            //$searchForm.find("input[name='categories[]'][value='" + $target.attr("data-slug") + "']").parents(".plCheck").click();
        //}


        updateList(true);
    });

    var updateSelectedCategories = function(){
        categories = [];
        $listCardsCategories.find("li.is-checked:not(.all)").each(function(){
            categories.push($(this).attr("data-id"));
        });

        $listCardsContainer.find(".count-filters-categories").text(categories.length>0?"("+categories.length+")":"");

        return categories;
    };


    //-----------------------------------------------------------
    // Regions
    //-----------------------------------------------------------

    $listCardsRegions.on('click', 'li', function (){
        var $target = $(this);

        setPage(1);

        //-----------------------------------------------------------
        // If the `all` button was selected, uncheck all other regions

        if($target.hasClass("all")){
            $listCardsRegions.find("li").removeClass("is-checked");
        }else{
            $target.toggleClass('is-checked');
        }

        //-----------------------------------------------------------
        // Update regions list

        updateSelectedRegions();


        //-----------------------------------------------------------
        // If at least one region was selected, uncheck the `all` button

        $listCardsRegions.find("li.all").toggleClass("is-checked", regions.length === 0);


        updateList(true);
    });

    var updateSelectedRegions = function(){
        regions = [];
        $listCardsRegions.find("li.is-checked:not(.all)").each(function(){
            regions.push($(this).attr("data-id"));
        });

        $listCardsContainer.find(".count-filters-regions").text(regions.length>0?"("+regions.length+")":"");

        return regions;
    };


});