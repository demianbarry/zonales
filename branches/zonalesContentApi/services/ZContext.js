var errors = require('../errors/errors');
var zoneService = require('./zones');
var solrService = require('./solr');

var contexts = new Array();

function Source() {
    this.name = "";
    this.checked = false;
}

function Tag() {
    this.name = "";
    this.checked = false;
}

function Filters(){
    this.sources = new Array();
    this.temp = "";
    this.tags = new Array();
}

function ZContext(){
    this.filters = new Filters();
    this.zTabs = new Array();
    this.zTab = "";
    this.selZone = "";
    this.efZone = "";
    this.start=0;
    this.firstIndexTime="";
    this.maxRelevance=0;
    this.searchKeywords=""; 
    
    this.setFirstIndexTime=function(indexTime){
        this.firstIndexTime = indexTime;
    }
    
    this.getFirstIndexTime=function(){
        return this.firstIndexTime;
    }

    this.setMaxRelevance=function(maxRelevance){
        this.maxRelevance = maxRelevance;
    }
    
    this.getMaxRelevance=function(){
        return this.maxRelevance;
    }
    
    this.setSearchKeyword=function(keyword) {
        this.searchKeyword = keyword;
    }

    this.getSearchKeyword=function() {
        return this.searchKeyword;
    }
}

module.exports.newZCtx = function newZCtx(callback) {
    callback = (callback || noop);
    callback(new ZContext());
    return(this);
} 

module.exports.getZCtx = function getZCtx(sessionId, callback) {
    callback = (callback || noop);
    console.log("getZCtx - SESSION ID: " + sessionId);
    var zCtx;
    if (typeof contexts[sessionId] != 'undefined') {
        zCtx = contexts[sessionId];        
        console.log("getZCtz - CONTEXTO EXISTE: " + JSON.stringify(zCtx));
    } else {
        zCtx = new ZContext();
        console.log("getZCtz - CREO NUEVO CONTEXTO: " + JSON.stringify(zCtx));
        contexts[sessionId] = zCtx;
    }    
    callback(zCtx);
    return(this);
}

//SETEO DE FUENTES

module.exports.addSource = function addSource(sessionId, source, callback) {
    callback = (callback || noop);
    
    try {
        var zCtx = contexts[sessionId];
        var index = searchSource(zCtx, source);
        if (index == -1) {
            var sourceObj = new Source();
            sourceObj.name = source;
            sourceObj.checked = true;
            zCtx.filters.sources.push(sourceObj);
        } else {
            zCtx.filters.sources[index].checked = true;
        }
        console.log("CONTEXT EN addSource: " + JSON.stringify(contexts[sessionId]));
        callback(errors.success);
        return(this);
    } catch (err) {
        console.log('Error al agregar una fuente -> ' + err);
        callback(errors.zctxError);
        return(this);
    }
}

module.exports.unckeckSource = function unckeckSource(sessionId, source, callback) {
    callback = (callback || noop);
    
    try {
        var zCtx = contexts[sessionId];
        var index = searchSource(zCtx, source); 
        if (index == -1) {
            var sourceObj = new Source();
            sourceObj.name = source;
            sourceObj.checked = false;
            zCtx.filters.sources.push(sourceObj);
        } else {
            zCtx.filters.sources[index].checked = false;
        }
        callback(errors.success);
        return(this);
    } catch (err) {
        console.log('Error al eliminar una fuente -> ' + err);
        callback(errors.zctxError);
        return(this);
    }
}

//Retorn el Ã­ndice en el array si la fuente ya existe, o -1 en caso contrario
function searchSource(zCtx, sourceStr) {
    if (zCtx.filters.sources.length > 0) {
        for (var i = 0; i < zCtx.filters.sources.length; i++){
            if (zCtx.filters.sources[i].name == sourceStr)
                return i;
        }
    }
    return -1;  
}

//SETEO DE TAGS

module.exports.addTag = function addTag(sessionId, tag, callback) {
    callback = (callback || noop);
    
    try {
        var zCtx = contexts[sessionId];
        var index = searchTag(zCtx, tag);
        if (index == -1) {
            var tagObj = new Tag();
            tagObj.name = tag;
            tagObj.checked = true;
            zCtx.filters.tags.push(tagObj);
        } else {
            zCtx.filters.tags[index].checked = true;
        }
        if(callback)
            callback(errors.success);
        return(this);
    } catch (err) {
        console.log('Error al agregar un tag -> ' + err);
        if(callback)
            callback(errors.zctxError);
        return(this);
    }
}

