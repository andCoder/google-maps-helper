/**
 * Created by Admin on 10.04.2017.
 */
(function () {
    var markers = [];
    var infoWindow = null;
    var parser = new TemplateParser();
    var showPreview = false;
    var TAG = 'gmh';
    var map = null;

    function initMap() {
        var mapElement = document.getElementById('map');
        if (mapElement != null) {
            map = new google.maps.Map(document.getElementById('map'));
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

    function createMarkersFromJson(markerIcon, options) {
        var requestOptions = {
            url: options.jsonUrl,
            method: 'GET'
        };
        if (options.hasOwnProperty('requestHeader')) {
            requestOptions.headers = options.requestHeader;
        }
        jQuery.ajax(requestOptions).done(function (data) {
            parseItem(data, markerIcon, options);
            createMarkerClusters();
        });
    }

    function parseItem(item, markerIcon, options, marker) {
        if (Array.isArray(item)) {
            item.forEach(function (inner) {
                if (!marker) {
                    marker = new google.maps.Marker({position: new google.maps.LatLng(0, 0)});
                    marker.setIcon(markerIcon);
                }
                marker = parseItem(inner, null, options, marker);
                addMarker(marker, options.apiKey);
                marker = null;
            });
            return marker;
        }
        else {
            return parseObject(item, options, marker);
        }
    }

    function parseObject(obj, options, marker) {
        var propNames = Object.getOwnPropertyNames(options);
        propNames.forEach(function (key) {
            if (obj.hasOwnProperty(options[key])) {
                if (key == 'latField') {
                    var position = marker.getPosition();
                    var newPosition = new google.maps.LatLng(obj[options[key]], position.lng());
                    marker.setPosition(newPosition);
                }
                else if (key == 'longField') {
                    var position = marker.getPosition();
                    var newPosition = new google.maps.LatLng(position.lat(), obj[options[key]]);
                    marker.setPosition(newPosition);
                }
            }
            else if (key == 'fields') {
                if (options['fields'].length > 0 && options['fields'][0] != '') {
                    for (var i = 0; i < options['fields'].length; i++) {
                        if (obj.hasOwnProperty(options['fields'][i])) {
                            marker['gmh_' + options['fields'][i]] = obj[options['fields'][i]];
                        }
                    }
                }
                else {
                    var objProps = Object.getOwnPropertyNames(obj);
                    objProps.forEach(function (prop) {
                        marker['gmh_' + prop] = obj[prop];
                    });
                }
            }
        });
        return marker;
    }

    function addMarker(marker, apiKey) {
        marker.setMap(map);
        google.maps.event.addListener(marker, 'click', function () {
            openInfoWindow(marker, apiKey);
        });
        markers.push(marker);
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

    function createMarkerClusters() {
        return new MarkerClusterer(map, markers,
            {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});
    }

    google.maps.event.addDomListener(window, "load", initMap());
})();