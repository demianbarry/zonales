// ----------------------- SERVICES -----------------------

var selectorsService = require('../services/selectors');

module.exports.tryEvent = function tryEvent(client) {


    //EVENTOS RELACIONADOS CON LA GESTION DE SelectorS
    //=============================================================================

    client.on('searchSelectors', function(searchObj, fn) {
        console.log('Recibi el evento searchSelectors desde el cliente');        
        selectorsService.get(searchObj, function(data){
            if (typeof(data) != 'undefined') {
                fn(data);
            }
        });
    });
    
    client.on('getSelectors', function(url, fn) {
        console.log('Recibi el evento getSelectors desde el cliente');        
        selectorsService.get({}, function(data){
            if (typeof(data) != 'undefined') {
                fn(data);
            }
        });
    });

    client.on('getSelector', function(url, fn) {
        console.log('Recibi el evento getSelector desde el cliente');        
        selectorsService.get({
            url: url
        }, function(data){
            if (typeof(data) != 'undefined') {
                if(data.length > 0)
                    fn(data[0]);
                else
                    fn();
            }
        });
    });
    
	
    client.on('saveSelector', function(selector, fn) {
        console.log('Recibi el evento saveSelector desde el cliente');
        selectorsService.set(selector, function(data){
            if (typeof(data) != 'undefined') {                
                fn(data);
            }
        });
    });
	
    client.on('updateSelector', function(selector, fn) {
        console.log('Recibi el evento updateSelector desde el cliente');
        //var obj = eval('(' + name + ')');
        selectorsService.update(selector.url, selector, function(data){
            if (typeof(data) != 'undefined') {                
                fn(data);
            }
        });
    });
    
    client.on('upsertSelector', function(selector, fn) {
        console.log('Recibi el evento upsertSelector desde el cliente');
        //var obj = eval('(' + name + ')');
        selectorsService.upsert(selector.url, selector, function(data){
            if (typeof(data) != 'undefined') {                
                fn(data);
            }
        });
    });
	
    client.on('removeSelector', function(url, fn) {
        console.log('Recibi el evento removeSelector desde el cliente');
        selectorsService.remove(url, function(data){
            if (typeof(data) != 'undefined') {
                fn(data);
            }
        });
    });    
}