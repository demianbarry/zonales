var mongoose = require('mongoose'),
    Schema = mongoose.Schema;
var errors = require('../errors/errors');
var geoDataModel = require('./geoData');

//Esquema JSON para las zonas
var zoneSchema = new Schema(
	{
	    id : { type: String, unique: true }, //Este campo debe ser único. No es igual al ID que le pone MongoDB
	    name : String,
	    parent : String, //ID de la zona padre
	    type : String, //Debe ser el nombre de un tipo de zona existente (zoneType)
	    state : {
	    	type:String, 
	    	enum:['generated', 'published', 'unpublished', 'void'], 
	    	default: 'generated'
	    }, ////Generado, publicado, despublicado, anulado
	    geoData: String //Debe ser un id de un dato geográfico existente
	}
);

//Conecto con la DB y seteo el Model (TODO: Ver tema de la conexión para unificarla para todos los models)
mongoose.connect('mongodb://localhost/crawl');
var zones = mongoose.model('zones', zoneSchema);
//module.exports.zone = zones;

//Retorna id y nombre de todas las zonas
module.exports.getAll = function getAll(short, callback) {
	// Make sure a callback is defined.
	callback = (callback || noop);
	
	try {
		if (short) {
			zones.find({}, ['id', 'name'], function(err, docs) {
			  if (err) {
				  console.log('Error obteniendo zones --> ' + err);
			  	  throw errors.apiError; 
			  }
			  callback(docs);
			  return(this);
		   });
		} else {
			zones.find({}, function(err, docs) {
			  if (err) {
				  console.log('Error obteniendo zones --> ' + err);
			  	  throw errors.apiError; 
			  }
			  callback(docs);
			  return(this);
		   });
		}
	} catch (err) {
		console.log('Error --> ' + err);
		throw errors.apiError;
	}
}

//Retorna un conjunto de zonas de acuerdo a los filtros utilizados 
module.exports.get = function get(filters, callback) {
	// Make sure a callback is defined.
	callback = (callback || noop);
	
	try {
		var filtros = JSON.parse(filters);
		zones.find(filtros, function(err, docs) {
		  if (err) {
		  	  console.log('Error obteniendo zonas --> ' + err);
			  throw errors.apiError;
		  }
		  callback(docs);
		  return(this);
	   });
	} catch (err) {
		console.log('Error --> ' + err);
		throw errors.apiError;
	}
}

//Retorna un conjunto de zonas con nombre similar al parámetro
module.exports.getLikeName = function getLikeName(name, callback) {
	// Make sure a callback is defined.
	callback = (callback || noop);

	try {
		var myregex = RegExp(name);
		zones.find({"name": myregex}, function(err, docs) {
		  if (err) {
			  console.log('Error obteniendo zonas --> ' + err);
			  throw errors.apiError;
		  }
		  callback(docs);
		  return(this);
	   });
	} catch (err) {
		console.log('Error --> ' + err);
		throw errors.apiError;
	}
}

//Retorna un conjunto de zonas con nombre similar al parámetro
module.exports.searchZones = function searchZones(filters, callback) {
	// Make sure a callback is defined.
	callback = (callback || noop);

	try {
		//var myregex = RegExp(name);
		filters.name = RegExp(filters.name);
		zones.find(filters, function(err, docs) { 
		  if (err) {
			  console.log('Error obteniendo zonas --> ' + err);
			  throw errors.apiError;
		  }
		  callback(docs);
		  return(this);
	   });
	} catch (err) {
		console.log('Error --> ' + err);
		throw errors.apiError;
	}
}

//Crea una nueva zona
module.exports.set = function set(zone, callback) {
	// Make sure a callback is defined.
	callback = (callback || noop);

	try {
		var ozone = new zones(JSON.parse(zone));
		ozone.save(function(err) {
		  if (err) {
			  console.log('Error guardando la zona --> ' + err);
			  throw errors.apiError;
		  }
		  callback(errors.success);
		  return(this);
	   });
	} catch (err) {
		console.log('Error --> ' + err);
		throw errors.apiError;
	}
}

//Actualiza una zona existente (búsqueda por ID)
module.exports.update = function update(id, data, callback) {
	// Make sure a callback is defined.
	callback = (callback || noop);
	
	try {
		var oid = JSON.parse('{"id":"' + id + '"}');
		var odata = JSON.parse(data);
		zones.update(oid, odata, function(err) {
			if (err) {
				  console.log('Error actualizando la zona --> ' + err);
				  throw errors.apiError;
			  }
			  callback(errors.success);
		  	  return(this);
		});
	} catch (err) {
		console.log('Error --> ' + err);
		throw errors.apiError;
	}
}

//Elimina una zona existente (búsqueda por ID) 
module.exports.remove = function remove(id, callback) {
	// Make sure a callback is defined.
	callback = (callback || noop);
	
	try {
		var oid = JSON.parse('{"id":"' + id + '"}');
		zones.findOne(oid, function(err, zone) {
		  if (err) {
			  console.log('Error eliminando la zona --> ' + err);
			  throw errors.apiError;
		  }
		  zone.remove(function(err) {
			  if (err) {
				  console.log('Error eliminando la zona --> ' + err);
				  throw errors.apiError;
			  }
			  callback(errors.success);
		  	  return(this);
		  });
	   });
	} catch (err) {
		console.log('Error --> ' + err);
		throw errors.apiError;
	}
}

