var mongoose = require('mongoose'),
    Schema = mongoose.Schema;
var errors = require('../errors/errors');
var baseService = require('./baseService');

//Esquema JSON para los tags
var wikimapiaCategoriesSchema = new Schema(
	{
	    id : { type: Number, unique: true }, //Este campo debe ser único. No es igual al ID que le pone MongoDB
	    title : String
	}
);

//Conecto con la DB y seteo el Model (TODO: Ver tema de la conexión para unificarla para todos los models)
mongoose.connect('mongodb://localhost/crawl');
var categories = mongoose.model('wikimapiaCategories', wikimapiaCategoriesSchema);

//Retorna id y nombre de todas las categorias
module.exports.getAll = function getAll(short, callback) {
	return baseService.getAll(categories, short, '["id", "title"]', callback);
}

//Retorna un conjunto de categorias de acuerdo a los filtros utilizados 
module.exports.get = function get(filters, callback) {
	return baseService.get(categories, filters, callback);
}

//Crea una nueva categorias
module.exports.set = function set(category, callback) {
	baseService.set(categories, category, function(response) {
        callback(response);
        return(this);
    });
}

//Actualiza un tag existente (búsqueda por ID)
module.exports.update = function update(id, data, callback) {
	return baseService.update(categories, 'id', id, data, callback);
}

//Elimina un tag existente (búsqueda por ID) 
module.exports.remove = function remove(id, callback) {
	return baseService.remove(categories, 'id', id, callback);
}

module.exports.getById = function getById(id, callback) {
	this.get({id: id}, function(cats) {
		if (typeof(cats) != 'undefined' && cats != null && typeof(cats[0]) != 'undefined' && cats[0] != null && callback) {
			callback(cats[0]);
		} else {
			callback(null);
		}
	});
}

