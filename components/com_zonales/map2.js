//Parámetros globales
var maxZoomOut = 4;
var maxZoomIn = 20;

//BOUNDS - Definidos para Argentina
var minLon = -9289807.96207;
var minLat = -8036517.191029;
var maxLon = -5053362.107152;
var maxLat = -2019394.325498;

//CENTER - Definidos para Argentina
var centerLon = -64.423444488507;
var centerLat = -41.105773412891;

/*window.addEvent('domready', function() {
    init();
});*/

var map;
var iniciado = false;
var formats;
var geoLayer;
var hoverSelectFeature;
var clickSelectFeature;

function initMapTab () {
    if (!iniciado) {
        initMap();
    }
    iniciado = true;
}

function updateFormats() {
    var in_options = {
        'internalProjection': map.baseLayer.projection,
        'externalProjection': new OpenLayers.Projection('EPSG:4326')
    };
    var out_options = {
        'internalProjection': map.baseLayer.projection,
        'externalProjection': new OpenLayers.Projection('EPSG:4326')
    };
    formats = {
      'in': {
        geojson: new OpenLayers.Format.GeoJSON(in_options)
      },
      'out': {
        geojson: new OpenLayers.Format.GeoJSON(out_options)
      }
    };
}

function clearMap() {
    geoLayer.destroyFeatures();
}

function clearBreadcrumb() {
    if ($('breadCrumb') != null)
        $('breadCrumb').empty();
}

function deserialize(geoJson, vector, dataObj) {
    var type = "geojson";
    var features = formats['in'][type].read(geoJson);
    if(features) {
        if(features.constructor != Array) {
            features = [features];
        }
        features.forEach(function(feature) {
            feature.data = dataObj;
        });
        vector.addFeatures(features);
    } else {
        alert('Bad input ' + type);
    }
}

function breadCrumbToExtendedString(zone) {
    var isZone = false;
    var extendedString = "";

    $('breadCrumb').getChildren().each (function (element) {
        if (element.innerHTML == zone) {
            isZone = true;
        }

        if (isZone) {
            if (element.innerHTML == ',')
                extendedString += element.innerHTML + ' ';
            else
                extendedString += element.innerHTML;
        }
    });
    return extendedString;
}

function setBreadCrumb(extendedString) {
    var element;
    clearBreadcrumb();
    var zones = extendedString.split(',');
    zones.each(function (zone) {
        element = new Element('a', {
            'html': zone.trim().replace(/_/g, " ").capitalize(),
            'onclick': 'setZone(breadCrumbToExtendedString(this.innerHTML));drawMap(breadCrumbToExtendedString(this.innerHTML));'
        }).addClass('zonalesBreadcrumb').inject($('breadCrumb'));
        element = new Element('spam', {'html': ','}).addClass('zonalesBreadcrumb').inject($('breadCrumb'));
    });
    element.dispose();
}

function ajustMapToGeo(geoData) {
    var type = "geojson";
    var features = formats['in'][type].read(geoData);
    var bounds;
    if(features) {
        if(features.constructor != Array) {
            features = [features];
        }
        for(var i=0; i<features.length; ++i) {
            if (!bounds) {
                bounds = features[i].geometry.getBounds();
            } else {
                bounds.extend(features[i].geometry.getBounds());
            }
        }
        map.zoomToExtent(bounds);
    }
}

function drawMap(extendedString) {
    clearMap();
    setBreadCrumb(extendedString);
    extendedString = extendedString.replace(/, /g,',+').replace(/ /g,'_').replace(/,\+/g,', ').toLowerCase();

    socket.emit("getPlaceByFilters", {extendedString:extendedString}, function(places) {

        if (typeof(places) != 'undefined' && places != null && typeof(places[0]) != 'undefined' && places[0] != null) {

            if (typeof(places[0].geoData) != 'undefined' && places[0].geoData != null && places[0].geoData != "") {
                socket.emit("getGeoData", {id: places[0].geoData}, function(geoData) {
                    if (typeof(geoData) != 'undefined' && geoData != null && typeof(geoData[0]) != 'undefined' && geoData[0] != null) {
                        ajustMapToGeo(geoData[0]);
                    }
                });
            }

            socket.emit("getPlaceByFilters", {parent:places[0].id}, function(childs) {
                childs.each(function(child) {
                    socket.emit("getGeoData", {id: child.geoData}, function(geoData) {
                        if (typeof(geoData) != 'undefined' && geoData != null && typeof(geoData[0]) != 'undefined' && geoData[0] != null) {
                            deserialize(geoData[0], geoLayer, child);
                        }
                    });
                });
            });

        } else {

            socket.emit("getZoneByFilters", {extendedString:extendedString}, function(zones) {
                if (typeof(zones) != 'undefined' && zones != null && typeof(zones[0]) != 'undefined' && zones[0] != null) {
                    
                    if (typeof(zones[0].geoData) != 'undefined' && zones[0].geoData != null && zones[0].geoData != "") {
                        socket.emit("getGeoData", {id: zones[0].geoData}, function(geoData) {
                            if (typeof(geoData) != 'undefined' && geoData != null && typeof(geoData[0]) != 'undefined' && geoData[0] != null) {
                                ajustMapToGeo(geoData[0]);
                            }
                        });
                    }

                    socket.emit("getZoneByFilters", {parent:zones[0].id}, function(childs) {
                        childs.each(function(child) {
                            socket.emit("getGeoData", {id: child.geoData}, function(geoData) {
                                if (typeof(geoData) != 'undefined' && geoData != null && typeof(geoData[0]) != 'undefined' && geoData[0] != null) {
                                    deserialize(geoData[0], geoLayer, child);
                                }
                            });
                        });
                    });

                    socket.emit("getPlaceByFilters", {zone:zones[0].id}, function(childs) {
                        childs.each(function(child) {
                            socket.emit("getGeoData", {id: child.geoData}, function(geoData) {
                                if (typeof(geoData) != 'undefined' && geoData != null && typeof(geoData[0]) != 'undefined' && geoData[0] != null) {
                                    deserialize(geoData[0], geoLayer, child);
                                }
                            });
                        });
                    });

                }
            });
        }
    });
    
}

