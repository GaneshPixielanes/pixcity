window.jQuery = window.$ = require('jquery');
require('../vendors/slick.min');

$(document).ready(function() {
    $('#map-info-bar').hide();
    window.SnazzyInfoWindow = require('snazzy-info-window/dist/snazzy-info-window.min');
    var map;
    var currentPositionMarker;
    var markers = [];
    var user = window.location.pathname.split("/").slice(-1)[0];
    var markerLen = 0;
    var loadCurrentLoc = $('#api-box').attr("data-current-location");
    var bounds = new google.maps.LatLngBounds();
    var sidebar = $('#api-box').attr('data-show-sidebar');
    var category_count = Array();
    var categoryList = Array();
    var regionList = $('.region-selected').val();
    var page = 1;
    var text = $('[name="search"]').val();
    var regions = "";
    var cityMaker = $("#api-box").attr('data-city-maker-id');
    var cardLatitude = $("#api-box").attr('data-latitude');
    var cardLongitude = $("#api-box").attr('data-longitude');
    var clusterMarker = '';

    // Display map
    function myMap() {

        var mapProp = {
            center: new google.maps.LatLng(48.8588377, 2.2770196),
            // mapTypeId: google.maps.MapTypeId.ROADMAP,
            // backgroundColor: '#FFF',
            zoom: 15,
            pointer: false,
            fullscreenControl: true,
            fullscreenControlOptions: {
                position: google.maps.ControlPosition.RIGHT_CENTER
            },
            mapTypeControlOptions: false,
            mapTypeControl: false,
            scaleControl: false,
            zoomControl: true,
            zoomControlOptions: {
                style: google.maps.ZoomControlStyle.LARGE
            },
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            styles:
                [
                    {
                        "featureType": "administrative",
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "visibility": "off"
                            }
                        ]
                    },
                    {
                        "featureType": "poi",
                        "stylers": [
                            {
                                "visibility": "off"
                            }
                        ]
                    },
                    {
                        "featureType": "road",
                        "elementType": "labels.icon",
                        "stylers": [
                            {
                                "visibility": "off"
                            }
                        ]
                    },
                    {
                        "featureType": "transit",
                        "stylers": [
                            {
                                "visibility": "off"
                            }
                        ]
                    }
                ]
        };
        map = new google.maps.Map(document.getElementById("map"), mapProp);
        // console.log(JSON.parse(coordinates));
        if (coordinates.length > 1) {
            var flightPlanCoordinates = [coordinates];

            var flightPath = new google.maps.Polygon({
                paths: flightPlanCoordinates,
                strokeColor: '#FF0000',
                strokeOpacity: 0.8,
                strokeWeight: 3,
                // fillColor: '#FF0000',
                fillOpacity: 0.35
            });
            flightPath.setMap(map);

            var flightPath = new google.maps.Polyline({
                path: flightPlanCoordinates,
                geodesic: true,
                strokeColor: '#FF0000',
                strokeOpacity: 1.0,
                strokeWeight: 2
            });

        }

        // Create the search box and link it to the UI element.
        var sidebar = document.getElementById('map-sidebar');


        map.controls[google.maps.ControlPosition.TOP_RIGHT].push(document.getElementById('pac-input'));
        if ("false" !== sidebar) {
            map.controls[google.maps.ControlPosition.LEFT].push(sidebar);
        }

        google.maps.event.addListener(map, 'bounds_changed', onBoundsChanged);

        // Update markers on the map
        getMarkers()

    }

    function onBoundsChanged() {
        if ($(map.getDiv()).children().eq(0).height() == window.innerHeight &&
            $(map.getDiv()).children().eq(0).width() == window.innerWidth) {
            $('.dropdown-search').css('height', '100vh');
        } else {
            // Do nothing
        }
    }

    //Display markers on the map
    function createMarkers(positions) {

        // $('#map > img').css('opacity','0.5');

        // $.each(markers, function(key, marker) {
        //     var isUnque = true;
        //     $.each(positions, function(pos, new_marker) {
        //         if ('(' + new_marker.latitude + ', ' + new_marker.longitude + ')' == marker.getPosition() && isUnque == true) {
        //             isUnque = false;
        //         }
        //     });
        //     if (isUnque == true) {
        //         marker.setOptions({
        //             'opacity': 0.1
        //         });
        //     } else {
        //         isUnque = true;
        //         marker.setMap(null);
        //     }
        // });

        setMapOnAll(null);
        markers = [];
        $.each(positions, function(key, position) {

            if(position.latitude == cardLatitude && position.longitude == cardLongitude)
            {
                icon = '/../../img/MAP/active/' + position.icon + '.svg';
            }
            else
            {
                icon = '/../../img/MAP/' + position.icon + '.svg';
            }

            marker = new google.maps.Marker({
                position: {
                    lat: parseFloat(position.latitude),
                    lng: parseFloat(position.longitude)
                },
                icon: icon,
                map: map
            });
            bounds.extend(new google.maps.LatLng(parseFloat(position.latitude), (position.longitude)));
            markers[key] = marker;
            google.maps.event.addListener(marker, 'mouseover', function() {
                $(".gm-style   img").addClass("icon-click")
            });
            google.maps.event.addListener(marker, 'click', function() {
                $('#map-sidebar').removeClass('d-md-block');
                // $.each(markers, function(pos, new_marker)
                // {
                //  if('('+new_marker.latitude+', '+new_marker.longitude+')' != marker.getPosition())
                //  {
                //      markers[pos].setOptions({'opacity': 0.1});
                //  }
                // });
                $('body').remove('.si-float-wrapper');
                $('.si-float-wrapper').html('');
                if ('undefined' !== typeof snWindow) {
                    snWindow.destroy();
                }

                $.post('/load-card/' + position.id, function(result) {
                    // var activeIcon = markers[key].icon.split('/');
                    // if('active' != activeIcon[5])
                    // {
                    //     markers[key].setIcon('/../../img/MAP/active/' + activeIcon[5]);
                    // }
                    var snWindow = new SnazzyInfoWindow({
                        marker: markers[key],
                        // content: 'Hi',
                        // wrapperClass: 'card-info-window',
                        content: result,
                        closeWhenOthersOpen: true,
                        showCloseButton: false,
                        placement: 'top',
                        pointer: false,
                        offset: {
                            top: '10px',
                            left: '22px'
                        },
                        callbacks: {
                            beforeOpen: function() {

                            },
                            afterOpen: function() {
                                try {
                                    $('.popup-slider').slick('unslick');

                                } catch (e) {
                                    // DO NOTHING
                                }
                                $('.popup-slider').slick({
                                    arrows: false,
                                    dots: true,
                                    centerMode: false,
                                    centerPadding: '40px',
                                    slidesToShow: 1,
                                    variableWidth: true,
                                    adaptiveHeight: false
                                });
                                $('.card').tooltip({
                                    selector: '[data-toggle="tooltip"]'
                                });

                                $(".si-content-wrapper .card .cta-like-card").click(function() {
                                    if ($('body').hasClass('logged-out')) {
                                        $("input[name='addLikeCard']").val($(this).attr("data-id"));
                                        $("#cardLikeLogoutModal").modal("show");
                                    } else {
                                        $.ajax({
                                            url: "/api/users/like/card",
                                            method: "POST",
                                            data: {
                                                card: $(this).attr("data-id")
                                            },
                                            success: function(e) {
                                                console.log('card liked');
                                            }
                                        }).done(function(res) {
                                            console.log('Like complete');
                                        });
                                    }
                                });

                                $(".si-content-wrapper .card .cta-favorite-card").click(function() {
                                    if ($('body').hasClass('logged-out')) {
                                        $("input[name='addFavoriteCard']").val($(this).attr("data-id"));
                                        $("#cardFavoriteLogoutModal").modal("show");
                                    } else {
                                        $.ajax({
                                            url: "/api/users/favorite/card",
                                            method: "POST",
                                            data: {
                                                card: $(this).attr("data-id")
                                            },
                                            success: function(e) {
                                                msg = JSON.parse(e);
                                                console.log(msg.msg);
                                                // $(this).html(e.msg);
                                                $(this).html(msg.msg);
                                                snWindow.close();
                                            }
                                        }).done(function(res) {
                                            console.log('favorite complete ');
                                        });
                                    }
                                });
                                $(".si-content-wrapper .popup-slider-image").css("cursor", "pointer");
                                $(".si-content-wrapper .popup-slider-image").click(function() {

                                    loadCardDetails(position.id);
                                });

                            }
                        }
                    });
                    snWindow.open();

                    // console.log('test');
                });
                // console.log('This marker has been clicked '+key);
            });

            google.maps.event.addListener(marker, 'onmouseout', function() {
                $('body').remove('.si-float-wrapper');
                $('.si-float-wrapper').html('');
                if ('undefined' !== typeof snWindow) {
                    snWindow.close();
                }
            });
        });

        if (positions.length > 0) {
            if("false" != $('#api-box').attr('data-auto-zoom'))
            {
                map.fitBounds(bounds);
            }
            if(positions.length == 1)
            {
                map.setZoom(15);
            }
        }
        //marker clusterer added
        cluster(map, markers);

        setTimeout(function() {
            if ($('#pac-input').hasClass('d-none')) {
                $('#pac-input').removeClass('d-none');
            }

        }, 1000);
    }
    // Add a marker clusterer to manage the markers.
    function cluster(map, markers) {
        if(clusterMarker != '')
        {
            clusterMarker.clearMarkers();
        }
        setMapOnAll(null);

        var mcOptions = {
            gridSize: 50,
            minimumClusterSize: 5,
            imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'
        };

        clusterMarker = new MarkerClusterer(map, markers, mcOptions);
        return clusterMarker;
    }
    //Get the locations for the corresponding profile
    function getMarkers()
    {
        $.ajax({
            method: 'POST',
            // url: '/api/maps/profile/'+user,
            url: $('#api-box').attr('data-api-url'),
            data: {
                regions: $('.region-selected').val(),
                categories: categoryList,
                text: $('[name="search"]').val(),
                cityMaker: cityMaker
            },
            success: function(results) {
                try {
                    results = JSON.parse(results);
                } catch (e) {
                    results = results;
                }
                createMarkers(results);
            }
        });
    }
    //getMarkers();

    function calculateCategoryCount(positions) {
        category_count = [];
        var tout = 0;
        $.each(positions, function(key, position) {

            if ("undefined" == typeof category_count[position.icon]) {
                category_count[position.icon] = 0;
            } else {
                // tout = tout+1;
                category_count[position.icon] = category_count[position.icon] + 1;
                $("#count-" + position.icon).html(category_count[position.icon] + 1);
                // $("#total-marker-count").html(tout);
            }
        });
    }

    $('#pac-input').on('keydown', function(e) {
        // e.preventDefault();
        $('#map > img').css('opacity', '0.2');
        // setMapOnAll(null);
    });
    // Perform filter of categories inside the map
    $('.pc_category_filter').click(function(e) {
        e.preventDefault();
        // setMapOnAll(null);
        var selectedCategory = $.trim($(this).attr('data-id'));
        var searchText = $("#pac-input").find('input').val();
        if ($.trim(searchText) == '') {
            searchText = 'na';
        }
        $.ajax({
            type: 'GET',
            url: $('#api-box').attr('data-api-search-url') + searchText + '/' + selectedCategory,
            success: function(result) {
                try {
                    results = JSON.parse(result);
                } catch (e) {
                    results = result;
                }
                createMarkers(result);

                // $(".pc_category_filter > span").html('0');
                // calculateCategoryCount(result);
                $('.pc-category-filter-icon').html("<i class='" + $(this).find('i').attr('class') + "'></i>");
            }
        });
    });

    function setMapOnAll(map) {
        // for (var i = 0; i < markers.length; i++) {
        //     markers[i].setMap(map);
        // }
        $.each(markers, function(key, marker) {
            markers[key].setMap(map);
        });
    }

    $('#pac-input').on('keyup', function() {
        var category = $('.is-category-active').attr('data-id');
        if ("undefined" == typeof category) {
            category = "";
        }
        for (var i = 0; i < markers.length; i++) {
            markers[i].setMap(null);
        }

        markers = [];
        // if($.trim($(this).find('input').val()) !== '')
        // {
        $.get($('#api-box').attr('data-api-search-url') + $(this).find('input').val() + '/' + category,
            function(result) {
                try {
                    results = JSON.parse(result);
                } catch (e) {
                    results = result;
                }
                createMarkers(result);

                $(".pc_category_filter > span").html('0');
                calculateCategoryCount(result);
            });
        // }
    });

    function locError(error) {
        // the current position could not be located
        // Do Nothing
    }

    function setCurrentPosition(pos) {

        if (!$.cookie('pc_user_location_reminder_cookie')) {
            $.cookie('pc_user_location_reminder_cookie', 'yes', {
                expires: 1
            })
            $.cookie('pc_user_location_lat_cookie', pos.coords.latitude, {
                expires: 1
            });
            $.cookie('pc_user_location_lng_cookie', pos.coords.longitude, {
                expires: 1
            });
        }


        // var lat = $.cookie('pc_user_location_lat_cookie');
        // var lng = $.cookie('pc_user_location_lng_cookie');

        var lat = pos.coords.latitude;
        var lng = pos.coords.longitude;
        var latlng = new google.maps.LatLng(lat, lng);

        currentPositionMarker = new google.maps.Marker({
            map: map,
            icon: '/../../img/MAP/my_pos.png',
            position: new google.maps.LatLng(
                pos.coords.latitude,
                pos.coords.longitude
            ),
            title: "Current Position"
        });

        map.setCenter(new google.maps.LatLng(pos.coords.latitude, pos.coords.longitude));

    }

    function displayAndWatch(position) {
        // set current position
        setCurrentPosition(position);
        // watch position
        // console.log(position):
        // watchCurrentPosition();
    }

    function watchCurrentPosition() {
        var positionTimer = navigator.geolocation.watchPosition(
            function(position) {
                setMarkerPosition(
                    currentPositionMarker,
                    position
                );
            });
    }

    function setMarkerPosition(marker, position) {
        marker.setPosition(
            new google.maps.LatLng(
                position.coords.latitude,
                position.coords.longitude)
        );
    }

    function initLocationProcedure() {
        myMap();

        if (navigator.geolocation) {
            var loc = navigator.geolocation.getCurrentPosition(displayAndWatch, locError);

        } else {
            alert("Your browser does not support the Geolocation API");
        }
    }

    if (loadCurrentLoc == "true") {
        initLocationProcedure();
    } else {
        myMap();
    }

    $('body').on('click', '.close-sidebar', function() {
        $('#map-sidebar').removeClass('d-md-block');
    });

    // Filters
    $('body').on('click', '#filters > li', function()
    {

        page = 1;
        categoryList = [];
        regionList = $('.region-selected').val();
        console.log(regionList);
        if($(this).hasClass('is-checked'))
        {
            $(this).removeClass('is-checked');
        }
        else
        {
            if(!$(this).hasClass('all'))
            {
                $('.all').removeClass('is-checked');
                $(this).addClass('is-checked');
            }
            else
            {
                $('.is-checked').removeClass('is-checked');
                $(this).addClass('is-checked');

            }
        }



        $.each($('.is-checked'),function(key, val)
        {
            categoryList.push($(val).attr('data-id'));
        });

        if($('.is-checked').length == 0)
        {
            $('.all').addClass('is-checked');
        }

        $.ajax({
            url:'/load-cards',
            method: 'POST',
            data: {
                categories: JSON.stringify(categoryList),
                page: page,
                regions: JSON.stringify(regionList),
                text: text,
                cityMaker: cityMaker
            },
            success: function(result)
            {
                console.log($(result).find('.category-card').length);
                $(".cards-filter-result").html(result);
            }
        });

        getMarkers();
    });

    $('body').on('click','#showMoreCards', function()
    {
        $(this).remove();
        var isCityMaker = $('#api-box').attr('data-city-maker-card');
        if(isCityMaker !== "true")
        {
            isCityMaker = false;
        }
        else
        {
            isCitymaker = true;
        }

        $(".loadCards:last").html("<p class=\"text-center\"><img src=\"/../../img/loader.gif\"/ alt=\"Loading...\"></p>");
        page = page+1 ;
        $.ajax({
            url:'/load-cards',
            method: 'POST',
            data: {
                categories: JSON.stringify(categoryList),
                page: page,
                regions: JSON.stringify(regionList),
                text: text,
                cityMaker: cityMaker,
                cmFlag: isCityMaker
            },
            success: function(result)
            {
                if(isCityMaker == false)
                {
                    $(".loadCards:last").html(result);
                }
                else
                {
                    $(".loadCards").html(''); // Remove loading symbol
                    $(".top-card-block:last").after(result);
                }

            }
        });
    });
});

function loadCardDetails(id) {
    // $('#map-info-bar').hide();
    var sidebar = $('#api-box').attr('data-show-sidebar');
    $('#map-sidebar').removeClass('d-md-block');
    $('#pixie-cards-slider').slick("unslick");
    $.get('/api/maps/card-details/' + id, function(result) {
        $("#map-sidebar").html(result);
        if ("false" != sidebar) {
            $('#map-sidebar').addClass('d-md-block');
        }

    });

    var isSidebarDataLoaded = sliderInit();
    if (isSidebarDataLoaded === true) {
        // $('#map-info-bar').show(500);
        $('#map-info-bar').css("display", "block");
    }

}

function sliderInit() {
    setTimeout(function() {
        $('.sidebar-slider').slick({
            arrows: false,
            dots: true,
            centerMode: true,
            centerPadding: '40px',
            slidesToShow: 1,
            variableWidth: true,
            adaptiveHeight: false
        });

        $('.similar-places-slider').slick({
            arrows: false,
            dots: true,
            centerMode: true,
            centerPadding: '40px',
            slidesToShow: 1,
            variableWidth: true,
            adaptiveHeight: false
        });
    }, 1000);

    return true;
}