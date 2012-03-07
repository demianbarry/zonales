// ----------------------- SERVICES -----------------------

var tagService = require('../services/tags');
var tagTypeService = require('../services/tagTypes');

module.exports.tryEvent = function tryEvent(client) {

	
    //EVENTOS RELACIONADOS CON LA GESTION DE TAGS
    //=============================================================================

    client.on('getTags', function(name, fn) {
        console.log('Recibi el evento getTags desde el cliente');
        tagService.getAll(name, function(data){
            if (typeof(data) != 'undefined') {
                fn(data);
            }
        });
    });

    client.on('getTagsByName', function(name, fn) {
        console.log('Recibi el evento getTagsByName desde el cliente');
        tagService.getLikeName(name, function(data){
            if (typeof(data) != 'undefined') {
                fn(data);
            }
        });		
    });
	
    client.on('searchTags', function(filters, fn) {
        console.log('Recibi el evento searchTags desde el cliente'); 
        tagService.searchTags(filters, function(data){
            if (typeof(data) != 'undefined') {
                fn(data);
            }
        });		
    });
	
    client.on('getTagByFilters', function(filters, fn) {
        console.log('Recibi el evento getTagByFilters desde el cliente');
        tagService.get(filters, function(data){
            if (typeof(data) != 'undefined') {		    
                fn(data);
            }
        });		
    });
	
    client.on('saveTag', function(name, fn) {
        console.log('Recibi el evento saveTag desde el cliente');
        tagService.set(name, function(data){
            if (typeof(data) != 'undefined') {
                fn(data);
            }
        });
    });
	
    client.on('updateTag', function(name, fn) {
        console.log('Recibi el evento updateTag desde el cliente');
        //var obj = eval('(' + name + ')');
        tagService.update(name.id, name, function(data){
            if (typeof(data) != 'undefined') {
                fn(data);
            }
        });
    });
	
    client.on('removeTag', function(id, fn) {
        console.log('Recibi el evento removeTag desde el cliente');
        tagService.remove(id, function(data){
            if (typeof(data) != 'undefined') {
                fn(data);
            }
        });
    });
	
    //EVENTOS RELACIONADOS CON LA GESTION DE TIPOS DE TAGS
    //=============================================================================

    client.on('getTagsTypes', function(name, fn) {
        console.log('Recibi el evento getTagsTypes desde el cliente');
        tagTypeService.getAll(name, function(data) {
            if (typeof(data) != 'undefined') {
                fn(data);
            }
        });		
    });
	
    client.on('getTagTypesByName', function(name, fn) {
        console.log('Recibi el evento getTagTypesByName desde el cliente');
        tagTypeService.getLikeName(name, function(data){
            if (typeof(data) != 'undefined') {
                fn(data);
            }
        });		
    });
	
    client.on('searchTagTypes', function(filters, fn) {
        console.log('Recibi el evento searchTagTypes desde el cliente'); 
        tagTypeService.searchTagTypes(filters, function(data){
            if (typeof(data) != 'undefined') {
                fn(data);
            }
        });		
    });
	
    client.on('getTagTypeByFilters', function(filters, fn) {
        console.log('Recibi el evento getTagTypeByFilters desde el cliente');
        tagTypeService.get(filters, function(data){
            if (typeof(data) != 'undefined') {		    
                fn(data);
            }
        });		
    });
	
    client.on('getTagParentTypes', function(name, fn) {
        console.log('Recibi el evento getParentTypes desde el cliente');
        tagTypeService.get(name, function(data) {
            if (typeof(data) != 'undefined' && typeof(data[0].parents) != 'undefined') {
                fn(data[0].parents);
            }
        });		
    });
	
    client.on('saveTagType', function(name, fn) {
        console.log('Recibi el evento saveTagType desde el cliente');
        tagTypeService.set(name, function(data){
            if (typeof(data) != 'undefined') {
                fn(data);
            }
        });
    });
	
    client.on('updateTagType', function(name, fn) {
        console.log('Recibi el evento updateTagType desde el cliente');
        //var obj = eval('(' + name + ')');
        tagTypeService.update(name.name, name, function(data){
            if (typeof(data) != 'undefined') {
                fn(data);
            }
        });
    });
	
    client.on('removeTagType', function(name, fn) {
        console.log('Recibi el evento removeTagType desde el cliente');
        tagTypeService.remove(name, function(data){
            if (typeof(data) != 'undefined') {
                fn(data);
            }
        });
    });	
	
}
