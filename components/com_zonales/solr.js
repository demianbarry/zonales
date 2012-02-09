function ZIRClient(){
    this.host = "localhost";
    this.port = 38080;
    this.searching = false;
    this.searchKeyword = "";
    this.firstIndexTime = null;
    this.lastIndexTime = null;
    this.minRelevance = null;
    this.firstModifiedTime = null;
    this.timeInterval = 10800001;
    this.rows = 20;
    this.ids = new Array();
    this.setSolrRows=function(cant){
        this.rows = cant;
    };
    this.getSolrRows=function(){
        return this.rows;
    };
    this.addIdToSolrSearch=function(id){
        this.ids.push(id);    
    }
    this.clearSolrIds=function(){        
        this.ids.empty();        
    }
 
    this.setFirstIndexTime=function(time) {
        this.firstIndexTime = time;
    }

    this.getFirstIndexTime=function() {
        return this.firstIndexTime;
    }

    this.setFirstModifiedTime=function(time) {
        this.firstModifiedTime = time;
    }

    this.getFirstModifiedTime=function() {
        return this.firstModifiedTime;
    }

    this.setLastIndexTime=function(time) {
        this.lastIndexTime = time;
    }

    this.getLastIndexTime=function() {
        return this.lastIndexTime;
    }

    this.setMinRelevance=function(relevance) {
        this.minRelevance = relevance;
    }

    this.getMinRelevance=function() {
        return this.minRelevance;
    }

    this.setSearchKeyword=function(keyword) {
        this.searchKeyword = keyword;
    }

    this.getSearchKeyword=function() {
        return this.searchKeyword;
    }

    this.getSolrDate=function(millis){
        var date = new Date(millis);
        return date.getFullYear() + '-' + complete(date.getMonth() + 1) + '-' + complete(date.getDate()) + 'T' +
        complete(date.getHours()) + ':' + complete(date.getMinutes()) + ':' + complete(date.getSeconds()) + '.' + date.getMilliseconds() + 'Z';
    }

    this.getSolrSort=function(myTab){

        var res = "";

        if (myTab == "enlared" || myTab == "noticiasenlared" || myTab == "portada"){
            res = "modified+desc";
        }
        if (myTab == "relevantes" || myTab == "noticiasenlaredrelevantes")
            res = "relevance+desc";

        return res;
    }

    this.getSolrSources=function(myTab){

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
        if (myTab == "geoActivos"){
            res = "q=";
        }

        return res;
    }

    this.getSolrZones=function(myZone) {
        var res = "";
        if (zcGetTab() != 'geoActivos') {
            if(typeof(myZone) == 'undefined' || !myZone || myZone == '')
                return "";

            res  = '+AND+zone:"';
            res += myZone.replace(/_/g, ' ').capitalize();
            res += '"';
        }

        return res.replace(' ', '+');

    }

    this.getSolrKeyword=function(keyword) {
        if(typeof(keyword) == 'undefined' || !keyword || keyword == '' || keyword == null)
            return "";

        var res  = '+AND+' + keyword.replace(/ /g, '+');

        return res;
    }

    this.getSolrIds=function() {
        var res = "";
        if (zcGetTab() == 'geoActivos') {
            this.ids.each(function(id,index) {
                res += (index != 0 ? "+OR+" : "")+'id:"' + encodeURIComponent(id) + '"';
            });
        }
        return res;
    }

    this.getSolrRange =function (myTab, more){

        var res = "";

        if (myTab == "enlared" || myTab == "noticiasenlared" || myTab == "portada" ){
            if (!more) {
                res = (this.lastIndexTime ? '&fq=indexTime:['+getSolrDate(this.lastIndexTime + this.timeInterval)+'+TO+*]' : '');
            } else {
                if (myTab == "portada") {
                    res = '&fq=modified:[*+TO+'+ reduceMilli(this.firstModifiedTime.replace('Z', '.000Z')) + ']';
                } else {
                    res = '&fq=indexTime:[*+TO+'+reduceMilli(this.firstIndexTime)+']';
                }
            }
        }

        if (myTab == "relevantes" || myTab == "noticiasenlaredrelevantes" ){
            if (!more){
                res =(this.lastIndexTime ? '&fq=indexTime:['+getSolrDate(this.lastIndexTime + timeInterval)+'+TO+*]' : '&fq=modified:['+($('tempoSelect').value != '0' ? 'NOW-'+
                    ($('tempoSelect').value) : '*')+'+TO+*]') + '&fq=!relevance:0+AND+relevance:[' + (this.minRelevance ? this.minRelevance : 0) + '+TO+*]' ;

            }
            else
                res = '&fq=indexTime:[*+TO+'+reduceMilli(this.firstIndexTime)+']'+'&fq=!relevance:0+AND+relevance:[*+TO+' + (this.minRelevance ? this.minRelevance : 0) + ']' ;
        }
        return res;
    }

    this.getSolrUrl=function(tab, zone, more, keyword) {
        var urlSolr = "/solr/select?indent=on&version=2.2&start=0&fl=*%2Cscore&rows=" + rows + "&qt=zonalesContent&sort="+
        this.getSolrSort(tab)+"&wt=json&explainOther=&hl.fl=&"+
        this.getSolrSources(tab)+
        this.getSolrZones(zone)+
        this.getSolrKeyword(keyword)+
        this.getSolrIds()+
        this.getSolrRange(tab,more);
        return urlSolr;
    }

    this.loadSolrPost=function(tab, zone, more, callback){
        if(this.searching) {
            callback();
            return (this);
        }

        var urlSolr = getSolrUrl(tab, zone, more, getSearchKeyword());

        socket.emit('proxyExecute', {
            host: this.host, 
            port: this.port, 
            path: urlSolr, 
            method: 'GET'
        }, function(jsonObj) {
            console.log(jsonObj);
            jsonObj.response.docs.each(function(doc) {
                var verbatim = eval('(' + doc.verbatim + ')');
                if (typeof(verbatim.zone) == 'object') {
                    verbatim.zone = verbatim.zone.name;
                    doc.verbatim = JSON.stringify(verbatim);
                }
            });
            callback(jsonObj);       
            return(this);
        });
        return (this);
    }

    this.getSolrHost=function() {
        return this.host;
    }

    this.getSolrPort=function() {
        return this.port;
    }
}
/*
var host = "localhost";
var port = 38080;
var searching = false;
var searchKeyword = "";
var firstIndexTime = null;
var lastIndexTime = null;
var minRelevance = null;
var firstModifiedTime = null;
var timeInterval = 10800001;
var rows = 20;
var ids = new Array();

function setSolrRows(cant) {
    rows = cant;
}

function getSeolrRows() {
    return rows;
}

function addIdToSolrSearch(id) {
    ids.push(id);
}

function clearSolrIds() {
    ids.empty();
}

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
    if (myTab == "geoActivos"){
        res = "q=";
    }

    return res;


}

function getSolrZones(myZone)
{
    var res = "";
    if (zcGetTab() != 'geoActivos') {
        if(typeof(myZone) == 'undefined' || !myZone || myZone == '')
            return "";

        res  = '+AND+zone:"';
        res += myZone.replace(/_/g, ' ').capitalize();
        res += '"';
    }

    return res.replace(' ', '+');

}

function getSolrKeyword(keyword) {
    if(typeof(keyword) == 'undefined' || !keyword || keyword == '' || keyword == null)
        return "";

    var res  = '+AND+' + keyword.replace(/ /g, '+');

    return res;
}

function getSolrIds() {
    var res = "";
    if (zcGetTab() == 'geoActivos') {
        ids.each(function(id,index) {
            res += (index != 0 ? "+OR+" : "")+'id:"' + encodeURIComponent(id) + '"';
        });
    }
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
    getSolrIds()+
    getSolrRange(tab,more);

    return urlSolr;
}

function loadSolrPost(tab, zone, more, callback){
    if(searching) {
        callback();
        return(this);
    }

    var urlSolr = getSolrUrl(tab, zone, more, getSearchKeyword());

    socket.emit('proxyExecute', {
        host: host, 
        port: port, 
        path: urlSolr, 
        method: 'GET'
    }, function(jsonObj) {
        console.log(jsonObj);
        callback(jsonObj);
        return(this);
    });

}

function getSolrHost() {
    return host;
}

function getSolrPort() {
    return port;
}*/
var zirClient = new ZIRClient();