function on_unselect_feature(event){

}

function on_select_feature(event){
    var selectedFeature = event.feature;
    if (typeof(selectedFeature.data.extendedString) != 'undefined' && selectedFeature.data.extendedString != null && selectedFeature.data.extendedString != '') {
        setZone(selectedFeature.data.extendedString.replace(/_/g, " ").capitalize());
    }
    drawMap(selectedFeature.data.extendedString.replace(/_/g, " ").capitalize());
}


function on_move(event) {
    if (map.getZoom() < maxZoomOut) map.zoomIn();
    if (map.getZoom() > maxZoomIn) map.zoomOut();
}

function initMap() {

    //Create a map with an empty array of controls
    map = new OpenLayers.Map('map_element', {
        maxExtent : new OpenLayers.Bounds(minLon, minLat, maxLon, maxLat),
        restrictedExtent : new OpenLayers.Bounds(minLon, minLat, maxLon, maxLat),
        units : 'm',
        projection : new OpenLayers.Projection('EPSG:4326'),
        displayProjection : new OpenLayers.Projection("EPSG:4326")
    });

    map.addControl(new OpenLayers.Control.LayerSwitcher());

    //Create a base layers
    var gphy = new OpenLayers.Layer.Google(
        "Google Physical",
        {type: google.maps.MapTypeId.TERRAIN}
    );
    var gmap = new OpenLayers.Layer.Google(
        "Google Streets", // the default
        {numZoomLevels: 20}
    );
    var ghyb = new OpenLayers.Layer.Google(
        "Google Hybrid",
        {type: google.maps.MapTypeId.HYBRID, numZoomLevels: 20}
    );
    var gsat = new OpenLayers.Layer.Google(
        "Google Satellite",
        {type: google.maps.MapTypeId.SATELLITE, numZoomLevels: 22}
    );

    //Create a base layer
    /*var google_map = new OpenLayers.Layer.Google(
        'Google Layer',
        {}
        );*/
    map.addLayer(gmap);
    map.addLayer(gsat);
    map.addLayer(ghyb);
    map.addLayer(gphy);
    
    map.setCenter(new OpenLayers.LonLat(centerLon, centerLat)
        .transform(new OpenLayers.Projection("EPSG:4326"), new OpenLayers.Projection("EPSG:900913")), maxZoomOut);

    geoLayer = new OpenLayers.Layer.Vector("Zones 'n Places");

    map.addLayer(geoLayer);

    clickSelectFeature = new OpenLayers.Control.SelectFeature(geoLayer, {});
    map.addControl(clickSelectFeature);
    clickSelectFeature.activate();

    //Let's style the features
    //Create a style object to be used by a StyleMap object
    var vector_style = new OpenLayers.Style({
     'fillColor': '#669933',
     'fillOpacity': .8,
     'strokeColor': '#aaee77',
     //'strokeColor': '#669933',
     'strokeWidth': 3
     });

    var vector_style_select = new OpenLayers.Style({
     'fillColor': '#cdcdcd',
     'fillOpacity': .9,
     'strokeColor': '#ffffff'
    })

    //Create a style map object and set the 'default' intent to the
    var vector_style_map = new OpenLayers.StyleMap({
     'default': vector_style,
     'select': vector_style_select
    });

    //Add the style map to the vector layer
    geoLayer.styleMap = vector_style_map;

    geoLayer.events.register('featureselected', this, on_select_feature);
    geoLayer.events.register('featureunselected', this, on_unselect_feature);
    map.events.register('moveend', this, on_move);

    updateFormats();
    if (zcGetZone() == '')
        drawMap('Argentina');
    else
        drawMap(zcGetZone());

}
