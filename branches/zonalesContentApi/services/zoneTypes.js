var mongoose = require('mongoose'),
    Schema = mongoose.Schema;
var errors = require('../errors/errors');
var baseService = require('./baseService');

//Esquema JSON para los tipos de zonas
var zoneTypeSchema = new Schema(
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
var zoneTypes = mongoose.model('zoneTypes', zoneTypeSchema);

//Retorna nombre de todos los tipos de zonas
module.exports.getAll = function getAll(short, callback) {
	return baseService.getAll(zoneTypes, short, '["name"]', callback);
}

//Retorna un conjunto de tipos de zonas de acuerdo a los filtros utilizados 
module.exports.get = function get(filters, callback) {
	return baseService.get(zoneTypes, filters, callback);
}


//Retorna un conjunto de tipos de zonas con nombre similar al parámetro
module.exports.getLikeName = function getLikeName(name, callback) {
	return baseService.getLikeName(zoneTypes, name, callback);
}

//Retorna un conjunto de tipos de zonas con nombre similar al parámetro
module.exports.searchZoneTypes = function searchZoneTypes(filters, callback) {
	return baseService.searchData(zoneTypes, filters, callback);
}

//Crea un nuevo tipo de zona
module.exports.set = function set(zoneType, callback) {
	return baseService.set(zoneTypes, zoneType, callback);
}


//Actualiza un tipo de zona existente (búsqueda por nombre)
module.exports.update = function update(name, data, callback) {
	return baseService.update(zoneTypes, 'name', name, data, callback);
}

//Elimina un tipo de zona existente (búsqueda por Nombre)
module.exports.remove = function remove(name, callback) {
	return baseService.remove(zoneTypes, 'name', name, callback);
}
