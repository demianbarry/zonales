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

var place = null;
var geoPlaceId = null;
var parent_id;

var map;
var vectors;
var formats;
var popup;
var ids = [];
var cant = 0;
var placeFilter = "";
var stateFilter = "";
var placeTypeFilter = "";
var placeZoneFilter = "";
var zones = [];
var zonesIds = [];
var parents = [];
var parentsIds = [];
var links = [];
var cantLinks = 0;

window.addEvent('domready', function () {
	  init();
	  socket = io.connect();
  	  socket.on('connect', function () { 
        socket.emit('getZonesExtendedString', true, function (data) {                    
            data.each(function(zone) {
                if (typeof(zone.extendedString) != 'undefined') { 
                    zones.include(zone.extendedString.replace(/_/g, ' ').capitalize());
                    zonesIds[zone.extendedString.replace(/_/g, ' ').capitalize()] = zone.id;
                }
            });
        });
	    socket.emit('getPlaceTypes', true, function (data) {			  		
	  		data.each(function(type) {
	  			if (typeof(type.name) != 'undefined') { 
	  				new Element('option', {'value' : type.name, 'html' : type.name.replace(/_/g, ' ').capitalize()}).inject($('type'));
	  			}
	  		});
            if(gup('id') != null && gup('id') != '') {
                getPlace(gup('id'), false);
             }
	    });
        if(gup('place') != null && gup('place') != '') {
            placeFilter = gup('place');
         }
       if(gup('state') != null && gup('state') != '') {
            stateFilter = gup('state');
         }
       if(gup('placeType') != null && gup('placeType') != '') {
            placeTypeFilter = gup('placeType');
         }  
       if(gup('placeZone') != null && gup('placeZone') != '') {
            placeZoneFilter = gup('placeZone');
         }
       if(gup('jsonInput') != null && gup('jsonInput') != 'true') {
            $('jsonInput').style.display = "none";
         }    
       $('backAnchor').href = '/CMUtils/placeList?place=' + placeFilter + '&state=' + stateFilter + '&placeType=' + placeTypeFilter + '&placeZone=' + placeZoneFilter;
	  });
});

function addLink(link) {
    if (cantLinks == 0) {
        var links_table = new Element('table', {'id' : 'links_table'}).addClass('configTable').inject($('linksDiv'));
        var links_title_tr = new Element('tr', {'style': 'background-color: lightGreen'}).inject(links_table);
        new Element('td', {'html' : 'Links'}).inject(links_title_tr);
        new Element('td', {'html' : 'Eliminar'}).inject(links_title_tr);
    }
    var link_line = new Element('tr', {'id' : 'pl' + cantLinks}).inject(links_table);
    new Element('td', {'html': link}).inject(link_line);
    var removeLink_td = new Element('td').inject(link_line);
    new Element('img', {'width' : '16', 'height' : '16', 'border': '0', 'alt': cantLinks, 'title' : 'Eliminar Link', 'src': '/images/publish_x.png', 'onclick' : 'removeLink('+ cantLinks + ')'}).inject(removeLink_td);
    links[cantLinks] = link;
    cantLinks++;
}

function removeLink(linkLine){
    $('links_table').removeChild($('pl'+linkLine));
    delete links[linkLine];
}

