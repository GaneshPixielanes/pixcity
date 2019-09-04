window.jQuery = window.$ = require('jquery');
require('../pages/map') ;

$(document).ready(function () {

    function calculateLimitcount() {
        var height = $("#scroll-map-count").height(), $section = $(".masonry-item-map-count"),
            limit = $section.eq(0).height();
        if (height < 3 * 350) {
            return 3 * 350;
        }
        return height - (1 * limit);
    }

    var hit = false;
    var next = true;

    $(document).scroll(function () {
        if ($(window).scrollTop() >= calculateLimitcount()) {
            if (hit || ! next) {
                return false;
            }
            callmapcountfunction();
            hit = true;
        }
    });

    function callmapcountfunction() {

        $(".loader-map-count").show();
        $.ajax({
            url:'/call-to-count-map',
            type:'GET',
            dataType: 'HTML'
        }).done(function(res){
            hit = false;
            next = false;
            $("#map-count").html(res);
            $(".loader-map-count").hide();
        }).fail(function () {
            hit = false;
        });

    }

});