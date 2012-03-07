// ----------------------- MODULES -----------------------
var fs = require('fs'),
express = require('express');

// ----------------------- CONTROLLERS -----------------------
//var sioClientSwitch = require('./controllers/sioClientSwitch'); 
var sioContextSwitch = require('./controllers/sioContextSwitch'); 
var sioGeoSwitch = require('./controllers/sioGeoSwitch'); 
var sioPlacesSwitch = require('./controllers/sioPlacesSwitch'); 
var sioProxySwitch = require('./controllers/sioProxySwitch'); 
var sioTagsSwitch = require('./controllers/sioTagsSwitch'); 
var sioZonesSwitch = require('./controllers/sioZonesSwitch'); 

// ----------------------- SERVICES -----------------------
var zoneService = require('./services/zones');
var zoneTypeService = require('./services/zoneTypes');
var placeService = require('./services/places');
var placeTypeService = require('./services/placeTypes');
var geoDataService = require('./services/geoData');
var tagService = require('./services/tags');
var tagTypeService = require('./services/tagTypes');
var zContextService = require('./services/ZContext');
var zProxyService = require('./services/zProxy');

//----------------------- ERRORS -----------------------
var errors = require('./errors/errors');

//----------------------- GLOBALS -----------------------
var app = express.createServer(),
io = require('socket.io').listen(app),
port = 4000;
 
app.configure(function() {
    app.use(express.logger());
    app.use(express.errorHandler());
    app.use(express.bodyParser());
    app.use(express.methodOverride()   );
    app.use(express.static(__dirname + '/static'));
}); 

app.set('views', __dirname + '/views');
app.set('view engine', 'jade');
app.set('view options',{
    layout: true
});

//----------------------- SOCKET.IO -----------------------

io.sockets.on('connection', function(client) {

    sioContextSwitch.tryEvent(client);
	sioGeoSwitch.tryEvent(client);
    sioPlacesSwitch.tryEvent(client);
    sioProxySwitch.tryEvent(client);
    sioTagsSwitch.tryEvent(client);
    sioZonesSwitch.tryEvent(client);

});


app.get('/', function(req,res) {
    res.render('index');
});

//----------------------- CMUTILS PAGES -----------------------

app.get('/CMUtils', function(req,res) {
    fs.readFile(__dirname + '/views/index.html', 'utf8', function(err, text){
        res.send(text);
    });
});

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

app.get('/CMUtils/zoneTypeList', function(req,res) {
    fs.readFile(__dirname + '/views/zoneTypeList.html', 'utf8', function(err, text){
        res.send(text);
    });
});

app.get('/CMUtils/zoneTypeEdit', function(req,res) {
    fs.readFile(__dirname + '/views/zoneTypeEdit.html', 'utf8', function(err, text){
        res.send(text);
    });
});


app.get('/CMUtils/tagList', function(req,res) {
    fs.readFile(__dirname + '/views/tagList.html', 'utf8', function(err, text){
        res.send(text);
    });
});

app.get('/CMUtils/tagEdit', function(req,res) {
    fs.readFile(__dirname + '/views/tagEdit.html', 'utf8', function(err, text){
        res.send(text);
    });
});

app.get('/CMUtils/tagTypeList', function(req,res) {
    fs.readFile(__dirname + '/views/tagTypeList.html', 'utf8', function(err, text){
        res.send(text);
    });
});

app.get('/CMUtils/tagTypeEdit', function(req,res) {
    fs.readFile(__dirname + '/views/tagTypeEdit.html', 'utf8', function(err, text){
        res.send(text);
    });
});

app.get('/CMUtils/placeList', function(req,res) {
    fs.readFile(__dirname + '/views/placeList.html', 'utf8', function(err, text){
        res.send(text);
    });
});

app.get('/CMUtils/placeEdit', function(req,res) {
    fs.readFile(__dirname + '/views/placeEdit.html', 'utf8', function(err, text){
        res.send(text);
    });
});

