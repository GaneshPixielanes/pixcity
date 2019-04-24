jQuery(document).ready(function() {

    var $apiRoutes = $("#api-routes");
    var apiRouteCreateCollection = $apiRoutes.attr("data-create-collection-route");
    var apiRouteUpdateCollection = $apiRoutes.attr("data-update-collection-route");
    var apiRouteDeleteCollection = $apiRoutes.attr("data-delete-collection-route");
    var apiRouteAddNote = $apiRoutes.attr("data-add-note-route");
    var apiRouteRemoveNote = $apiRoutes.attr("data-remove-note-route");

    var $inputCollectionName = $("#collection-name");
    var currentEditedCollectionId;
    var $currentEditedCollection;

    var totalCollections;
    var totalCards;

    //---------------------------------------------
    // Collection modal
    //---------------------------------------------

    var collectionCards = [];

    function resetCollection(){

        $(".regions-list .all").click();
        $(".categories .all").click();

        collectionCards = [];
        applyCollectionChanges();
    }

    function updateView(){
        $(".collCheck input").each(function(){
            $(this).prop("checked", isInCollection($(this).val()));
        });
    }

    function addCardToCollection(id){
        collectionCards.push(id);
    }

    function removeCardFromCollection(id){
        collectionCards = collectionCards.filter(function(item) {
            return item !== id
        });
    }

    function isInCollection(id){
        return collectionCards.indexOf(id) !== -1;
    }

    $(document).on("change", "input[name='selectcard[]']", function(){
        var id = $(this).val();
        if($(this).prop("checked")){
            addCardToCollection(id);
        }
        else{
            removeCardFromCollection(id);
        }

        applyCollectionChanges();
    });

    function applyCollectionChanges(){
        $(".collection-total-cards").text(collectionCards.length);
    }

    $(".open-new-collection").click(function(e){
        e.preventDefault();
        newCollection();
    });

    function newCollection(){
        resetCollection();

        collectionCards = [];
        currentEditedCollectionId = null;
        $inputCollectionName.val("");
        updateView();

        $(".edit-mode").hide();
        $(".create-mode").show();

        $(".modalColl").modal("show");
    }

    function editCollection(id){
        resetCollection();

        $(".edit-mode").show();
        $(".create-mode").hide();

        currentEditedCollectionId = id;
        $currentEditedCollection = $(".collection-"+id);

        var name = $currentEditedCollection.find(".collection-name").text();
        var ids = [];

        $currentEditedCollection.find(".card").each(function(){
            ids.push($(this).attr("data-id"));
        });

        collectionCards = ids.slice(0);
        $inputCollectionName.val(name);

        updateView();

        $(".modalColl").modal("show");
    }

    $(window).on("card-list-updated", function(){
        updateView();
    });

    function hideModal(){
        $(".modalColl").modal("hide");
    }

    function updateCounter(){
        totalCollections = $(".collection").length;
        totalCards = $(".collection .card").length;

        if(totalCollections > 0){
            $(".head-title .has-collection").show();
            $(".head-title .no-collection").hide();

            $(".total-collections").text(totalCollections);
            $(".total-cards").text(totalCards);
        }
        else{
            $(".head-title .has-collection").hide();
            $(".head-title .no-collection").show();
        }
    }


    //---------------------------------------------
    // Edit collection
    //---------------------------------------------

    $(document).on("click", ".cta-edit-collection", function(e){
        e.preventDefault();
        var id = $(this).attr("data-id");
        editCollection(id);
    });


    //---------------------------------------------
    // Create/Update collection
    //---------------------------------------------

    var loading = false;

    $(".submit-collection-form").click(function(e){
        e.preventDefault();

        if(loading) return false;

        loading = true;

        if(currentEditedCollectionId) {
            $.ajax({
                type: "POST",
                url: apiRouteUpdateCollection,
                data: {
                    name: $inputCollectionName.val(),
                    cards: collectionCards,
                    id: currentEditedCollectionId
                }
            }).done(function (res) {
                if(res.html){
                    $(".collection-"+res.id).replaceWith(res.html);
                    updateCounter();
                }
            }).always(function(){loading = false; hideModal();});
        }
        else{
            $.ajax({
                type: "POST",
                url: apiRouteCreateCollection,
                data: {
                    name: $inputCollectionName.val(),
                    cards: collectionCards
                }
            }).done(function (res) {
                if(res.html){
                    $("#collections").prepend(res.html);
                    updateCounter();
                }
            }).always(function(){loading = false; hideModal();});
        }

    });

    //---------------------------------------------
    // Delete collection
    //---------------------------------------------

    var deleteCollectionId;

    $(document).on("click", ".cta-delete-collection",function(e){

        e.preventDefault();
        deleteCollectionId = $(this).attr("data-id");
        $(".modal-confirm-delete").modal("show");

    });

    $(".confirm-delete-collection").click(function(){
        $(".modal-confirm-delete").modal("hide");

        if(loading || !deleteCollectionId) return false;

        loading = true;
        var id = deleteCollectionId;

        $.ajax({
            type: "POST",
            url: apiRouteDeleteCollection,
            data: {
                id: id
            }
        }).done(function (res) {
            $(".collection-"+id).remove();
            updateCounter();
        }).always(function(){loading = false; hideModal(); deleteCollectionId = null;});
    });


    //---------------------------------------------
    // Notes
    //---------------------------------------------

    var notePopOverSettings = {
        html: true,
        trigger: "click",
        template: '<div class="popover popBlue collComPop"><div class="arrow"></div><div class="popover-content"></div></div>',
        container: "body",
        content: function () {
            return $(this).next().html();
        },
        selector: '.cta-note-popover'
    };

    $('body').popover(notePopOverSettings);

    $(document).on("click", ".cta-add-note", function(e){
        e.preventDefault();
        if(loading) return false;

        var collectionId = $(this).attr("data-id");
        var text = $(this).parent().find("[name='note-text']").val();

        loading = true;

        $.ajax({
            type: "POST",
            url: apiRouteAddNote,
            data: {
                id: collectionId,
                text: text
            }
        }).done(function (res) {
            updateNotes(res);
            $(".collection-" + res.id).find(".cta-note-popover").popover("show");
        }).always(function(){loading = false;});

    });

    $(document).on("click", ".cta-delete-note", function(e){
        e.preventDefault();
        if(loading) return false;

        var id = $(this).attr("data-id");
        var collectionId = $(this).attr("data-collection-id");

        loading = true;

        $.ajax({
            type: "POST",
            url: apiRouteRemoveNote,
            data: {
                id: collectionId,
                idNote: id
            }
        }).done(function (res) {
            updateNotes(res);
            $(".collection-" + res.id).find(".cta-note-popover").popover("show");
        }).always(function(){loading = false;});

    });

    function updateNotes(res){
        $(".collComPop").find(".notes-container").html(res.html);
        $(".collection-"+res.id).find(".notes-container").html(res.html);

        if(res.total > 0) {
            $(".collection-" + res.id).find(".cta-note-popover").html("<span>"+res.total+"</span>");
        }
        else{
            $(".collection-" + res.id).find(".cta-note-popover").html("");
        }
    }



    //---------------------------------------------
    // Share
    //---------------------------------------------

    var sharePopOverSettings = {
        html: true,
        trigger: "click",
        template: '<div class="popover popRs"><div class="arrow"></div><div class="popover-content"></div></div>',
        content: function () {
            window.Sharer.init();
            return $(this).next().html();
        },
        selector: '.cta-share-popover'
    };

    $('html').popover(sharePopOverSettings);

    $('html').on('shown.bs.popover', ".cta-share-popover", function () {
        console.log("hello !");
        window.Sharer.init();
    });


    //---------------------------------------------
    // Filter list
    //---------------------------------------------

    $("#collections-list").change(function(){
       var selected = $(this).val();
       if(selected){
           $(".collection").hide();
           $(".collection-"+selected).show();
       }
       else{
           $(".collection").show();
       }
    });

});
