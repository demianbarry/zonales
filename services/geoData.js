var mongoose = require('mongoose'),
    Schema = mongoose.Schema;
var errors = require('../errors/errors');
var baseService = require('./baseService');

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

featureSchema.index({'geometry.coordinates': '2d'});

//Esquema JSON para las locaciones geográficas
var geoSchema = new Schema(
		{
		    type : {
		    	type: String, 
		    	enum: ['FeatureCollection'], 
		    	default: 'FeatureCollection'
		    },
		    features : [featureSchema],
		    id : { type: String, unique: true }
		}
);


//Conecto con la DB y seteo el Model (TODO: Ver tema de la conexión para unificarla para todos los models)
mongoose.connect('mongodb://localhost/crawl');
var geo = mongoose.model('geoData', geoSchema);

//Retorna un conjunto de datos geográficos de acuerdo a los filtros utilizados
module.exports.get = function get(filters, callback) {
	return baseService.get(geo, filters, callback);
}

//Crea un nuevo dato geográfico
module.exports.set = function set(geoData, callback) {
	return baseService.set(geo, geoData, callback);
}

//Actualiza un dato geográfico existente (búsqueda por ID)
module.exports.update = function update(id, data, callback) {
	return baseService.update(geo, 'id', id, data, callback);
}

//Elimina una zona existente (búsqueda por ID) 
module.exports.remove = function remove(id, callback) {
	return baseService.remove(geo, 'id', id, callback);	
}
