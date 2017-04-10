/**
 * Created by Admin on 10.04.2017.
 */

function initMap() {
    var mapElement = document.getElementById('map');
    if (mapElement != null) {
        var mapOptions = {
            center: {lat: -34.397, lng: 150.644},
            zoom: 8
        };
        if (typeof options !== 'undefined' && options !== null) {
            if (options.hasOwnProperty('map_type')) {
                mapOptions.mapTypeId = options.map_type;
            }
        }
        var map = new google.maps.Map(document.getElementById('map'), mapOptions);
    }
}
google.maps.event.addDomListener(window, "load", initMap());