function getPlace(id, parent){
	  //var jid = '{"id":"' + id + '"}';
	  socket.emit('getPlaceByFilters', {id: id}, function (data) {
       	var jsonObj = data[0];			  		
	  	 if (parent) {
             $('parent').value = jsonObj.extendedString.replace(/_/g, ' ').capitalize();
         } else {
             if (typeof($('id').value) != 'undefined') {
             	$('id').value = jsonObj.id;
             	edit = true;
             } else {
             	$('id').value = "";
             }
             typeof(jsonObj.name) != 'undefined' ? $('name').value = jsonObj.name.replace(/_/g, ' ').capitalize() : $('name').value = "";
             typeof(jsonObj.description) != 'undefined' ? $('description').value = jsonObj.description : $('description').value = "";
             typeof(jsonObj.address) != 'undefined' ? $('address').value = jsonObj.address : $('address').value = "";
             if (typeof(jsonObj.zone) != 'undefined') {
                socket.emit('getZoneByFilters', {id: jsonObj.zone}, function(data) {
                    if (typeof(data[0]) != 'undefined' && data[0] != null) {
                        $('placeZone').value = data[0].extendedString.replace(/_/g, ' ').capitalize();
                        zonesIds[data[0].extendedString.replace(/_/g, ' ').capitalize()] = data[0].id;
                    }
                });
             } else {
                $('placeZone').value = "";  
             } 
             if (typeof(jsonObj.type) != 'undefined') {
                 $('type').value = jsonObj.type;
                 getParentTypes(jsonObj.type);
             } else {
                 $('type').value = "";
             }
             if (typeof(jsonObj.parent) != 'undefined'  && jsonObj.parent != null && jsonObj.parent != "") {
                 getPlace(jsonObj.parent, true);
             }
             typeof(jsonObj.image) != 'undefined' ? $('image').value = jsonObj.image : $('image').value = "";
             if (typeof(jsonObj.links) != 'undefined') {
                  jsonObj.links.each(function(link){
                        addLink(link);
                  });
             }
             if (typeof(jsonObj.geoData) != 'undefined'  && jsonObj.geoData != null && jsonObj.geoData != "") {
             	geoedit = true;
              	geoPlaceId = jsonObj.geoData;
              	//var gzid = '{"id":"' + geoPlaceId + '"}';
              	socket.emit('getGeoData', {id: geoPlaceId}, function(data) {
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
	 socket.emit('getPlaceByFilters', {type: type}, function (jsonObj) {
    		jsonObj.each(function(parent) {
              if (typeof(parent.extendedString) != undefined && parent.extendedString != null && parent.extendedString != '') {
                parents.include(parent.extendedString.replace(/_/g, ' ').capitalize());
                parentsIds[parent.extendedString.replace(/_/g, ' ').capitalize()] = parent.id;
              }
         });
    });
}

function getParentTypes(type) {
 	 //var jtype = '{"name":"' + type + '"}';
 	 //$('padre').empty();
	 socket.emit('getPlaceParentTypes', {name: type}, function (jsonObj) {
	 		console.log("PARENT TYPESSSS: " + JSON.stringify(jsonObj));
    		jsonObj.each(function(parentType) {
                getParents(parentType);
         });
    });	  		        
}

function savePlace() {

    var jsonPlace = '{"name":"' + $('name').value.replace(/ /g, '_').toLowerCase() + '"';
    jsonPlace += ',"id":"' + $('id').value + '"';
    jsonPlace += ',"parent":"' + (typeof(parentsIds[$('parent').value]) != 'undefined' ? parentsIds[$('parent').value] : "") + '"';
    jsonPlace += ',"type":"' + $('type').value + '"';
    jsonPlace += ',"description":"' + $('description').value + '"';
    jsonPlace += ',"address":"' + $('address').value + '"';
    jsonPlace += ',"image":"' + $('image').value + '"';
    jsonPlace += ',"zone":"' + zonesIds[$('placeZone').value] + '"';

    if (links.length > 0) {
         jsonPlace += ',"links":[';
         for (i = 0; i < links.length; i++) {
             if (links[i] != null) {
                 jsonPlace += '"' + links[i] + '",';
             }
         }
         jsonPlace = jsonPlace.substring(0, jsonPlace.length - 1);
         jsonPlace += ']';
     }

    var objGeo;
    if ($('geoJson').value != '') {
        objGeo = eval('(' + $('geoJson').value + ')');    
    }
    
    if(geoedit && $('geoJson').value != '') {
    	jsonPlace += ',"geoData":"' + geoPlaceId + '"';
    	socket.emit('updateGeoData', objGeo, function (resp) {
    		//var resp = eval('(' + data + ')'); 
    		if (resp.cod == 100) {
                jsonPlace += '}';
                savePlaceEmit(jsonPlace);
    			alert("Se ha actualizado el dato geográfico"); 
    		} else {
    			alert("Error al actualizar el dato geográfico");
    		}
    	});	
    } else {
        if ($('geoJson').value != '') {
	  		jsonPlace += ',"geoData":"' + geoPlaceId + '"';
	    	socket.emit('saveGeoData', objGeo, function (resp) {
	    		//var resp = eval('(' + data + ')'); 
				if (resp.cod == 100) {
                    jsonPlace += ',"geoData":' + resp.id + '}';
                    savePlaceEmit(jsonPlace);
	    			alert("Se ha guardado el dato geográfico"); 
	    		} else {
	    			alert("Error al guardar el dato geográfico");
	    		}
	    	});	
          } else {
                jsonPlace += '}';
                savePlaceEmit(jsonPlace);
          }
    }
    
}

function savePlaceEmit(jsonPlace) {
    var objPlace = eval('(' + jsonPlace + ')');
    
     if (edit) {
        socket.emit('updatePlace', objPlace, function (resp) {
            //var resp = eval('(' + data + ')'); 
            if (resp.cod == 100) {
                alert("Se ha actualizado el place"); 
            } else {
                alert("Error al actualizar el place");
            }
        }); 
    } else {
        socket.emit('savePlace', objPlace, function (resp) {
            //var resp = eval('(' + data + ')'); 
            if (resp.cod == 100) {
                alert("Se ha guardado el place"); 
            } else {
                alert("Error al guardar el place");
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
    if (typeof(geoPlaceId) == 'undefined' || geoPlaceId == null) {
        str = str.substring(0, str.length-2) + ',\n    "id": "' + geoPlaceId + '"\n}';
        document.getElementById('geoJson').value = str;
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

    /*var google_map = new OpenLayers.Layer.Google(
        'Google Layer',
        {}
    );*/

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