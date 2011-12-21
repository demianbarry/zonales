var mongoose = require('mongoose'),
    Schema = mongoose.Schema;
var errors = require('../errors/errors');
var baseService = require('./baseService');

//Esquema JSON para los tipos de tags
var tagTypeSchema = new Schema(
	{
	    name : { type: String, unique: true },  //Este campo debe ser único
	    parents : [String], //Nombres de los tipos que son padre del actual
	    state : {
	    	type: String, 
	    	enum:['generated', 'published', 'unpublished', 'void'], 
	    	default: 'generated'
	    }
	}
);

//Conecto con la DB y seteo el Model (TODO: Ver tema de la conexión para unificarla para todos los models)
mongoose.connect('mongodb://localhost/crawl');
var tagTypes = mongoose.model('tagTypes', tagTypeSchema);
//module.exports.zoneType = zoneTypes;

//Retorna nombre de todos los tipos de tags
module.exports.getAll = function getAll(short, callback) {
	return baseService.getAll(tagTypes, short, '["name"]', callback);
}
//Retorna un conjunto de tipos de tags de acuerdo a los filtros utilizados 
module.exports.get = function get(filters, callback) {
	return baseService.get(tagTypes, filters, callback);
}


//Retorna un conjunto de tipos de tags con nombre similar al parámetro
module.exports.getLikeName = function getLikeName(name, callback) {
	return baseService.getLikeName(tagTypes, name, callback);
}		
//Retorna un conjunto de tipos de tags con nombre similar al parámetro
module.exports.searchTagTypes = function searchTagTypes(filters, callback) {
	return baseService.searchData(tagTypes, filters, callback);
}

//Crea un nuevo tipo de tag
module.exports.set = function set(tagType, callback) {
	return baseService.set(tagTypes, tagType, callback);
}


//Actualiza un tipo de tag existente (búsqueda por nombre)
module.exports.update = function update(name, data, callback) {
	return baseService.update(tagTypes, 'name', name, data, callback);
}

//Elimina un tipo de tag existente (búsqueda por Nombre)
module.exports.remove = function remove(name, callback) {
	return baseService.remove(tagTypes, 'name', name, callback);
}

