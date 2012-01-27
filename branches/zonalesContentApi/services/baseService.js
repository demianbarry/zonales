var mongoose = require('mongoose'),
    Schema = mongoose.Schema;
var errors = require('../errors/errors');

//Retorna id y nombre de todos los registros
//short: registro corto, solos los fields indicados en el campo fields
//fields: formato [<field1>,<field2>,..]
module.exports.getAll = function getAll(model, short, fields, callback) {
	// Make sure a callback is defined.
	callback = (callback || noop);
	
	try {
		if (short) {
			var fields = eval('(' + fields + ')');
			model.find({}, fields, function(err, docs) {
			  if (err) {
				  console.log('Error obteniendo datos --> ' + err);
			  	  throw errors.apiError; 
			  }
			  callback(docs);
			  return(this);
		   });
		} else {
			model.find({}, function(err, docs) {
			  if (err) {
				  console.log('Error obteniendo datos --> ' + err);
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

//Retorna un conjunto de datos de acuerdo a los filtros utilizados 
module.exports.get = function get(model, filters, callback) {
	// Make sure a callback is defined.
	callback = (callback || noop);
	
	try {
		//var filtros = JSON.parse(filters);
		model.find(filters, function(err, docs) {
		  if (err) {
			  throw errors.apiError;
		  }
		  console.log(JSON.stringify(docs));
		  callback(docs);
		  return(this);
	   });
	} catch (err) {
		console.log('Error --> ' + err);
		throw errors.apiError;
	}
}

//Retorna un conjunto de datos con nombre similar al parámetro
module.exports.getLikeName = function getLikeName(model, name, callback) {
	// Make sure a callback is defined.
	callback = (callback || noop);

	try {
		var myregex = RegExp(name);
		model.find({"name": myregex}, function(err, docs) {
		  if (err) {
			  console.log('Error obteniendo datos --> ' + err);
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

//Retorna un conjunto de datos con nombre similar al parámetro
module.exports.searchData = function searchData(model, filters, callback) {
	// Make sure a callback is defined.
	callback = (callback || noop);

	try {
		//var myregex = RegExp(name);
		if (typeof(filters.name) != 'undefined') {
			filters.name = RegExp(filters.name);
		}
		model.find(filters, function(err, docs) { 
		  if (err) {
			  console.log('Error obteniendo datos --> ' + err);
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

//Crea una nueva dato
module.exports.set = function set(model, data, callback) {
	// Make sure a callback is defined.
	callback = (callback || noop);

	try {
		var odata = new model(JSON.parse(data));
		odata.save(function(err) {
		  if (err) {
			  console.log('Error guardando el dato --> ' + err);
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

//Actualiza un dato existente
module.exports.update = function update(model, searchFieldName, searchFieldData, data, callback) {
	// Make sure a callback is defined.
	callback = (callback || noop);
	
	try {
		var oid = JSON.parse('{"' + searchFieldName + '":"' + searchFieldData + '"}');
		var odata = JSON.parse(data);
		model.update(oid, odata, function(err) {
			if (err) {
				  console.log('Error actualizando el dato --> ' + err);
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

//Elimina un dato existente  
module.exports.remove = function remove(model, searchFieldName, searchFieldData, callback) {
	// Make sure a callback is defined.
	callback = (callback || noop);
	
	try {
		var oid = JSON.parse('{"' + searchFieldName + '":"' + searchFieldData + '"}');
		model.findOne(oid, function(err, zone) {
		  if (err) {
			  console.log('Error eliminando el dato --> ' + err);
			  throw errors.apiError;
		  }
		  model.remove(function(err) {
			  if (err) {
				  console.log('Error eliminando el dato --> ' + err);
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

