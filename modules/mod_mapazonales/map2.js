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
var selectedFeature;
var popup;

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

function deserialize(geoJson, vector, dataObj, draw) {
    var type = "geojson";
    var features = formats['in'][type].read(geoJson);
    if(features) {
        if(features.constructor != Array) {
            features = [features];
        }
        features.forEach(function(feature) {
            feature.data = dataObj;
        });
        if (draw)
            vector.addFeatures(features);
        return features;
    } else {
        //alert('Bad input ' + type);
        return null;
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
            'onclick': 'zTab.setZone(breadCrumbToExtendedString(this.innerHTML));drawMap(breadCrumbToExtendedString(this.innerHTML));ajustMapToExtendedString(breadCrumbToExtendedString(this.innerHTML));'
        }).addClass('zonalesBreadcrumb').inject($('breadCrumb'));
        element = new Element('spam', {'html': ','}).addClass('zonalesBreadcrumb').inject($('breadCrumb'));
    });
    element.dispose();
}

function ajustMapToGeo(feature) {
    if(feature) {
        var bounds = feature.geometry.getBounds();
        map.zoomToExtent(bounds);
    }
}

function ajustMapToExtendedString(extendedString) {
    extendedString = extendedString.replace(/, /g, ',+').replace(/ /g, '_').replace(/,\+/g, ', ').toLowerCase();
    zTab.socket.emit('getGeoDataByZoneExtendedString', {extendedString: extendedString}, function(data) {
        if (data) {
            if (data.extendedString)
                var eString = data.extendedString.replace(/_/g, ' ').capitalize();
            if (data.geoData) {
                var feature = deserialize(data.geoData, geoLayer, null, false)[0];
                ajustMapToGeo(feature);
            }
        }
    });
}

function drawMap(extendedString) {
    clearMap();
    setBreadCrumb(extendedString);
    extendedString = extendedString.replace(/, /g,',+').replace(/ /g,'_').replace(/,\+/g,', ').toLowerCase();

    zTab.socket.emit('drawChildren', {extendedString:extendedString}, function(resp) {

    });

}

function on_unselect_feature(event){

}

function on_select_feature(event){
    selectedFeature = event.feature;
    var extendedString = selectedFeature.data.extendedString.replace(/_/g, " ").capitalize();
    ajustMapToGeo(selectedFeature);
    if (typeof(selectedFeature.data.extendedString) != 'undefined' && selectedFeature.data.extendedString != null && selectedFeature.data.extendedString != '') {
        zTab.setZone(selectedFeature.data.extendedString.replace(/_/g, " ").capitalize());
    }
    drawMap(extendedString);
    //drawPopup(event);
}

function on_select_zone() {
    map.removePopup(popup);
    if (typeof(selectedFeature.data.extendedString) != 'undefined' && selectedFeature.data.extendedString != null && selectedFeature.data.extendedString != '') {
        zTab.setZone(selectedFeature.data.extendedString.replace(/_/g, " ").capitalize());
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
        "Físico",
        {type: google.maps.MapTypeId.TERRAIN}
    );
    var gmap = new OpenLayers.Layer.Google(
        "Rutero", // the default
        {numZoomLevels: 20}
    );
    var ghyb = new OpenLayers.Layer.Google(
        "Híbrido",
        {type: google.maps.MapTypeId.HYBRID, numZoomLevels: 20}
    );
    var gsat = new OpenLayers.Layer.Google(
        "Satelital",
        {type: google.maps.MapTypeId.SATELLITE, numZoomLevels: 22}
    );

    //Create a base layer
    /*var google_map = new OpenLayers.Layer.Google(
        'Google Layer',
        {}
        );*/
    map.addLayer(gmap);
    map.addLayer(ghyb);
    map.addLayer(gsat);
    map.addLayer(gphy);

    map.setCenter(new OpenLayers.LonLat(centerLon, centerLat)
        .transform(new OpenLayers.Projection("EPSG:4326"), new OpenLayers.Projection("EPSG:900913")), maxZoomOut);

    geoLayer = new OpenLayers.Layer.Vector("Zonas y lugares");

    map.addLayer(geoLayer);

    clickSelectFeature = new OpenLayers.Control.SelectFeature(geoLayer, {});
    map.addControl(clickSelectFeature);
    clickSelectFeature.activate();

    //Let's style the features
    //Create a style object to be used by a StyleMap object
    var vector_style = new OpenLayers.Style({
     'fillColor': '#009CC9',
     'fillOpacity': .4,
     'strokeColor': '#009CC9',
     'strokeWidth': 1
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

    zTab.socket.on('drawGeoData', function(data) {
        deserialize(data.geoData, geoLayer, data.data, true);
    });

    updateFormats();
    var mapZone;

    if (zTab.zCtx.zcGetZone() == '')
        mapZone = 'Argentina';
    else
        mapZone = zTab.zCtx.zcGetZone();

    drawMap(mapZone);
    ajustMapToExtendedString(mapZone);


}

function drawPopup(event) {
    var mapsources = [];
    var source = [];

    source[0] = 'Facebook';
    source[1] = '36';
    mapsources.push(source);

    source[0] = 'Twitter';
    source[1] = '53';
    mapsources.push(source);

    var maptags = [];
    var tag = [];

    tag[0] = 'Interes General';
    tag[1] = '43';
    maptags.push(tag);

    tag[0] = 'Actualidad';
    tag[1] = '46';
    maptags.push(tag);

    var popupContentHTML = "";

    popupContentHTML += '<h3 id="popupTitle">' + selectedFeature.data.extendedString + '</h3>';

    popupContentHTML += "<br><img src='/images/ver_post.gif' alt='Seleccionar' onClick='on_select_zone();'>";

    popupContentHTML += "</div>";

    popupContentHTML += "<br>";

    popupContentHTML += "<p>La zona seleccionada tiene información de las fuentes: <p><ul>";

    //Muestro los contadores de fuentes
    for(var i = 0; i < mapsources.length; i++) {
        popupContentHTML += "<li><strong>"
        + mapsources[i][0]
        + ": </strong>"
        + mapsources[i][1]
        + "</li>";
    }

    popupContentHTML += "</ul><br><p>con los tags:</p>";

    //Muestro los contadores de tags
    for(var i = 0; i < maptags.length; i++) {
        popupContentHTML += "<li><strong>"
        + maptags[i][0]
        + ": </strong>"
        + maptags[i][1]
        + "</li>";
    }

    //Armo el popup
    var lonlat = new OpenLayers.LonLat(event.feature.geometry.getCentroid().x,event.feature.geometry.getCentroid().y);
    var size = new OpenLayers.Size(50, 100);
    popup = new OpenLayers.Popup.FramedCloud("countPopup", lonlat, size, popupContentHTML, null, true, null);
    popup.displayClass = OpenLayers.Popup.FramedCloud
    map.addPopup(popup, true);
}

