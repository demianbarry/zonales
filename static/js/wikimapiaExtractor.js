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
var places = [];
var zoneExtendedStrings = [];
var zoneTypes = [];

window.addEvent('domready', function () {
    init();
    $('savePlacesButton').disabled = true;
    socket = io.connect();
    socket.on('connect', function () { 
        socket.emit('getZonesExtendedString', null, function (zones) {
            zones.each(function (zone) {
                if (typeof(zone.extendedString) != 'undefined') {
                    zoneExtendedStrings.push(zone.extendedString.replace(/_/g, ' ').capitalize());
                }
            });
        });
        socket.emit('getZonesTypes', true, function (data) {                    
            data.each(function(type) {
                if (typeof(type.name) != 'undefined') { 
                    zoneTypes.push(type.name);
                }
            });
        });
    });
});

function on_move(event) {
    if (map.getZoom() < maxZoomOut) map.zoomIn();
}

function search() {
    places.empty();
    socket.emit('wikimapiaBboxSearch', {
        bbox: map.getExtent().transform(new OpenLayers.Projection("EPSG:900913"), new OpenLayers.Projection("EPSG:4326")).toBBOX(),
        category: $('categoryFilter').value,
        count: $('countFilter').value,
        page: $('pageFilter').value
    }, function (response) {
        makeTable(response);
    });
}

function moreInfo() {
    $('savePlacesButton').disabled = false;
    $$("table#resultTable.resultTable tr.tableRow td input").each(function(check){
        if (check.checked && $('ee_' + check.id).innerHTML == '-') {
            socket.emit('wikimapiaGetObject', check.id, function(data) {
                if (typeof(data) != 'undefined' && data != null) {
                    if (typeof(data.tags) != 'undefined' && data.tags != null) {
                        $('cat_' + data.id).innerHTML = "";
                        data.tags.each(function (tag) {
                            $('cat_' + data.id).innerHTML += tag.title + ' / '; 
                        });
                    }

                    var title = data.title != null ? data.title : data.titleSuperArray.title;
                    var extendedString = title;
                    var isZone = false;

                    if (typeof(data.location) != 'undefined' && data.location != null) {
                        if (accentsTidy(title) == data.location.place) {
                            isZone = true;
                        } else {
                            extendedString += ', ' + data.location.place;
                        }
                        extendedString += ', ' + data.location.state + ', ' + data.location.country;
                    }
                    $('ee_' + data.id).innerHTML = extendedString;
                    data.extendedString = extendedString;
                    $('type_' + data.id).value = isZone ? 'Zona' : 'Place';
                    places[data.id] = data;
                }
            });
        }
    });
}

function checkInfo(id, checked) {
    if(checked && $('type_' + id).innerHTML == '-'){
        $('savePlacesButton').disabled = true;
    }
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

}

function savePlaces() {
    var checks = $$("table#resultTable.resultTable tr.tableRow td input");
    savePlace(checks);
}

