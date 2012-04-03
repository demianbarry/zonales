var zProxy = require('./zProxy');
var zContextService = require('./ZContext');

var host = "localhost";
var port = 38080;
var rows = 20;

function setFirstIndexTime(time) {
    firstIndexTime = time;
}

function getFirstIndexTime() {
    return firstIndexTime;
}

function setLastIndexTime(time) {
    lastIndexTime = time;
}

function getLastIndexTime() {
    return lastIndexTime;
}

function setMinRelevance(relevance) {
    minRelevance = relevance;
}

function getMinRelevance() {
    return minRelevance;
}

String.prototype.capitalize = function(){
    return this.replace( /(^|\s)([a-z])/g , function(m,p1,p2){
        return p1+p2.toUpperCase();
    } );
};

function getSolrDate(millis){ 
    var date = new Date(millis);
    return date.getFullYear() + '-' + complete(date.getMonth() + 1) + '-' + complete(date.getDate()) + 'T' +
    complete(date.getHours()) + ':' + complete(date.getMinutes()) + ':' + complete(date.getSeconds()) + '.' + date.getMilliseconds() + 'Z';
}

function getSolrSort(myTab){

    var res = "";

    if (myTab == "enlared" || myTab == "noticiasenlared"){
        res = "";
    }
    if (myTab == "relevantes" || myTab == "noticiasenlaredrelevantes")
        res = "relevance+desc";

    return res;
}

function getSolrSources(myTab){

    var res = "";
    if (myTab == "enlared" || myTab == "relevantes"){
        res = "q=source:(facebook+OR+twitter)";
    }
    if (myTab == "noticiasenlared" || myTab == "noticiasenlaredrelevantes"){
        //res = "q=!source:(facebook+OR+twitter)";
        res = "q=!source:(facebook+OR+twitter)";
    }
    if (myTab == "portada"){
        res = "q=tags:(Portada)";
    }

    return res;
}

function getSolrZones(myZone) {
    if(typeof(myZone) == 'undefined' || !myZone || myZone == '')
        return "";

    var res  = '+AND+zoneExtendedString:"';
    res += myZone.replace(/_/g, ' ').capitalize();
    res += '"';

    return res.replace(' ', '+');

}

function getSolrBoosting(zCtx) {
    var tabs = new Array('portada','enlared','noticiasenlared');
    var res  = '&bf=ord(modified)^';
    if(!zCtx || !zCtx.selZone || zCtx.selZone == '' || tabs.indexOf(zCtx.zTab) == -1)
        return res+'100000000';
    res += Math.pow(10,(zCtx.selZone.split(',').length*2+2))+'&bq=';
    var extendedString = zCtx.selZone;
    var boost = "";
    
    do {
        boost += '+zoneExtendedString:"'+extendedString+'"^'+Math.pow(100,extendedString.split(',').length)*100;
        boost += '+zonePartialExtendedString:"'+extendedString+'"^'+Math.pow(100,extendedString.split(',').length)*10;
        extendedString = extendedString.substr(extendedString.indexOf(', ')+2);
    }while(extendedString.indexOf(',') > 0);
    boost += '+zoneExtendedString:"'+extendedString+'"^10000';
    boost += '+zonePartialExtendedString:"'+extendedString+'"^1000';
    
    boost += '+modified:[NOW-48HOURS TO *]^'+Math.pow(10,zCtx.selZone.split(',').length*4);
    boost += '+modified:[NOW-7DAYS TO NOW-48HOURS]^'+Math.pow(10,zCtx.selZone.split(',').length*4-5);
    boost += '+modified:[NOW-30DAYS TO NOW-7DAYS]^'+Math.pow(10,zCtx.selZone.split(',').length*4-10);
    
    return res + boost.replace(/\ /g,"+");
}

function getSolrFilterQuery(zCtx, nuevos) {
    console.log('=========> TAB: '+zCtx.zTab);
    var tabs = new Array('relevantes','noticiasenlaredrelevantes');
    var res = '';
    if(tabs.indexOf(zCtx.zTab) != -1){        
        if(nuevos && zCtx.getMaxRelevance() != 0)        
            res  += '&fq=relevance:['+(parseInt(zCtx.getMaxRelevance())+1)+'+TO+*]';
        if(zCtx.filters.temp != '' && zCtx.filters.temp != '0')
            res  += (res.length > 0 ? '+AND+' : '&fq=') + 'modified:[NOW-'+zCtx.filters.temp+'+TO+*]';        
    } else {        
        if(nuevos && zCtx.getFirstIndexTime() != "")
            res  = '&fq=indexTime:['+zCtx.getFirstIndexTime()+'+TO+*]';
    }
    
    return res;
}

function getSolrDate(d){
    return d.getFullYear() + "-" + fixTime(d.getMonth()) + "-" + fixTime(d.getDate())+"T"+fixTime(d.getHours()) + ":" + fixTime(d.getMinutes()) + ":" + fixTime(d.getSeconds()) + "."+d.getMilliseconds()+"Z";
}


function fixTime(i) {
    return (i<10 ? "0" + i : i);
}

function getSolrKeyword(zCtx) {
    if(zCtx.getSearchKeyword() === undefined || !zCtx.getSearchKeyword() || zCtx.getSearchKeyword() == '')
        return "";

    var res  = '+AND+' + zCtx.getSearchKeyword().replace(/ /g, '+');

    return res;
}

function getSolrUrl(zCtx, nuevos) {
    var urlSolr = "/solr/select?indent=on&version=2.2&start="+(nuevos ? 0 : zCtx.start)+"&fl=*%2Cscore&rows=" + rows + "&qt=zonalesContent&sort="+
    getSolrSort(zCtx.zTab)+"&wt=json&explainOther=&hl.fl=&"+
    getSolrSources(zCtx.zTab)+
    getSolrZones()+
    getSolrKeyword(zCtx)+
    getSolrBoosting(zCtx)+     
    getSolrFilterQuery(zCtx, nuevos);

    return urlSolr;
}

