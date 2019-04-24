jQuery(document).ready(function() {

    var routeDepartmentsList = $("#api-routes").attr("data-departments-route");

    //---------------------------------------------
    // Nested selects
    //---------------------------------------------

    var regionSelect = $("#card_wall_region");
    var departmentSelect = $("#card_wall_department");

    regionSelect.change(function(){
        var regionId = $(this).val();

        $.get(
            routeDepartmentsList,
            {
                regionId: regionId
            },
            function(departments) {
                departmentSelect.find(":gt(0)").remove();
                $.each(departments, function (key, department) {
                    departmentSelect.append('<option value="' + department.id + '">' + department.name + '</option>');
                });
                departmentSelect.val('').selectpicker('refresh');
            }
        );

    });



});

