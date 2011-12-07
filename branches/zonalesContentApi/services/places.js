var mongoose = require('mongoose'),
    Schema = mongoose.Schema;
var errors = require('../errors/errors');

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
	    geoData: String //Debe ser un id de un dato geográfico existente
	}
);

//Conecto con la DB y seteo el Model (TODO: Ver tema de la conexión para unificarla para todos los models)
mongoose.connect('mongodb://localhost/crawl');
var places = mongoose.model('places', placeSchema);

//Retorna id y nombre de todos los lugares
module.exports.getAll = function getAll(req,res) {
	res.writeHead(200, {"Content-Type": "text/javascript"});
	try {
		places.find({}, ['id', 'name'], function(err, docs) {
		  if (err) {
			  res.write(errors.apiError);
			  res.end();
			  console.log('Error obteniendo lugares --> ' + err);
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

//Retorna un conjunto de lugares de acuerdo a los filtros utilizados 
module.exports.get = function get(req,res) {
	res.writeHead(200, {"Content-Type": "text/javascript"});
	try {
		var filtros = JSON.parse(req.query.filters);
		places.find(filtros, function(err, docs) {
		  if (err) {
			  res.write(errors.apiError);
			  res.end();
			  console.log('Error obteniendo lugares --> ' + err);
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

//Retorna un conjunto de lugares con nombre similar al parámetro
module.exports.getLikeName = function getLikeName(req,res) {
	res.writeHead(200, {"Content-Type": "text/javascript"});
	try {
		var myregex = RegExp(req.query.name);
		places.find({"name": myregex}, function(err, docs) {
		  if (err) {
			  res.write(errors.apiError);
			  res.end();
			  console.log('Error obteniendo lugares --> ' + err);
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

//Crea una nuevo lugar
module.exports.set = function set(req,res) {
	res.writeHead(200, {"Content-Type": "text/javascript"});
	try {
		var place = new places(JSON.parse(req.query.place));
		place.save(function(err) {
		  if (err) {
			  res.write(errors.apiError);
			  res.end();
			  console.log('Error guardando el lugar --> ' + err);
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

//Actualiza un lugar existente (búsqueda por ID)
module.exports.update = function update(req,res) {
	res.writeHead(200, {"Content-Type": "text/javascript"});
	try {
		var id = JSON.parse('{"id":"' + req.query.id + '"}');
		var data = JSON.parse(req.query.data);
		places.update(id, data, function(err) {
			if (err) {
				  res.write(errors.apiError);
				  res.end();
				  console.log('Error actualizando el lugar --> ' + err);
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

//Elimina un lugar existente (búsqueda por ID) 
module.exports.remove = function remove(req,res) {
	res.writeHead(200, {"Content-Type": "text/javascript"});
	try {
		var id = JSON.parse('{"id":"' + req.query.id + '"}');
		places.findOne(id, function(err, place) {
		  if (err) {
			  res.write(errors.apiError);
			  res.end();
			  console.log('Error eliminando el lugar --> ' + err);
			  return;
		  }
		  place.remove(function(err) {
			  if (err) {
				  res.write(errors.apiError);
				  res.end();
				  console.log('Error eliminando el lugar --> ' + err);
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
