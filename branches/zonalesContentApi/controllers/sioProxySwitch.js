// ----------------------- SERVICES -----------------------

var zProxyService = require('../services/zProxy');
var solrService = require('../services/solr');

module.exports.tryEvent = function tryEvent(client) {

	
    //EVENTOS RELACIONADOS CON EL PROXY
    //=============================================================================

    client.on('proxyExecute', function(data, fn) {
        console.log('RecibÃ­ evento proxyExecute');
        zProxyService.execute(data.host, data.port, data.path, data.method, function (response) {
            fn(response);
        });
    });

    client.on('proxyExecuteSolrCommand', function(data, fn) {
        console.log('RecibÃ­ evento proxyExecuteSolrCommand');
        zProxyService.solrCommand(data.data, data.commit, function (response) {
            fn(response);
        });
    });

    client.on('loadMorePosts', function(data, fn) {
        console.log('Recibí evento loadMorePost');        
        solrService.loadPostsFromSolr(client, data.sessionId, true);
    });
    
    client.on('loadNewPosts', function(data, fn) {
        console.log('Recibí evento loadNewPost');
        solrService.loadNewPostsFromSolr(client, data.sessionId, true);
    });
    
    client.on('resetStart', function(data, fn) {
        console.log('Recibí evento resetStart');
        zContextService.resetStart(data.sessionId);
    });

}

