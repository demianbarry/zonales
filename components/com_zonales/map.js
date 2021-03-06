//Parámetros globales
var cantMax = '1000';
var maxZoomOut = 4;
var openLayersProxyHost = '/cgi-bin/proxy.cgi?url=';
var proxyUri = '/curl_proxy.php?host=localhost&port=38080&ws_path=';
var geoExtractorUrl = 'http://localhost:38080/ZCrawlGeoExtractor/getPost';
var defaultSortField = 'modified';
var strategyDistance = 30;

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
var vector_layer;
var popup;
var mapsources = new Array();
var maptags = new Array();
var mapzones = new Array();
var cant = 0;
var ignoredSources = new Array();

//-------------------------------
//HTML Related
//-------------------------------
//Function to be called that updates vector layer when submit
//  is clicked

function setMapZone(lon, lat, zoomLevel) {
    map.setCenter(new OpenLayers.LonLat(lon, lat), zoomLevel);
}

function ampliar(lon, lat) {
    if (typeof popup != 'undefined') {
        popup.destroy();
    }
    var zoomLevel = map.getZoom() + 3;
    setMapZone(lon, lat, zoomLevel);
}

function mapToPost() {
    $('postsDiv').set({
        style: 'display:block'
    });
    $('mapDiv').set({
        style: 'display:none'
    });
    loadPost(true);
}

function update_vector_layer(){
    document.getElementById('ajaxLoader').style.display = "inline";
    vector_layer.protocol.options.params['since'] = "NOW-" + document.getElementById('tempoSelect').value;
    if (ignoredSources.length > 0) {
        vector_layer.protocol.options.params['ignoredSources'] = ignoredSources.toString();
    } else {
        delete(vector_layer.protocol.options.params['ignoredSources']);
    }
    var bounds = map.getExtent().transform(new OpenLayers.Projection("EPSG:900913"), new OpenLayers.Projection("EPSG:4326"));
    vector_layer.protocol.options.params['minLat'] = bounds.bottom;
    vector_layer.protocol.options.params['maxLat'] = bounds.top;
    vector_layer.protocol.options.params['minLon'] = bounds.left;
    vector_layer.protocol.options.params['maxLon'] = bounds.right;
    vector_layer.refresh();

}

function initMapTab () {
    if (!iniciado) {
        initMap();
    }
    iniciado = true;
}

