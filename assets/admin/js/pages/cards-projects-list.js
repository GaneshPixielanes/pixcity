jQuery(document).ready(function() {

    var routeDepartmentsList = $("#api-routes").attr("data-departments-route");
    var routePixiesList = $("#api-routes").attr("data-pixies-route");
    var routeAssignProject = $("#api-routes").attr("data-assign-route");


    //------------------------------------
    // Bulk
    //------------------------------------

    var bulkIds = [];

    function checkBulk(){
        bulkIds = [];
        $("input[name='bulk[]']:checked").each( function () {
            bulkIds.push($(this).val());
        });

        updateBulkRow();

        return bulkIds;
    }

    function updateBulkRow(){
        if(bulkIds.length > 0){
            $("#bulk-row").fadeIn();
        }
        else{
            $("#bulk-row").fadeOut();
        }
    }

    $("input[name='bulk[]']").change(function(){
        checkBulk();
    });

    $("#bulk-checkall").change(function(){
        $("input[name='bulk[]']").prop("checked", $(this).is(":checked"));
        checkBulk();
    });

    $("#bulk-action").change(function(){
        switch($(this).val()){
            case "assign":
                $("#bulk-assign-pixie").fadeIn();
                break;
            default:
                $("#bulk-assign-pixie").fadeOut();
        }
    });

    $("#apply-bulk").click(function(){
       switch($("#bulk-action").val()){
           case "assign":
               bulkAssign();
               break;
           default:
               console.log("Do nothing");
       }
    });


    //------------------------------------
    // Autocomplete
    //------------------------------------

    var bulkPixieId;

    var $bulkPixieAutoComplete = $("#bulk-assign-pixie");
    var debounceAutocomplete;
    $bulkPixieAutoComplete.find("input").keyup(function(e) {
        autocompletePixie();
    }).focus(function(e) {
        autocompletePixie();
    }).blur(function(e) {
        setTimeout(function(){
            $bulkPixieAutoComplete.find("ul").remove();
        }, 100);
    });

    function autocompletePixie(){
        var search = $bulkPixieAutoComplete.find("input").val();
        if(!search) return false;

        if(debounceAutocomplete) clearTimeout(debounceAutocomplete);
        debounceAutocomplete = setTimeout(function(){
            $.get(
                routePixiesList,
                {
                    search: search
                },
                function(pixies) {
                    if(pixies.length === 1){
                        bulkPixieId = pixies[0].id;
                    }
                    else{
                        bulkPixieId = null;
                    }

                    $bulkPixieAutoComplete.find("ul").remove();
                    var listHtml = "<ul>";
                    var tags;
                    pixies.forEach(function(pixie){
                        tags = "";
                        pixie.pixie.regions.forEach(function(region){
                            tags += "<span class='tag'>"+region.name+"</span>";
                        });
                        listHtml += "<li class='pixie' data-id='"+pixie.id+"'><div class='name'>"+pixie.firstname+" "+pixie.lastname+"</div>"+tags+"</li>";
                    })
                    listHtml += "</ul>";
                    $bulkPixieAutoComplete.append(listHtml);
                }
            );
        }, 300);
    }

    $bulkPixieAutoComplete.on("click", ".pixie", function(){
        bulkPixieId = $(this).attr("data-id");
        $bulkPixieAutoComplete.find("input").val($(this).find(".name").text()+" (ID: "+bulkPixieId+")");
    });

    function bulkAssign(){
        if(bulkPixieId && bulkIds.length > 0){

            $.post(
                routeAssignProject,
                {
                    userId: bulkPixieId,
                    projectsIds: bulkIds
                },
                function(projects) {
                    if(projects && projects.length > 0){
                        projects.forEach(function(project){
                           $("tr[data-id='"+project.id+"']").replaceWith($(project.row));
                        });
                        swal("Assignation", "Les projets ont bien été assignés", "success");
                        $bulkPixieAutoComplete.find("input").val('');
                        checkBulk();
                    }
                    else{

                    }
                }
            );

        }
    }



});

