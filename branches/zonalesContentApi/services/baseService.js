var mongoose = require('mongoose'),
Schema = mongoose.Schema;
var errors = require('../errors/errors');

//Retorna id y nombre de todos los registros
//short: registro corto, solos los fields indicados en el campo fields
//fields: formato [<field1>,<field2>,..]
module.exports.getAll = function getAll(model, short, fields, callback) {
    // Make sure a callback is defined.
    callback = (callback || noop);
	
    try {
        if (short) {
            var fields = eval('(' + fields + ')');
            model.find({}, fields, function(err, docs) {
                if (err) {
                    console.log('Error obteniendo datos --> ' + err);
                    throw errors.apiError; 
                }
                callback(docs);
                return(this);
            });
        } else {
            model.find({}, function(err, docs) {
                if (err) {
                    console.log('Error obteniendo datos --> ' + err);
                    throw errors.apiError; 
                }
                callback(docs);
                return(this);
            });
        }
    } catch (err) {
        console.log('Error --> ' + err);
        throw errors.apiError;
    }
}

//Retorna un conjunto de datos de acuerdo a los filtros utilizados 
module.exports.get = function get(model, filters, callback) {
    // Make sure a callback is defined.
    callback = (callback || noop);
	
    try {
        //var filtros = JSON.parse(filters);
        model.find(filters, function(err, docs) {
            if (err) {
                throw errors.apiError;
            }
            //console.log(JSON.stringify(docs));
            callback(docs);
            return(this);
        });
    } catch (err) {
        console.log('Error --> ' + err);
        throw errors.apiError;
    }
}

//Retorna un conjunto de datos con nombre similar al parámetro
module.exports.getLikeName = function getLikeName(model, name, callback) {
    // Make sure a callback is defined.
    callback = (callback || noop);

    try {
        var myregex = RegExp(name);
        model.find({
            "name": myregex
        }, function(err, docs) {
            if (err) {
                console.log('Error obteniendo datos --> ' + err);
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

//Retorna un conjunto de datos con nombre similar al parámetro
module.exports.searchData = function searchData(model, filters, callback) {
    // Make sure a callback is defined.
    callback = (callback || noop);

    try {
        //var myregex = RegExp(name);
        //TODO: Chanchada, arreglar esto con parámetros
        if (typeof(filters.name) != 'undefined') {
            filters.name = RegExp(filters.name);
        }
        if (typeof(filters.zone) != 'undefined') {
            filters.zone = RegExp(filters.zone);
        }
        model.find(filters, function(err, docs) { 
            if (err) {
                console.log('Error obteniendo datos --> ' + err);
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

//Crea una nueva dato
module.exports.set = function set(model, data, callback) {
    // Make sure a callback is defined.
    callback = (callback || noop);

    try {
        var odata = new model(data);
        odata.save(function(err) {
            if (err) {
                console.log('Error guardando el dato --> ' + err);
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

//Actualiza un dato existente
module.exports.update = function update(model, searchFieldName, searchFieldData, data, callback, fields) {
    // Make sure a callback is defined.
    callback = (callback || noop);
	
    try {
        var oid = JSON.parse('{"' + searchFieldName + '":"' + searchFieldData + '"}');        
        if(fields && typeof fields == 'object' && typeof fields.forEach == 'function'){
            var reg = new Object();    
            fields.forEach(function(field){
                if(data[field])
                    reg[field] = data[field];
            });
            //console.log('---------> ID: '+JSON.stringify(oid) + ' --- OBJ: '+JSON.stringify(reg));
            delete reg._id;
            
            model.update(oid, reg, function(err) {
                if (err) {
                    console.log('Error actualizando el dato --> ' + err);
                    throw errors.apiError;
                }
                callback(errors.success);
                return(this);
            });            
        } else {        
            //console.log('---------> ID: '+JSON.stringify(oid) + ' --- OBJ: '+JSON.stringify(data));
            model.update(oid, data, function(err) {
                if (err) {
                    console.log('Error actualizando el dato --> ' + err);
                    throw errors.apiError;
                }
                callback(errors.success);
                return(this);
            });
        }
    } catch (err) {
        console.log('Error --> ' + JSON.stringify(err));
        throw errors.apiError;
    }
}

//Actualiza un dato existente
module.exports.upsert = function upsert(model, searchFieldName, searchFieldData, data, callback) {
    // Make sure a callback is defined.
    callback = (callback || noop);
	
    try {
        var oid = JSON.parse('{"' + searchFieldName + '":"' + searchFieldData + '"}');        
        model.update(oid, data, {
            upsert: true
        }, function(err) {
            if (err) {
                console.log('Error actualizando el dato --> ' + err);
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

//Elimina un dato existente  
module.exports.remove = function remove(model, searchFieldName, searchFieldData, callback) {
    // Make sure a callback is defined.
    callback = (callback || noop);
	
    try {
        var oid = JSON.parse('{"' + searchFieldName + '":"' + searchFieldData + '"}');
        model.findOne(oid, function(err, data) {
            if (err) {
                console.log('Error eliminando el dato --> ' + err);
                throw errors.apiError;
            }
            data.remove(function(err) {
                if (err) {
                    console.log('Error eliminando el dato --> ' + err);
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

