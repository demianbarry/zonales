var mongoose = require('mongoose'),
Schema = mongoose.Schema;
var errors = require('../errors/errors');
var baseService = require('./baseService');

//Esquema JSON para las zonas
var zoneSchema = new Schema(
{
    id : { type: String, unique: true }, //Este campo debe ser único. No es igual al ID que le pone MongoDB
    name : String,
    parent : String, //ID de la zona padre
    type : String, //Debe ser el nombre de un tipo de zona existente (zoneType)
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
var zones = mongoose.model('zones', zoneSchema);
//module.exports.zone = zones;

//Retorna id y nombre de todas las zonas
module.exports.getAll = function getAll(short, callback) {
    return baseService.getAll(zones, short, '["id", "name"]', callback);
}

//Retorna un conjunto de zonas de acuerdo a los filtros utilizados 
module.exports.get = function get(filters, callback) {
    return baseService.get(zones, filters, callback);
}

//Retorna un conjunto de zonas con nombre similar al parámetro
module.exports.getLikeName = function getLikeName(name, callback) {
    return baseService.getLikeName(zones, name, callback);
}

//Retorna un conjunto de zonas con nombre similar al parámetro
module.exports.searchZones = function searchZones(filters, callback) {
    return baseService.searchData(zones, filters, callback);
}

//Crea una nueva zona
module.exports.set = function set(zone, callback) {
    return baseService.set(zones, zone, callback);
}

//Actualiza una zona existente (búsqueda por ID)
module.exports.update = function update(id, data, callback) {
    return baseService.update(zones, 'id', id, data, callback);
}

//Elimina una zona existente (búsqueda por ID) 
module.exports.remove = function remove(id, callback) {
    return baseService.remove(zones, 'id', id, callback);
}

module.exports.zonesExtendedString = function (){

}

module.exports.updateExtendedString = function(zone, parent){
    updateExtendedString(zone, parent);
};

function updateExtendedString(zone, parent){
    zone.extendedString = zone.name;
    if (parent)
        zone.extendedString += ", "+parent.extendedString;

    baseService.get(zones, JSON.parse('{"parent":"'+zone.id+'"}'), function(leaves){
        leaves.forEach (function (leaf) {
            console.log('---------> ZONA: '+ zone.name);
            console.log(JSON.stringify(leaf._doc));
            getExtendedString(leaf._doc, zone);
        });
    });
    delete zone._id;
    baseService.update(zones, 'id', zone.id, zone, function(){});
    return zone.extendedString;
}

