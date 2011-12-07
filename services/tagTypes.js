var mongoose = require('mongoose'),
    Schema = mongoose.Schema;
var errors = require('../errors/errors');

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
module.exports.getAll = function getAll(callback) {
	// Make sure a callback is defined.
	callback = (callback || noop);
	
	try {
		tagTypes.find({}, ['name'], function(err, docs) {
		  if (err) {
			  console.log('Error obteniendo tipos de tags --> ' + err);
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

//Retorna un conjunto de tipos de tags de acuerdo a los filtros utilizados 
module.exports.get = function get(filters, callback) {
	// Make sure a callback is defined.
	callback = (callback || noop);
	
	try {
		var filtros = JSON.parse(filters);
		tagTypes.find(filtros, function(err, docs) {
		  if (err) {
		  	  console.log('Error obteniendo tipos de tags --> ' + err);
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


//Retorna un conjunto de tipos de tags con nombre similar al parámetro
module.exports.getLikeName = function getLikeName(name, callback) {
	// Make sure a callback is defined.
	callback = (callback || noop);

	try {
		var myregex = RegExp(name);
		tagTypes.find({"name": myregex}, function(err, docs) {
		  if (err) {
			  console.log('Error obteniendo tipos de tags --> ' + err);
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

//Crea un nuevo tipo de tag
module.exports.set = function set(tagType, callback) {
	// Make sure a callback is defined.
	callback = (callback || noop);

	try {
		var tagType = new tagTypes(JSON.parse(tagType));
		tagType.save(function(err) {
		  if (err) {
			  console.log('Error guardando el tipo de tag --> ' + err);
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


//Actualiza un tipo de tag existente (búsqueda por nombre)
module.exports.update = function update(id, data, callback) {
	// Make sure a callback is defined.
	callback = (callback || noop);
	
	try {
		var oid = JSON.parse('{"id":"' + id + '"}');
		var odata = JSON.parse(data);
		tagTypes.update(oid, odata, function(err) {
			if (err) {
				  console.log('Error actualizando el tipo de tag --> ' + err);
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

//Elimina un tipo de tag existente (búsqueda por Nombre)
module.exports.remove = function remove(id, callback) {
	// Make sure a callback is defined.
	callback = (callback || noop);
	
	try {
		var oid = JSON.parse('{"id":"' + id + '"}');
		tagTypes.findOne(oid, function(err, tagType) {
		  if (err) {
			  console.log('Error eliminando el tipo de tag --> ' + err);
			  throw errors.apiError;
		  }
		  tagType.remove(function(err) {
			  if (err) {
				  console.log('Error eliminando el tipo de tag --> ' + err);
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

