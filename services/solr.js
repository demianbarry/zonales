var http = require('http');

var host = "localhost";
var port = "38080";
var rows = 0;

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

function getSolrZones(myZone)
{
    if(typeof(myZone) == 'undefined' || !myZone || myZone == '')
        return "";

    var res  = '+AND+zone:"';
    res += myZone.replace(/_/g, ' ').capitalize();
    res += '"';

    return res.replace(' ', '+');

}

function getSolrUrl(tab, zone) {
    var urlSolr = "/solr/select?indent=on&version=2.2&start=0&fl=*%2Cscore&rows=" + rows + "&qt=zonalesContent&sort="+
    getSolrSort(tab)+"&wt=json&explainOther=&hl.fl=&"+
    getSolrSources(tab)+
    getSolrZones(zone);

    return urlSolr;
}

module.exports.countSolrPost = function countSolrPost(tab, zone, callback){
	 console.log("ESTOY EN SOLR. tab: " + tab + "zone: " + zone);

    urlSolr = getSolrUrl(tab, zone);
    var urlProxy = '/curl_proxy.php?host='+getSolrHost()+'&port='+getSolrPort()+'&ws_path=' + encodeURIComponent(urlSolr);

	console.log("SOLR PROXY URL: " + urlProxy);
    
    var options = {
		  host: 'localhost',
		  port: 82,
		  path: urlProxy,
		  method: 'GET'
	 };
		
	 http.request(options, function(res) {
		  console.log('STATUS: ' + res.statusCode);
		  console.log('HEADERS: ' + JSON.stringify(res.headers));
		  res.setEncoding('utf8');
		  res.on('data', function (json) {
	   	    console.log('BODY: ' + json);
	   	    var jsonObj = eval('(' + json + ')');
				 callback(jsonObj.response.numFound);
             return(this);
		  });
	 }).end();
}

function getSolrHost() {
    return host;
}

function getSolrPort() {
    return port;
}
