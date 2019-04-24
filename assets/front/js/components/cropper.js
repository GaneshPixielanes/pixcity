const Cropper = require('cropperjs/dist/cropper.min') ;


var $modal;
var $btnValidateCrop;
var $image;
var cropper;
var aspectRatio = 1;

module.exports = {

    init: function(){
        $modal = $("#modal-cropper-tool");
        $btnValidateCrop = $("#validate-crop");
        $image = $("#crop-image");

        $modal.on('shown.bs.modal', function () {
            cropper = new Cropper($image[0], {
                aspectRatio: aspectRatio,
                autoCropArea: 0.8,
                strict: true,
                ready: function () {

                }
            });
        }).on('hidden.bs.modal', function () {
            cropper.destroy();
        });

        $btnValidateCrop.click(function (e) {
            e.preventDefault();

            cropper.getCroppedCanvas().toBlob(function (blob) {
                $modal.modal("hide");
                $(module.exports).trigger("crop", blob);
            }, 'image/jpeg');
        })
    },

    show: function(image, ratio) {
        aspectRatio = ratio;
        $image.attr("src", image);
        $modal.modal("show");
    }
};

