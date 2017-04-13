/**
 * Created by Admin on 10.04.2017.
 */
var markers = [];
var infoWindow = null;
var parser = new TemplateParser();

var TAG = 'gmh';

function initMap() {
    var mapElement = document.getElementById('map');
    if (mapElement != null) {
        var markers = [];
        var map = new google.maps.Map(document.getElementById('map'));
        infoWindow = new google.maps.InfoWindow();

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
            if (options.hasOwnProperty('info_window')) {
                parser.setTemplate(options.info_window);
            }
            if (options.hasOwnProperty('json_url')) {
                var icon = null;

                if (options.hasOwnProperty('icon')) {
                    icon = options.icon;
                }

                createMarkersFromJson(
                    map,
                    icon,
                    options.json_url,
                    options.json_latitude_field,
                    options.json_longitude_field,
                    options.json_fields
                );
            }
        }
    }
}

function createMarkersFromJson(map, markerIcon, jsonUrl, latField, longField, fields) {
    jQuery.ajax({
        url: jsonUrl,
        method: 'GET'
    }).done(function (data) {
        data.forEach(function (item) {
            var markerItem = {};
            if (item.hasOwnProperty(latField) && item.hasOwnProperty(longField)) {
                var markerPos = new google.maps.LatLng(item[latField], item[longField]);
                markerItem = new google.maps.Marker({
                    position: markerPos
                });
                if (markerIcon != null) {
                    markerItem.setIcon(markerIcon);
                }
                markerItem.setMap(map);
                if (fields.length > 0 &&
                    fields[0].length > 0 &&
                    fields[0].toLowerCase() !== 'all') {
                    fields.forEach(function (field) {
                        if (item.hasOwnProperty(field)) {
                            markerItem[TAG + '_' + field] = item[field];
                        }
                    });
                }
                else {
                    var propNames = Object.getOwnPropertyNames(item);
                    propNames.forEach(function (name) {
                        markerItem[TAG + '_' + name] = item[name];
                    });
                }
                google.maps.event.addListener(markerItem, 'mouseover', function () {
                    openInfoWindow(markerItem);
                });
                addMarker(markerItem);
            }
        });
        createMarkerClusters(map);
        //addInfoWindow();
    });
}

function openInfoWindow(marker) {
    var content = parser.parse(marker);
    if (content) {
        infoWindow.setContent(content);
        infoWindow.open(map, marker);
    }
}

function addMarker(marker) {
    markers.push(marker);
}

function createMarkerClusters(map) {
    return new MarkerClusterer(map, markers,
        {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});
}

function addInfoWindow() {
    markers.forEach(function (marker) {
        marker.addListener('click', openInfoWindow(marker));
    });
}

google.maps.event.addDomListener(window, "load", initMap());