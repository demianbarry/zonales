// ----------------------- MODULES -----------------------
var fs = require('fs'),
	 express = require('express');

// ----------------------- MODELS -----------------------
var zoneService = require('./services/zones');
var zoneTypeService = require('./services/zoneTypes');
var placeService = require('./services/places');
var placeTypeService = require('./services/placeTypes');
var geoDataService = require('./services/geoData');
var tagService = require('./services/tags');
var tagTypeService = require('./services/tagTypes');

//----------------------- ERRORS -----------------------
var errors = require('./errors/errors');

//----------------------- GLOBALS -----------------------
var app = express.createServer(),
	 io = require('socket.io').listen(app);
 
app.configure(function() {
   app.use(express.logger());
   app.use(express.errorHandler());
   app.use(express.bodyParser());
   app.use(express.methodOverride()   );
   app.use(express.static(__dirname + '/static'));
}); 

app.set('views', __dirname + '/views');
app.set('view engine', 'jade');
app.set('view options',{layout: true});

//----------------------- SOCKET.IO -----------------------

io.sockets.on('connection', function(client) {

	client.on('getZones', function(name, fn) {
		console.log('Recibi el evento getZones desde el cliente');
		zoneService.getAll(function(data){
		  if (typeof(data) != 'undefined') {
		  	 fn(data);
		  }
	   });
	});

	client.on('getZonesByName', function(name, fn) {
		console.log('Recibi el evento getZonesByName desde el cliente');
		zoneService.getLikeName(name, function(data){
		  if (typeof(data) != 'undefined') {
		    fn(data);
		  }
	   });		
	});
	
	client.on('getZonesTypes', function(name, fn) {
		console.log('Recibi el evento getZonesTypes desde el cliente');
		zoneTypeService.getAll(function(data) {
		  if (typeof(data) != 'undefined') {
		    fn(data);
		  }
	   });		
	});
	
	client.on('getZoneByFilters', function(filters, fn) {
		console.log('Recibi el evento getZoneByFilters desde el cliente');
		zoneService.get(filters, function(data){
		  if (typeof(data) != 'undefined') {		    
		    fn(data);
		  }
	   });		
	});
	
	client.on('getParentTypes', function(name, fn) {
		console.log('Recibi el evento getParentTypes desde el cliente');
		zoneTypeService.get(name, function(data) {
		  if (typeof(data) != 'undefined' && typeof(data[0].parents) != 'undefined') {
		  	 fn(data[0].parents);
		  }
	   });		
	});
	
	client.on('saveZone', function(name, fn) {
		console.log('Recibi el evento saveZone desde el cliente');
		zoneService.set(name, function(data){
		  if (typeof(data) != 'undefined') {
		    fn(data);
		  }
	   });
	});
	
	client.on('updateZone', function(name, fn) {
		console.log('Recibi el evento updateZone desde el cliente');
		var obj = eval('(' + name + ')');
		zoneService.update(obj.id, name, function(data){
		  if (typeof(data) != 'undefined') {
		    fn(data);
		  }
	   });
	});
	
	client.on('getGeoData', function(filters, fn) {
		console.log('Recibi el evento getGeoData desde el cliente');
		geoDataService.get(filters, function(data){
		  if (typeof(data) != 'undefined') {		    
		    fn(data);
		  }
	   });
	});
	
	client.on('saveGeoData', function(name, fn) {
		console.log('Recibi el evento saveGeoData desde el cliente');
		geoDataService.set(name, function(data){
		  if (typeof(data) != 'undefined') {
		    fn(data);
		  }
	   });
	});
	
	client.on('updateGeoData', function(name, fn) {
		console.log('Recibi el evento updateZone desde el cliente');
		var obj = eval('(' + name + ')');
		geoDataService.update(obj.id, name, function(data){
		  if (typeof(data) != 'undefined') {
		    fn(data);
		  }
	   });
	});
	
});



app.get('/', function(req,res) {
   res.render('index');
});

//----------------------- CMUTILS PAGES -----------------------

app.get('/CMUtils/zoneList', function(req,res) {
	fs.readFile(__dirname + '/views/zoneList.html', 'utf8', function(err, text){
	    res.send(text);
	});
});