function savePlace(checks) {
    var check = checks.pick();
    checks.erase(check);
    if (check != null) {
        if (check.checked) {
            if ($('type_' + check.id).value == 'Place') {
                var place = places[check.id];
                if (typeof(place) != 'undefined' && place != null && typeof(place.polygon) != 'undefined' && place.polygon != null && place.polygon.length > 0 ) {
                    var extendedString = place.extendedString.replace(/, /g, ',+').replace(/ /g, '_').replace(/,\+/g, ', ').toLowerCase();
                    var zoneExtendedString = $('zone_' + check.id).value.replace(/, /g, ',+').replace(/ /g, '_').replace(/,\+/g, ', ').toLowerCase();
                    var geodata = getGeodata(place);

                    socket.emit('getPlaceByFilters', {extendedString: extendedString}, function(zoneObjs) {
                        if (typeof(zoneObjs) != 'undefined' && zoneObjs != null && typeof(zoneObjs[0]) != 'undefined' && zoneObjs[0] != null) {
                            //TODO: Actualizar un place
                            alert("Place existente");
                            savePlace(checks);
                        } else {
                            //Nuevo Place
                            socket.emit('getZoneByFilters', {extendedString: zoneExtendedString}, function(zone) {
                                if (typeof(zone) != 'undefined' && zone != null && typeof(zone[0]) != 'undefined' && zone[0] != null) {
                                    placeObj = toPlace(place, zone[0].id, zoneExtendedString);
                                    socket.emit('saveGeoData', geodata, function(response) {
                                        placeObj.geoData = response.id;
                                        socket.emit('savePlace', placeObj, function(response) {
                                            console.log(JSON.stringify(response));
                                            alert(place.extendedString + " guardado");
                                        });
                                        savePlace(checks);
                                    });
                                } else {
                                    alert("No recupero Zona");
                                    savePlace(checks);
                                }
                            });
                        }
                    });
                } else {
                    alert("Ampliar información del place " + check.id);
                    savePlace(checks);
                }
            } else {
                var zone = places[check.id];
                if (typeof(zone) != 'undefined' && zone != null && typeof(zone.polygon) != 'undefined' && zone.polygon != null && zone.polygon.length > 0 ) {
                    var extendedString = $('zone_' + check.id).value.replace(/, /g, ',+').replace(/ /g, '_').replace(/,\+/g, ', ').toLowerCase();
                    var geodata = getGeodata(zone);

                    socket.emit('getZoneByFilters', {extendedString: extendedString}, function(zoneObjs) {
                        if (typeof(zoneObjs) != 'undefined' && zoneObjs != null && typeof(zoneObjs[0]) != 'undefined' && zoneObjs[0] != null) {
                            var zoneObj = zoneObjs[0];
                            if (typeof(zoneObj.geoData) != 'undefined' && zoneObj.geoData != null && zoneObj.geoData != '') {
                                geodata.id = zoneObj.geoData;
                                socket.emit('updateGeoData', geodata, function(response) {
                                    console.log(JSON.stringify(response));
                                });
                            } else {
                                socket.emit('addGeoDataToZone', {zone:zoneObj, geodata: geodata}, function(response) {
                                    console.log(JSON.stringify(response));
                                });
                            }
                            alert(zone.extendedString + " actualizado");
                            savePlace(checks);
                        } else {
                            //TODO: Warning. Esto está duro, solo sirve por ahora para zonas superiores a provincia (igual no hay en wikimapia provincias)
                            var parentExtendedString = zone.location.state.replace(/ /g, '_').toLowerCase() + ', ' + zone.location.country.replace(/ /g, '_').toLowerCase();
                            socket.emit('getZoneByFilters', {extendedString: parentExtendedString}, function(parent) {
                                if (typeof(parent) != 'undefined' && parent != null && typeof(parent[0]) != 'undefined' && parent[0] != null) {
                                    var zoneObj = toZone(zone, parent[0].id, $('zoneType_' + zone.id).value);
                                    socket.emit('saveGeoData', geodata, function(response) {
                                        zoneObj.geoData = response.id;
                                        socket.emit('saveZone', zoneObj, function(response) {
                                            console.log(JSON.stringify(response));
                                            alert(zone.extendedString + " guardado");
                                        });
                                    });
                                    savePlace(checks);
                                } else {
                                    savePlace(checks);
                                }
                            });
                        }
                    });
                } else {
                    savePlace(checks);
                }
            }
        } else {
            //alert("No está checked" + check.id);
            savePlace(checks);
        }
    }
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
        'html' : 'Cadena Extendida'
    }).inject(config_title_tr);
    new Element('td', {
        'html' : 'Categorías'
    }).inject(config_title_tr);
    new Element('td', {
        'html' : 'Tipo'
    }).inject(config_title_tr);
    new Element('td', {
        'html' : 'Zona'
    }).inject(config_title_tr);
    var checktd = new Element('td').inject(config_title_tr);    
    var checkbox = new Element ('input',{
        'type':'checkbox'       
    }).inject(checktd).addEvent('click', function(){        
        $$("table#resultTable.resultTable tr.tableRow td input").each(function(check){
            check.checked = checkbox.checked;
        });
    });

    jsonObj.folder.each(function(place){
        var config_title_tr = new Element('tr', {
                'id': 'tr_' + place.id,            
            'class': 'tableRow'
        }).inject(places_table);
        new Element('td', {
            'html' : place.id
            }).inject(config_title_tr);
        var nameTd = new Element('td').inject(config_title_tr);
        new Element('a', {
            'html' : place.name,
            'href' : place.url
            }).inject(nameTd);
        new Element('td', {
            'id': 'ee_' + place.id,
            'html' : "-" //Cadena Extendida
        }).inject(config_title_tr);
        new Element('td', {
            'id': 'cat_' + place.id,
            'html' : "-" //Categoría
        }).inject(config_title_tr);
        var typetd = new Element('td').inject(config_title_tr);
        var typeselect = new Element('select', {
            'id': 'type_' + place.id,
            'onchange' : 'refreshList(' + place.id + ');'
        }).inject(typetd);
        new Element('option', {
            'value' : 'Zone',
            'html': 'Zone'
        }).inject(typeselect);
        new Element('option', {
            'value' : 'Place',
            'html': 'Place'
        }).inject(typeselect);
        new Element('option', {
            'value' : 'New Zone',
            'html': 'New Zone'
        }).inject(typeselect);
        var zoneTypeselect = new Element('select', {
            'id': 'zoneType_' + place.id, 
            'style': 'display: none'
        }).inject(typetd);
        zoneTypes.each(function (zoneType) {
            new Element('option', {
                'value' : zoneType,
                'html': zoneType.replace(/_/g, ' ').capitalize()
            }).inject(zoneTypeselect);
        });
        var zonetd = new Element('td').inject(config_title_tr);
        new Element('input', {
            'id': 'zone_' + place.id,
            'onblur': 'if(this.getNext() != null) this.getNext().empty();',
            'onkeyup': 'populateOptions(event, this, false, zoneExtendedStrings);'
        }).inject(zonetd);
        var checktd = new Element('td').inject(config_title_tr);
        new Element ('input',{
            'id': place.id,
            'type':'checkbox',
            'onclick': 'checkInfo(this.id, this.checked)'
        }).inject(checktd);
    });
}

