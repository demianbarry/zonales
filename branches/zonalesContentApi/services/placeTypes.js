var mongoose = require('mongoose'),
    Schema = mongoose.Schema;
var errors = require('../errors/errors');

//Esquema JSON para los tipos de zonas
var placeTypeSchema = new Schema(
	{
	    name : { type: String, unique: true },  //Este campo debe ser único
	    description : [String], //Descripción del tipo de lugar
	    state : {
	    	type:String, 
	    	enum:['generated', 'published', 'unpublished', 'void'], 
	    	default: 'generated'
	    }
	}
);

//Conecto con la DB y seteo el Model (TODO: Ver tema de la conexión para unificarla para todos los models)
mongoose.connect('mongodb://localhost/crawl');
var placeTypes = mongoose.model('placeTypes', placeTypeSchema);

//Retorna nombre de todos los tipos de lugares
module.exports.getAll = function getAll(req,res) {
	res.writeHead(200, {"Content-Type": "text/javascript"});
	try {
		placeTypes.find({}, ['name'], function(err, docs) {
		  if (err) {
			  res.write(errors.apiError);
			  res.end();
			  console.log('Error obteniendo tipos de lugares --> ' + err);
			  return;
		  }
		  res.write(JSON.stringify(docs));
		  res.end();
	   });
	} catch (err) {
		res.write(errors.apiError);
		res.end();
		console.log('Error --> ' + err);
	}
}

//Retorna un conjunto de tipos de lugares de acuerdo a los filtros utilizados 
module.exports.get = function get(req,res) {
	res.writeHead(200, {"Content-Type": "text/javascript"});
	try {
		var filtros = JSON.parse(req.query.filters);
		placeTypes.find(filtros, function(err, docs) {
		  if (err) {
			  res.write(errors.apiError);
			  res.end();
			  console.log('Error obteniendo tipos de lugares --> ' + err);
			  return;
		  }
		  res.write(JSON.stringify(docs));
		  res.end();
	   });
	} catch (err) {
		res.write(errors.apiError);
		res.end();
		console.log('Error --> ' + err);
	}
}

//Retorna un conjunto de tipos de lugares con nombre similar al parámetro
module.exports.getLikeName = function getLikeName(req,res) {
	res.writeHead(200, {"Content-Type": "text/javascript"});
	try {
		var myregex = RegExp(req.query.name);
		placeTypes.find({"name": myregex}, function(err, docs) {
		  if (err) {
			  res.write(errors.apiError);
			  res.end();
			  console.log('Error obteniendo tipos de lugares --> ' + err);
			  return;
		  }
		  res.write(JSON.stringify(docs));
		  res.end();
	   });
	} catch (err) {
		res.write(errors.apiError);
		res.end();
		console.log('Error --> ' + err);
	}
}

//Crea un nuevo tipo de lugar
module.exports.set = function set(req,res) {
	res.writeHead(200, {"Content-Type": "text/javascript"});
	try {
		var placeType = new placeTypes(JSON.parse(req.query.placeType));
		placeType.save(function(err) {
		  if (err) {
			  res.write(errors.apiError);
			  res.end();
			  console.log('Error guardando el tipo de lugar --> ' + err);
			  return;
		  }
		  res.write(errors.success);
		  res.end();
	   });
	} catch (err) {
		res.write(errors.apiError);
		res.end();
		console.log('Error --> ' + err);
	}
}

//Actualiza un tipo de lugar existente (búsqueda por nombre)
module.exports.update = function update(req,res) {
	res.writeHead(200, {"Content-Type": "text/javascript"});
	try {
		var name = JSON.parse('{"name":"' + req.query.name + '"}');
		var data = JSON.parse(req.query.data);
		placeTypes.update(name, data, function(err) {
			if (err) {
				  res.write(errors.apiError);
				  res.end();
				  console.log('Error actualizando el tipo de lugar --> ' + err);
				  return;
			  }
			  res.write(errors.success);
			  res.end();
		});
	} catch (err) {
		res.write(errors.apiError);
		res.end();
		console.log('Error --> ' + err);
	}
}

//Elimina un tipo de lugar existente (búsqueda por Nombre) 
module.exports.remove = function remove(req,res) {
	res.writeHead(200, {"Content-Type": "text/javascript"});
	try {
		var name = JSON.parse('{"name":"' + req.query.name + '"}');
		placeTypes.findOne(name, function(err, placeType) {
		  if (err) {
			  res.write(errors.apiError);
			  res.end();
			  console.log('Error eliminando el tipo de lugar --> ' + err);
			  return;
		  }
		  placeType.remove(function(err) {
			  if (err) {
				  res.write(errors.apiError);
				  res.end();
				  console.log('Error eliminando el tipo de lugar --> ' + err);
				  return;
			  }
			  res.write(errors.success);
			  res.end();
		  });
	   });
	} catch (err) {
		res.write(errors.apiError);
		res.end();
		console.log('Error --> ' + err);
	}
}
