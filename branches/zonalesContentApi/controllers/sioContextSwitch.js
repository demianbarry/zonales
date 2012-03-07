// ----------------------- SERVICES -----------------------

var zContextService = require('../services/ZContext');
var solrService = require('../services/solr');

module.exports.tryEvent = function tryEvent(client) {

	
    //EVENTOS RELACIONADOS CON LA GESTION DEL CONTEXTO DE ZONALES
    //=============================================================================

    client.on('getCtx', function(sessionId, fn) {
        console.log('RecibÃ­ evento getCtx - SessionId: ' + sessionId);
        zContextService.getZCtx(sessionId, function(zCtx) {
            zCtx.start = 0;
            fn(zCtx);
        });	 
    });
	
    client.on('addSourceToZCtx', function(data, fn) {
        console.log('RecibÃ­ evento addSourceToZCtx, fuente = ' + data.source);
        zContextService.addSource(data.sessionId, data.source, function (response) {
            fn(response);
        //loadPostsFromSolr(client, data.sessionId);
        });
    });
	
    client.on('uncheckSourceFromZCtx', function(data, fn) {
        console.log('RecibÃ­ evento uncheckSourceFromZCtx, fuente = ' + data.source);
        zContextService.unckeckSource(data.sessionId, data.source, function (response) {
            fn(response);
        //loadPostsFromSolr(client, data.sessionId);
        });
    });
	
    client.on('addTagToZCtx', function(data, fn) {
        console.log('RecibÃ­ evento addTagToZCtx, tag = ' + data.tag);
        zContextService.addTag(data.sessionId, data.tag, function (response) {
            if(fn)
                fn(response);
        //loadPostsFromSolr(client, data.sessionId);
        });
    });
    
    client.on('addKeywordToZCtx', function(data, fn) {
        console.log('RecibÃ­ evento addKeywordToZCtx, keyword = ' + data.keyword);
        zContextService.addKeyword(data.sessionId, data.keyword, function (response) {
            if(fn)
                fn(response);
            solrService.loadPostsFromSolr(client, data.sessionId);
        });
    });
	
    client.on('uncheckTagFromZCtx', function(data, fn) {
        console.log('RecibÃ­ evento uncheckTagFromZCtx, tag = ' + data.tag);
        zContextService.unckeckTag(data.sessionId, data.tag, function (response) {
            fn(response);
        //loadPostsFromSolr(client, data.sessionId);
        });
    });
	
    client.on('setSelectedZoneToCtx', function(data, fn) {
        console.log('RecibÃ­ evento setSelectedZoneToCtx, zone = ' + data.zone);
        zContextService.setSelectedZone(data.sessionId, data.zone, function (response) {
            console.log(response);
            fn(response);
            solrService.loadPostsFromSolr(client, data.sessionId);
        });
    });
	
    client.on('setTempToCtx', function(data, fn) {
        console.log('RecibÃ­ evento setTempToCtx, temp = ' + data.temp);
        zContextService.setTemp(data.sessionId, data.temp, function (response) {
            fn(response);
            solrService.loadPostsFromSolr(client, data.sessionId);
        });
    });
	
    client.on('setTabToCtx', function(data, fn) {
        console.log('RecibÃ­ evento setTabToCtx, tab = ' + data.tab);
        zContextService.setTab(data.sessionId, data.tab, function (response) {
            fn(response);
            solrService.loadPostsFromSolr(client, data.sessionId);
        });
    });
	
}