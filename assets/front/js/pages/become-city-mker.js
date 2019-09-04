window.jQuery = window.$ = require('jquery');
require('../pages/map') ;

$(document).ready(function () {

    function calculateLimitCityMaker() {
        var height = $("#scroll-become-citymaker").height(), $section = $(".masonry-item-become-citymaker"),
            limit = $section.eq(0).height();
        if (height < 3 * 400) {
            return 3 * 400;
        }
        return height - (1 * limit);
    }

    var hit = false;
    var next = true;

    $(document).scroll(function () {
        if ($(window).scrollTop() >= calculateLimitCityMaker()) {
            if (hit || ! next) {
                return false;
            }
            callCityMakerfunction();
            hit = true;
        }
    });

    function callCityMakerfunction() {
        $(".loader-citymaker").show();
        $.ajax({
            url:'/call-to-become-city-maker',
            type:'GET',
            dataType: 'HTML'
        }).done(function(res){
            hit = false;
            next = false;
            $("#become-citymaker").html(res);
            $(".loader-citymaker").hide();
        }).fail(function () {
            hit = false;
        });

    }

});