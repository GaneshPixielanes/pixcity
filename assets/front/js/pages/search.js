require('../vendors/fixto.min');
require('../components/popular-cards');
require('../components/pagination');

var map;
var markers = [];
var markersCards = [];
var marker, card;
var lat, lng, id;
var zindex = 0;
var isBoundsChanged = true;

jQuery(document).ready(function() {


    $('.map.col-md-5, .map.col-md-7').fixTo('body', {
        mind: 'nav'
    });

    $(window).on('load', function() {

        function loadMapMarkers() {

            for (var i = 0; i < markers.length; i++) {
                markers[i].setMap(null);
                markersCards[i].destroy();
            }
            markers = [];
            markersCards = [];

            var delay = 0;
            $(".cards-grid .card[data-latitude]").each(function () {
                addMarkerWithDelay(this, delay*100);
                delay++;
            });
        }
        loadMapMarkers();

        function addMarkerWithDelay(card, delay){
            if(typeof google !== 'object' || typeof google.maps !== 'object') return false;

            setTimeout(function(){
                id = $(card).attr("data-id");
                lat = parseFloat($(card).attr("data-latitude"));
                lng = parseFloat($(card).attr("data-longitude"));

                if (getMarkerIndexById(id) === null) {

                    marker = new google.maps.Marker({
                        id: id,
                        latitude: lat,
                        longitude: lng,
                        icon: mapSettings.icon,
                        position: {lat: lat, lng: lng},
                        map: map,
                        title: $(card).find('.cardTitle').text(),
                        animation: google.maps.Animation.FADE
                    });

                    marker.addListener('mouseover', function() {
                        this.setZIndex(++zindex);
                    });

                    cardInfoWindow = new SnazzyInfoWindow({
                        marker: marker,
                        wrapperClass: 'card-info-widnow',
                        content: $(card).prop('outerHTML'),
                        closeWhenOthersOpen: true,
                        showCloseButton: false,
                        placement: 'right',
                        pointer: false,
                        offset: {
                            top: '10px',
                            left: '22px'
                        },
                        callbacks: {
                            beforeOpen: function () {
                                this._marker.setZIndex(++zindex);
                                this._marker.setIcon(mapSettings.iconActive);
                            },
                            afterClose: function () {
                                this._marker.setIcon(mapSettings.icon);
                            },
                            afterOpen: function(){
                                $(".si-content-wrapper .card .cta-like-card").click(function(){
                                   $(window).trigger("like-card", $(this).attr("data-id"));
                                });

                                $(".si-content-wrapper .card .cta-favorite-card").click(function(){
                                    $(window).trigger("favorite-card", $(this).attr("data-id"));
                                });
                            }
                        }
                    });

                    markers.push(marker);
                    markersCards.push(cardInfoWindow);

                    setTimeout(function(){
                       $('img[src^="'+mapSettings.icon+'"]').addClass("anim");
                    }, 100);
                }
            }, delay);
        }

        $(window).on("card-list-updated", function(){
            loadMapMarkers();
        });

        $(document).on("mouseenter", ".cards-grid .card[data-latitude]", function(){
            if(markers[getMarkerIndexById($(this).attr("data-id"))]) {
                markers[getMarkerIndexById($(this).attr("data-id"))].setZIndex(++zindex);
                markers[getMarkerIndexById($(this).attr("data-id"))].setIcon(mapSettings.iconActive);
            }
        });

        $(document).on("mouseleave", ".cards-grid .card[data-latitude]",function(){
            if(markers[getMarkerIndexById($(this).attr("data-id"))]) {
                markers[getMarkerIndexById($(this).attr("data-id"))].setIcon(mapSettings.icon);
            }
        });

        function getMarkerIndexById(id){
            for(var i=0; i<markers.length; i++){
                if(parseInt(markers[i].id) === parseInt(id)){
                    return i;
                }
            }
            return null;
        }

        function centerMapOnMarkers(){
            var bounds = new google.maps.LatLngBounds();
            for (i = 0; i < markers.length; i++) {
                bounds.extend(markers[i].position);
            }

            if(markers.length > 1)
            {
                map.fitBounds(bounds);
            }
            else
            {
                map.setCenter(bounds.getCenter());
                map.setZoom(15);
            }

        }

        /* TOGGLE MAP*/
        $("#toggleMap, #toggleMapMobile").click(function (e) {
            e.preventDefault();

            $(".region .main").toggleClass("container");
            $(".region .main").toggleClass("container-full");
            $(".region.main").toggleClass("container-fluid");
            $(".toggle").toggleClass("col-md-5");
            $(".toggle-category").toggleClass("col-md-5");
            $(".toggle").toggleClass("col-md-12");
            $(".toggle-category").toggleClass("col-md-12");
            $(".map").toggleClass("open");

            buildSlider($(".map").hasClass("open"));

            $(window).resize();

            if(map){
                setTimeout(function(){
                    google.maps.event.trigger(map, 'resize');
                    centerMapOnMarkers();
                }, 1000);
            }

            /* reset ISOTOPE*/
            /*var $grid = $('#cardsGrid').isotope({
             itemSelector: '.card',
             layoutMode: 'fitRows'
             });*/

            /*reset carousel*/
            //$('.slider').slick('unslick');


        });

        $(".map .mobile-close").click(function(e){
            e.preventDefault();
            $("#toggleMapMobile").click();
        });

        $(".orderby-dropdown li a").click(function(e){
            e.preventDefault();
            $("#searchNavBar input[name='orderby']").val($(this).attr("data-value"));
            $("#searchNavBar button[type='submit']").click();
        });



        function buildSlider(isLarge){

            if($('.carrousel-search').length === 0) return false;

            $('.carrousel-search').each(function(){
                if($(this).hasClass("slick-initialized"))
                    $(this).slick("unslick");
            });

            if(!isLarge) {

                $('.carrousel-search').slick({
                    dots: false,
                    infinite: false,
                    speed: 300,
                    slidesToShow: 5,
                    slidesToScroll: 5,
                    prevArrow: '<a class="slick-prev" href="#"></a>',
                    nextArrow: '<a class="slick-next" href="#"></a>',
                    responsive: [
                        {
                            breakpoint: 1241,
                            settings: {
                                slidesToShow: 4,
                                slidesToScroll: 4,

                            }
                        },
                        {
                            breakpoint: 992,
                            settings: {
                                slidesToShow: 3,
                                slidesToScroll: 3,

                            }
                        },
                        {
                            breakpoint: 768,
                            settings: {
                                slidesToShow: 2,
                                slidesToScroll: 2
                            }
                        },
                        {
                            breakpoint: 550,
                            settings: {
                                slidesToShow: 1,
                                slidesToScroll: 1
                            }
                        }
                    ]
                });

            }
            else{

                $('.carrousel-search').slick({
                    dots: false,
                    infinite: false,
                    speed: 300,
                    slidesToShow: 7,
                    slidesToScroll: 7,
                    prevArrow: '<a class="slick-prev" href="#"></a>',
                    nextArrow: '<a class="slick-next" href="#"></a>',
                    responsive: [
                        {
                            breakpoint: 1710,
                            settings: {
                                slidesToShow: 6,
                                slidesToScroll: 6,

                            }
                        },
                        {
                            breakpoint: 1600,
                            settings: {
                                slidesToShow: 5,
                                slidesToScroll: 5,

                            }
                        },
                        {
                            breakpoint: 1241,
                            settings: {
                                slidesToShow: 4,
                                slidesToScroll: 4,

                            }
                        },
                        {
                            breakpoint: 992,
                            settings: {
                                slidesToShow: 3,
                                slidesToScroll: 3,

                            }
                        },
                        {
                            breakpoint: 768,
                            settings: {
                                slidesToShow: 2,
                                slidesToScroll: 2
                            }
                        },
                        {
                            breakpoint: 550,
                            settings: {
                                slidesToShow: 1,
                                slidesToScroll: 1
                            }
                        }
                    ]
                });

            }


        }

        buildSlider(false);
        setTimeout(function(){
            google.maps.event.trigger(map, 'resize');
            centerMapOnMarkers();
        }, 500);

    });

});


