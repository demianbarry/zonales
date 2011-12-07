var mongoose = require('mongoose'),
    Schema = mongoose.Schema;
var errors = require('../errors/errors');

var featureSchema = new Schema(
	{
    	type : {
	    	type: String, 
	    	enum: ['Feature'], 
	    	default: 'Feature'
	    },
      properties : {},
      geometry : {  //Un feature contiene un objeto geométrico
        	type : {
		    	type: String, 
		    	enum: ['Point', 'LineString', 'Polygon', 'Multipoint', 'MultiLineString'], 
		    	default: 'Polygon'
		   },
         coordinates: {}
      }
  }	
);

featureSchema.index({'geometry.coordinates': '2d'});

//Esquema JSON para las locaciones geográficas
var geoSchema = new Schema(
		{
		    type : {
		    	type: String, 
		    	enum: ['FeatureCollection'], 
		    	default: 'FeatureCollection'
		    },
		    features : [featureSchema],
		    id : { type: String, unique: true }
		}
);


//Conecto con la DB y seteo el Model (TODO: Ver tema de la conexión para unificarla para todos los models)
mongoose.connect('mongodb://localhost/crawl');
var geo = mongoose.model('geoData', geoSchema);

//Retorna un conjunto de datos geográficos de acuerdo a los filtros utilizados
module.exports.get = function get(filters, callback) {
	// Make sure a callback is defined.
	callback = (callback || noop);
	
	try {
		var filtros = JSON.parse(filters);
		geo.find(filtros, function(err, docs) {
		  if (err) {
		  	  console.log('Error obteniendo Datos Geográficos --> ' + err);
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

//Crea un nuevo dato geográfico
module.exports.set = function set(geoData, callback) {
	// Make sure a callback is defined.
	callback = (callback || noop);

	try {
		var ogeo = new geo(JSON.parse(geoData));
		ogeo.save(function(err) {
		  if (err) {
			  console.log('Error guardando el dato geográfico --> ' + err);
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

//Actualiza un dato geográfico existente (búsqueda por ID)
module.exports.update = function update(id, data, callback) {
	// Make sure a callback is defined.
	callback = (callback || noop);
	
	try {
		var oid = JSON.parse('{"id":"' + id + '"}');
		var odata = JSON.parse(data);
		geo.update(oid, odata, function(err) {
			if (err) {
				  console.log('Error actualizando el dato geográfico --> ' + err);
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
		geo.findOne(oid, function(err, geoData) {
		  if (err) {
			  console.log('Error eliminando el dato geográfico --> ' + err);
			  throw errors.apiError;
		  }
		  geoData.remove(function(err) {
			  if (err) {
				  console.log('Error eliminando el dato geográfico --> ' + err);
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