app.get('/CMUtils/zoneEdit', function(req,res) {
	fs.readFile(__dirname + '/views/zoneEdit.html', 'utf8', function(err, text){
	    res.send(text);
	});
});

//----------------------- SERVICES SWITCH -----------------------

//----------------------- ZONES -----------------------

//Servicio que obtiene el id y el nombre de todas las zonas.
app.get('/zone/getAll', function(req,res) {
	res.writeHead(200, {"Content-Type": "text/javascript"});
	try {
		zoneService.getAll(function(data){
			res.write(JSON.stringify(data));
			res.end();
		});
	} catch (err) {
		if (err == errors.apiError) {
			res.write(errors.apiError);
		} else {
			res.write(errors.unknowError);
		}
		res.end();
	}
});

//Servicio que obtiene un conjunto de zonas de acuerdo a los filtros utilizados. El parámetro filtro es JSON.
app.get('/zone/get', function(req, res) {
	res.writeHead(200, {"Content-Type": "text/javascript"});
	try {
		zoneService.get(req.query.filters, function(data){
			res.write(JSON.stringify(data));
			res.end();
		});
	} catch (err) {
		if (err == errors.apiError) {
			res.write(errors.apiError);
		} else {
			res.write(errors.unknowError);
		}
		res.end();
	}
});

//Servicio que obtiene un conjunto de zonas cuyo nombre es similar al parámetro name, al estilo like de SQL.
app.get('/zone/getLikeName', function(req, res) {
	res.writeHead(200, {"Content-Type": "text/javascript"});
	try {
		zoneService.getLikeName(req.query.name, function(data){
			res.write(JSON.stringify(data));
			res.end();
		});
	} catch (err) {
		if (err == errors.apiError) {
			res.write(errors.apiError);	
		} else {
			res.write(errors.unknowError);
		}
		res.end();
	}
});

//Servicio que setea una zona. El parámetro zone es JSON.
app.get('/zone/set', function(req, res) {
	res.writeHead(200, {"Content-Type": "text/javascript"});
	try {
		zoneService.set(req.query.zone, function(data){
			res.write(data);
			res.end();
		});
	} catch (err) {
		if (err == errors.apiError) {
			res.write(errors.apiError);
		} else {
			res.write(errors.unknowError);
		}
		res.end();
	}
});

//Servicio que actualiza una zona. Parámetros: ID de la zona a buscar, JSON con nuevos datos
app.get('/zone/update', function(req, res) {
	res.writeHead(200, {"Content-Type": "text/javascript"});
	try {
		zoneService.update(req.query.id, req.query.data, function(data){
			res.write(data);
			res.end();
		});
	} catch (err) {
		if (err == errors.apiError) {
			res.write(errors.apiError);
		} else {
			res.write(errors.unknowError);
		}
		res.end();
	}
});

//Servicio que elimina una zona. Parámetros: ID de la zona a eliminar
app.get('/zone/remove', function(req, res) {
	res.writeHead(200, {"Content-Type": "text/javascript"});
	try {
		zoneService.remove(req.query.id, function(data){
			res.write(data);
			res.end();
		});
	} catch (err) {
		if (err == errors.apiError) {
			res.write(errors.apiError);
		} else {
			res.write(errors.unknowError);
		}
		res.end();
	}
});

//----------------------- ZONE TYPES -----------------------

//Servicio que obtiene el nombre de todos los tipos de zonas
app.get('/zoneType/getAll', function(req,res) {
	res.writeHead(200, {"Content-Type": "text/javascript"});
	try {
		zoneTypeService.getAll(function(data){
			res.write(JSON.stringify(data));
			res.end();
		});
	} catch (err) {
		if (err == errors.apiError) {
			res.write(errors.apiError);
		} else {
			res.write(errors.unknowError);
		}
		res.end();
	}
});

//Servicio que obtiene un conjunto de tipos zonas de acuerdo a los filtros utilizados. El parámetro filtro es JSON.
app.get('/zoneType/get', function(req, res) {
	res.writeHead(200, {"Content-Type": "text/javascript"});
	try {
		zoneTypeService.get(req.query.filters, function(data){
			res.write(JSON.stringify(data));
			res.end();
		});
	} catch (err) {
		if (err == errors.apiError) {
			res.write(errors.apiError);
		} else {
			res.write(errors.unknowError);
		}
		res.end();
	}
});

