var express = require('express'),
i18n = require("i18n");
var mongoose = require('mongoose'),
Schema = mongoose.Schema;
var errors = require('../errors/errors');
var baseService = require('./baseService');
var geoService = require('./geoData');
var incIdsService = require('./incIds');
i18n.configure({
    //
    locales:['es'],
    //
    //register: global,
    directory: './locales'
});

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
    extendedString : { type: String, unique: true }
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

module.exports.getAllExtendedStrings = function getAllExtendedStrings(callback) {
    return baseService.getAll(zones, true, '["id", "extendedString"]', callback);
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
    filters.name = i18n.r__(filters.name);    
    return baseService.get(zones, {'name': new RegExp(filters.name,'i'), 'type': new RegExp(filters.type,'i'),'state': new RegExp(filters.state,'i')}, function(docs){
        docs.forEach(function(doc){
            doc.name = i18n.__(doc.name);
        });
        callback(docs);
    });
}

//Crea una nueva zona
module.exports.set = function set(zone, callback) {
    incIdsService.getId("zones", function(id) {
        console.log("------>>>>>>----->>>>> ZONES NextId: " + id);
        zone.id = id;
        var zoneNorm = normZone(zone.name);
        zone.extendedString = zone.name;
        i18n.add(zoneNorm,zone.name);
        zone.name = zoneNorm;
        baseService.set(zones, zone, function(response) {
            response.id = id;
            callback(response);
            return(this);
        });
    })
    
}

//Actualiza una zona existente (búsqueda por ID)
module.exports.update = function update(id, data, callback) {
    return baseService.update(zones, 'id', id, data, callback, ['parent', 'type', 'state', 'geoData']);
}

//Elimina una zona existente (búsqueda por ID) 
module.exports.remove = function remove(id, callback) {
    return baseService.remove(zones, 'id', id, callback);
}

module.exports.getExtendedString = function getExtendedString(id, callback) {
    this.get({id:id}, function(zone) {
        if (typeof(zone) != 'undefined' && zone != null && typeof(zone[0]) != 'undefined' && zone[0] != null) {
            callback(zone[0].extendedString);
        }
    });
}

module.exports.getZoneById = function getZoneById(id, callback) {
    this.get({id:id}, function(zones) {
        if (typeof(zones) != 'undefined' && zones != null && typeof(zones[0]) != 'undefined' && zones[0] != null && callback) {
            callback(zones[0]);
        }
    });
}

module.exports.getZoneByExtendedString = function getZoneByExtendedString(extendedString, callback) {
    console.log('ZONE SERVICE, cadena: ' + extendedString);
    this.get({extendedString:extendedString}, function(zones) {
        console.log('ZONE SERVICE: ' + JSON.stringify(zones));
        if (typeof(zones) != 'undefined' && zones != null && typeof(zones[0]) != 'undefined' && zones[0] != null && callback) {
            callback(zones[0]);
        } else {
            callback(null);
        }
    });
}

module.exports.updateExtendedString = function(zone, parent){
    updateExtendedString(zone, parent);
};

function updateExtendedString(zone, parent){
    zone.extendedString = normZone(zone.name);

    if (parent)
        zone.extendedString += ", " + parent.extendedString;

    baseService.get(zones, {parent:zone.id}, function(leaves){
        leaves.forEach (function (leaf) {
            console.log('---------> ZONA: '+ zone.name);
            console.log(JSON.stringify(leaf._doc));
            updateExtendedString(leaf._doc, zone);
        });
    });
    delete zone._id;
    i18n.add(zone.extendedString, i18n.__(zone.name)+', '+i18n.__(parent.extendedString)); 
    baseService.update(zones, 'id', zone.id, zone, function(){});
    return zone.extendedString;
}

function normZone(word) {

        word = word.toLowerCase();
        word = word.replace(/ /g, '_');
        word = word.replace(/à|á|ä|â/, "a");
        word = word.replace(/è|é|ë|ê/, "e");
        word = word.replace(/ì|í|ï|î/, "i");
        word = word.replace(/ò|ó|ö|ô/, "o");
        word = word.replace(/ù|ú|ü|û/, "u");

        return word;

}

module.exports.addGeoDataToZone = function(zone, geodata, callback) {
    geoService.set(geodata, function(response) {
        console.log("------------_>>> GEODATA GUARDADO, ID: " + response.id);
        if (typeof(response) != 'undefined' && response != null && typeof(response.id) != 'undefined' && response.id != null) {
            zone.geoData = response.id;
            delete zone._id;
            baseService.update(zones, 'id', zone.id, zone, function(resp) {
                console.log("------------_>>> ZONA ACTUALIZADA, RESP: " + JSON.stringify(resp));
                callback(resp);
                return(this);
            });
        }
    });
}