module.exports.addKeyword = function addKeyword(sessionId, keyword, callback) {
    callback = (callback || noop);
    
    try {
        var zCtx = contexts[sessionId];
        zCtx.setSearchKeyword(keyword);
        
        if(callback)
            callback(errors.success);
    
        return(this);
    } catch (err) {
        console.log('Error al agregar un keyword -> ' + err);
        if(callback)
            callback(errors.zctxError);
        return(this);
    }
}

module.exports.unckeckTag = function unckeckTag(sessionId, tag, callback) {
    callback = (callback || noop);
    
    try {
        var zCtx = contexts[sessionId];
        var index = searchTag(zCtx, tag);               
        if (index == -1) {
            var tagObj = new Tag();
            tagObj.name = tag;
            tagObj.checked = false;
            zCtx.filters.tags.push(tagObj);
        } else {
            zCtx.filters.tags[index].checked = false;
        }
        callback(errors.success);
        return(this);
    } catch (err) {
        console.log('Error al eliminar un tag -> ' + err);
        callback(errors.zctxError);
        return(this);
    }
}

module.exports.setSelectedZone = function setSelectedZone(sessionId, zone, callback) {
    callback = (callback || noop);
    
    try {
        contexts[sessionId].selZone = zone;
        contexts[sessionId].efZone = zone;
        /*getEfectiveZone(contexts[sessionId].zTab, zone, function(efZone) {
           if (efZone != null) {            
                contexts[sessionId].efZone = efZone.id;
            } else {
                contexts[sessionId].efZone = "";
            }
            callback(efZone);
            return(this);
        });*/
        callback(zone);
    } catch (err) {
        console.log('Error al setear la zona seleccionada -> ' + err);
        callback(errors.zctxError);
        return(this);
    }
}

module.exports.setStart = function setStart(sessionId, start) {
    
    try {
        contexts[sessionId].start += start;
    } catch (err) {
        console.log('Error al setear el start -> ' + err);        
        return(this);
    }
}

module.exports.resetStart = function resetStart(sessionId) {
    
    try {
        contexts[sessionId].start = 0;        
    } catch (err) {
        console.log('Error al setear el start -> ' + err);        
        return(this);
    }
}

function getEfectiveZone(tab, zoneId, callback) {
    console.log(zoneId);
    if (zoneId != '' && zoneId != null && typeof(zoneId) != 'undefined') {
        zoneService.get({
            id: zoneId
        }, function(zones) {
            var zone = zones[0];
            solrService.countSolrPost(tab, zone.name, function(cant) {
                if (cant > 0) {
                    callback(zone);
                    return(this);
                } else {
                    if (zone.parent == '0') {
                        callback(null);
                        return(this);
                    } else {
                        getEfectiveZone(tab, zone.parent, function(efZone) {
                            callback(efZone);
                            return(this);
                        });
                    }
                }
            });
        });
    } else {
        callback(null);
        return(this);
    }
}

module.exports.setTemp = function setTemp(sessionId, temp, callback) {
    callback = (callback || noop);
    
    try {
        contexts[sessionId].filters.temp = temp;
        callback(errors.success);
        return(this);
    } catch (err) {
        console.log('Error al setear la temporalidad -> ' + err);
        callback(errors.zctxError);
        return(this);
    }
}

module.exports.setTab = function setTab(sessionId, tab, callback) {
    callback = (callback || noop);
    
    try {
        contexts[sessionId].zTab = tab;
        callback(errors.success);
        return(this);
    } catch (err) {
        console.log('Error al setear el tab -> ' + err);
        callback(errors.zctxError);
        return(this);
    }
}
//Retorn el Ã­ndice en el array si el tag ya existe, o -1 en caso contrario
function searchTag(zCtx, tagStr) {
    if (zCtx.filters.tags.length > 0) {
        for (var i = 0; i < zCtx.filters.tags.length; i++){
            if (zCtx.filters.tags[i].name == tagStr)
                return i;
        }
    }
    return -1;  
}