//Servicio que obtiene un conjunto de tipos zonas cuyo nombre es similar al parámetro name, al estilo like de SQL.
app.get('/zoneType/getLikeName', function(req, res) {
	res.writeHead(200, {"Content-Type": "text/javascript"});
	try {
		zoneTypeService.getLikeName(req.query.name, function(data){
			res.write(JSON.stringify(data));
			res.end();
		});
	} catch (err) {
		if (err == errors.apiError) {
			res.write(errors.apiError);	
		} else {
			res.write(errors.unknowError);
		}
		res.end();
	}
});

//Servicio que setea un tipo de zona. El parámetro zone es JSON.
app.get('/zoneType/set', function(req, res) {
	res.writeHead(200, {"Content-Type": "text/javascript"});
	try {
		zoneTypeService.set(req.query.zoneType, function(data){
			res.write(data);
			res.end();
		});
	} catch (err) {
		if (err == errors.apiError) {
			res.write(errors.apiError);
		} else {
			res.write(errors.unknowError);
		}
		res.end();
	}
});

//Servicio que actualiza un tipo zona. Parámetros: Nombre del tipo de zona a buscar, JSON con nuevos datos
app.get('/zoneType/update', function(req, res) {
	res.writeHead(200, {"Content-Type": "text/javascript"});
	try {
		zoneTypeService.update(req.query.id, req.query.data, function(data){
			res.write(data);
			res.end();
		});
	} catch (err) {
		if (err == errors.apiError) {
			res.write(errors.apiError);
		} else {
			res.write(errors.unknowError);
		}
		res.end();
	}
});

//Servicio que elimina un tipo de zona. Parámetros: Nombre del tipo zona a eliminar
app.get('/zoneType/remove', function(req, res) {
	res.writeHead(200, {"Content-Type": "text/javascript"});
	try {
		zoneTypeService.remove(req.query.id, function(data){
			res.write(data);
			res.end();
		});
	} catch (err) {
		if (err == errors.apiError) {
			res.write(errors.apiError);
		} else {
			res.write(errors.unknowError);
		}
		res.end();
	}
});

//----------------------- TAGS -----------------------

//Servicio que obtiene el id y el nombre de todos los tags
app.get('/tag/getAll', function(req,res) {
	res.writeHead(200, {"Content-Type": "text/javascript"});
	try {
		tagService.getAll(function(data){
			res.write(JSON.stringify(data));
			res.end();
		});
	} catch (err) {
		if (err == errors.apiError) {
			res.write(errors.apiError);
		} else {
			res.write(errors.unknowError);
		}
		res.end();
	}
});

//Servicio que obtiene un conjunto de tags de acuerdo a los filtros utilizados. El parámetro filtro es JSON.
app.get('/tag/get', function(req, res) {
	res.writeHead(200, {"Content-Type": "text/javascript"});
	try {
		tagService.get(req.query.filters, function(data){
			res.write(JSON.stringify(data));
			res.end();
		});
	} catch (err) {
		if (err == errors.apiError) {
			res.write(errors.apiError);
		} else {
			res.write(errors.unknowError);
		}
		res.end();
	}
});

//Servicio que obtiene un conjunto de tags cuyo nombre es similar al parámetro name, al estilo like de SQL.
app.get('/tag/getLikeName', function(req, res) {
	res.writeHead(200, {"Content-Type": "text/javascript"});
	try {
		tagService.getLikeName(req.query.name, function(data){
			res.write(JSON.stringify(data));
			res.end();
		});
	} catch (err) {
		if (err == errors.apiError) {
			res.write(errors.apiError);	
		} else {
			res.write(errors.unknowError);
		}
		res.end();
	}
});

//Servicio que setea un tag. El parámetro tag es JSON.
app.get('/tag/set', function(req, res) {
	res.writeHead(200, {"Content-Type": "text/javascript"});
	try {
		tagService.set(req.query.tag, function(data){
			res.write(data);
			res.end();
		});
	} catch (err) {
		if (err == errors.apiError) {
			res.write(errors.apiError);
		} else {
			res.write(errors.unknowError);
		}
		res.end();
	}
});

