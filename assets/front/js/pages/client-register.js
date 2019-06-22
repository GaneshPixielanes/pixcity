
const firstError = require('../components/first-error') ;
require('../components/avatar-upload') ;
require('../components/address') ;
require('../components/links-collection') ;
require('../components/froala-max') ;

jQuery(document).ready(function() {

    $form = $('form[name="client"]');


    var validator = $form.validate({
        ignore: ".tab-pane:not(.active) :hidden",
        rules: {
            "client[clientInfo][siret]": {maxlength: 14},
        }

    });

    $form.on('submit',function () {
        $form.valid();
    })

});