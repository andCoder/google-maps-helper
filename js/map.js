/**
 * Created by Admin on 10.04.2017.
 */

function initMap() {
    var mapElement = document.getElementById('map');
    if (mapElement != undefined) {
        var map = new google.maps.Map(document.getElementById('map'), {
            center: {lat: -34.397, lng: 150.644},
            zoom: 8
        });
        if (map != null) {

        }
    }
}
google.maps.event.addDomListener(window, "load", initMap());