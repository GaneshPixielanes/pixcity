module.exports = {
    show: function(onLoad) {
        var firstErrorTab = $((onLoad)?".error-message":".input-container.error").first().parents(".tab-pane").attr("id");

        if (firstErrorTab && !$("a[href='#" + firstErrorTab + "']").parent().hasClass("active")) {
            $("a[href='#" + firstErrorTab + "']").click();
        }
    }
};


jQuery(document).ready(function() {

    module.exports.show(true);

});