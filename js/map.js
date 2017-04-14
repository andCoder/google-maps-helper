/**
 * Created by Admin on 10.04.2017.
 */
var markers = [];
var infoWindow = null;
var parser = new TemplateParser();
var showPreview = false;
var TAG = 'gmh';

function initMap() {
    var mapElement = document.getElementById('map');
    if (mapElement != null) {
        var markers = [];
        var map = new google.maps.Map(document.getElementById('map'));
        infoWindow = new google.maps.InfoWindow();

        if (typeof options !== 'undefined' && options !== null) {
            map.setMapTypeId(options.map_type);
            try {
                map.setZoom(parseFloat(options.zoom));
            }
            catch (e) {
                console.log(e);
            }

            var center = new google.maps.LatLng(options.map_center[0], options.map_center[1]);
            map.setCenter(center);

            showPreview = options.display_streetmap;
            parser.setTemplate(options.info_window);

            var markerOptions = {
                apiKey: options.api_key,
                jsonUrl: options.json_url,
                latField: options.json_latitude_field,
                longField: options.json_longitude_field,
                fields: options.json_fields
            };

            createMarkersFromJson(
                map,
                options.icon,
                markerOptions
            );


            var interval = 0;
            try {
                interval = parseInt(options.refresh_interval);
                if (interval > 0) {
                    window.setTimeout(function () {
                        google.maps.event.trigger(map, 'resize');
                    }, interval * 1000)
                }
            }
            catch (e) {
                console.log(e);
            }

        }
    }
}

function createMarkersFromJson(map, markerIcon, options) {
    var requestOptions = {
        url: options.jsonUrl,
        method: 'GET'
    };
    if (options.hasOwnProperty('requestHeader')) {
        requestOptions.headers = options.requestHeader;
    }
    jQuery.ajax(requestOptions).done(function (data) {
        data.forEach(function (item) {
            var markerItem = {};

            var markerPos = new google.maps.LatLng(item[options.latField], item[options.longField]);
            markerItem = new google.maps.Marker({
                position: markerPos
            });
            if (markerIcon != null) {
                markerItem.setIcon(markerIcon);
            }
            markerItem.setMap(map);
            if (options.fields.length > 0 &&
                options.fields[0].length > 0 &&
                options.fields[0].toLowerCase() !== 'all') {
                options.fields.forEach(function (field) {
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
            google.maps.event.addListener(markerItem, 'click', function () {
                openInfoWindow(markerItem, options.apiKey);
            });
            addMarker(markerItem);
        });
        createMarkerClusters(map);
        //addInfoWindow();
    });
}

function openInfoWindow(marker, apiKey) {
    var content = parser.parse(marker);
    if (content) {
        infoWindow.setContent(content);
        infoWindow.open(map, marker);

        if (showPreview && apiKey) {
            var preview = document.getElementById('gmh-point-preview');
            preview.setAttribute('src', 'https://maps.googleapis.com/maps/api/streetview?size=100x50&location=' + marker.getPosition().lat() +
                ',' + marker.getPosition().lng() + '&key=' + apiKey);
            preview.setAttribute('style', 'width:auto;');
        }
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