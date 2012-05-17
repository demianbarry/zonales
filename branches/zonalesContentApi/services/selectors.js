var mongoose = require('mongoose'),
Schema = mongoose.Schema;
var errors = require('../errors/errors');
var baseService = require('./baseService');

//Esquema JSON para los selectores
var selectorSchema = new Schema(
{
    type : String,
    selector : String,
    validator : String
}
);

var selectorsSchema = new Schema(
{
    url : {
        type: String, 
        unique: true
    },
    urlLogo : String,
    selectors : [selectorSchema]  //Array de links a sitios relacionados con la zona  --  OPCIONAL
}
);

//Conecto con la DB y seteo el Model (TODO: Ver tema de la conexión para unificarla para todos los models)
mongoose.connect('mongodb://localhost/crawl');
var selectors = mongoose.model('feedselector', selectorsSchema, 'feedselector');

//Retorna id y nombre de todas los selectors
module.exports.getAll = function getAll(shortVersion, callback) {
    return baseService.getAll(selectors, shortVersion, '["url", "selectors"]', callback);
}

//Retorna un conjunto de selectors de acuerdo a los filtros utilizados 
module.exports.get = function get(filters, callback) {    
    return baseService.get(selectors, filters, callback);
}

//Retorna un conjunto de selectors con nombre similar al parámetro
module.exports.searchSelectors = function searchSelectors(filters, callback) {
    return baseService.searchData(selectors, filters, callback);
}

//Crea un nuevo selector
module.exports.set = function set(selector, callback) {    
    baseService.set(selectors, selector, function(response) {            
        callback(response);
        return(this);
    });    
}

//Actualiza un selector existente (búsqueda por ID)
module.exports.update = function update(url, data, callback) {
    return baseService.update(selectors, 'url', url, data, callback);
}

module.exports.upsert = function upsert(url, data, callback) {
    return baseService.upsert(selectors, 'url', url, data, callback);
}

//Elimina un selector existente (búsqueda por ID) 
module.exports.remove = function remove(url, callback) {
    return baseService.remove(selectors, 'url', url, callback);
}

module.exports.getSelectorsByURL = function getSelectorsByURL(url, callback) {
    this.get({
        url:url
    }, function(selectors) {
        if (typeof(selectors) != 'undefined' && selectors != null && callback) {
            callback(selectors);
        }
    });
}