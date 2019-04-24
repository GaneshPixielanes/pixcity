//const LightGallery = require('lightgallery');
//require('lg-thumbnail.js');

var map;
var $map;

jQuery(document).ready(function() {
    var creditsLink = $('.instagramLink').attr('href');
    creditsLink = creditsLink.split('/')[3];
    $('.creditsLink').html('Crédit Photo: <a href="'+$('.instagramLink').attr('href')+'"  style="color:#fff">@'+creditsLink+'</a>');
    $('#interview-content').fadeOut(); // Do not display Interview content by default
    $gallery = $("#card-gallery");

    $(window).on('load', function() {

        $map = $("#card-map");

        if(map){
            marker = new google.maps.Marker({
                map: map,
                draggable: false,
                animation: google.maps.Animation.DROP,
                position: {lat: parseFloat($map.attr("data-lat")), lng: parseFloat($map.attr("data-lng"))}
            });

            map.setCenter(marker.getPosition());
        }

    });



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


    $(".card-masterhead").click(function(e){
        e.preventDefault();

        var images = [];
        $gallery.find("li").each(function(){
            images.push({
                thumb: $(this).attr("data-thumb"),
                src: $(this).attr("data-image"),
                subHtml: '<h4>Crédit Photo: <a href="'+$('.instagramLink').attr('href')+'"  style="color:#fff">@'+creditsLink+'</a></h4>'
            })
        });

        /*lightGallery(document.getElementById('card-gallery'), {
            dynamic: true,
            download: false,
            dynamicEl: images
        })*/

        $("#card-gallery").lightGallery({
            dynamic: true,
            download: false,
            dynamicEl: images
        });
    });

    $(document).on('contextmenu', 'img', function() {
        return false;
    });


});


window.googleMapsApiInit = function() {

    window.SnazzyInfoWindow = require('snazzy-info-window/dist/snazzy-info-window.min');

    var mapOptions = {
        center: new google.maps.LatLng(47.02427937142805, 1.6561921375000477),
        zoom: 14,
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
                        // "visibility": "simplified"
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
                        // "lightness": "-2"
                    },
                    {
                        // "gamma": "3.46"
                    },
                    {
                        // "hue": "#0083ff"
                    },
                    {
                        // "saturation": "-100"
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

    map = new google.maps.Map(document.getElementById("card-map"), mapOptions);

    $('#show-interview').on('click',function (e) {
        e.preventDefault();
        $(this).hide();
        $('#interview-content').fadeIn(1000);
    });
};