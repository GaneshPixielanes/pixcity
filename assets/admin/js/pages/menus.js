jQuery(document).ready(function() {

    var sortRoute = $("#api-routes").attr("data-sort-route");

    //---------------------------------------------
    // Menu items
    //---------------------------------------------

    // Store the collection tag
    var collectionSelector = [".menu-items-collection"];
    var $collectionHolder;
    var $collection;

    collectionSelector.forEach(function(selector){

        // Get the tag that holds the collection and the add item button
        $collectionHolder = $(selector);

        // count the collection items we have on load
        $collectionHolder.data('index', $collectionHolder.find('.collection-row').length);

        // Add new collection item
        $(document).on('click', selector+' .add-item', function(e) {
            e.preventDefault();
            $collection = $(this).parents(".repeater-container");
            // Get the data-prototype
            var prototype = $collection.data('prototype');

            // get the new index
            var index = $collection.data('index');

            // Update the name and id
            var newForm = prototype;
            newForm = newForm.replace(/__name__/g, index);

            // Update the index and add the form
            $collection.data('index', index + 1);
            var $newFormLi = $(newForm);
            $collection.find(".add-item").parents("li").before($newFormLi.hide().fadeIn(300));

            $('select:not(.classic)').selectpicker({});
        });

        // Remove an item from the collection
        $(document).on('click', selector+' .delete-item', function(e) {
            e.preventDefault();
            $(this).parents(".collection-row").fadeOut(300, function() { $(this).remove(); });
        });

    });



    var el = document.getElementById('menu-items-collection');
    var sortable = Sortable.create($(".menu-items-collection")[0], {
        handle: '.drag-handle',
        animation: 150,

        onSort: function (evt) {
            updateItemsPositions();
        }

    });

    function updateItemsPositions(){
        $(".menu-item").each(function(index){
            $(this).find(".position input").val(index);
        });
    }



});