app.get('/CMUtils/placeTypeList', function(req,res) {
    fs.readFile(__dirname + '/views/placeTypeList.html', 'utf8', function(err, text){
        res.send(text);
    });
});

app.get('/CMUtils/placeTypeEdit', function(req,res) {
    fs.readFile(__dirname + '/views/placeTypeEdit.html', 'utf8', function(err, text){
        res.send(text);
    });
});

app.get('/CMUtils/solrCommands', function(req,res) {
    fs.readFile(__dirname + '/views/solrCommands.html', 'utf8', function(err, text){
        res.send(text);
    });
});


//----------------------- SERVICES SWITCH -----------------------

//----------------------- PLACES -----------------------

//Servicio que obtiene el id y el nombre de todas las zonas.
app.get('/place/getAll', function(req,res) {
    res.writeHead(200, {
        "Content-Type": "text/javascript"
    });
    try {
        placeService.getAll(req.query.short, function(data){
            res.write(JSON.stringify(data));
            res.end();
        });
    } catch (err) {
        res.write(JSON.stringify(err));
        res.end();
    }
});


//----------------------- ZONES -----------------------

//Servicio que obtiene el id y el nombre de todas las zonas.
app.get('/zone/getAll', function(req,res) {
    res.writeHead(200, {
        "Content-Type": "text/javascript"
    });
    try {
        zoneService.getAll(req.query.short, function(data){
            res.write(JSON.stringify(data));
            res.end();
        });
    } catch (err) {
        res.write(JSON.stringify(err));
        res.end();
    }
});

//Servicio que obtiene un conjunto de zonas de acuerdo a los filtros utilizados. El parámetro filtro es JSON.
app.get('/zone/get', function(req, res) {
    res.writeHead(200, {
        "Content-Type": "text/javascript"
    });
    try {
        zoneService.get(eval('(' + req.query.filters + ')'), function(data){
            res.write(JSON.stringify(data));
            res.end();
        });
    } catch (err) {
        res.write(JSON.stringify(err));
        res.end();
    }
});

//Servicio que 
app.get('/zone/updateExtendedString', function(req, res) {
    res.writeHead(200, {
        "Content-Type": "text/javascript"
    });
    try {
        var filter = eval('({"id":"' + req.query.id + '"})')
        zoneService.get(filter, function(data){                    
            if(data.length > 0)
                res.write(JSON.stringify(data[0].extendedString));                        
            res.end();
        });
    } catch (err) {
        res.write(JSON.stringify(err));
        res.end();
    }
});

//Servicio que obtiene un conjunto de zonas cuyo nombre es similar al parámetro name, al estilo like de SQL.
app.get('/zone/getLikeName', function(req, res) {
    res.writeHead(200, {
        "Content-Type": "text/javascript"
    });
    try {
        zoneService.getLikeName(req.query.name, function(data){
            res.write(JSON.stringify(data));
            res.end();
        });
    } catch (err) {
        res.write(JSON.stringify(err));
        res.end();
    }
});

//Servicio que setea una zona. El parámetro zone es JSON.
app.get('/zone/set', function(req, res) {
    res.writeHead(200, {
        "Content-Type": "text/javascript"
    });
    try {
        zoneService.set(req.query.zone, function(data){
            res.write(JSON.stringify(data));
            res.end();
        });
    } catch (err) {
        res.write(JSON.stringify(err));
        res.end();
    }
});

//Servicio que actualiza una zona. Parámetros: ID de la zona a buscar, JSON con nuevos datos
app.get('/zone/update', function(req, res) {
    res.writeHead(200, {
        "Content-Type": "text/javascript"
    });
    try {
        zoneService.update(req.query.id, req.query.data, function(data){
            res.write(JSON.stringify(data));
            res.end();
        });
    } catch (err) {
        res.write(JSON.stringify(err));
        res.end();
    }
});

