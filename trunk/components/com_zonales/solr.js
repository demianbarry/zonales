var host = "localhost";
var port = "38080";
var searching = false;
var searchKeyword = "";
var firstIndexTime = null;
var lastIndexTime = null;
var minRelevance = null;
var firstModifiedTime = null;
var timeInterval = 10800001;
var rows = 20;

function setFirstIndexTime(time) {
    firstIndexTime = time;
}

function getFirstIndexTime() {
    return firstIndexTime;
}

function setFirstModifiedTime(time) {
    firstModifiedTime = time;
}

function getFirstModifiedTime() {
    return firstModifiedTime;
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

function setSearchKeyword(keyword) {
    searchKeyword = keyword;
}

function getSearchKeyword() {
    return searchKeyword;
}

function getSolrDate(millis){
    var date = new Date(millis);
    return date.getFullYear() + '-' + complete(date.getMonth() + 1) + '-' + complete(date.getDate()) + 'T' +
    complete(date.getHours()) + ':' + complete(date.getMinutes()) + ':' + complete(date.getSeconds()) + '.' + date.getMilliseconds() + 'Z';
}

function getSolrSort(myTab){

    var res = "";

    if (myTab == "enlared" || myTab == "noticiasenlared" || myTab == "portada"){
        res = "modified+desc";
    }
    if (myTab == "relevantes" || myTab == "noticiasenlaredrelevantes")
        res = "relevance+desc";

    return res;
}

function getSolrSources(myTab){

    /*var res  = "(";
    res += mySources.join("+OR+");
    res += ")";*/
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

function getSolrKeyword(keyword) {
    if(typeof(keyword) == 'undefined' || !keyword || keyword == '' || keyword == null)
        return "";

    var res  = '+AND+' + keyword.replace(/ /g, '+');

    return res;
}

function getSolrRange(myTab, more){

    var res = "";

    if (myTab == "enlared" || myTab == "noticiasenlared" || myTab == "portada" ){
        if (!more) {
            res = (lastIndexTime ? '&fq=indexTime:['+getSolrDate(lastIndexTime + timeInterval)+'+TO+*]' : '');
        } else {
            if (myTab == "portada") {
                res = '&fq=modified:[*+TO+'+ reduceMilli(firstModifiedTime.replace('Z', '.000Z')) + ']';
            } else {
                res = '&fq=indexTime:[*+TO+'+reduceMilli(firstIndexTime)+']';
            }
        }
    }

    if (myTab == "relevantes" || myTab == "noticiasenlaredrelevantes" ){
        if (!more){
            res =(lastIndexTime ? '&fq=indexTime:['+getSolrDate(lastIndexTime + timeInterval)+'+TO+*]' : '&fq=modified:['+($('tempoSelect').value != '0' ? 'NOW-'+
                ($('tempoSelect').value) : '*')+'+TO+*]') + '&fq=!relevance:0+AND+relevance:[' + (minRelevance ? minRelevance : 0) + '+TO+*]' ;

        }
        else
            res = '&fq=indexTime:[*+TO+'+reduceMilli(firstIndexTime)+']'+'&fq=!relevance:0+AND+relevance:[*+TO+' + (minRelevance ? minRelevance : 0) + ']' ;
    }
    return res;
}

function getSolrUrl(tab, zone, more, keyword) {
    var urlSolr = "/solr/select?indent=on&version=2.2&start=0&fl=*%2Cscore&rows=" + rows + "&qt=zonalesContent&sort="+
    getSolrSort(tab)+"&wt=json&explainOther=&hl.fl=&"+
    getSolrSources(tab)+
    getSolrZones(zone)+
    getSolrKeyword(keyword)+
    getSolrRange(tab,more);

    return urlSolr;
}

function loadSolrPost(tab, zone, more, callback){
    if(searching) {
        callback();
        return(this);
    }
    var urlSolr = getSolrUrl(tab, zone, more, getSearchKeyword());
    var urlProxy = '/curl_proxy.php?host='+getSolrHost()+'&port='+getSolrPort()+'&ws_path=' + encodeURIComponent(urlSolr);

    var req = new Request.JSON({
        url: urlProxy,
        method: 'get',
        onRequest: function(){
            searching = true;
        },
        onComplete: function(jsonObj) {
            searching = false;
            callback(jsonObj);
            return(this);
        },
        onFailure: function(){
            callback();
            return(this);
        }
    }).send();
}

function getSolrHost() {
    return host;
}

function getSolrPort() {
    return port;
}