function initMap() {
    //specify proxyhost
    OpenLayers.ProxyHost = openLayersProxyHost;

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

    strategy = new OpenLayers.Strategy.Cluster();
    strategy.distance = strategyDistance;

    //Add vector layer
    vector_layer = new OpenLayers.Layer.Vector('ZonalesPost',
    {
        projection: new OpenLayers.Projection('EPSG:4326'),
        protocol: new OpenLayers.Protocol.HTTP({
            url: geoExtractorUrl,
            params: {
                'cant':cantMax,
                'sortField':defaultSortField,
                'sortOrder':'desc',
                'since':'NOW-' + zcGetContext().filters.temp
            },  //, 'minLon':'-78.969515', 'minLat':'-59.155008', 'maxLon':'-48.295686','maxLat':'-17.017761'
            format: new OpenLayers.Format.KML({
                extractAttributes: true,
                extractStyles:true
            })
        }),
        strategies: [new OpenLayers.Strategy.Fixed(), strategy]   //new OpenLayers.Strategy.Fixed(),
    });
    //actualizarFiltros();
    update_vector_layer();
    map.addLayer(vector_layer);

    //Let's style the features
    //Create a style object to be used by a StyleMap object
    var vector_style = new OpenLayers.Style({
        'fillColor': '#669933',
        'fillOpacity': .8,
        'fontColor': '#f0f0f0',
        'fontFamily': 'arial, sans-serif',
        'fontSize': '.9em',
        'fontWeight': 'bold',
        'label': '${num_points}',
        'pointRadius': '${point_radius}',
        'strokeColor': '#aaee77',
        'strokeWidth': 3
    },
    //Second parameter contains a context parameter
    {
        context: {
            num_points: function(feature){
                return feature.attributes.count;
            },
            point_radius: function(feature){
                return 9 + (Math.log(feature.attributes.count) * 2)
            }
        }
    });

    var vector_style_select = new OpenLayers.Style({
        'fillColor': '#cdcdcd',
        'fillOpacity': .9,
        'fontColor': '#232323',
        'strokeColor': '#ffffff'
    })

    //Create a style map object and set the 'default' intent to the
    var vector_style_map = new OpenLayers.StyleMap({
        'default': vector_style,
        'select': vector_style_select
    });

    //Add the style map to the vector layer
    vector_layer.styleMap = vector_style_map;

    //Add a select feature control
    var select_feature_control = new OpenLayers.Control.SelectFeature(
        vector_layer,
        {
        }
        )
    map.addControl(select_feature_control);
    select_feature_control.activate();


    //Functions to call for the select feature control
    function on_select_feature(event){
        //Vacio las listas de contadores
        mapsources = new Array();
        maptags = new Array();
        mapzones = new Array();
        zirClient.clearSolrIds();
        //setSolrRows(20);
        cant = 0;

        //Store the clusters
        var cluster = event.feature.cluster;

        //Recorro los elementos para contar las fuentes y los tags
        for(var i = 0; i < event.feature.attributes.count; i++){
            addSource(cluster[i].attributes.name); //Fuente
            var postDetail = JSON.parse(cluster[i].attributes.description);
            postDetail.tags.each(function(tag){
                addTag(tag); //Tags
            });
            zirClient.addIdToSolrSearch(postDetail.id);
            mapzones.include(postDetail.zone);
            cant++;
        }

        var popupContentHTML = "";

        popupContentHTML += '<h3 id="popupTitle">Información</h3>';
        popupContentHTML += "<br><div style='verticalAlign: center'><img src='/images/ampliar.gif' alt='Ampliar' onClick='ampliar(" + event.feature.geometry.x + "," + event.feature.geometry.y +")'>";

        if (cant < 200) {
            //setSolrRows(event.feature.attributes.count);
            popupContentHTML += "<br><img src='/images/ver_post.gif' alt='Ver posts' onClick='mapToPost();'>";
        }

        popupContentHTML += "</div>";

        popupContentHTML += "<br>";

        popupContentHTML += "<p>Usted está viendo información de las zonas:<p><ul>";

        //Muestro las zonas
        for(var i = 0; i < mapzones.length; i++) {
            popupContentHTML += "<li><strong>"
            + mapzones[i]
            + "</strong></li>";
        }

        popupContentHTML += "</ul><br><p>obtenida de las fuentes:<p><ul>";

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
        var lonlat = new OpenLayers.LonLat(event.feature.geometry.x,event.feature.geometry.y);
        var size = new OpenLayers.Size(150, 300);
        popup = new OpenLayers.Popup.FramedCloud("countPopup", lonlat, size, popupContentHTML, null, true, null);
        popup.displayClass = OpenLayers.Popup.FramedCloud
        map.addPopup(popup);
        var point = new OpenLayers.Geometry.Point(event.feature.geometry.x, event.feature.geometry.y);
        point.transform(new OpenLayers.Projection("EPSG:900913"), new OpenLayers.Projection("EPSG:4326"));
        //alfaFromGeo(point.y, point.x);

    //"<img src='" + cluster[i].style.externalGraphic + "' />"

    }


    function addSource(valor){
        var ind, pos;
        var find = false;
        for(ind=0; ind < mapsources.length; ind++)
        {
            if (typeof mapsources[ind] != 'undefined' && mapsources[ind][0] == valor) {
                mapsources[ind][1] = mapsources[ind][1] + 1;
                zcAddSource(valor);
                find = true;
                break;
            }
        }
        if (!find) {
            mapsources[mapsources.length] = new Array(valor, 1);
        }
    }

    function addTag(valor){
        var ind, pos;
        var find = false;
        for(ind=0; ind < maptags.length; ind++)
        {
            if (typeof maptags[ind] != 'undefined' && maptags[ind][0] == valor) {
                maptags[ind][1] = maptags[ind][1] + 1;
                zcAddTag(valor);
                find = true;
                break;
            }
        }
        if (!find) {
            maptags[maptags.length] = new Array(valor, 1);
        }
    }

    //on unselect function
    function on_unselect_feature(event){
        if (typeof popup != 'undefined') {
            popup.destroy();
        }
    }

    function on_features_added(event){
        document.getElementById('ajaxLoader').style.display = "none";
        /*vector_layer.features.each(function(feature){
            feature.cluster.each(function(cluster){
                if(!$('chk'+cluster.attributes.name)) {
                    var tr = new Element('tr');
                    new Element('input', {
                        'id': 'chk'+cluster.attributes.name,
                        'type': 'checkbox',
                        'checked': true,
                        'value': cluster.attributes.name,
                        'onclick':'filtrar(this.value, this.checked);'
                    }).inject(new Element('td').inject(tr));
                    new Element('td', {
                        'html': cluster.attributes.name
                        }).inject(tr);
                    if (cluster.attributes.name == 'Facebook' || cluster.attributes.name == 'Twitter') {
                        tr.inject($("enLaRed"));
                    } else {
                        tr.inject($("noticiasEnLaRed"));
                    }
                }
            });
        });*/
    }

    //on unselect function
    /*function on_dblclick_feature(event){
                 var popupContentHTML = "<br>Prueba</br>";
                 alert("Hice doble click, pero el evento capturado es de JavaScript y no sirve para nada");
	     }*/

    function alfaFromGeo(lat, lon) {
        //"http://nominatim.openstreetmap.org/reverse?
        var url = "/reverse?lat=" + lat + "&lon=" + lon + "&format=json";

        var urlProxy = '/curl_proxy.php?host=nominatim.openstreetmap.org&port=80&ws_path=' + encodeURIComponent(url);

        var req = new Request.JSON({
            url: urlProxy,
            method: 'get',
            onRequest: function(){
            },
            onComplete: function(jsonObj) {
                //var jsonObj = JSON.parse(response);
                var mostrar = "Zona indefinida";
                if (typeof(jsonObj.address.city) != 'undefined') {
                    mostrar = jsonObj.address.city;
                } else if(typeof(jsonObj.address.town) != 'undefined') {
                    mostrar = jsonObj.address.town;
                } else if (typeof(jsonObj.address.village) != 'undefined') {
                    mostrar = jsonObj.address.village;
                } else if (typeof(jsonObj.address.state) != 'undefined') {
                    mostrar = "Provincia de " + jsonObj.address.state;
                } else if (typeof(jsonObj.address.country) != 'undefined') {
                    mostrar = jsonObj.address.country;
                }

                document.getElementById('cercaDe').innerHTML = mostrar;
                /*if (document.getElementById('popupTitle') != null) {
                    document.getElementById('popupTitle').innerHTML = mostrar;
                }*/
            },
            onFailure: function(){
            }
        }).send();
    }

    function on_zoom(event) {
        if (map.getZoom() < maxZoomOut) map.zoomIn();
        alfaFromGeo(map.getCenter().transform(new OpenLayers.Projection("EPSG:900913"), new OpenLayers.Projection("EPSG:4326")).lat, map.getCenter().transform(new OpenLayers.Projection("EPSG:900913"), new OpenLayers.Projection("EPSG:4326")).lon);
        update_vector_layer();
    }

    vector_layer.events.register('featureselected', this, on_select_feature);
    vector_layer.events.register('featureunselected', this, on_unselect_feature);
    vector_layer.events.register('featuresadded', this, on_features_added);
    map.events.register('moveend', this, on_zoom);
    //vector_layer.events.register('dblclick', this, on_dblclick_feature);

    if(!map.getCenter()){
        map.zoomToMaxExtent();
    }

    //Add events to HTML input element
    document.getElementById('tempoSelect').addEventListener(
        'change',
        update_vector_layer,
        false);

}