//Servicio que actualiza un tag. Parámetros: ID del tag a buscar, JSON con nuevos datos
app.get('/tag/update', function(req, res) {
	res.writeHead(200, {"Content-Type": "text/javascript"});
	try {
		tagService.update(req.query.id, req.query.data, function(data){
			res.write(data);
			res.end();
		});
	} catch (err) {
		if (err == errors.apiError) {
			res.write(errors.apiError);
		} else {
			res.write(errors.unknowError);
		}
		res.end();
	}
});

//Servicio que elimina un tag. Parámetros: ID del tag a eliminar
app.get('/tag/remove', function(req, res) {
	res.writeHead(200, {"Content-Type": "text/javascript"});
	try {
		tagService.remove(req.query.id, function(data){
			res.write(data);
			res.end();
		});
	} catch (err) {
		if (err == errors.apiError) {
			res.write(errors.apiError);
		} else {
			res.write(errors.unknowError);
		}
		res.end();
	}
});

//----------------------- TAG TYPES -----------------------

//Servicio que obtiene el nombre de todos los tipos de tags
app.get('/tagType/getAll', function(req,res) {
	res.writeHead(200, {"Content-Type": "text/javascript"});
	try {
		tagTypeService.getAll(function(data){
			res.write(JSON.stringify(data));
			res.end();
		});
	} catch (err) {
		if (err == errors.apiError) {
			res.write(errors.apiError);
		} else {
			res.write(errors.unknowError);
		}
		res.end();
	}
});

//Servicio que obtiene un conjunto de tipos de tags de acuerdo a los filtros utilizados. El parámetro filtro es JSON.
app.get('/tagType/get', function(req, res) {
	res.writeHead(200, {"Content-Type": "text/javascript"});
	try {
		tagTypeService.get(req.query.filters, function(data){
			res.write(JSON.stringify(data));
			res.end();
		});
	} catch (err) {
		if (err == errors.apiError) {
			res.write(errors.apiError);
		} else {
			res.write(errors.unknowError);
		}
		res.end();
	}
});

//Servicio que obtiene un conjunto de tipos de tags cuyo nombre es similar al parámetro name, al estilo like de SQL.
app.get('/tagType/getLikeName', function(req, res) {
	res.writeHead(200, {"Content-Type": "text/javascript"});
	try {
		tagTypeService.getLikeName(req.query.name, function(data){
			res.write(JSON.stringify(data));
			res.end();
		});
	} catch (err) {
		if (err == errors.apiError) {
			res.write(errors.apiError);	
		} else {
			res.write(errors.unknowError);
		}
		res.end();
	}
});

//Servicio que setea un tipo de tag. El parámetro tagType es JSON.
app.get('/tagType/set', function(req, res) {
	res.writeHead(200, {"Content-Type": "text/javascript"});
	try {
		tagTypeService.set(req.query.tagType, function(data){
			res.write(data);
			res.end();
		});
	} catch (err) {
		if (err == errors.apiError) {
			res.write(errors.apiError);
		} else {
			res.write(errors.unknowError);
		}
		res.end();
	}
});

//Servicio que actualiza un tipo de tag. Parámetros: Nombre del tipo de tag a buscar, JSON con nuevos datos
app.get('/tagType/update', function(req, res) {
	res.writeHead(200, {"Content-Type": "text/javascript"});
	try {
		tagTypeService.update(req.query.id, req.query.data, function(data){
			res.write(data);
			res.end();
		});
	} catch (err) {
		if (err == errors.apiError) {
			res.write(errors.apiError);
		} else {
			res.write(errors.unknowError);
		}
		res.end();
	}
});

//Servicio que elimina un tipo de tag. Parámetros: Nombre del tipo de tag a eliminar
app.get('/tagType/remove', function(req, res) {
	res.writeHead(200, {"Content-Type": "text/javascript"});
	try {
		tagTypeService.remove(req.query.id, function(data){
			res.write(data);
			res.end();
		});
	} catch (err) {
		if (err == errors.apiError) {
			res.write(errors.apiError);
		} else {
			res.write(errors.unknowError);
		}
		res.end();
	}
});


//----------------------- PLACES -----------------------

//Servicio que obtiene el nombre de todos los lugares
app.get('/place/getAll', function(req,res) {
	placeService.getAll(req,res);
});

//Servicio que obtiene un conjunto de lugares de acuerdo a los filtros utilizados. El parámetro filtro es JSON.
app.get('/place/get', function(req, res) {
	placeService.get(req,res);
});

