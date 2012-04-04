var htmlparser = require("htmlparser");
var zProxyService = require('../services/zProxy');
var zoneService = require('../services/zones');
var zoneLogService = require('../services/zoneLog');
var utilsService = require('../services/utils');
var geoDataService = require('../services/geoData');
var wikimapiaCategoriesService = require('../services/wikimapiaCategories');
var async = require("async");

var noCityCategories = ["city", "partido", "municipality", "second level administration", "capital city of state/province/region", "district", "region", "commune - administrative division"];
var count;
var category;


module.exports.fetchZones = function fetchZones(westLimit, southLimit, eastLimit, northLimit, lonDelta, latDelta, cnt, cat) {
	count = cnt;
	category = cat;
	var west = westLimit;
	var north = northLimit;
	var east = westLimit + lonDelta;
	var south = northLimit - latDelta;

	async.whilst(
	    function () { return south >= (southLimit - latDelta); },
	    function (callback) {
	    	fetchBox(west, south, east, north, function(response) {
				west = east;
				east += lonDelta;
				if (east > eastLimit) {
					west = westLimit;
					east = west + lonDelta;
					north = south;
					south -= latDelta;
				}	
				callback();
			});
	    },
	    function (err) {
	        if (err)
				console.log("Error en async.whilst: " + err);
	    }
	);
}


function fetchBox(west, south, east, north, callback) {
	var bbox = setBbox(west, south, east, north);
	var page = 1;
	console.log("Buscando página 1 en bbox: " + bbox);
	zProxyService.wikimapiaBboxSearch(bbox, category, count, page, function(data) {
		console.log("Llamando a fetchObjects...");
		fetchObjects(data.folder, function(response) {
			if (response == 'ok') {
				var cantPages = Math.floor(data.found / 100) + 1;
				if (page > cantPages) {
					while (page < cantPages) {
						page++;
						console.log("Buscando página " + page + " en bbox: " + bbox);
						zProxyService.wikimapiaBboxSearch(bbox, category, count, page, function(data) {
							fetchObjects(data.folder, function(response) {
								if (response == 'ok') {
									callback();
								}
							});
						});
					}
				} else {
					callback();
				}
			} else {
				callback();
			}
		});
	});
}


function setBbox(west, south, east, north) {
	return '' + west + ',' + south + ',' + east + ',' + north;
}


function fetchObjects(wikipamiaFolder, callback) {
	async.series([
			function (callbackSeries) {
				if (typeof(wikipamiaFolder) != 'undefined' && wikipamiaFolder != null && wikipamiaFolder.length > 0) {
					console.log("Extrayendo objetos: " + JSON.stringify(wikipamiaFolder));
					async.forEachSeries(wikipamiaFolder, 
						function (wikimapiaPlace, callbackForEachSeries) {
							console.log(JSON.stringify(wikimapiaPlace));
							zProxyService.wikimapiaGetObject(wikimapiaPlace.id, function(place) {
								console.log("Obteniendo objeto " + place.id);
								fetchZone(place);
								callbackForEachSeries();
							});				
						}, 
						function(err) {
							if (err)
								console.log("Error en forEachSeries: " + err);
							callbackSeries();
						}
					);
				} else {
					callbackSeries();
				}
			}
		],
		function(err, result) {
			if (err)
				console.log("Error en forEachSeries: " + err);
			callback('ok');
		}
	);
}

function fetchZone(wikimapiaPlace) {
	var zoneExtendedString	= getExtendedString(wikimapiaPlace, false);
	console.log('Wikimapia place title: ' + (wikimapiaPlace.titleForTiles.es != null ? wikimapiaPlace.titleForTiles.es : wikimapiaPlace.titleSuperArray.title));
	console.log('Buscando Zona: ' + zoneExtendedString);

	//Busco zona sin eliminar acentos			
	zoneService.getZoneByExtendedString(zoneExtendedString, function(zone) {
		if (zone) {
			addGeoDataToZone(zone, getGeodata(wikimapiaPlace));
		} else {
			//Busco nuevamente la zona, pero sacandole los acentos a la cadena extendida
			zoneService.getZoneByExtendedString(utilsService.accentsTidy(zoneExtendedString), function(zone) {
				if (zone) {
					addGeoDataToZone(zone, getGeodata(wikimapiaPlace));
				} else {
					addInZoneLog(wikimapiaPlace);
				}
			});
		}
	});
}