//Servicio que elimina una zona. Parámetros: ID de la zona a eliminar
app.get('/zone/remove', function(req, res) {
    res.writeHead(200, {
        "Content-Type": "text/javascript"
    });
    try {
        zoneService.remove(req.query.id, function(data){
            res.write(JSON.stringify(data));
            res.end();
        });
    } catch (err) {
        res.write(JSON.stringify(err));
        res.end();
    }
});

//Servicio que obtiene la cadena extendida de una zona.
app.get('/zone/getExtendedString', function(req, res) {
    res.writeHead(200, {
        "Content-Type": "text/javascript"
    });
    try {
        res.write(zoneService.getExtendedString(JSON.parse(req.query.zone), null));
        res.end();
    } catch (err) {
        res.write(JSON.stringify(err));
        res.end();
    }
});

//----------------------- ZONE TYPES -----------------------

//Servicio que obtiene el nombre de todos los tipos de zonas
app.get('/zoneType/getAll', function(req,res) {
    res.writeHead(200, {
        "Content-Type": "text/javascript"
    });
    try {
        zoneTypeService.getAll(req.query.short, function(data){
            res.write(JSON.stringify(data));
            res.end();
        });
    } catch (err) {
        res.write(JSON.stringify(err));
        res.end();
    }
});

//Servicio que obtiene un conjunto de tipos zonas de acuerdo a los filtros utilizados. El parámetro filtro es JSON.
app.get('/zoneType/get', function(req, res) {
    res.writeHead(200, {
        "Content-Type": "text/javascript"
    });
    try {
        zoneTypeService.get(eval('(' + req.query.filters + ')'), function(data){
            res.write(JSON.stringify(data));
            res.end();
        });
    } catch (err) {
        res.write(JSON.stringify(err));
        res.end();
    }
});

//Servicio que obtiene un conjunto de tipos zonas cuyo nombre es similar al parámetro name, al estilo like de SQL.
app.get('/zoneType/getLikeName', function(req, res) {
    res.writeHead(200, {
        "Content-Type": "text/javascript"
    });
    try {
        zoneTypeService.getLikeName(req.query.name, function(data){
            res.write(JSON.stringify(data));
            res.end();
        });
    } catch (err) {
        res.write(JSON.stringify(err));
        res.end();
    }
});

//Servicio que setea un tipo de zona. El parámetro zone es JSON.
app.get('/zoneType/set', function(req, res) {
    res.writeHead(200, {
        "Content-Type": "text/javascript"
    });
    try {
        zoneTypeService.set(req.query.zoneType, function(data){
            res.write(JSON.stringify(data));
            res.end();
        });
    } catch (err) {
        res.write(JSON.stringify(err));
        res.end();
    }
});

//Servicio que actualiza un tipo zona. Parámetros: Nombre del tipo de zona a buscar, JSON con nuevos datos
app.get('/zoneType/update', function(req, res) {
    res.writeHead(200, {
        "Content-Type": "text/javascript"
    });
    try {
        zoneTypeService.update(req.query.id, req.query.data, function(data){
            res.write(JSON.stringify(data));
            res.end();
        });
    } catch (err) {
        res.write(JSON.stringify(err));
        res.end();
    }
});

//Servicio que elimina un tipo de zona. Parámetros: Nombre del tipo zona a eliminar
app.get('/zoneType/remove', function(req, res) {
    res.writeHead(200, {
        "Content-Type": "text/javascript"
    });
    try {
        zoneTypeService.remove(req.query.id, function(data){
            res.write(JSON.stringify(data));
            res.end();
        });
    } catch (err) {
        res.write(JSON.stringify(err));
        res.end();
    }
});

//----------------------- TAGS -----------------------

//Servicio que obtiene el id y el nombre de todos los tags
app.get('/tag/getAll', function(req,res) {
    res.writeHead(200, {
        "Content-Type": "text/javascript"
    });
    try {
        tagService.getAll(req.query.short, function(data){
            res.write(JSON.stringify(data));
            res.end();
        });
    } catch (err) {
        res.write(JSON.stringify(err));
        res.end();
    }
});

