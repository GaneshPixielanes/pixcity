var datatableLang = require('../vendors/i18n/datatable.french.json');
/* Preview of e-mail body */

$(document).ready(function () {

   //---------------------------------------------------------
   // Ajax datatable

   $datatable = $(".datatable-ajax");

   var ajax = $datatable.attr("data-ajax");

   var table = $datatable.DataTable({
      responsive: true,
      serverSide: true,
      ajax: {
         url: ajax,
         type: "POST"
      },
      columns: [
         { "data": "subject" },
         { "data": "slug" },
         { "data": "sentTo" },
         { "data": "createdAt" },
         { "data": "status" },
         { "data": "id",
            "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
               $(nTd).html("<a href='edit/"+oData.id+"' class=\"btn btn-circle bg-pink waves-effect waves-circle waves-float\"><i class=\"material-icons\">edit</i></a>");
            }
         }
      ],
      language: datatableLang,
      createdRow: function ( row, data, index ) {
         if(data.deleted === true){
            $(row).addClass('deleted');
         }
      },
      "order": [[ 0, "desc" ]]
   });

   $('#email_body').change(function () {
      $('#previewBody').html($(this).val());
   });
});

