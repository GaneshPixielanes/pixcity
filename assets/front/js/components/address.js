//---------------------------------------------
// Google Maps API - Autocomplete address fields
//---------------------------------------------

var autocomplete;
var marker;
var map;

function onAddressChanged(){
    var place = autocomplete.getPlace();
    fillInAddress(place);
}

function fillInAddress(place) {

    $(".gm-address").val("");
    var addressStreetNumber = "";

    for (var i = 0; i < place.address_components.length; i++) {
        switch (place.address_components[i].types[0]) {
            case "street_number":
                addressStreetNumber = place.address_components[i].long_name;
                break;
            case "route":
                $(".gm-address").val(addressStreetNumber + " " + place.address_components[i].long_name);
                break;
            case "locality":
                $(".gm-city").val(place.address_components[i].long_name);
                break;
            case "postal_code":
                $(".gm-zipcode").val(place.address_components[i].long_name);
                break;
            case "country":
                $(".gm-country").val(place.address_components[i].short_name);
                break;
            default:
        }
    }

    $(".gm-latitude").val(place.geometry.location.lat());
    $(".gm-longitude").val(place.geometry.location.lng());

    if(marker) marker.setPosition(place.geometry.location);
    if(map) map.setCenter(place.geometry.location);
}

window.googleMapsApiInit = function () {

    // Init auto-completer
    autocomplete = new google.maps.places.Autocomplete((document.getElementsByClassName('gm-address')[0]), {types: ['geocode']});
    autocomplete.addListener('place_changed', onAddressChanged);

    // Init Google Maps

    if(document.getElementById('gm-address-map')) {

        // Init Geocoder
        var geocoder= new google.maps.Geocoder();

        var lat = document.getElementsByClassName('gm-latitude')[0].value;
        var lng = document.getElementsByClassName('gm-longitude')[0].value;
        var zoom = 13;

        if(!lat || !lng){
            // If unset, place a default marker
            lat = 48.85661400000001;
            lng = 2.3522219000000177;
            zoom = 6;
        }

        map = new google.maps.Map(document.getElementById('gm-address-map'), {
            center: {lat: parseFloat(lat), lng: parseFloat(lng)},
            zoom: zoom
        });

        marker = new google.maps.Marker({
            position: {lat: parseFloat(lat), lng: parseFloat(lng)},
            map: map,
            draggable: true,
        });

        google.maps.event.addListener(marker, 'dragend', function () {
            geocoder.geocode({
                latLng: marker.getPosition()
            }, function (responses) {
                if (responses && responses.length > 0) {
                    fillInAddress(responses[0]);
                } else {

                }
            });
        });
    }

};
