var mongoose = require('mongoose'),
    Schema = mongoose.Schema;
var errors = require('../errors/errors');
var baseService = require('./baseService');
var incIdsService = require('./incIds');

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
			if (response.cod == 100) {
				incIdsService.incrementId("geodata", function() {
					console.log("ID de geodata Incrementado");
				});
			}
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
