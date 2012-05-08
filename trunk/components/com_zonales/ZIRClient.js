function ZIRClient(socket){
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
    this.socket = socket;
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

        if (myTab == "portada"){
            res = "score+desc";
        }
        if (myTab == "enlared" || myTab == "noticiasenlared"){
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
            res = "bq=(tags:(Portada)+AND+modified:[NOW-48HOURS+TO+*])^999999+OR+(tags:(Portada)+AND+modified:[NOW-7DAYS TO NOW-48HOURS])^999";
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
                res = (this.lastIndexTime ? '&fq=indexTime:['+this.getSolrDate(this.lastIndexTime + this.timeInterval)+'+TO+*]' : '');
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
                res =(this.lastIndexTime ? '&fq=indexTime:['+this.getSolrDate(this.lastIndexTime + this.timeInterval)+'+TO+*]' : '&fq=modified:['+($('tempoSelect').value != '0' ? 'NOW-'+
                    ($('tempoSelect').value) : '*')+'+TO+*]') + '&fq=!relevance:0+AND+relevance:[' + (this.minRelevance ? this.minRelevance : 0) + '+TO+*]' ;

            }
            else
                res = '&fq=indexTime:[*+TO+'+reduceMilli(this.firstIndexTime)+']'+'&fq=!relevance:0+AND+relevance:[*+TO+' + (this.minRelevance ? this.minRelevance : 0) + ']' ;
        }
        return res;
    }
    
    this.getSolrBoostQuery =function (myTab){
        var res = '';
        /*if(myTab=='portada') {
            res += '&bq=(modified:[NOW-48HOURS+TO+*])^1000+OR+(modified:[NOW-7DAYS TO NOW-48HOURS])^100';
        }*/
        return res;
    }

    this.getSolrUrl=function(tab, zone, more, keyword) {
        var urlSolr = "/solr/select?indent=on&version=2.2&start=0&fl=*%2Cscore&rows=" + this.rows + "&qt=zonalesContent&sort="+
        this.getSolrSort(tab)+"&wt=json&explainOther=&hl.fl=&"+
        this.getSolrSources(tab)+
        this.getSolrZones(zone)+
        this.getSolrKeyword(keyword)+
        this.getSolrIds() +
        this.getSolrRange(tab,more) +
        this.getSolrBoostQuery(tab);
        return urlSolr;
    }

    this.loadSolrPost=function(tab, zone, more, callback){
        console.log(this.searching);
        if(this.searching) {
            callback();
        } else {
            this.searching = true;
            var urlSolr = this.getSolrUrl(tab, zone, more, this.getSearchKeyword());
            console.log(urlSolr);        
            this.socket.emit('proxyExecute', {
                host: this.host, 
                port: this.port, 
                path: urlSolr, 
                method: 'GET'
            }, function(jsonObj) {
                console.log(jsonObj);
                this.searching = false;                
                jsonObj.response.docs.each(function(doc) {
                    var verbatim = eval('(' + doc.verbatim + ')');
                    alert("TYPE ZONE: " + typeof(verbatim.zone));
                    alert("ZONE: " + JSON.stringify(verbatim.zone));
                    if (typeof(verbatim.zone) == 'object') {
                        verbatim.zone = verbatim.zone.extendedString;
                        doc.verbatim = JSON.stringify(verbatim);
                    }
                });
                //console.log('Callback emit '+this.searching);
                callback(jsonObj);        
            });     
        }        
    }

    this.getSolrHost=function() {
        return this.host;
    }

    this.getSolrPort=function() {
        return this.port;
    }
}