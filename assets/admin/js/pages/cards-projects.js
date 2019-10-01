jQuery(document).ready(function() {

    var routeDepartmentsList = $("#api-routes").attr("data-departments-route");
    var routePixiesList = $("#api-routes").attr("data-pixies-route");
    var routeUpload = $("#api-routes").attr("data-upload-route");
    var routeSaveTemplate = $("#api-routes").attr("data-savetemplate-route");
    var routeLoadTemplate = $("#api-routes").attr("data-loadtemplate-route");
    var priceFlag = false;
    $('#card_project_price').on('input',function () {
        if(priceFlag == false)
        {
            $('#priceConfirmationModal').modal('show');
        }
    });

    $('#allowPayment').on('click', function () {
        priceFlag = true;
    });

    $('#disallowPayment').on('click', function () {
        priceFlag = false;
        $('#card_project_price').val(0);
    });
    //---------------------------------------------
    // Nested selects
    //---------------------------------------------

    var regionSelect = "#card_project_region";
    var departmentSelect = "#card_project_department";
    var pixieSelect = "#card_project_pixie";

    $(document).on("change", regionSelect, function(){
        var regionId = $(this).val();

        $.get(
            routeDepartmentsList,
            {
                regionId: regionId
            },
            function(departments) {
                $(departmentSelect).find(":gt(0)").remove();
                $.each(departments, function (key, department) {
                    $(departmentSelect).append('<option value="' + department.id + '">' + department.name + '</option>');
                });
                $(departmentSelect).val('').selectpicker('refresh');
            }
        );

        $.get(
            routePixiesList,
            {
                regionId: regionId
            },
            function(pixies) {
                $(pixieSelect).find(":gt(0)").remove();
                $.each(pixies, function (key, pixie) {
                    $(pixieSelect).append('<option value="' + pixie.id + '">' + pixie.firstname + ' ' + pixie.lastname + '</option>');
                });
                $(pixieSelect).val('').selectpicker('refresh');
            }
        );

    });


    //---------------------------------------------
    // Card Info Collection
    //---------------------------------------------

    // Store the collection tag
    var collectionSelector = [".card-template-infos-collection", ".card-template-attachments-collection"];
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
            var $newFormLi = $('<li></li>').append(newForm);
            $collection.find(".add-item").parents("li").before($newFormLi.hide().fadeIn(300));
        });

        // Remove an item from the collection
        $(document).on('click', selector+' .delete-item', function(e) {
            e.preventDefault();
            $(this).parents(".collection-row").fadeOut(300, function() { $(this).remove(); });
        });

    });


    //---------------------------------------------
    // Attachments
    //---------------------------------------------

    $(document).on('change', '.upload-zone .file-input', function() {
        var file = this.files[0];
        var formData = new FormData();
        formData.append("file", file);

        $(this).parents(".upload-zone").addClass("uploading");

        $.ajax({
            url: routeUpload,
            type: 'POST',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            xhr: function() {
                var myXhr = $.ajaxSettings.xhr();
                if (myXhr.upload) {
                    myXhr.upload.addEventListener('progress', function(e) {
                        if (e.lengthComputable) {
                            $('.upload-zone .progress-bar').width((e.loaded / e.total) * 100 + "%");
                        }
                    } , false);
                }
                return myXhr;
            }
        }).done(function(attachment) {
            addAttachment(attachment);
            $(".upload-zone").removeClass("uploading");
        }).fail(function(xhr, status, error) {
            $container.removeClass("uploading");
            swal({icon: "error", text: "Votre fichier n'a pas pu �tre envoy�"});
            $(input).val("");
        });
    });

    function addAttachment(attachment){
        $collection = $(".card-template-attachments-collection");
        var prototype = $collection.data('prototype');

        var index = $collection.data('index');
        var newForm = prototype;
        newForm = newForm.replace(/__name__/g, index);
        var $newFormRow = $(newForm);
        $collection.data('index', index + 1);

        $newFormRow.addClass("type-"+attachment.type);
        $newFormRow.find(".field input").val(attachment.name);
        $newFormRow.find(".thumb").css({backgroundImage: 'url('+attachment.url+')'});
        $newFormRow.find(".name").text(attachment.name);

        $collection.find(".ajax-upload-item").parents("li").before($newFormRow.hide().fadeIn(300));
    }


    //------------------------------------
    // Save as a template
    //------------------------------------

    $("#save-as-template").click(function(){

        swal({
            text: 'Nom du nouveau mod�le',
            content: "input",
            button: {
                text: "Sauvegarder",
                closeModal: false,
            },
        }).then(function(name){
            console.log(name);
            if(name) {
                $.ajax({
                    type: "POST",
                    url: routeSaveTemplate,
                    data: $("form[name='card_project']").serialize() + '&templateName=' + name,
                    success: function (data) {
                        swal("Mod�le de Card", "Le template de card � bien �t� cr��", "success");
                    }
                });
            }

        });


    });


    //------------------------------------
    // Load a template
    //------------------------------------

    $("form#load-template .btn-load-template").click(function(){

        var templateId = $("form#load-template select").val();

        if(templateId) {

            swal({
                title: "Attention",
                text: "Le contenu du formulaire sera remplac� par celui du mod�le.",
                icon: "warning",
                buttons: [
                    'Annuler',
                    'Continuer'
                ],
                dangerMode: true,
            }).then(function (isConfirm) {
                if (isConfirm) {

                    $.ajax({
                        type: "POST",
                        url: routeLoadTemplate,
                        data: {
                            id: templateId
                        },
                        success: function(data){
                            swal({
                                title: 'Succ�s',
                                text: 'Le mod�le a bien �t� charg�',
                                icon: 'success'
                            });

                            $("form[name='card_project']").replaceWith(data.form);
                            $('select:not(.classic)').selectpicker({});

                        }
                    });


                } else {
                    swal("Op�ration annul�e", "Le mod�le n'a pas �t� charg�", "error");
                }
            })
        }


    });


});

$(document).ready(function()
{
    var isSaved = true;

    $('body').on('keypress','input, .fr-view', function () {
        console.log('triggered');
        isSaved = false;
    });

    $('body').on('click','button, a.waves-effect', function()
    {
        isSaved = true;
    });
    // Check if the user has saved or not before leaving
    jQuery(window).bind('beforeunload', function(){
        if(isSaved == false)
        {
            return 1;
        }

    });
});

$(document).ready(function()
{
    var interview = $('#card_project_isInterview').val();
    var mode = $('#mode').val();

    if(interview == '0' || interview == null)
    {
        console.log('matched');
        $('.type-froala').fadeOut();
        $('#card_project_price').val('0');
    }

    $('#card_project_isInterview').on('change',function () {
        $('.type-froala').fadeToggle();
        if($(this).val() == 1)
        {
            $('#card_project_price').val('10');

            if(mode != '')
            {
                console.log(mode);
                $('#card_project_region').attr('disabled','disabled');
                $('#card_project_department').attr('disabled','disabled');
                $('#card_project_pixie').attr('disabled','disabled');
                $('#card_project_name').attr('disabled','disabled');
                $('input[type="checkbox"]').attr('disabled','disabled');

            }
        }
        else
        {
            $('#card_project_price').val('0');
        }
    });

    
    $('form').on('submit',function(){
        $('#card_project_region').removeAttr('disabled');
        $('#card_project_department').removeAttr('disabled');
        $('#card_project_pixie').removeAttr('disabled');
        $('#card_project_name').removeAttr('disabled');
        $('input[type="checkbox"]').removeAttr('disabled');
    });
});