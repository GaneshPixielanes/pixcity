jQuery(document).ready(function() {

    var totalRegionsSelected, totalCategoriesSelected;
    var totalRegionsSelectedStr, totalCategoriesSelectedStr;
    var $searchContainer = $(".search-form-container");
    var $searchForm = $searchContainer.find("form");

    $searchForm.find("input[name='regions[]']").change(function(){
        totalCategoriesSelected = $searchForm.find("input[name='regions[]']:checked").length;
        totalCategoriesSelectedStr = (totalCategoriesSelected > 0)?"("+totalCategoriesSelected+")":"";
        $searchContainer.find("#regionSearch .count").text(totalCategoriesSelectedStr)
    });

    $searchForm.find("input[name='categories[]']").change(function(){
        totalRegionsSelected = $searchForm.find("input[name='categories[]']:checked").length;
        totalRegionsSelectedStr = (totalRegionsSelected > 0)?"("+totalRegionsSelected+")":"";
        $searchContainer.find("#filterSearch .count").text(totalRegionsSelectedStr)
    });

    $searchForm.find(".btn-apply").click(function(e){

        $searchForm.submit();
        e.preventDefault();
    });

    $searchForm.find(".btn-cancel").click(function(e){
        e.preventDefault();
        $searchForm.submit();
        $searchForm.find("input[name='regions[]']").prop("checked", false).trigger("change");
        $searchForm.find("input[name='categories[]']").prop("checked", false).trigger("change");
        $searchForm.find("input[name='search']").val("");
    });

    $(".search-pixies-with-same-params").click(function(e){
        e.preventDefault();
        $searchForm.find("input[name='type']").val("pixies");
        $searchForm.submit();
    });

});