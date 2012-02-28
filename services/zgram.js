var mongoose = require('mongoose'),
Schema = mongoose.Schema;
var errors = require('../errors/errors');
var baseService = require('./baseService');

//Esquema JSON para las zonas
var zGramSchema = new Schema(
{
    _id : ObjectId,
    cod : Number, 
    msg : String, 
    descripcion : String, 
    localidad : String, 
    fuente : String,
    tags : [String],
    uriFuente : String, 
    criterios : [{
                    deLosUsuarios : [String] 
                }, 
                {
                    deLosUsuariosLatitudes : [Number] 
                },
                {
                    deLosUsuariosLongitudes : [Number] 
                },
                {
                    palabras : [String] 
                }, 
                {
                    amigosDe : String
                }],
    noCriterios : [{
                    deLosUsuarios : [String] 
                }, 
                {
                    deLosUsuariosLatitudes : [Number] 
                },
                {
                    deLosUsuariosLongitudes : [Number] 
                },
                {
                    palabras : [String] 
                }, 
                {
                    amigosDe : String
                }],
    comentarios : null, "filtros" : null, "tagsFuente" : false, "incluyeComentarios" : false, "estado" : "published", "verbatim" : "**\"Teatro Argentino de La Plata, Fb\"** extraer para la localidad \"La Plata\" mediante la fuente facebook asignando los tags \"Cultura\" a partir de los usuarios \"137422022940872\" [-34.91773727902449,-57.95164959895021] incluye comentarios.", "periodicidad" : 20, "siosi" : false, "ultimaExtraccionConDatos" : NumberLong("1329313405117"), "ultimoHitDeExtraccion" : NumberLong("1329313405117"), "sourceLatitude" : null, "sourceLongitude" : null, "creado" : NumberLong("1326813824887"), "creadoPor" : "Anonimo", "modificado" : NumberLong("1329313405121"), "modificadoPor" : "Anonimo" }

    
    
    id : { type: String, unique: true }, //Este campo debe ser único. No es igual al ID que le pone MongoDB
    name : String,
    parent : String, //ID de la zona padre
    type : String, //Debe ser el nombre de un tipo de zona existente (zGramType)
    state : {
        type:String,
        enum:['generated', 'published', 'unpublished', 'void'],
    default: 'generated'
    }, ////Generado, publicado, despublicado, anulado
    geoData: String, //Debe ser un id de un dato geográfico existente
    extendedString : { type: String }
}
);

//Conecto con la DB y seteo el Model (TODO: Ver tema de la conexión para unificarla para todos los models)
mongoose.connect('mongodb://localhost/crawl');
var zGrams = mongoose.model('extractions', zGramSchema);
//module.exports.zGram = zGrams;

//Retorna id y nombre de todas las zonas
module.exports.getAll = function getAll(short, callback) {
    return baseService.getAll(zGrams, short, '["id", "name"]', callback);
}

//Retorna un conjunto de zonas de acuerdo a los filtros utilizados 
module.exports.get = function get(filters, callback) {
    return baseService.get(zGrams, filters, callback);
}

//Retorna un conjunto de zonas con nombre similar al parámetro
module.exports.getLikeName = function getLikeName(name, callback) {
    return baseService.getLikeName(zGrams, name, callback);
}

//Retorna un conjunto de zonas con nombre similar al parámetro
module.exports.searchzGrams = function searchzGrams(filters, callback) {
    return baseService.searchData(zGrams, filters, callback);
}

//Crea una nueva zona
module.exports.set = function set(zGram, callback) {
    return baseService.set(zGrams, zGram, callback);
}

//Actualiza una zona existente (búsqueda por ID)
module.exports.update = function update(id, data, callback) {
    return baseService.update(zGrams, 'id', id, data, callback);
}

//Elimina una zona existente (búsqueda por ID) 
module.exports.remove = function remove(id, callback) {
    return baseService.remove(zGrams, 'id', id, callback);
}

module.exports.zGramsExtendedString = function (){

}

module.exports.getExtendedString = function(zGram, parent){
    getExtendedString(zGram, parent);
};

function getExtendedString(zGram, parent){
    zGram.extendedString = zGram.name;
    if (parent)
        zGram.extendedString += ", "+parent.extendedString;
    baseService.get(zGrams, JSON.parse('{"parent":"'+zGram.id+'"}'), function(leaves){
        leaves.forEach (function (leaf) {
            console.log('---------> ZONA: '+ zGram.name);
            console.log(JSON.stringify(leaf._doc));
            getExtendedString(leaf._doc, zGram);
        });
    });
    delete zGram._id;
    baseService.update(zGrams, 'id', zGram.id, zGram, function(){});
    return zGram.extendedString;
}

