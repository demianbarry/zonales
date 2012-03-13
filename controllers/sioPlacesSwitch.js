// ----------------------- SERVICES -----------------------

var placeService = require('../services/places');
var zoneService = require('../services/zones');
var placeTypeService = require('../services/placeTypes');
var incIdsService = require('../services/incIds');

module.exports.tryEvent = function tryEvent(client) {


    //EVENTOS RELACIONADOS CON LA GESTION DE PLACES
    //=============================================================================

    client.on('getPlaces', function(name, fn) {
        console.log('Recibi el evento getPlaces desde el cliente');
        placeService.getAll(name, function(data){
            if (typeof(data) != 'undefined') {
                fn(data);
            }
        });
    });

    client.on('getPlacesByName', function(name, fn) {
        console.log('Recibi el evento getPlacesByName desde el cliente');
        placeService.getLikeName(name, function(data){
            if (typeof(data) != 'undefined') {
                fn(data);
            }
        });		
    });
	
    client.on('searchPlaces', function(filters, fn) {
        console.log('Recibi el evento searchPlaces desde el cliente'); 
        placeService.searchPlaces(filters, function(data){
            if (typeof(data) != 'undefined') {
                fn(data);
            }
        });		
    });
	
    client.on('getPlaceByFilters', function(filters, fn) {
        console.log('Recibi el evento getPlaceByFilters desde el cliente');
        placeService.get(filters, function(data){
            if (typeof(data) != 'undefined') {		    
                fn(data);
            }
        });		
    });
	
    client.on('savePlace', function(name, fn) {
        console.log('Recibi el evento savePlace desde el cliente');
        placeService.set(name, function(data){
            if (typeof(data) != 'undefined') {
                zoneService.get({id:name.zone}, function(zone) {
                    if (zone)
                        placeService.updateExtendedString(name, zone.length > 0 ? zone[0] : null);
                });
                fn(data);
            }
        });
    });
	
    client.on('updatePlace', function(name, fn) {
        console.log('Recibi el evento updatePlace desde el cliente');
        //var obj = eval('(' + name + ')');
        placeService.update(name.id, name, function(data){
            if (typeof(data) != 'undefined') {
                zoneService.get({id:name.zone}, function(zone) {
                if (zone)
                    placeService.updateExtendedString(name, zone.length > 0 ? zone[0] : null);
                });
                fn(data);
            }
        });
    });
	
    client.on('removePlace', function(id, fn) {
        console.log('Recibi el evento removePlace desde el cliente');
        placeService.remove(id, function(data){
            if (typeof(data) != 'undefined') {
                fn(data);
            }
        });
    });

    client.on('getPlacesByZone', function(zoneid, fn) {
        console.log('Recibi el evento getPlacesByZone desde el cliente');
        placeService.getPlacesByZone(zoneid, function(data){
            if (typeof(data) != 'undefined') {
                fn(data);
            }
        });
    });

    client.on('getPlaceZone', function(id, fn) {
        console.log('Recibi el evento getZone desde el cliente');
        placeService.getZone(id, function(data){
            if (typeof(data) != 'undefined') {
                fn(data);
            }
        });
    });

    client.on('getPlaceChildrens', function(id, fn) {
        console.log('Recibi el evento getChildrens desde el cliente');
        placeService.getChildrens(id, function(data){
            if (typeof(data) != 'undefined') {
                fn(data);
            }
        });
    });

    client.on('getPlaceParent', function(id, fn) {
        console.log('Recibi el evento getParent desde el cliente');
        placeService.getParent(id, function(data){
            if (typeof(data) != 'undefined') {
                fn(data);
            }
        });
    });

    client.on('getPlaceExtendedString', function(id, fn) {
        console.log('Recibi el evento getExtendedString desde el cliente');
        placeService.getExtendedString(id, function(data){
            if (typeof(data) != 'undefined') {
                fn(data);
            }
        });
    });

    client.on('getUserPlaces', function(userId, fn) {
        console.log('Recibi el evento getUserPlaces desde el cliente');
        placeService.getUserPlaces(userId, function(data){
            if (typeof(data) != 'undefined') {
                fn(data);
            }
        });
    });

    client.on('getNextPlaceId', function(ignored, fn) {
        console.log('Recibi el evento getNextPlaceId desde el cliente');
        incIdsService.getId("places", function(id){
            if (typeof(id) != 'undefined') {
                fn(id);
            }
        });
    });

	
    //EVENTOS RELACIONADOS CON LA GESTION DE TIPOS DE PLACES
    //=============================================================================

    client.on('getPlaceTypes', function(name, fn) {
        console.log('Recibi el evento getPlaceTypes desde el cliente');
        placeTypeService.getAll(name, function(data) {
            if (typeof(data) != 'undefined') {
                fn(data);
            }
        });		
    });
	
    client.on('getPlaceTypesByName', function(name, fn) {
        console.log('Recibi el evento getPlaceTypesByName desde el cliente');
        placeTypeService.getLikeName(name, function(data){
            if (typeof(data) != 'undefined') {
                fn(data);
            }
        });		
    });
	
    client.on('searchPlaceTypes', function(filters, fn) {
        console.log('Recibi el evento searchPlaceTypes desde el cliente'); 
        placeTypeService.searchPlaceTypes(filters, function(data){
            if (typeof(data) != 'undefined') {
                fn(data);
            }
        });		
    });
	
    client.on('getPlaceTypeByFilters', function(filters, fn) {
        console.log('Recibi el evento getPlaceTypeByFilters desde el cliente');
        placeTypeService.get(filters, function(data){
            if (typeof(data) != 'undefined') {		    
                fn(data);
            }
        });		
    });
	
    client.on('getPlaceParentTypes', function(name, fn) {
        console.log('Recibi el evento getPlaceParentTypes desde el cliente');
        placeTypeService.get(name, function(data) {
            console.log("ACACACACACACACACA: " + JSON.stringify(data));
            if (typeof(data) != 'undefined' && typeof(data[0].parents) != 'undefined') {
                fn(data[0].parents);
            }
        });		
    });
	
    client.on('savePlaceType', function(name, fn) {
        console.log('Recibi el evento savePlaceType desde el cliente');
        placeTypeService.set(name, function(data){
            if (typeof(data) != 'undefined') {
                fn(data);
            }
        });
    });
	
    client.on('updatePlaceType', function(name, fn) {
        console.log('Recibi el evento updatePlaceType desde el cliente');
        //var obj = eval('(' + name + ')');
        placeTypeService.update(name.name, name, function(data){
            if (typeof(data) != 'undefined') {
                fn(data);
            }
        });
    });
	
    client.on('removePlaceType', function(name, fn) {
        console.log('Recibi el evento removePlaceType desde el cliente');
        placeTypeService.remove(name, function(data){
            if (typeof(data) != 'undefined') {
                fn(data);
            }
        });
    });
}