var geocoder, map, marker;
function initialize() {
    var myLatlng = new google.maps.LatLng(23.973875,120.982024);
    var mapOptions = {
      zoom: 8,
      center: myLatlng
    }
    map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
    marker = new google.maps.Marker({
        draggable:true,
        position: myLatlng,
    });
    var input = document.getElementById('pac-input');
    var autocomplete = new google.maps.places.Autocomplete(input);
    autocomplete.bindTo('bounds', map);
    var infowindow = new google.maps.InfoWindow();
    geocoder = new google.maps.Geocoder();

    // To add the marker to the map, call setMap();
    marker.setVisible(false);
    marker.setMap(map);

    google.maps.event.addListener(marker,'drag',function(event) {
        setInput(event.latLng.lat(),event.latLng.lng());
    });

    google.maps.event.addListener(marker,'dragend',function(event) {
        setInput(event.latLng.lat(),event.latLng.lng());
    });

    if($('#lat').val() && $('#lat').val() != "0" && $('#lng').val() && $('#lng').val() != "0"){
        myLatlng = new google.maps.LatLng($('#lat').val(),$('#lng').val());
        map.setCenter(myLatlng);
        map.setZoom(17);  // Why 17? Because it looks good.
        marker.setPosition(myLatlng);
        marker.setVisible(true);
    }

    // autocomplete
    google.maps.event.addListener(autocomplete, 'place_changed', function() {
        infowindow.close();
        marker.setVisible(false);
        var place = autocomplete.getPlace();
        if (!place.geometry) {
            return;
        }

        // If the place has a geometry, then present it on a map.
        if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
        } else {
            map.setCenter(place.geometry.location);
        }
        marker.setIcon(/** @type {google.maps.Icon} */({
            url: place.icon,
            size: new google.maps.Size(71, 71),
            origin: new google.maps.Point(0, 0),
            anchor: new google.maps.Point(17, 34),
            scaledSize: new google.maps.Size(35, 35)
        }));
        setInput(place.geometry.location.lat(),place.geometry.location.lng());
        map.setZoom(17);  // Why 17? Because it looks good.
        marker.setPosition(place.geometry.location);
        marker.setVisible(true);

        var address = '';
        if (place.address_components) {
            address = [
            (place.address_components[0] && place.address_components[0].short_name || ''),
            (place.address_components[1] && place.address_components[1].short_name || ''),
            (place.address_components[2] && place.address_components[2].short_name || '')
            ].join(' ');
        }

        infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
        infowindow.open(map, marker);
    });
}

$(function() {
    $('#refresh-map').click(function(){
        var address = $('#pac-input').val();
        if(!address) {
            marker.setVisible(false);
            setInput(0,0);
            return false;
        }

        geocoder.geocode( { 'address': address}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                map.setCenter(results[0].geometry.location);
                map.setZoom(17);  // Why 17? Because it looks good.
                marker.setPosition(results[0].geometry.location);
                marker.setVisible(true);
                setInput(results[0].geometry.location.lat(),results[0].geometry.location.lng());
            }
        });
    });
});

function setInput(lat,lan){
    $('#lat').val(lat);
    $('#lng').val(lan);
}

function loadScript() {
  var script = document.createElement("script");
  script.type = "text/javascript";
  script.src = "http://maps.googleapis.com/maps/api/js?sensor=true&libraries=places&callback=initialize";
  if(document.getElementById("map-canvas")){
    document.body.appendChild(script);
  }
}

window.onload = loadScript;