// ----------------------- SERVICES -----------------------

var zoneService = require('../services/zones');
var zoneTypeService = require('../services/zoneTypes');
var placeService = require('../services/places');
var placeTypeService = require('../services/placeTypes');
var geoDataService = require('../services/geoData');
var tagService = require('../services/tags');
var tagTypeService = require('../services/tagTypes');
var zContextService = require('../services/ZContext');
var zProxyService = require('../services/zProxy');
var solrService = require('../services/solr');

module.exports.tryEvent = function tryEvent(client) {

	//EVENTOS RELACIONADOS CON LA GESTION DE ZONAS
	//=============================================================================

	client.on('getZones', function(name, fn) {
		console.log('Recibi el evento getZones desde el cliente');
		zoneService.getAll(name, function(data){
		  if (typeof(data) != 'undefined') {
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
                    zoneService.get({id:name.parent}, function(parent) {
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
                    zoneService.get({id:name.parent}, function(parent) {
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
		console.log('Recibi el evento updateZone desde el cliente');
		//var obj = eval('(' + name + ')');
		geoDataService.update(name.id, name, function(data){
		  if (typeof(data) != 'undefined') {
		    fn(data);
		  }
	   });
	});
	
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
	
	//EVENTOS RELACIONADOS CON LA GESTION DEL CONTEXTO DE ZONALES
	//=============================================================================

	client.on('getCtx', function(sessionId, fn) {
		console.log('Recibí evento getCtx - SessionId: ' + sessionId);
		zContextService.getZCtx(sessionId, function(zCtx) {
			fn(zCtx);
		});	 
	});
	
	client.on('addSourceToZCtx', function(data, fn) {
		console.log('Recibí evento addSourceToZCtx, fuente = ' + data.source);
		zContextService.addSource(data.sessionId, data.source, function (response) {
			fn(response);
			loadPostsFromSolr(client, data.sessionId);
		});
	});
	
	client.on('uncheckSourceFromZCtx', function(data, fn) {
		console.log('Recibí evento uncheckSourceFromZCtx, fuente = ' + data.source);
		zContextService.unckeckSource(data.sessionId, data.source, function (response) {
			fn(response);
			loadPostsFromSolr(client, data.sessionId);
		});
	});
	
	client.on('addTagToZCtx', function(data, fn) {
		console.log('Recibí evento addTagToZCtx, tag = ' + data.tag);
		zContextService.addTag(data.sessionId, data.tag, function (response) {
			fn(response);
			loadPostsFromSolr(client, data.sessionId);
		});
	});
	
	client.on('uncheckTagFromZCtx', function(data, fn) {
		console.log('Recibí evento uncheckTagFromZCtx, tag = ' + data.tag);
		zContextService.unckeckTag(data.sessionId, data.tag, function (response) {
			fn(response);
			loadPostsFromSolr(client, data.sessionId);
		});
	});
	
	client.on('setSelectedZoneToCtx', function(data, fn) {
		console.log('Recibí evento setSelectedZoneToCtx, zone = ' + data.zone);
		zContextService.setSelectedZone(data.sessionId, data.zone, function (response) {
			fn(response);
			loadPostsFromSolr(client, data.sessionId);
		});
	});
	
	client.on('setTempToCtx', function(data, fn) {
		console.log('Recibí evento setTempToCtx, temp = ' + data.temp);
		zContextService.setTemp(data.sessionId, data.temp, function (response) {
			fn(response);
			loadPostsFromSolr(client, data.sessionId);
		});
	});
	
	client.on('setTabToCtx', function(data, fn) {
		console.log('Recibí evento setTabToCtx, tab = ' + data.tab);
		zContextService.setTab(data.sessionId, data.tab, function (response) {
			fn(response);
			loadPostsFromSolr(client, data.sessionId);
		});
	});
	
	//EVENTOS RELACIONADOS CON EL PROXY
	//=============================================================================

	client.on('proxyExecute', function(data, fn) {
		console.log('Recibí evento proxyExecute');
		zProxyService.execute(data.host, data.port, data.path, data.method, function (response) {
			fn(response);
		});
	});

	client.on('proxyExecuteSolrCommand', function(data, fn) {
		console.log('Recibí evento proxyExecuteSolrCommand');
		zProxyService.solrCommand(data.data, data.commit, function (response) {
			fn(response);
		});
	});



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
		    fn(data);
		  }
	   });
	});
	
	client.on('updatePlace', function(name, fn) {
		console.log('Recibi el evento updatePlace desde el cliente');
		//var obj = eval('(' + name + ')');
		placeService.update(name.id, name, function(data){
		  if (typeof(data) != 'undefined') {
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

function loadPostsFromSolr(client, sessionId){
	zContextService.getZCtx(sessionId, function(zCtx){
		solrService.retrieveSolrPosts(zCtx.tab, zCtx.efZone, function(resp){
			client.emit('solrPosts',{response: resp});
		}); 
	});
}