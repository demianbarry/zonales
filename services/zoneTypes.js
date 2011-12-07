var mongoose = require('mongoose'),
    Schema = mongoose.Schema;
var errors = require('../errors/errors');

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
//module.exports.zoneType = zoneTypes;

//Retorna nombre de todos los tipos de zonas
module.exports.getAll = function getAll(callback) {
	// Make sure a callback is defined.
	callback = (callback || noop);
	
	try {
		zoneTypes.find({}, ['name'], function(err, docs) {
		  if (err) {
			  console.log('Error obteniendo tipos de zonas --> ' + err);
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

//Retorna un conjunto de tipos de zonas de acuerdo a los filtros utilizados 
module.exports.get = function get(filters, callback) {
	// Make sure a callback is defined.
	callback = (callback || noop);
	
	try {
		var filtros = JSON.parse(filters);
		zoneTypes.find(filtros, function(err, docs) {
		  if (err) {
		  	  console.log('Error obteniendo tipos de zonas --> ' + err);
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


//Retorna un conjunto de tipos de zonas con nombre similar al parámetro
module.exports.getLikeName = function getLikeName(name, callback) {
	// Make sure a callback is defined.
	callback = (callback || noop);

	try {
		var myregex = RegExp(name);
		zoneTypes.find({"name": myregex}, function(err, docs) {
		  if (err) {
			  console.log('Error obteniendo tipos de zonas --> ' + err);
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

//Crea un nuevo tipo de zona
module.exports.set = function set(zoneType, callback) {
	// Make sure a callback is defined.
	callback = (callback || noop);

	try {
		var zoneType = new zoneTypes(JSON.parse(zoneType));
		zoneType.save(function(err) {
		  if (err) {
			  console.log('Error guardando el tipo de zona --> ' + err);
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


//Actualiza un tipo de zona existente (búsqueda por nombre)
module.exports.update = function update(id, data, callback) {
	// Make sure a callback is defined.
	callback = (callback || noop);
	
	try {
		var oid = JSON.parse('{"id":"' + id + '"}');
		var odata = JSON.parse(data);
		zoneTypes.update(oid, odata, function(err) {
			if (err) {
				  console.log('Error actualizando el tipo de zona --> ' + err);
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

//Elimina un tipo de zona existente (búsqueda por Nombre)
module.exports.remove = function remove(id, callback) {
	// Make sure a callback is defined.
	callback = (callback || noop);
	
	try {
		var oid = JSON.parse('{"id":"' + id + '"}');
		zoneTypes.findOne(oid, function(err, zoneType) {
		  if (err) {
			  console.log('Error eliminando el tipo de zona --> ' + err);
			  throw errors.apiError;
		  }
		  zoneType.remove(function(err) {
			  if (err) {
				  console.log('Error eliminando el tipo de zona --> ' + err);
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

