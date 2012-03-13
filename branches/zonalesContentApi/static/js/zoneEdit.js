//Definiciones manejo pantalla
var socket;
var edit = false;
var geoedit = false;

//Parámetros globales
var maxZoomOut = 4;

//BOUNDS - Definidos para Argentina
var minLon = -9289807.96207;
var minLat = -8036517.191029;
var maxLon = -5053362.107152;
var maxLat = -2019394.325498;

//CENTER - Definidos para Argentina
var centerLon = -64.423444488507;
var centerLat = -41.105773412891;

var zone = null;
var geoZoneId = null;
var parent_id;

var map;
var vectors;
var formats;
var popup;
var ids = new Array();
var cant = 0;
var zoneFilter = "";
var stateFilter = "";
var zoneTypeFilter = "";
var parent_id = "";


window.addEvent('domready', function() {
	  init();
	  socket = io.connect();
  	  socket.on('connect', function () { 
	    socket.emit('getZonesTypes', true, function (data) {			  		
	  		data.each(function(type) {
	  			if (typeof(type.name) != 'undefined') { 
	  				new Element('option', {'value' : type.name, 'html' : type.name.replace(/_/g, ' ').capitalize()}).inject($('tipo'));
	  			}
	  		});
	  		if(gup('id') != null && gup('id') != '') {
		        getZone(gup('id'), false);
		     }
			if(gup('zone') != null && gup('zone') != '') {
		        zoneFilter = gup('zone');
		     }
		   if(gup('state') != null && gup('state') != '') {
		        stateFilter = gup('state');
		     }
		   if(gup('zoneType') != null && gup('zoneType') != '') {
		        zoneTypeFilter = gup('zoneType');
		     }  
		   $('backAnchor').href = '/CMUtils/zoneList?zone=' + zoneFilter + '&state=' + stateFilter + '&zoneType=' + zoneTypeFilter;
	    });
	  });
});

function getZone(id, parent){
	  //var jid = '{"id":"' + id + '"}';
	  socket.emit('getZoneByFilters', {id: id}, function (data) {
       	var jsonObj = data[0];			  		
	  		if (parent) {
             $('padre').value = jsonObj.name;
         } else {
             if (typeof($('id').value) != 'undefined') {
             	$('id').value = jsonObj.id;
             	edit = true;
             } else {
             	$('id').value = "";
             }
             typeof(jsonObj.name) != 'undefined' ? $('nombre').value = jsonObj.name.replace(/_/g, ' ').capitalize() : $('nombre').value = "";
             if (typeof(jsonObj.type) != 'undefined') {
             	  //alert(typeOf($('tipo').getElement('option[value=' + jsonObj.type + ']'))); //.set('selected');
                 $('tipo').value = jsonObj.type;
                 //alert($('tipo').value);
                 getParentTypes(jsonObj.type);
             } else {
                 $('tipo').value = "";
             }
             if (typeof(jsonObj.parent) != 'undefined'  && jsonObj.parent != null && jsonObj.parent != "") {
                 parent_id = jsonObj.parent;
             }
             if (typeof(jsonObj.geoData) != 'undefined' && jsonObj.geoData != null && jsonObj.geoData != "") {
             	geoedit = true;
              	geoZoneId = jsonObj.geoData;
              	//var gzid = '{"id":"' + geoZoneId + '"}';
              	socket.emit('getGeoData', {id: geoZoneId}, function(data) {
                    if(typeof(data) != 'undefined' && data != null && typeof(data[0]) != 'undefined' && data[0] != null && data[0] != '') {
					   $('geoJson').value = JSON.stringify(data[0]);
            		   deserialize();              		
                   }
              	});
             }  
         }	
	  });
}

function getParents(type) {
	 console.log(type);
	 //var jtype = '{"type":"' + type + '"}';
	 socket.emit('getZoneByFilters', {type: type}, function (jsonObj) {
    		jsonObj.each(function(parent) {
              var el = new Element('option', {'value' : parent.id, 'html' : parent.name.replace(/_/g, ' ').capitalize()}).inject($('padre'));
              if (parent.id == parent_id) {
                  el.selected = true;
              }
         });
    });
}

function getParentTypes(type) {
 	 //var jtype = '{"name":"' + type + '"}';
 	 $('padre').empty();
	 socket.emit('getParentTypes', {name: type}, function (jsonObj) {
	 		console.log("PARENT TYPESSSS: " + JSON.stringify(jsonObj));
    		jsonObj.each(function(parentType) {
              getParents(parentType);
         });
    });	  		        
}