function getGeodata(entity) {
    var geodata = {};    
    var feature = {};
    var pointsArray = [];
    
    geodata.type = "FeatureCollection";
    geodata.features = [];
    
    feature.type = "Feature";
    feature.properties = {};
    feature.geometry = {};
    feature.geometry.type = "Polygon";
    feature.geometry.coordinates = [];

    entity.polygon.each(function (point) {
       var arrayPoint = [];
       arrayPoint.push(point.x);
       arrayPoint.push(point.y);
       pointsArray.push(arrayPoint);
    });

    feature.geometry.coordinates.push(pointsArray);

    geodata.features.push(feature);

    return geodata;
}

function toPlace(wikimapiaPlace, zoneid, zoneExtendedString) {
    var place = {};
    place.id = wikimapiaPlace.id;
    var name = wikimapiaPlace.titleForTiles.es != null ? wikimapiaPlace.titleForTiles.es : wikimapiaPlace.titleSuperArray.title;
    place.name = name.replace(/ /g, '_').toLowerCase();
    place.description = wikimapiaPlace.description;
    //address : String, //Domicilio alfanumérico  --  OPCIONAL
    place.links = [];
    place.links.push(wikimapiaPlace.url);
    //image : String, //URL de una imagen del lugar  --  OPCIONAL
    place.zone = zoneid;
    //type : String, //Debe ser el nombre de un tipo de lugar existente
    place.categories = [];
    if (typeof(wikimapiaPlace.tags) != 'undefined' && wikimapiaPlace.tags != null) {
        wikimapiaPlace.tags.each(function (tag) {
            place.categories.push(tag.title);
        });
    }
    place.state = 'generated';
    //geoData: String, //Debe ser un id de un dato geográfico existente
    //parent : String,
    place.extendedString = place.name + ', ' + zoneExtendedString;
    place.created = {
        creationDate: new Date(),
        createdBy: 1
    };
    place.modified = {
        creationDate: new Date(),
        createdBy: 1
    };
    return place;
}

function toZone(wikimapiaPlace, parent, type) {
    var zone = {};
    zone.id =  wikimapiaPlace.id;
    var name = wikimapiaPlace.titleForTiles.es != null ? wikimapiaPlace.titleForTiles.es : wikimapiaPlace.titleSuperArray.title;
    zone.name = name.replace(/ /g, '_').toLowerCase();
    zone.parent = parent;
    zone.type = type;
    zone.state = 'generated';
    //geoData: String, //Debe ser un id de un dato geográfico existente
    //extendedString : { type: String, unique: true }
    return zone;
}

function refreshList(id) {
    if ($('type_' + id).value == 'New Zone') {
        $('zoneType_' + id).setStyle('display', 'inline');
    } else {
        $('zoneType_' + id).setStyle('display', 'none');
    }
}

function searchAndZoom(event, field) {
    switch(event.keyCode){
        case 13:
            socket.emit('googleMapSearch', field.get('value'), function(response) {
                if (typeof(response) != 'undefined' && response != null && typeof(response.results) != 'undefined' && response.results != null && response.results.length > 0) {
                    var result = response.results[0];
                    if (typeof(result.geometry) != 'undefined' && result.geometry != null && typeof(result.geometry.viewport) != 'undefined' && result.geometry.viewport != null) {
                        var googleBounds = result.geometry.viewport;
                        var southwest = new OpenLayers.LonLat(googleBounds.southwest.lng, googleBounds.southwest.lat).transform(new OpenLayers.Projection("EPSG:4326"), new OpenLayers.Projection("EPSG:900913"));
                        var northeast = new OpenLayers.LonLat(googleBounds.northeast.lng, googleBounds.northeast.lat).transform(new OpenLayers.Projection("EPSG:4326"), new OpenLayers.Projection("EPSG:900913"));
                        var bounds = new OpenLayers.Bounds(southwest.lon, southwest.lat, northeast.lon, northeast.lat);
                        map.zoomToExtent(bounds);
                    }
                }
            });
    }
}