//Servicio que obtiene un conjunto de tags de acuerdo a los filtros utilizados. El parámetro filtro es JSON.
app.get('/tag/get', function(req, res) {
    res.writeHead(200, {
        "Content-Type": "text/javascript"
    });
    try {
        tagService.get(req.query.filters, function(data){
            res.write(JSON.stringify(data));
            res.end();
        });
    } catch (err) {
        res.write(JSON.stringify(err));
        res.end();
    }
});

//Servicio que obtiene un conjunto de tags cuyo nombre es similar al parámetro name, al estilo like de SQL.
app.get('/tag/getLikeName', function(req, res) {
    res.writeHead(200, {
        "Content-Type": "text/javascript"
    });
    try {
        tagService.getLikeName(req.query.name, function(data){
            res.write(JSON.stringify(data));
            res.end();
        });
    } catch (err) {
        res.write(JSON.stringify(err));
        res.end();
    }
});

//Servicio que setea un tag. El parámetro tag es JSON.
app.get('/tag/set', function(req, res) {
    res.writeHead(200, {
        "Content-Type": "text/javascript"
    });
    try {
        tagService.set(req.query.tag, function(data){
            res.write(JSON.stringify(data));
            res.end();
        });
    } catch (err) {
        res.write(JSON.stringify(err));
        res.end();
    }
});

//Servicio que actualiza un tag. Parámetros: ID del tag a buscar, JSON con nuevos datos
app.get('/tag/update', function(req, res) {
    res.writeHead(200, {
        "Content-Type": "text/javascript"
    });
    try {
        tagService.update(req.query.id, req.query.data, function(data){
            res.write(JSON.stringify(data));
            res.end();
        });
    } catch (err) {
        res.write(JSON.stringify(err));
        res.end();
    }
});

//Servicio que elimina un tag. Parámetros: ID del tag a eliminar
app.get('/tag/remove', function(req, res) {
    res.writeHead(200, {
        "Content-Type": "text/javascript"
    });
    try {
        tagService.remove(req.query.id, function(data){
            res.write(JSON.stringify(data));
            res.end();
        });
    } catch (err) {
        res.write(JSON.stringify(err));
        res.end();
    }
});

//----------------------- TAG TYPES -----------------------

//Servicio que obtiene el nombre de todos los tipos de tags
app.get('/tagType/getAll', function(req,res) {
    res.writeHead(200, {
        "Content-Type": "text/javascript"
    });
    try {
        tagTypeService.getAll(req.query.short, function(data){
            res.write(JSON.stringify(data));
            res.end();
        });
    } catch (err) {
        res.write(JSON.stringify(err));
        res.end();
    }
});

//Servicio que obtiene un conjunto de tipos de tags de acuerdo a los filtros utilizados. El parámetro filtro es JSON.
app.get('/tagType/get', function(req, res) {
    res.writeHead(200, {
        "Content-Type": "text/javascript"
    });
    try {
        tagTypeService.get(req.query.filters, function(data){
            res.write(JSON.stringify(data));
            res.end();
        });
    } catch (err) {
        res.write(JSON.stringify(err));
        res.end();
    }
});

//Servicio que obtiene un conjunto de tipos de tags cuyo nombre es similar al parámetro name, al estilo like de SQL.
app.get('/tagType/getLikeName', function(req, res) {
    res.writeHead(200, {
        "Content-Type": "text/javascript"
    });
    try {
        tagTypeService.getLikeName(req.query.name, function(data){
            res.write(JSON.stringify(data));
            res.end();
        });
    } catch (err) {
        res.write(JSON.stringify(err));
        res.end();
    }
});

//Servicio que setea un tipo de tag. El parámetro tagType es JSON.
app.get('/tagType/set', function(req, res) {
    res.writeHead(200, {
        "Content-Type": "text/javascript"
    });
    try {
        tagTypeService.set(req.query.tagType, function(data){
            res.write(JSON.stringify(data));
            res.end();
        });
    } catch (err) {
        res.write(JSON.stringify(err));
        res.end();
    }
});

