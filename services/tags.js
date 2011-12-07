var mongoose = require('mongoose'),
    Schema = mongoose.Schema;
var errors = require('../errors/errors');

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
module.exports.getAll = function getAll(callback) {
	// Make sure a callback is defined.
	callback = (callback || noop);
	
	try {
		tags.find({}, ['id', 'name'], function(err, docs) {
		  if (err) {
			  console.log('Error obteniendo tags --> ' + err);
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

//Retorna un conjunto de tags de acuerdo a los filtros utilizados 
module.exports.get = function get(filters, callback) {
	// Make sure a callback is defined.
	callback = (callback || noop);
	
	try {
		var filtros = JSON.parse(filters);
		tags.find(filtros, function(err, docs) {
		  if (err) {
		  	  console.log('Error obteniendo tags --> ' + err);
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

//Retorna un conjunto de tags con nombre similar al parámetro
module.exports.getLikeName = function getLikeName(name, callback) {
	// Make sure a callback is defined.
	callback = (callback || noop);

	try {
		var myregex = RegExp(name.replace(' ', '_').toLowerCase());
		tags.find({"name": myregex}, function(err, docs) {
		  if (err) {
			  console.log('Error obteniendo tags --> ' + err);
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

//Crea un nuevo tag
module.exports.set = function set(tag, callback) {
	// Make sure a callback is defined.
	callback = (callback || noop);

	try {
		var tag = new tags(JSON.parse(tag));
		tag.save(function(err) {
		  if (err) {
			  console.log('Error guardando el tag --> ' + err);
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

//Actualiza un tag existente (búsqueda por ID)
module.exports.update = function update(id, data, callback) {
	// Make sure a callback is defined.
	callback = (callback || noop);
	
	try {
		var oid = JSON.parse('{"id":"' + id + '"}');
		var odata = JSON.parse(data);
		tags.update(oid, odata, function(err) {
			if (err) {
				  console.log('Error actualizando el tag --> ' + err);
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

//Elimina un tag existente (búsqueda por ID) 
module.exports.remove = function remove(id, callback) {
	// Make sure a callback is defined.
	callback = (callback || noop);
	
	try {
		var oid = JSON.parse('{"id":"' + id + '"}');
		tags.findOne(oid, function(err, tag) {
		  if (err) {
			  console.log('Error eliminando el tag --> ' + err);
			  throw errors.apiError;
		  }
		  tag.remove(function(err) {
			  if (err) {
				  console.log('Error eliminando el tag --> ' + err);
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

