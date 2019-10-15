window.jQuery = window.$ = require('jquery');
require('../pages/map') ;

$(document).ready(function () {

    function calculateLimitTopCard() {
        var height = $("#scroll-top-card").height(), $section = $(".masonry-item-top-card"),
            limit = $section.eq(0).height();
        if (height < 3 * 350) {
            return 3 * 350;
        }
        return height - (1 * limit);
    }

    var hit = false;
    var next = true;

    $(document).scroll(function () {
        if ($(window).scrollTop() >= calculateLimitTopCard()) {
            if (hit || ! next) {
                return false;
            }
            callMapTopCardfunction();
            hit = true;
        }
    });

    function callMapTopCardfunction() {
        $(".loader-top-card").show();
        $.ajax({
            url:'/call-to-top-cards',
            type:'GET',
            dataType: 'HTML'
        }).done(function(res){
            hit = false;
            next = false;
            $("#top-card").html(res);
            $(".loader-top-card").hide();
        }).fail(function () {
            hit = false;
        });

    }

});