var mongoose = require('mongoose'),
    Schema = mongoose.Schema;
var errors = require('../errors/errors');
var baseService = require('./baseService');
var zoneService = require('./zones');
var incIdsService = require('./incIds');

//Esquema JSON para las zonas
var placeSchema = new Schema(
	{
	    id : { type: String, unique: true }, //Este campo debe ser único. No es igual al ID que le pone MongoDB
	    name : String,
	    description : String, //Descripción del lugar  --  OPCIONAL
	    address : String, //Domicilio alfanumérico  --  OPCIONAL
	    links : [String],  //Array de links a sitios relacionados con la zona  --  OPCIONAL
	    image : String, //URL de una imagen del lugar  --  OPCIONAL
	    zone : String, //ID de la zona a la que pertenece el lugar
	    type : String, //Debe ser el nombre de un tipo de lugar existente
	    typeTrash : String, //Tipo de lugar original, obtenido desde el extractor de lugares  --  OPCIONAL
	    state : {
	    	type:String, 
	    	enum:['generated', 'published', 'unpublished', 'void'], 
	    	default: 'generated'
	    },
	    geoData: String, //Debe ser un id de un dato geográfico existente
	    parent : String,
	    extendedString : { type: String, unique: true}
	}
);

//Conecto con la DB y seteo el Model (TODO: Ver tema de la conexión para unificarla para todos los models)
mongoose.connect('mongodb://localhost/crawl');
var places = mongoose.model('places', placeSchema);

//Retorna id y nombre de todas los places
module.exports.getAll = function getAll(short, callback) {
	return baseService.getAll(places, short, '["id", "name"]', callback);
}

//Retorna un conjunto de places de acuerdo a los filtros utilizados 
module.exports.get = function get(filters, callback) {
	return baseService.get(places, filters, callback);
}

//Retorna un conjunto de places con nombre similar al parámetro
module.exports.getLikeName = function getLikeName(name, callback) {
	return baseService.getLikeName(places, name, callback);
}

//Retorna un conjunto de places con nombre similar al parámetro
module.exports.searchPlaces = function searchPlaces(filters, callback) {
	return baseService.searchData(places, filters, callback);
}

//Crea un nuevo place
module.exports.set = function set(place, callback) {
	incIdsService.getId("places", function(id) {
		//console.log("------>>>>>>----->>>>> NextId: " + id);
		place.id = id;
		baseService.set(places, place, function(response) {
			if (response.cod == 100) {
				incIdsService.incrementId("places", function() {
					console.log("ID de places Incrementado");
				});
			}
			callback(response);
			return(this);
		});
	})
}

//Actualiza un place existente (búsqueda por ID)
module.exports.update = function update(id, data, callback) {
	return baseService.update(places, 'id', id, data, callback);
}

//Elimina un place existente (búsqueda por ID) 
module.exports.remove = function remove(id, callback) {
	return baseService.remove(places, 'id', id, callback);
}

module.exports.getPlacesByZone = function getPlacesByZone(zoneid, callback) {
	this.get({zone:zoneid}, function(places) {
		if (typeof(places) != 'undefined' && places != null && callback) {
			callback(places);
		}
	});
}

module.exports.getZone = function getZone(id, callback) {
	this.get({id:id}, function (place) {
		if (typeof(place) != 'undefined' && place != null && typeof(place[0]) != 'undefined' && place[0] != null) {
			zoneService.get({id:place[0].zone}, function (zone) {
				if (typeof(zone) != 'undefined' && zone != null && callback) {
					callback(zone);
				}
			});
		}
	});
}

module.exports.getChildrens = function getChildrens(id, callback) {
	this.get({parent:id}, function(places) {
		if (typeof(places) != 'undefined' && places != null && callback) {
			callback(places);
		}
	});
}

module.exports.getParent = function getParent(id, callback) {
	this.get({id:id}, function (place) {
		if (typeof(place) != 'undefined' && place != null && typeof(place[0]) != 'undefined' && place[0] != null) {
			baseService.get(places,{id:place[0].parent}, function (parent) {
				if (typeof(parent) != 'undefined' && parent != null && callback) {
					callback(parent);
				}
			});
		}
	});
}

module.exports.getExtendedString = function getExtendedString(id, callback) {
	this.get({id:id}, function(place) {
		if (typeof(place) != 'undefined' && place != null && typeof(place[0]) != 'undefined' && place[0] != null) {
			callback(place.extendedString);
		}
	});
}

module.exports.updateExtendedString = function(place, zone){
    updateExtendedString(place, zone);
};

function updateExtendedString(place, zone){
    place.extendedString = place.name;
    if (zone)
        place.extendedString += ", "+zone.extendedString;

    delete place._id;
    baseService.update(places, 'id', place.id, place, function(){});
    return place.extendedString;
}
