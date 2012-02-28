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
        res = "max(modified,created)+desc";
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
    if(!zCtx || !zCtx.selZone || zCtx.selZone == '')
        return "";

    var res  = '&bq=';
    var extendedString = zCtx.selZone;
    var boost = "";
    
    do {
        boost += '+zoneExtendedString:"'+extendedString+'"^'+Math.pow(1000,extendedString.split(',').length);
        boost += '+zonePartialExtendedString:'+'", '+extendedString+'"^'+Math.pow(1000,extendedString.split(',').length);
        extendedString = extendedString.substr(extendedString.indexOf(', ')+2);
    }while(extendedString.indexOf(',') > 0);    
    

    return res + boost;
}

function getSolrUrl(tab, zone, zCtx) {
    var urlSolr = "/solr/select?indent=on&version=2.2&start=0&fl=*%2Cscore&rows=" + rows + "&qt=zonalesContent&sort="+
    getSolrSort(tab)+"&wt=json&explainOther=&hl.fl=&"+
    getSolrSources(tab)+
    getSolrZones(zone)+
    getSolrBoosting(zCtx);

    return urlSolr;
}

module.exports.countSolrPost = function countSolrPost(tab, zone, callback){
    console.log("ESTOY EN SOLR. tab: " + tab + "zone: " + zone);

    urlSolr = getSolrUrl(tab, zone);
    
    zProxy.execute(host, port, urlSolr, 'GET', function(jsonObj) {
        //var jsonObj = eval('(' + response + ')');
        callback(jsonObj.response.numFound);
    });    
}

module.exports.retrieveSolrPosts = function retrieveSolrPosts(zCtx, callback){
    //console.log("ESTOY EN SOLR. tab: " + tab + "zone: " + zone);
    var urlSolr = getSolrUrl(zCtx.zTab, zCtx.selZone, zCtx);
    console.log("=========> URL SORL"+ urlSolr);
    
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
