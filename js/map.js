/**
 * Created by Admin on 10.04.2017.
 */
var markers = [];

function initMap() {
    var mapElement = document.getElementById('map');
    if (mapElement != null) {
        var markers = [];
        var map = new google.maps.Map(document.getElementById('map'));

        if (typeof options !== 'undefined' && options !== null) {
            if (options.hasOwnProperty('map_type')) {
                map.setMapTypeId(options.map_type);
            }
            if (options.hasOwnProperty('zoom')) {
                try {
                    map.setZoom(parseFloat(options.zoom));
                }
                catch (e) {
                    console.log(e);
                }
            }
            if (options.hasOwnProperty('map_center')) {
                var center = new google.maps.LatLng(options.map_center[0], options.map_center[1]);
                map.setCenter(center);
            }
            if (options.hasOwnProperty('json_url')) {
                createMarkersFromJson(
                    map,
                    options.json_url,
                    options.json_latitude_field,
                    options.json_longitude_field,
                    options.json_fields
                );
            }
        }
    }
}

function createMarkersFromJson(map, jsonUrl, latField, longField, fields) {
    jQuery.ajax({
        url: jsonUrl,
        method: 'GET'
    }).done(function (data) {
        data.forEach(function (item) {
            var markerItem = {};
            if (item.hasOwnProperty(latField) && item.hasOwnProperty(longField)) {
                var markerPos = new google.maps.LatLng(item[latField], item[longField]);
                markerItem.marker = new google.maps.Marker({
                    position: markerPos
                });
                markerItem.marker.setMap(map);
                if (fields.length > 0 &&
                    fields[0].length > 0 &&
                    fields[0].toLowerCase() !== 'all') {
                    fields.forEach(function (field) {
                        if (item.hasOwnProperty(field)) {
                            markerItem[field] = item[field];
                        }
                    });
                }
                else {
                    var propNames = Object.getOwnPropertyNames(item);
                    propNames.forEach(function (name) {
                        markerItem[name] = item[name];
                    });
                }
                addMarker(markerItem);
            }
        });
        createMarkerClusters(map)
    });
}

function addMarker(marker) {
    markers.push(marker);
}

function createMarkerClusters(map) {
    var markerObjectArr = [];
    markers.forEach(function (markerItem) {
        markerObjectArr.push(markerItem.marker);
    });
    var markerCluster = new MarkerClusterer(map, markerObjectArr,
        {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});
}

google.maps.event.addDomListener(window, "load", initMap());