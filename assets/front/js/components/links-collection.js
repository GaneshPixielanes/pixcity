jQuery(document).ready(function() {

    $socialTypeSelectContainer = $(".addRs-container");
    $socialTypeSelect = $socialTypeSelectContainer.find("select");

    //---------------------------------------------
    // Users Links Collection
    //---------------------------------------------

    var linkTypes = {
        "instagram": {
            "id": "instagram", "name": "Instagram", "icon": "fab fa-instagram"
        },
        "facebook": {
            "id": "facebook", "name": "Facebook", "icon": "fab fa-facebook-f"
        },
        "twitter": {
            "id": "twitter", "name": "Twitter", "icon": "fab fa-twitter"
        },
        "youtube": {
            "id": "youtube", "name": "Youtube", "icon": "fab fa-youtube"
        },
        "blog": {
            "id": "blog", "name": "Blog", "icon": "fab fa-blogger-b"
        },
        "other": {
            "id": "other", "name": "Autre", "icon": "fas fa-link"
        }
    };


    // Store the collection tag
    var collectionSelector = ".user-links-collection";
    var $collectionHolder;

    // Get the tag that holds the collection and the add item button
    $collectionHolder = $(collectionSelector);

    // count the collection items we have on load
    $collectionHolder.data('index', $collectionHolder.find('.collection-row').length);

    // Add new collection item
    $(document).on('click', collectionSelector+' .add-item', function(e) {
        e.preventDefault();

        var type = $(this).parent().find("select").val();

        // Get the data-prototype
        var prototype = $collectionHolder.data('prototype');

        // get the new index
        var index = $collectionHolder.data('index');

        // Update the name and id
        var newForm = prototype;
        newForm = newForm.replace(/__name__/g, index);

        // Update the index and add the form
        $collectionHolder.data('index', index + 1);
        var $newFormLi = $(newForm);

        $newFormLi.find(".user-link").attr("data-type", type);
        $newFormLi.find("select").val(type);
        $newFormLi.find("label").text(linkTypes[type].name);
        $newFormLi.find("label").addClass(linkTypes[type].name);
        $newFormLi.find("."+linkTypes[type].name).after('<span style="color: #fd6b84;"> * </span>');
        $newFormLi.find(".link-icon").addClass(linkTypes[type].icon);

        $collectionHolder.find(".add-item").parents("li").before($newFormLi.hide().fadeIn(300));
        rebuildList();
    });

    // Remove an item from the collection
    $(document).on('click', collectionSelector+' .delete-item', function(e) {
        e.preventDefault();
        $(this).parents(".collection-row").fadeOut(300, function() {
            $(this).parent().remove();
            rebuildList();
        });
    });

    function rebuildList(){
        var types = jQuery.extend({}, linkTypes);
        $(".user-link").each(function(){
           if(types[$(this).attr("data-type")]) delete types[$(this).attr("data-type")];
        });

        $socialTypeSelect.empty();
        $.each(types, function(key,value) {
            $socialTypeSelect.append($("<option></option>").attr("value", key).text(value.name));
        });

        $socialTypeSelectContainer.toggle(Object.keys(types).length > 0);
    }

    rebuildList();

});