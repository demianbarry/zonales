var mongoose = require('mongoose'),
    Schema = mongoose.Schema;
var errors = require('../errors/errors');
var baseService = require('./baseService');

//Esquema JSON para los tipos de zonas
var placeTypeSchema = new Schema(
	{
	    name : { type: String, unique: true },  //Este campo debe ser único
	    parents : [String],
	    description : [String], //Descripción del tipo de lugar
	    state : {
	    	type:String, 
	    	enum:['generated', 'published', 'unpublished', 'void'], 
	    	default: 'generated'
	    }
	}
);

//Conecto con la DB y seteo el Model (TODO: Ver tema de la conexión para unificarla para todos los models)
mongoose.connect('mongodb://localhost/crawl');
var placeTypes = mongoose.model('placeTypes', placeTypeSchema);

//Retorna id y nombre de todas los tipos de places
module.exports.getAll = function getAll(short, callback) {
	return baseService.getAll(placeTypes, short, '["name"]', callback);
}

//Retorna un conjunto de tipos de places de acuerdo a los filtros utilizados 
module.exports.get = function get(filters, callback) {
	return baseService.get(placeTypes, filters, callback);
}

//Retorna un conjunto de tipos de places con nombre similar al parámetro
module.exports.getLikeName = function getLikeName(name, callback) {
	return baseService.getLikeName(placeTypes, name, callback);
}

//Retorna un conjunto de tipos de places con nombre similar al parámetro
module.exports.searchPlaceTypes = function searchPlaceTypes(filters, callback) {
	return baseService.searchData(placeTypes, filters, callback);
}

//Crea un nuevo tipo de place
module.exports.set = function set(placeType, callback) {
	return baseService.set(placeTypes, placeType, callback);
}

//Actualiza un tipo de place existente (búsqueda por ID)
module.exports.update = function update(name, data, callback) {
	return baseService.update(placeTypes, 'name', name, data, callback);
}

//Elimina un tipo de place existente (búsqueda por ID) 
module.exports.remove = function remove(name, callback) {
	return baseService.remove(placeTypes, 'name', name, callback);
}


