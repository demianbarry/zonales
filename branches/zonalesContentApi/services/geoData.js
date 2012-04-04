var mongoose = require('mongoose'),
    Schema = mongoose.Schema;
var errors = require('../errors/errors');
var baseService = require('./baseService');
var incIdsService = require('./incIds');
var zoneService = require('./zones');
var placeService = require('./places');

var featureSchema = new Schema(
	{
    	type : {
	    	type: String, 
	    	enum: ['Feature'], 
	    	default: 'Feature'
	    },
      properties : {},
      geometry : {  //Un feature contiene un objeto geométrico
        	type : {
		    	type: String, 
		    	enum: ['Point', 'LineString', 'Polygon', 'Multipoint', 'MultiLineString'], 
		    	default: 'Polygon'
		   },
         coordinates: {}
      }
  }	
);

//Esquema JSON para las locaciones geográficas
var geoSchema = new Schema(
		{
		    type : {
		    	type: String, 
		    	enum: ['FeatureCollection'], 
		    	default: 'FeatureCollection'
		    },
		    features : [featureSchema],
		    id : { type: String, unique: true },
		    geospatial: {
	      		point:[Number],
	      		multipoint: [
	      			{ point: [Number] }
	      		]
	      	}
		}
);

geoSchema.index({'geospatial.point': '2d'});
geoSchema.index({'geospatial.multipoint.point': '2d'});


//Conecto con la DB y seteo el Model (TODO: Ver tema de la conexión para unificarla para todos los models)
mongoose.connect('mongodb://localhost/crawl');
var geo = mongoose.model('geoData', geoSchema);

//Retorna un conjunto de datos geográficos de acuerdo a los filtros utilizados
module.exports.get = function get(filters, callback) {
	return baseService.get(geo, filters, callback);
}

//Crea un nuevo dato geográfico
module.exports.set = function set(geoData, callback) {	
	copyGeometricalDataToGeospatial(geoData);
	incIdsService.getId("geodata", function(id) {
		//console.log("------>>>>>>----->>>>> NextId: " + id);
		geoData.id = id;
		baseService.set(geo, geoData, function(response) {
			response.id = id;
			callback(response);
			return(this);
		});
	})
}

//Actualiza un dato geográfico existente (búsqueda por ID)
module.exports.update = function update(id, data, callback) {
	copyGeometricalDataToGeospatial(data);
	return baseService.update(geo, 'id', id, data, callback);
}

//Elimina una zona existente (búsqueda por ID) 
module.exports.remove = function remove(id, callback) {
	return baseService.remove(geo, 'id', id, callback);	
}

module.exports.drawChildren = function drawChildren(extendedString, client) {
	console.log('================================================================================');
	console.log('Cadena: ' + extendedString);
	placeService.getPlaceByExtendedString(extendedString, function(place) {
		console.log('PLACE: ' + JSON.stringify(place));
		if (typeof(place) != 'undefined' && place != null) {
			//recupero los hijos
			placeService.getPlaceByFilters({parent:place.id}, function(places) {
				places.forEach(function(place) {
					var data = {};
					data.data = place;
					if (typeof(place.geoData) != 'undefined' && place.geoData != null) {
						baseService.get(geo, {id: place.geoData}, function(geoData) {
							if (typeof(geoData) != 'undefined' && geoData != null && typeof(geoData[0]) != 'undefined' && geoData[0] != null) {
								delete geoData[0].geospatial;
								data.geoData = geoData[0];
								client.emit('drawGeoData', data);
							}
						});
					}
				});
			});
		} else {
			zoneService.getZoneByExtendedString(extendedString, function(zone) {
				console.log('ZONE: ' + JSON.stringify(zone));
				if (typeof(zone) != 'undefined' && zone != null) {
					//recupero los hijos
					zoneService.get({parent:zone.id}, function(zones) {
						zones.forEach(function(zone) {
							var data = {};
							data.data = zone;
							if (typeof(zone.geoData) != 'undefined' && zone.geoData != null) {
								baseService.get(geo, {id: zone.geoData}, function(geoData) {
									if (typeof(geoData) != 'undefined' && geoData != null && typeof(geoData[0]) != 'undefined' && geoData[0] != null) {
										delete geoData[0].geospatial;
										data.geoData = geoData[0];
										client.emit('drawGeoData', data);
									}
								});
							}
						});
					});
					placeService.get({zone:zone.id}, function(places) {
						places.forEach(function(place) {
							var data = {};
							data.data = place;
							if (typeof(place.geoData) != 'undefined' && place.geoData != null) {
								baseService.get(geo, {id: place.geoData}, function(geoData) {
									if (typeof(geoData) != 'undefined' && geoData != null && typeof(geoData[0]) != 'undefined' && geoData[0] != null) {
										delete geoData[0].geospatial;
										data.geoData = geoData[0];
										client.emit('drawGeoData', data);
									}
								});
							}
						});
					});
				}
			});
		}
	});	
}

module.exports.getGeoDataByZoneExtendedString = function getGeoDataByZoneExtendedString(extendedString, callback) {
	//console.log('ZONE GEODATA. EXTENDED STRING: ' + extendedString);
	zoneService.getZoneByExtendedString(extendedString, function(zone) {
		//console.log('ZONE GEODATA. ZONE: ' + JSON.stringify(zone));
		if (typeof(zone) != 'undefined' && zone != null) {
			baseService.get(geo, {id: zone.geoData}, function(geoData) {
				if (typeof(geoData) != 'undefined' && geoData != null && typeof(geoData[0]) != 'undefined' && geoData[0] != null) {
					var data = {};
					delete geoData[0].geospatial;
					data.geoData = geoData[0];
					data.extendedString = extendedString;
					//console.log('ZONE GEODATA: ' + JSON.stringify(data));
					callback(data);
				}
			});
		}
	});
}

module.exports.getGeoDataById = function getGeoDataById(id, callback) {
	this.get({id:id}, function(geoDatas) {
		if (typeof(geoDatas) != 'undefined' && geoDatas != null && typeof(geoDatas[0]) != 'undefined' && geoDatas[0] != null && callback) {
			callback(geoDatas[0]);
		}
	});
}

function copyGeometricalDataToGeospatial(geoData) {
	if (typeof(geoData.features) != 'undefined' && geoData.features != null) {
		geoData.geospatial = {};
		geoData.features.forEach(function (feature) {
			if (typeof(feature.geometry.type) != 'undefined' && feature.geometry.type == 'Point') {
				geoData.geospatial.point = feature.geometry.coordinates;
			}
			if (typeof(feature.geometry.type) != 'undefined' && feature.geometry.type == 'Polygon') {
				geoData.geospatial.multipoint = [];
				feature.geometry.coordinates[0].forEach(function (point) {
					geoData.geospatial.multipoint.push({point: point});
				});
			}
		});
	}
	return geoData;
}
