function getCoords(adAddress,adLocation){
    var coord;

    var geocoder = new google.maps.Geocoder();
    geocoder.geocode( {
        'address': adAddress + ', ' + adLocation + ', ' + 'Argentina'
    }, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            // obtengo la direccion  results[0].formatted_address,
            // la muestro
            coord = results[0].geometry.location;
        } else {
            alert("Geocode was not successful for the following reason: " + status);
        }

    });

    return coord;
}

function getAddress(latLong){
var address;
    var geocoder = new google.maps.Geocoder();
    geocoder.geocode( {
        'latLng': latLong
    }, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            // obtengo la direccion  results[0].formatted_address,
            // la muestro
            address = results[0].address_components[0].type[0]
        } else {
            alert("Geocode was not successful for the following reason: " + status);
        }

    });

    return address;
}

function initialize() {
    var adLatitude = $('ad_latitude');
    var adLongitude = $('ad_longitud');
    
    var myLatlng;

    if (adLatitude == "" || adLongitude == ""){
        myLatlng = getCoords($('contact_address'),$('ad_location'));
    }
    else {
        myLatlng = new google.maps.LatLng(adLatitude,adLongitude);
    }
    
    var myOptions = {
        zoom: 2,
        center: myLatlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    }

    map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);


    google.maps.event.addListener(map, 'click', function(event) {
        // instancio el geocoder
        var geocoder = new google.maps.Geocoder();
        //solicito la direccion de las coordenadas event.latLng
        var latlng = event.latLng;
        geocoder.geocode( {
            'latLng': latlng
        }, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                // obtengo la direccion
                // la muestro
                var infowindow = new google.maps.InfoWindow({
                    content: results[0].formatted_address,
                    position: latlng
                });
                infowindow.open(map);
            } else {
                alert("Geocode was not successful for the following reason: " + status);
            }

        });
    });
}