//Servicio que obtiene un conjunto de lugares cuyo nombre es similar al parámetro name, al estilo like de SQL.
app.get('/place/getLikeName', function(req, res) {
	placeService.getLikeName(req,res);
});

//Servicio que setea un lugar. El parámetro zone es JSON.
app.get('/place/set', function(req, res) {
	placeService.set(req,res);
});

//Servicio que actualiza un lugar. Parámetros: ID del lugar a buscar, JSON con nuevos datos
app.get('/place/update', function(req, res) {
	placeService.update(req,res);
});

//Servicio que elimina un lugar. Parámetros: ID del lugar a eliminar
app.get('/place/remove', function(req, res) {
	placeService.remove(req,res);
});

//----------------------- PLACE TYPES -----------------------

//Servicio que obtiene el nombre de todos los tipos de lugares
app.get('/placeType/getAll', function(req,res) {
	placeTypeService.getAll(req,res);
});

//Servicio que obtiene un conjunto de tipos de lugares de acuerdo a los filtros utilizados. El parámetro filtro es JSON.
app.get('/placeType/get', function(req, res) {
	placeTypeService.get(req,res);
});

//Servicio que obtiene un conjunto de tipos de lugares cuyo nombre es similar al parámetro name, al estilo like de SQL.
app.get('/placeType/getLikeName', function(req, res) {
	placeTypeService.getLikeName(req,res);
});

//Servicio que setea un tipo de lugar. El parámetro zone es JSON.
app.get('/placeType/set', function(req, res) {
	placeTypeService.set(req,res);
});

//Servicio que actualiza un tipo de lugar. Parámetros: Nombre del tipo de lugar a buscar, JSON con nuevos datos
app.get('/placeType/update', function(req, res) {
	placeTypeService.update(req,res);
});

//Servicio que elimina un tipo de lugar. Parámetros: Nombre del tipo de lugar a eliminar
app.get('/placeType/remove', function(req, res) {
	placeTypeService.remove(req,res);
});

//----------------------- GEO DATA -----------------------

//Servicio que obtiene un conjunto de datos geográficos de acuerdo a los filtros utilizados. El parámetro filtro es JSON.
app.get('/tagType/get', function(req, res) {
	res.writeHead(200, {"Content-Type": "text/javascript"});
	try {
		geoDataService.get(req.query.filters, function(data){
			res.write(JSON.stringify(data));
			res.end();
		});
	} catch (err) {
		if (err == errors.apiError) {
			res.write(errors.apiError);
		} else {
			res.write(errors.unknowError);
		}
		res.end();
	}
});

//Servicio que setea un dato geográfico. El parámetro zone es JSON.
app.post('/geoData/set', function(req, res) {
	res.writeHead(200, {"Content-Type": "text/javascript"});
	try {
		geoDataService.set(req.body.geoData, function(data){
			res.write(data);
			res.end();
		});
	} catch (err) {
		if (err == errors.apiError) {
			res.write(errors.apiError);
		} else {
			res.write(errors.unknowError);
		}
		res.end();
	}
});

//Servicio que actualiza un dato geográfico. Parámetros: Nombre del tipo de lugar a buscar, JSON con nuevos datos
app.get('/geoData/update', function(req, res) {
	res.writeHead(200, {"Content-Type": "text/javascript"});
	try {
		geoDataService.update(req.query.id, req.query.data, function(data){
			res.write(data);
			res.end();
		});
	} catch (err) {
		if (err == errors.apiError) {
			res.write(errors.apiError);
		} else {
			res.write(errors.unknowError);
		}
		res.end();
	}
});

//Servicio que elimina un dato geográfico. Parámetros: Nombre del tipo de lugar a eliminar
app.get('/geoData/remove', function(req, res) {
	res.writeHead(200, {"Content-Type": "text/javascript"});
	try {
		geoDataService.remove(req.query.id, function(data){
			res.write(data);
			res.end();
		});
	} catch (err) {
		if (err == errors.apiError) {
			res.write(errors.apiError);
		} else {
			res.write(errors.unknowError);
		}
		res.end();
	}
});

//----------------------- SERVER -----------------------

app.listen(4000);
console.log('Servidor Express escuchando en el puerto 4000!!!');