function getSolrUrlById(id) {
    var urlSolr = "/solr/select?indent=on&version=2.2&fl=*%2Cscore&rows=" + rows + "&qt=zonalesContent&wt=json&q=id:"+id;
    return urlSolr;
}

module.exports.countSolrPost = function countSolrPost(zCtx, callback){
    console.log("ESTOY EN SOLR. tab: " + zCtx.zTab + "zone: " + zCtx.selZone);

    var urlSolr = getSolrUrl(zCtx.zTab, zCtx.selZone);
    
    zProxy.execute(host, port, urlSolr, 'GET', function(jsonObj) {
        //var jsonObj = eval('(' + response + ')');
        callback(jsonObj.response.numFound);
    });
}

//module.exports.retrieveSolrPosts = retrieveSolrPosts(zCtx, callback);
function retrieveSolrPosts(zCtx, callback){
    //console.log("ESTOY EN SOLR. tab: " + tab + "zone: " + zone);
    var urlSolr = getSolrUrl(zCtx);
    console.log("=========> URL SORL: "+ urlSolr);
    
    zProxy.execute(host, port, urlSolr, 'GET', function(jsonObj) {
        //var jsonObj = eval('(' + response + ')');
        callback(jsonObj);
    });
}

//module.exports.retrieveNewSolrPosts = retrieveNewSolrPosts(zCtx, callback);
function retrieveNewSolrPosts(zCtx, callback){
    //console.log("ESTOY EN SOLR. tab: " + tab + "zone: " + zone);
    var urlSolr = getSolrUrl(zCtx, true);
    console.log("=========> URL SORL: "+ urlSolr);
    
    zProxy.execute(host, port, urlSolr, 'GET', function(jsonObj) {
        //var jsonObj = eval('(' + response + ')');
        callback(jsonObj);
    });
}

module.exports.loadPostsFromSolr=function loadPostsFromSolr(client, sessionId, more){
    if(!more){
        zContextService.resetStart(sessionId);
    }
    zContextService.getZCtx(sessionId, function(zCtx){
        retrieveSolrPosts(zCtx, function(resp){
            if(resp && resp.response && resp.response.docs){
                resp.response.docs.forEach(function(doc){
                    if(!zCtx.getFirstIndexTime() || (new Date(zCtx.getFirstIndexTime()).getTime()) < (new Date(doc.indexTime)).getTime())
                        zCtx.setFirstIndexTime(addMilli(doc.indexTime));
                    if(!zCtx.getMaxRelevance() || zCtx.getMaxRelevance() < JSON.parse(doc.verbatim).relevance)
                        zCtx.setMaxRelevance(JSON.parse(doc.verbatim).relevance);
                });
            }
            if(typeof(resp) != 'undefined'){
                zContextService.setStart(sessionId, resp.response.docs.length+1);
                if(more){
                    client.emit('solrMorePosts',{
                        response: resp.response
                    });
                } else {
                    //zContextService.resetStart(sessionId);                    
                    client.emit('solrPosts',{
                        response: resp.response
                    });
                }
            }
        }); 
    });
}

module.exports.loadNewPostsFromSolr=function loadNewPostsFromSolr(client, sessionId){
    zContextService.getZCtx(sessionId, function(zCtx){
        retrieveNewSolrPosts(zCtx, function(resp){                    
            if(resp && resp.response && resp.response.docs){
                console.log('FIRST INDEX TIME: '+zCtx.getFirstIndexTime());
                resp.response.docs.forEach(function(doc){
                    console.log('DOC INDEX TIME: '+doc.indexTime);
                    if(!zCtx.getFirstIndexTime() || (new Date(zCtx.getFirstIndexTime())).getTime() < (new Date(doc.indexTime)).getTime())
                        zCtx.setFirstIndexTime(addMilli(doc.indexTime));
                    if(!zCtx.getMaxRelevance() || zCtx.getMaxRelevance() < JSON.parse(doc.verbatim).relevance)
                        zCtx.setMaxRelevance(JSON.parse(doc.verbatim).relevance);
                });
                zContextService.setStart(sessionId, resp.response.docs.length+1);
            }
            if(typeof(resp) != 'undefined'){                
                client.emit('solrNewPosts',{
                    response: resp.response
                });
            }
        }); 
    });
}

module.exports.getSolrPost=function getSolrPost(id, callback){
    var urlSolr = getSolrUrlById(id);
    console.log("=========> URL SORL: "+ urlSolr);
    
    zProxy.execute(host, port, urlSolr, 'GET', function(jsonObj) {
        //var jsonObj = eval('(' + response + ')');
        callback(jsonObj);
    });    
}

function getSolrHost() {
    return host;
}

function getSolrPort() {
    return port;
}

function reduceMilli(date) {
    var milli = TryParseInt(date.substring(date.lastIndexOf('.')+1, date.lastIndexOf('Z')-1), 1);
    var finalDate = date.substr(0, date.lastIndexOf('.')+1) + (milli - 1) + 'Z';
    return finalDate;
}

function addMilli(date) {
    var milli = TryParseInt(date.substring(date.lastIndexOf('.')+1, date.lastIndexOf('Z')-1), 998);
    var finalDate = date.substr(0, date.lastIndexOf('.')+1) + (milli + 1) + 'Z';
    return finalDate;
}

function TryParseInt(str,defaultValue){
    var retValue = defaultValue;
    if(str!=null){
        if(str.length>0){
            if (!isNaN(str)){
                retValue = parseInt(str);
            }
        }
    }
    return retValue;
}