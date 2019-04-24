//------------------------------------
// Vendors - jQuery & Bootstrap are loaded from a CDN because of the use of Bootstrap 3.3
//------------------------------------

window.jQuery = window.$ = require('jquery');
require('./vendors/bootstrap.min.js');
require('node-waves');
require('jquery-validation');
require('jquery-validation/dist/localization/messages_fr');
require('jquery-slimscroll');
require('datatables.net-bs' );
require('datatables.net-responsive-bs' );
require('datatables.net-rowreorder' );
require('bootstrap-select' );

require('froala-editor/js/froala_editor.pkgd.min.js');
require('froala-editor/js/third_party/embedly.min');

window.moment = require('moment/moment.js');
require('moment/locale/fr');
require('./vendors/bootstrap-material-datetimepicker.js');
require('sweetalert');
require('select2');
window.Sortable = require('sortablejs/Sortable.min');
var datatableLang = require('./vendors/i18n/datatable.french.json');


//------------------------------------
// Require images
//------------------------------------

const imagesCtx = require.context('../images', true, /\.(png|jpg|jpeg|gif|ico|svg|webp)$/);
imagesCtx.keys().forEach(imagesCtx);


//------------------------------------
// App
//------------------------------------

jQuery.validator.addMethod("password", function(value, element) {
    return this.optional( element ) || /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d$@$!%*#?&]{8,}$/.test( value );
}, 'Votre mot de passe doit contenir au moins 8 caractères dont au moins 1 chiffre');

jQuery.validator.addMethod("phone", function(value, element) {
    return this.optional( element ) || /^(\+?\d+){9,}$/.test( value );
}, 'Numéro de téléphone invalide');


//------------------------------------
// Admin
//------------------------------------

require('./vendors/admin.js');
require('./pages.js');
require('./shared/typeahead.js');
require('./shared/cardlist.js');


window.froalaDisplayError = function(  editor, error ){

}

define(["jquery", "jquery-validation"], function( $ ) {


    $(document).ready(function () {

        //$('select').selectpicker({});

        $('#sign_in').validate({
            rules: {
                "_password": {minlength: 1, password: false},
            },
            highlight: function (input) {
                $(input).parents('.form-line').addClass('error');
            },
            unhighlight: function (input) {
                $(input).parents('.form-line').removeClass('error');
            },
            errorPlacement: function (error, element) {
                $(element).parents('.input-group').append(error);
            }
        });



        //---------------------------------------------------------
        // Date picker

        $(".js-datepicker input").bootstrapMaterialDatePicker({
            weekStart : 1,
            time: false,
            lang: 'fr',
            format : 'dddd DD MMMM YYYY'
        });


        //---------------------------------------------------------
        // DATATABLE

        $('.active-datatable').each(function(){


            //---------------------------------------------------------
            // For table with row reorder, hide the two firsts columns (position/id)

            var colDef = [];
            if($(this).hasClass("sortable")){
                colDef = [
                    { orderable: true, className: 'reorder', targets: 0, visible: false },
                    { orderable: false, className: 'reorder', targets: 1, visible: false },
                    { orderable: false, targets: '_all' }
                ];
            }

            if($(this).hasClass("bulkable")){
                colDef = [
                    { orderable: false, targets: 0 },
                ];
            }

            //---------------------------------------------------------
            // Get position update route URL

            var updatePositionRoute = $(this).attr("data-update-position-route");



            //---------------------------------------------------------
            // Set configuration

            var datatableConfig = {
                rowReorder: {
                    enable: $(this).hasClass("sortable"),
                    selector: ".drag-handle",
                    snapX: true
                },
                columnDefs: colDef,
                order: [[$(this).attr("data-defaultsortby"), $(this).attr("data-defaultsortby")?$(this).attr("data-defaultsortdir"):"asc"]],
                "language": datatableLang
            };

            //---------------------------------------------------------
            // Init the datatable

            var table = $(this).DataTable(datatableConfig);


            //---------------------------------------------------------
            // On row reorder - update the position

            table.on( 'row-reorder', function ( e, diff, edit ) {

                var draggedId = edit.triggerRow.data()[1];
                var newPosition;

                for ( var i=0 ; i<diff.length ; i++ ) {
                    if(draggedId === $(diff[i].node).attr("data-id")){
                        newPosition = diff[i].newData;
                    }
                }

                if(draggedId && newPosition){
                    $.post(
                        updatePositionRoute,
                        {
                            id: draggedId,
                            position: newPosition
                        },
                        function( data ) {
                            console.log(data);
                        }
                    );
                }

            } );

        });


        //---------------------------------------------------------
        // Delete form confirm

        $(document).on("click", ".delete-form button", function(e){
            e.preventDefault();
            $form = $(this).parents("form");

            swal({
                title: "Attention",
                text: "Cet élément sera supprimé définitivement.",
                icon: "warning",
                buttons: [
                    'Annuler',
                    'Supprimer'
                ],
                dangerMode: true,
            }).then(function (isConfirm) {
                if (isConfirm) {
                    $form.submit();
                }
            })

        });


    });

});