function addGeoDataToZone(zoneObj, geodata) {
	if (typeof(zoneObj.geoData) == 'undefined' || !zoneObj.geoData || zoneObj.geoData == '') { //Si la zona no está previamente georreferenciada
		zoneService.addGeoDataToZone(zoneObj, geodata, function(response) {
	        console.log('Dato geográfico agregado a la zona ' + zoneObj.extendedString);
	    });
	}
}


function addInZoneLog(wikimapiaPlace) {
	var parentExtendedString = getExtendedString(wikimapiaPlace, true);

	//Busco el padre
	zoneService.getZoneByExtendedString(parentExtendedString, function(parent) {
		//Si a cadena del padre da nulo o vació no es Argentina
		if (parent) {
			var zoneLog = toZone(wikimapiaPlace, parent, category); //Por ahora en el log dejo la categoría de wikimapia, luego del mapero, podría poner la cateogría zonales.
			var geodata = getGeodata(wikimapiaPlace);
			geoDataService.set(geodata, function(response) {
	            zoneLog.geoData = response.id;
	            zoneLogService.upsert(zoneLog.id, zoneLog, function(response) {
					console.log('Zona agregada al ZoneLog: ' + zoneLog.extendedString + ', ' + (parent ? parent.extendedString : ''));
				}); 
	        });
	    }
	});		
}


//Convierte un objeto de Wikimapia en una Zona de Zonales
function toZone(wikimapiaPlace, parent, type) {
    var zone = {};
    zone.id =  wikimapiaPlace.id;
    var name = wikimapiaPlace.titleForTiles.es != null ? wikimapiaPlace.titleForTiles.es : wikimapiaPlace.titleSuperArray.title;
    zone.name = name.replace(/, /g, ',+').replace(/ /g, '_').replace(/,\+/g, ', ').toLowerCase();
    zone.parent = parent;
    zone.type = type;
    zone.state = 'generated';
    //geoData: String, //Debe ser un id de un dato geográfico existente
    zone.extendedString = zone.name;
    return zone;
}


//Arma posible cadena extendida desde el place de Wikimapia
function getExtendedString(wikimapiaPlace, parent) {
	var parentExtendedString;
	var zoneExtendedString;
	var name = wikimapiaPlace.titleForTiles.es != null ? wikimapiaPlace.titleForTiles.es : wikimapiaPlace.titleSuperArray.title;

	//Armo cadenas extendidas
	if (isInclude(noCityCategories, category))
		parentExtendedString = wikimapiaPlace.location.state.replace(/ /g, '_').toLowerCase() + ', ' + wikimapiaPlace.location.country.replace(/ /g, '_').toLowerCase();
	else
		parentExtendedString = wikimapiaPlace.location.place.replace(/ /g, '_').toLowerCase() + ', ' + wikimapiaPlace.location.state.replace(/ /g, '_').toLowerCase() + ', ' + wikimapiaPlace.location.country.replace(/ /g, '_').toLowerCase();

	zoneExtendedString = name.replace(/ /g, '_').toLowerCase() + ', ' + parentExtendedString;

	if (parent)
		return parentExtendedString;
	else 
		return zoneExtendedString;
}


//Obtiene Geodata en formato Zonales desde un objeto de Wikimapia
function getGeodata(wikimapiaPlace) {
	/*console.log("------------------------ GET GEO DATA ------------------------------");
	console.log("------------------------ WIKIMAPIA POLYGON ------------------------------");
	console.log(JSON.stringify(wikimapiaPlace.polygon));
	console.log("------------------------ END WIKIMAPIA POLYGON ------------------------------");*/
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

    wikimapiaPlace.polygon.forEach(function (point) {
       var arrayPoint = [];
       arrayPoint.push(point.x);
       arrayPoint.push(point.y);
       pointsArray.push(arrayPoint);
    });

    feature.geometry.coordinates.push(pointsArray);

    geodata.features.push(feature);

    /*console.log("------------------------ ZONALES GEODATA ------------------------------");
	console.log(JSON.stringify(geodata));
	console.log("------------------------ END ZONALES GEODATA ------------------------------");*/

    return geodata;
}


//Utilidad para buscar elemento en Array, pasar luego a Utils si se considera correcto
function isInclude(arr, obj) {
    return (arr.indexOf(obj) != -1);
}