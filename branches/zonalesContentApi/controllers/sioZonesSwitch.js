// ----------------------- SERVICES -----------------------

var zoneService = require('../services/zones');
var zoneTypeService = require('../services/zoneTypes');
var incIdsService = require('../services/incIds');
var i18n = require('i18n');
i18n.configure({
    //
    locales:['es'],
    //
    //register: global,
    directory: '../locales'
});

module.exports.tryEvent = function tryEvent(client) {

    //EVENTOS RELACIONADOS CON LA GESTION DE ZONAS
    //=============================================================================

    client.on('getZones', function(name, fn) {
        console.log('Recibi el evento getZones desde el cliente');
        zoneService.getAll(name, function(data){
            if (typeof(data) != 'undefined') {
                data.forEach(function(zone){
                    zone.name = i18n.__(zone.name);    
                });                
                fn(data);
            }
        });
    });

    client.on('getZonesExtendedString', function(name, fn) {
        console.log('Recibi el evento getZonesExtendedString desde el cliente');
        zoneService.getAllExtendedStrings(function(data){
            if (typeof(data) != 'undefined') {
                data.forEach(function(zone){
                    zone.extendedString = i18n.__(zone.extendedString);
                });
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
	
    client.on('searchZones', function(filters, fn) {
        console.log('Recibi el evento searchZones desde el cliente'); 
        zoneService.searchZones(filters, function(data){
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
	
    client.on('saveZone', function(name, fn) {
        console.log('Recibi el evento saveZone desde el cliente');
        zoneService.set(name, function(data){
            if (typeof(data) != 'undefined') {
                zoneService.get({
                    id:name.parent
                }, function(parent) {
                    if (parent)
                        zoneService.updateExtendedString(name, parent.length > 0 ? parent[0] : null);
                });
                fn(data);
            }
        });
    });
	
    client.on('updateZone', function(name, fn) {
        console.log('Recibi el evento updateZone desde el cliente');
        //var obj = eval('(' + name + ')');
        zoneService.update(name.id, name, function(data){
            if (typeof(data) != 'undefined') {
                zoneService.get({
                    id:name.parent
                }, function(parent) {
                    if (parent)
                        zoneService.updateExtendedString(name, parent.length > 0 ? parent[0] : null);
                });
                fn(data);
            }
        });
    });
	
    client.on('removeZone', function(id, fn) {
        console.log('Recibi el evento removeZone desde el cliente');
        zoneService.remove(id, function(data){
            if (typeof(data) != 'undefined') {
                fn(data);
            }
        });
    });

    client.on('getZoneByExtendedString', function(id, fn) {
        console.log('Recibi el evento getExtendedString desde el cliente');
        zoneService.getExtendedString(id, function(data){
            if (typeof(data) != 'undefined') {
                fn(data);
            }
        });
    });

    client.on('addGeoDataToZone', function(data, fn) {
        console.log('Recibi el evento addGeoDataToZone desde el cliente');
        console.log("------------_>>>PARAMETROS: " + JSON.stringify(data));
        console.log("------------_>>>ENDPARAMETROS: ");
        zoneService.addGeoDataToZone(data.zone, data.geodata, function(response){
            if (typeof(response) != 'undefined') {
                fn(response);
            }
        });
    });

	
    //EVENTOS RELACIONADOS CON LA GESTION DE TIPOS DE ZONAS
    //=============================================================================

    client.on('getZonesTypes', function(name, fn) {
        console.log('Recibi el evento getZonesTypes desde el cliente');
        zoneTypeService.getAll(name, function(data) {
            if (typeof(data) != 'undefined') {
                fn(data);
            }
        });		
    });
	
    client.on('getZoneTypesByName', function(name, fn) {
        console.log('Recibi el evento getZoneTypesByName desde el cliente');
        zoneTypeService.getLikeName(name, function(data){
            if (typeof(data) != 'undefined') {
                fn(data);
            }
        });		
    });
	
    client.on('searchZoneTypes', function(filters, fn) {
        console.log('Recibi el evento searchZoneTypes desde el cliente'); 
        zoneTypeService.searchZoneTypes(filters, function(data){
            if (typeof(data) != 'undefined') {
                fn(data);
            }
        });		
    });
	
    client.on('getZoneTypeByFilters', function(filters, fn) {
        console.log('Recibi el evento getZoneTypeByFilters desde el cliente');
        zoneTypeService.get(filters, function(data){
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
	
    client.on('saveZoneType', function(name, fn) {
        console.log('Recibi el evento saveZoneType desde el cliente');
        zoneTypeService.set(name, function(data){
            if (typeof(data) != 'undefined') {
                fn(data);
            }
        });
    });
	
    client.on('updateZoneType', function(name, fn) {
        console.log('Recibi el evento updateZoneType desde el cliente');
        //var obj = eval('(' + name + ')');
        zoneTypeService.update(name.name, name, function(data){
            if (typeof(data) != 'undefined') {
                fn(data);
            }
        });
    });
	
    client.on('removeZoneType', function(name, fn) {
        console.log('Recibi el evento removeZoneType desde el cliente');
        zoneTypeService.remove(name, function(data){
            if (typeof(data) != 'undefined') {
                fn(data);
            }
        });
    });
}
