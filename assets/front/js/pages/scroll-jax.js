window.jQuery = window.$ = require('jquery');
require('../pages/map') ;

$(document).ready(function () {

    function calculateLimit() {
        var height = $("#scroll-map").height(), $section = $(".masonry-item"),
            limit = $section.eq(0).height();
        if (height < 3 * 200) {
            return 3 * 200;
        }
        return height - (1 * limit);
    }

    var hit = false;
    var next = true;

    $(document).scroll(function () {
        if ($(window).scrollTop() >= calculateLimit()) {
            if (hit || ! next) {
                return false;
            }
            callmapfunction();
            hit = true;
        }
    });

    function callmapfunction() {

        $(".loader-card").show();

        $.ajax({
            url:'/call-to-card',
            type:'GET',
            dataType: 'HTML'
        }).done(function(res){
            hit = false;
            next = false;
            $("#card-wrap").html(res);
            $(".loader-card").hide();
        }).fail(function () {
            hit = false;
        });

    }

});