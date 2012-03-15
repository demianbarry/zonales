var socket;

//Parámetros globales
var maxZoomOut = 3;

//BOUNDS - Definidos para Argentina
var minLon = -9289807.96207;
var minLat = -8036517.191029;
var maxLon = -5053362.107152;
var maxLat = -2019394.325498;

//CENTER - Definidos para Argentina
var centerLon = -64.423444488507;
var centerLat = -41.105773412891;

var map;

window.addEvent('domready', function () {
	  init();
	  socket = io.connect();
  	  /*socket.on('connect', function () { 
      }*/
});

OpenLayers.Control.Click = OpenLayers.Class(OpenLayers.Control, {                
    defaultHandlerOptions: {
        'single': true,
        'double': false,
        'pixelTolerance': 0,
        'stopSingle': false,
        'stopDouble': false
    },

    initialize: function(options) {
        this.handlerOptions = OpenLayers.Util.extend(
            {}, this.defaultHandlerOptions
        );
        OpenLayers.Control.prototype.initialize.apply(
            this, arguments
        ); 
        this.handler = new OpenLayers.Handler.Click(
            this, {
                'click': this.trigger
            }, this.handlerOptions
        );
    }, 

    trigger: function(e) {
        var lonlat = map.getExtent().getCenterLonLat()
					.transform(new OpenLayers.Projection("EPSG:900913"), new OpenLayers.Projection("EPSG:4326"));	
        alert('Centro: ' + lonlat.lat + ", " + lonlat.lon + '\nZoom Level: ' + map.getZoom() +
        		  '\nBounds: ' + map.getExtent().transform(new OpenLayers.Projection("EPSG:900913"), new OpenLayers.Projection("EPSG:4326")).toBBOX()
        );
    }

});

function on_move(event) {
    if (map.getZoom() < maxZoomOut) map.zoomIn();
}

function search() {
    socket.emit('wikimapiaBboxSearch', {
        bbox: map.getExtent().transform(new OpenLayers.Projection("EPSG:900913"), new OpenLayers.Projection("EPSG:4326")).toBBOX(),
        category: $('categoryFilter').value,
        count: $('countFilter').value,
        page: $('pageFilter').value
    }, function (response) {
        makeTable(JSON.parse(response));
    });
}

function init() {

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

    map.addLayers([ghyb, gmap, gsat, gphy]);

    map.setCenter(new OpenLayers.LonLat(centerLon, centerLat).transform(new OpenLayers.Projection("EPSG:4326"), new OpenLayers.Projection("EPSG:900913")), maxZoomOut);

    map.events.register('moveend', this, on_move);
    
    var click = new OpenLayers.Control.Click();
	map.addControl(click);
	click.activate();            

}

function makeTable(jsonObj){

    $('resultslist_content').empty();

    var places_table = new Element('table', {
        'id' : 'resultTable'
    }).addClass('resultTable').inject($('resultslist_content'));
    var config_title_tr = new Element('tr', {
        'class': 'tableRowHeader'
    }).inject(places_table);
    new Element('td', {
        'html' : 'Id'
    }).inject(config_title_tr);
    new Element('td', {
        'html' : 'Nombre'
    }).inject(config_title_tr);
    new Element('td', {
        'html' : 'URL'
    }).inject(config_title_tr);
    new Element('td', {
        'html' : 'Cadena Extendida'
    }).inject(config_title_tr);
    new Element('td', {
        'html' : 'Tipo'
    }).inject(config_title_tr);
    new Element('td', {
        'html' : 'Seleccionar'
    }).inject(config_title_tr);

    jsonObj.folder.each(function(place){
        var config_title_tr = new Element('tr', {
                'id': 'tr_' + place.id,            
            'class': 'tableRow'
        }).inject(places_table);
        new Element('td', {
            'html' : place.id
            }).inject(config_title_tr);
        new Element('td', {
            'html' : place.name
            }).inject(config_title_tr);
        var urlTd = new Element('td').inject(config_title_tr);
        new Element('a', {
            'html' : place.url,
            'href' : place.url
            }).inject(urlTd);
        new Element('td', {
            'html' : "-" //Cadena Extendida
        }).inject(config_title_tr);
        new Element('td', {
            'html' : "-" //Tipo
            }).inject(config_title_tr);
        var selectTd = new Element('td', {'html': '-'}).inject(config_title_tr);
    });
}