window.googleMapsApiInit = function() {
    if(!google) return false;
    window.SnazzyInfoWindow = require('snazzy-info-window/dist/snazzy-info-window.min');

    var mapOptions = {
        center: new google.maps.LatLng(47.02427937142805, 1.6561921375000477),
        zoom: 5.5,
        styles: [
            {
                "featureType": "administrative.country",
                "elementType": "labels.text.fill",
                "stylers": [
                    {
                        // "color": "#ef5285"
                    }
                ]
            },
            {
                "featureType": "administrative.province",
                "elementType": "labels.text.fill",
                "stylers": [
                    {
                        // "color": "#ef5285"
                    }
                ]
            },
            {
                "featureType": "administrative.locality",
                "elementType": "labels.text.fill",
                "stylers": [
                    {
                        // "color": "#ef5285"
                    }
                ]
            },
            {
                "featureType": "landscape",
                "elementType": "all",
                "stylers": [
                    {
                        // "hue": "#0083ff"
                    }
                ]
            },
            {
                "featureType": "poi.park",
                "elementType": "all",
                "stylers": [
                    {
                        // "hue": "#00ffbe"
                    },
                    {
                        // "lightness": "-14"
                    },
                    {
                        // "saturation": "21"
                    }
                ]
            },
            {
                "featureType": "road",
                "elementType": "all",
                "stylers": [
                    {
                        "visibility": "on"
                    }
                ]
            },
            {
                "featureType": "road",
                "elementType": "labels.icon",
                "stylers": [
                    {
                        // "hue": "#ff0053"
                    },
                    {
                        "visibility": "simplified"
                    },
                    {
                        // "saturation": "-100"
                    },
                    {
                        // "lightness": "0"
                    }
                ]
            },
            {
                "featureType": "road.highway",
                "elementType": "geometry",
                "stylers": [
                    {
                        "weight": "1"
                    },
                    {
                        "visibility": "on"
                    },
                    {
                        "lightness": "-2"
                    },
                    {
                        // "gamma": "3.46"
                    },
                    {
                        // "hue": "#0083ff"
                    },
                    {
                        "saturation": "-100"
                    }
                ]
            },
            {
                "featureType": "water",
                "elementType": "all",
                "stylers": [
                    {
                        // "color": "#103558"
                    },
                    {
                        // "visibility": "simplified"
                    },
                    {
                        // "lightness": "10"
                    }
                ]
            }
        ]
    };

    if(document.getElementById("map-france")) {
        map = new google.maps.Map(document.getElementById("map-france"), mapOptions);
        google.maps.event.addListener( map, 'bounds_changed', onBoundsChanged );

    }


    function onBoundsChanged() {
        console.log($('.gm-style-pbc').height());
        if($('.gm_style').height()===window.innnerHeight)
        {
             isBoundsChanged = true;
        }
        else
        {
         isBoundsChanged = false;
        }
    }

    $('body').on("shown.bs.modal", function(e) {
        // var esc = $.Event("keydown", { keyCode: 27 });
        console.log(isBoundsChanged);
        if(isBoundsChanged == true)
        {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            } else if (document.webkitCancelFullScreen) {
                document.webkitCancelFullScreen();
            }

        }
    });

};

$(document).ready(function()
{
    //---------------------------------------------
    // Share
    //---------------------------------------------

    var sharePopOverSettings = {
        html: true,
        trigger: "click",
        template: '<div class="popover popRs"><div class="arrow"></div><div class="popover-content"></div></div>',
        content: function () {
            window.Sharer.init();
            return $(this).next().html();
        },
        selector: '.cta-share-popover'
    };

    $('html').popover(sharePopOverSettings);

    $('html').on('shown.bs.popover', ".cta-share-popover", function (e) {
        e.preventDefault();
        window.Sharer.init();
    });
});

