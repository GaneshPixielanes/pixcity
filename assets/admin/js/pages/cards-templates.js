jQuery(document).ready(function() {

    //---------------------------------------------
    // Card Info Collection
    //---------------------------------------------

    // Store the collection tag
    var collectionSelector = ".card-template-infos-collection";
    var $collectionHolder;

    // Get the tag that holds the collection and the add item button
    $collectionHolder = $(collectionSelector);

    // count the collection items we have on load
    $collectionHolder.data('index', $collectionHolder.find('.collection-row').length);

    // Add new collection item
    $(document).on('click', collectionSelector+' .add-item', function(e) {
        e.preventDefault();

        // Get the data-prototype
        var prototype = $collectionHolder.data('prototype');

        // get the new index
        var index = $collectionHolder.data('index');

        // Update the name and id
        var newForm = prototype;
        newForm = newForm.replace(/__name__/g, index);

        // Update the index and add the form
        $collectionHolder.data('index', index + 1);
        var $newFormLi = $('<li></li>').append(newForm);
        $collectionHolder.find(".add-item").parents("li").before($newFormLi.hide().fadeIn(300));
    });

    // Remove an item from the collection
    $(document).on('click', collectionSelector+' .delete-item', function(e) {
        e.preventDefault();
        $(this).parents(".collection-row").fadeOut(300, function() { $(this).remove(); });
    });


});

