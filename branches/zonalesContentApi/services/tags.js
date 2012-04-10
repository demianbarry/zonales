var mongoose = require('mongoose'),
    Schema = mongoose.Schema;
var errors = require('../errors/errors');
var baseService = require('./baseService');
var incIdsService = require('./incIds');

//Esquema JSON para los tags
var tagSchema = new Schema(
	{
	    id : { type: String, unique: true }, //Este campo debe ser único. No es igual al ID que le pone MongoDB
	    name : String,
	    parent : String, //ID del tag padre
	    type : String, //Debe ser el nombre de un tipo de tag existente (tagType)
	    state : {
	    	type: String, 
	    	enum:['generated', 'published', 'unpublished', 'void'], 
	    	default: 'generated'
	    } ////Generado, publicado, despublicado, anulado
	}
);

//Conecto con la DB y seteo el Model (TODO: Ver tema de la conexión para unificarla para todos los models)
mongoose.connect('mongodb://localhost/crawl');
var tags = mongoose.model('tags', tagSchema);
//module.exports.zone = zones;

//Retorna id y nombre de todas los tags
module.exports.getAll = function getAll(short, callback) {
	return baseService.getAll(tags, short, '["id", "name"]', callback);
}

//Retorna un conjunto de tags de acuerdo a los filtros utilizados 
module.exports.get = function get(filters, callback) {
	return baseService.get(tags, filters, callback);
}

//Retorna un conjunto de tags con nombre similar al parámetro
module.exports.searchTags = function searchTags(filters, callback) {
	return baseService.getLikeName(tags, name, callback);
}

//Retorna un conjunto de tags con nombre similar al parámetro
module.exports.searchTags = function searchTags(filters, callback) {
	return baseService.searchData(tags, filters, callback);
}

//Crea un nuevo tag
module.exports.set = function set(tag, callback) {
	incIdsService.getId("tags", function(id) {
		//console.log("------>>>>>>----->>>>> NextId: " + id);
		tag.id = id;
		baseService.set(tags, tag, function(response) {
			if (response.cod == 100) {
				incIdsService.incrementId("tags", function() {
					console.log("ID de tags Incrementado");
				});
			}
			callback(response);
			return(this);
		});
	})
}

//Actualiza un tag existente (búsqueda por ID)
module.exports.update = function update(id, data, callback) {
	return baseService.update(tags, 'id', id, data, callback);
}

//Elimina un tag existente (búsqueda por ID) 
module.exports.remove = function remove(id, callback) {
	return baseService.remove(tags, 'id', id, callback);
}

