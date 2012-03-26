// ----------------------- SERVICES -----------------------

var geoDataService = require('../services/geoData');
var incIdsService = require('../services/incIds');

module.exports.tryEvent = function tryEvent(client) {

	
    //EVENTOS RELACIONADOS CON LA GESTION DE DATOS GEOGRAFICOS
    //=============================================================================

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
        console.log('Recibi el evento updateGeoData desde el cliente');
        //var obj = eval('(' + name + ')');
        geoDataService.update(name.id, name, function(data){
            if (typeof(data) != 'undefined') {
                fn(data);
            }
        });
    });

    client.on('drawChildren', function(name, fn) {
        console.log('Recibi el evento drawChildren desde el cliente');
        geoDataService.drawChildren(name.extendedString, client);
        fn("OK");
    });

    client.on('getGeoDataByZoneExtendedString', function(name, fn) {
        console.log('Recibi el evento getGeoDataByZoneExtendedString desde el cliente');
        geoDataService.getGeoDataByZoneExtendedString(name.extendedString, function(data) {
            if (typeof(data) != 'undefined') {
                fn(data);
            } 
        });
    });    
	
}