//Servicio que actualiza un tipo de tag. Parámetros: Nombre del tipo de tag a buscar, JSON con nuevos datos
app.get('/tagType/update', function(req, res) {
    res.writeHead(200, {
        "Content-Type": "text/javascript"
    });
    try {
        tagTypeService.update(req.query.id, req.query.data, function(data){
            res.write(JSON.stringify(data));
            res.end();
        });
    } catch (err) {
        res.write(JSON.stringify(err));
        res.end();
    }
});

//Servicio que elimina un tipo de tag. Parámetros: Nombre del tipo de tag a eliminar
app.get('/tagType/remove', function(req, res) {
    res.writeHead(200, {
        "Content-Type": "text/javascript"
    });
    try {
        tagTypeService.remove(req.query.id, function(data){
            res.write(JSON.stringify(data));
            res.end();
        });
    } catch (err) {
        res.write(JSON.stringify(err));
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
    res.writeHead(200, {
        "Content-Type": "text/javascript"
    });
    try {
        geoDataService.get(req.query.filters, function(data){
            res.write(JSON.stringify(data));
            res.end();
        });
    } catch (err) {
        res.write(JSON.stringify(err));
        res.end();
    }
});

//Servicio que setea un dato geográfico. El parámetro zone es JSON.
app.post('/geoData/set', function(req, res) {
    res.writeHead(200, {
        "Content-Type": "text/javascript"
    });
    try {
        geoDataService.set(req.body.geoData, function(data){
            res.write(JSON.stringify(data));
            res.end();
        });
    } catch (err) {
        res.write(JSON.stringify(err));
        res.end();
    }
});

//Servicio que actualiza un dato geográfico. Parámetros: Nombre del tipo de lugar a buscar, JSON con nuevos datos
app.get('/geoData/update', function(req, res) {
    res.writeHead(200, {
        "Content-Type": "text/javascript"
    });
    try {
        geoDataService.update(req.query.id, req.query.data, function(data){
            res.write(JSON.stringify(data));
            res.end();
        });
    } catch (err) {
        res.write(JSON.stringify(err));
        res.end();
    }
});

//Servicio que elimina un dato geográfico. Parámetros: Nombre del tipo de lugar a eliminar
app.get('/geoData/remove', function(req, res) {
    res.writeHead(200, {
        "Content-Type": "text/javascript"
    });
    try {
        geoDataService.remove(req.query.id, function(data){
            res.write(JSON.stringify(data));
            res.end();
        });
    } catch (err) {
        res.write(JSON.stringify(err));
        res.end();
    }
});

//----------------------- SERVER -----------------------

app.listen(port);
console.log('Servidor Express escuchando en el puerto ' + port + '!!!');


//----------------------- PRUEBAS -----------------------

//------------------- Prueba de servicios: Obtener los places de una Zona, Obtener la zona de un place -------------------

/*placeService.getPlacesByZone(164, function (places) {
   console.log("RECUPERO PLACES PARA LA ZONA 164: Chubut -------> " + JSON.stringify(places));
});

placeService.getZone(2, function (zone) {
   console.log("RECUPERO LA ZONA PARA EL PLACE 2: UNPSJB Sede P. Madryn -------> " + JSON.stringify(zone));
});*/

//06-03-2012 12:05 -> Funcionó OK



//------------------- Prueba de servicios: Obtener los hijos de un place, Obtener los padres de un place -------------------

/*placeService.getChildrens(1, function (places) {
   console.log("RECUPERO HIJOS DEL PLACE 1: UNPSJB -------> " + JSON.stringify(places) + "---------------->");
});

placeService.getParent(2, function (parent) {
   console.log("RECUPERO PADRE DEL PLACE 2: UNPSJB Sede P. Madryn -------> " + JSON.stringify(parent) + "---------------->");
});*/

//06-03-2012 15:35 -> Funcionó OK