function saveZone() {

    var jsonZone = '{"name":"' + $('nombre').value.replace(/ /g, '_').toLowerCase() + '","id":"' + $('id').value + '","parent":"' + $('padre').value + '","type":"' + $('tipo').value + '"';

	var objGeo;
    if ($('geoJson').value != '') {
        objGeo = eval('(' + $('geoJson').value + ')');    
    }
    
    if(geoedit) {
    	jsonZone += ',"geoData":"' + geoZoneId + '"';
    	socket.emit('updateGeoData', objGeo, function (resp) {
    		//var resp = eval('(' + data + ')'); 
    		if (resp.cod == 100) {
    			alert("Se ha actualizado el dato geográfico"); 
    		} else {
    			alert("Error al actualizar el dato geográfico");
    		}
    	});	
    } else {
    	  if (geoZoneId != null) {
    	  		jsonZone += ',"geoData":"' + geoZoneId + '"';
		    	socket.emit('saveGeoData', objGeo, function (resp) {
		    		//var resp = eval('(' + data + ')'); 
    				if (resp.cod == 100) {
		    			alert("Se ha guardado el dato geográfico"); 
		    		} else {
		    			alert("Error al guardar el dato geográfico");
		    		}
		    	});	
    	  }
    }
    jsonZone += '}';
    
	 var objZone = eval('(' + jsonZone + ')');
    
	 if (edit) {
    	socket.emit('updateZone', objZone, function (resp) {
    		//var resp = eval('(' + data + ')'); 
    		if (resp.cod == 100) {
    			alert("Se ha actualizado la zona"); 
    		} else {
    			alert("Error al actualizar la zona");
    		}
    	});	
    } else {
    	socket.emit('saveZone', objZone, function (resp) {
    		//var resp = eval('(' + data + ')'); 
    		if (resp.cod == 100) {
    			alert("Se ha guardado la zona"); 
    		} else {
    			alert("Error al guardar la zona");
    		}
    	});
    }    
    
}

//Funciones de manejo del mapa

function on_move(event) {
    if (map.getZoom() < maxZoomOut) map.zoomIn();
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

function serialize(event) {
    var type = 'geojson';
    var str = formats['out'][type].write(vectors.features, true);
    if (typeof(geoZoneId) == 'undefined' || geoZoneId == null) {
    	  socket.emit('getNextGeoId', null, function (id) {
            geoZoneId = id;
            str = str.substring(0, str.length-2) + ',\n    "id": "' + geoZoneId + '"\n}';
            document.getElementById('geoJson').value = str;
        });
    }
}

function deserialize() {
    var element = document.getElementById('geoJson');
    var type = "geojson";
    var features = formats['in'][type].read(element.value);
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
        vectors.addFeatures(features);
        map.zoomToExtent(bounds);
        /*var plural = (features.length > 1) ? 's' : '';
        element.value = features.length + ' feature' + plural + ' added';*/
    } else {
        element.value = 'Bad input ' + type;
    }
}

function clearMap() {
    $('geoJson').value = "";
    vectors.destroyFeatures();
}

function on_unselect() {
    document.getElementById('geoJson').value = "";
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

    vectors = new OpenLayers.Layer.Vector("Vector Layer");

    map.addLayers([ghyb, gmap, gsat, gphy, vectors]);
    map.addControl(new OpenLayers.Control.MousePosition());
    map.addControl(new OpenLayers.Control.EditingToolbar(vectors));

    /*var options = {
        hover: true,
        onSelect: serialize,
        onUnselect: on_unselect
    };*/

    var select = new OpenLayers.Control.SelectFeature(vectors, {});
    map.addControl(select);
    select.activate();

    //Let's style the features
    //Create a style object to be used by a StyleMap object
    var vector_style = new OpenLayers.Style({
     'fillColor': '#669933',
     'fillOpacity': .8,
     'strokeColor': '#aaee77',
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
    vectors.styleMap = vector_style_map;

    updateFormats();

    map.setCenter(new OpenLayers.LonLat(centerLon, centerLat).transform(new OpenLayers.Projection("EPSG:4326"), new OpenLayers.Projection("EPSG:900913")), maxZoomOut);

    map.events.register('moveend', this, on_move);
    vectors.events.register('featuresadded', this, serialize);
}