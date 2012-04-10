var mongoose = require('mongoose'),
    Schema = mongoose.Schema;
var errors = require('../errors/errors');
var baseService = require('./baseService');

//Esquema JSON para los tags
var incIdSchema = new Schema(
	{
	    key : { type: String, unique: true }, //Este campo debe ser único. No es igual al ID que le pone MongoDB
	    nextId : Number,
	}
);

//Conecto con la DB y seteo el Model (TODO: Ver tema de la conexión para unificarla para todos los models)
mongoose.connect('mongodb://localhost/crawl');
var incIds = mongoose.model('incIds', incIdSchema);
//module.exports.zone = zones;

module.exports.getId = function getId(model, callback) {
	this.get({key:model}, function(incModel) {
		if(typeof(incModel) != 'undefined' && incModel != null && typeof(incModel[0]) != 'undefined' && incModel[0] != null) {
				incrementId(model, function() {
					console.log("Indice de " + model + " incrementado. Nuevo valor: " + incModel[0].nextId + 1);
				});
				callback(incModel[0].nextId);
			} else {
				callback(null);
			}
		return(this);
	})
}


function incrementId(model, callback) {
	try {
		incIds.update({key:model}, {$inc: {nextId : 1}}, {upsert: true}, function(err) {
			if (err) {
				  console.log('Error actualizando el dato --> ' + err);
				  throw errors.apiError;
			  }
			  callback("OK");
			  return(this);
		});
	} catch (err) {
		console.log('Error --> ' + err);
		throw errors.apiError;
	}
}

//Retorna id y nombre de todas los tags
module.exports.getAll = function getAll(short, callback) {
	return baseService.getAll(incIds, short, '["id", "name"]', callback);
}

//Retorna un conjunto de tags de acuerdo a los filtros utilizados 
module.exports.get = function get(filters, callback) {
	return baseService.get(incIds, filters, callback);
}

//Retorna un conjunto de tags con nombre similar al parámetro
module.exports.searchTags = function searchTags(filters, callback) {
	return baseService.getLikeName(incIds, name, callback);
}

//Retorna un conjunto de tags con nombre similar al parámetro
module.exports.searchTags = function searchTags(filters, callback) {
	return baseService.searchData(incIds, filters, callback);
}

//Crea un nuevo tag
module.exports.set = function set(incId, callback) {
	return baseService.set(incIds, incId, callback);
}

//Actualiza un tag existente (búsqueda por ID)
module.exports.update = function update(id, data, callback) {
	return baseService.update(incIds, 'key', id, data, callback);
}

//Elimina un tag existente (búsqueda por ID) 
module.exports.remove = function remove(id, callback) {
	return baseService.remove(incIds, 'key', id, callback);
}

