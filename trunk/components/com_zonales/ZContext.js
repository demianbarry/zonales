function Source(name, checked) {
    this.name = name ? name : "";
    this.checked = checked !== undefined ? checked : false;
}

function Tag(name, checked) {
    this.name = name ? name : "";
    this.checked = checked !== undefined ? checked : false;
}

function Filters(){
    this.sources = new Array();
    this.temp = "";
    this.tags = new Array();
}

function ZContext(socketParam, sessionIdParam, nodeURLParam){
    var filters = new Filters();
    var zTabs = new Array();
    var zTab = "";
    var selZone = "";
    var efZone = "";
    var searchKeyword = "";
    var selZoneName = "";
    var efZoneName = "";
    var provinceName = "";
    var socket = socketParam;
    var sessionId = sessionIdParam;//Cookie.read("cfaf9bd7c00084b9c67166a357300ba3"); //Revisar esto!!!
    var nodeURL = nodeURLParam;
    

    this.setSearchKeyword=function(keyword) {
        //Persisto el contexto en el servidor
        socket.emit('addKeywordToZCtx', {
            sessionId: sessionId,
            keyword: keyword
        },function(){
            searchKeyword = keyword;
        });

    }

    this.getSearchKeyword=function() {
        return searchKeyword;
    }
    
    var initZCtx = function initZCtx(callback) {                        
        socket.emit('getCtx', sessionId, function(zCtxFromServer) {            
            console.log(JSON.stringify(zCtxFromServer));
            zCtxFromServer.filters.sources.each(function(source){
                filters.sources.push(new Source(source.name, source.checked));
            });
            zCtxFromServer.filters.tags.each(function(tag){
                filters.tags.push(new Tag(tag.name, tag.checked));
            });
            filters.temp = zCtxFromServer.filters.temp;
            //this.filters = zCtxFromServer.filters;
            zTabs = zCtxFromServer.zTabs;
            zTab = zCtxFromServer.zTab;
            selZone = zCtxFromServer.selZone;
            efZone = zCtxFromServer.efZone;
            searchKeyword = zCtxFromServer.searchKeyword;
            if(!selZone)
                selZone = '';
            selZoneName = efZoneName = selZone.replace(/_/g, ' ').capitalize();
            callback();
        /*getZoneById(this.selZone, function(selZone) {
                if (typeof(selZone) != 'undefined' && selZone != null) {
                    selZoneName = selZone.name.replace(/_/g, ' ').capitalize();;
                }
                getZoneById(this.efZone, function(efZone) {
                    if (typeof(efZone) != 'undefined' && efZone != null) {
                        efZoneName = efZone.name.replace(/_/g, ' ').capitalize();;
                    }
                    callback(zCtx);
                    return(this);
                });
            });*/
        });        
    }
    this.initZCtx = initZCtx;
    
    this.setSelectedZone = function setSelectedZone(zone, zoneName, parent, parentName, callback) {
        //alert("EN CONTEXT: SetZone. zoneId: " + zone + " zoneName: " + zoneName + " parendId: " + parent + " parentName: " + parentName);
        //Actualizo en contexto en el cliente
        if (zone == '' && parent == '') {
            selZone = '';
            selZoneName = '';
        } else if (zone == '' && parent != '') {
            selZone = parent;
            selZoneName = parentName;
            zone = parent;
        } else {
            selZone = zone;
            selZoneName = zoneName;
        }

        //Persisto el contexto en el servidor
        //alert("ZONE: " + zone + " ZONE NAME: " + zoneName);
        socket.emit('setSelectedZoneToCtx', {
            sessionId: sessionId,
            zone: zone
        }, function() {
            //alert(JSON.stringify(response));
            if (callback)
                callback();
            return(this);
        });
    }
    
    this.zcGetContext = function zcGetContext() {
        return this;
    }

    this.zcSetProvinceName = function zcSetProvinceName(name) {
        provinceName = name;
    }

    this.zcGetProvinceName = function zcGetProvinceName(){
        return provinceName;
    }

    this.zcAddSource = function zcAddSource(source){
        //Actualizo el contexto en el mismo cliente
        var index = zcSearchSource(this, source);
        if (index == -1) {
            var sourceObj = new Source();
            sourceObj.name = source;
            sourceObj.checked = true;
            filters.sources.push(sourceObj);
        } else {
            filters.sources[index].checked = true;
        }

        //Persisto el contexto en el servidor
        socket.emit('addSourceToZCtx', {
            sessionId: sessionId,
            source: source
        }, function(response) {});
    }

    this.zcUncheckSource = function zcUncheckSource(source){
        //Actualizo el contexto en el mismo cliente
        var index = zcSearchSource(this, source);
        if (index == -1) {
            var sourceObj = new Source();
            sourceObj.name = source;
            sourceObj.checked = false;
            filters.sources.push(sourceObj);
        } else {
            filters.sources[index].checked = false;
        }

        //Persisto el contexto en el servidor
        socket.emit('uncheckSourceFromZCtx', {
            sessionId: sessionId,
            source: source
        }, function(response) {});
    }

    this.zcAddTag = function zcAddTag(tag){
        //Actualizo el contexto en el mismo cliente
        var index = zcSearchTag(this, tag);
        if (index == -1) {
            var tagObj = new Tag();
            tagObj.name = tag;
            tagObj.checked = true;
            filters.tags.push(tagObj);
        } else {
            filters.tags[index].checked = true;
        }

        //Persisto el contexto en el servidor
        socket.emit('addTagToZCtx', {
            sessionId: sessionId,
            tag: tag
        });
    }

    this.zcUncheckTag = function zcUncheckTag(tag){
        //Actualizo el contexto en el mismo cliente
        var index = zcSearchTag(this, tag);
        if (index == -1) {
            var tagObj = new Tag();
            tagObj.name = tag;
            tagObj.checked = false;
            filters.tags.push(tagObj);
        } else {
            filters.tags[index].checked = false;
        }

        //Persisto el contexto en el servidor
        socket.emit('uncheckTagFromthis', {
            sessionId: sessionId,
            tag: tag
        }, function(response) {});
    }

    this.zcGetTags = function zcGetTags() {
        return filters.tags;
    }

    this.zcGetCheckedTags = function zcGetCheckedTags() {
        var tags = new Array();
        filters.tags.each(function (tag) {
            if (tag.checked)
                tags.push(tag.name);
        });
        return tags;
    }

    this.zcGetCheckedSources = function zcGetCheckedSources() {
        var sources = new Array();
        filters.sources.each(function (source) {
            if (source.checked)
                sources.push(source.name);
        });
        return sources;
    }

    //Retorn el índice en el array si la fuente ya existe, o -1 en caso contrario
    this.zcSearchSource = function zcSearchSource(zCtx, sourceStr) {
        if (filters.sources.length > 0) {
            for (var i = 0; i < filters.sources.length; i++){
                if (filters.sources[i].name == sourceStr)
                    return i;
            }
        }
        return -1;
    }

    //Retorn el índice en el array si el tag ya existe, o -1 en caso contrario
    this.zcSearchTag = function zcSearchTag(zCtx, tagStr) {
        if (filters.tags.length > 0) {
            for (var i = 0; i < filters.tags.length; i++){
                if (filters.tags[i].name == tagStr)
                    return i;
            }
        }
        return -1;
    }

    this.getAllZones = function getAllZones(callback) {
        socket.emit('getZones', true, function(response) {
            callback(response);
            return(this);
        });
    }

    this.getProvincias = function getProvincias(callback) {
        socket.emit('getZoneByFilters', {
            "type":"provincia"
        }, function(response) {
            callback(response);
            return(this);
        });
    }

    this.getZonesByProvincia = function getZonesByProvincia(id_provincia, callback) {
        socket.emit('getZoneByFilters', {
            "parent":id_provincia
        }, function(response) {
            callback(response);
            return(this);
        });
    }

    this.getZoneById = function getZoneById(id, callback) {
        socket.emit('getZoneByFilters', {
            "id":id
        }, function(response) {
            callback(response[0]);
            return(this);
        });
    }
    
    this.getIdByZone = function getIdByZone(extendedString, callback) {
        console.log("dentro de getIdByZone con "+extendedString);
        socket.emit('getZoneByFilters', {
            "extendedString":extendedString
        }, function(zones) {
            // alert(JSON.stringify(zones[0]));
            //  MyZoneId = zones[0].id;
            callback(zones[0].id);
            return(this);
        });
    }

    this.getPlaces = function getPlaces(zoneid, callback) {
        //alert(typeof(zoneid));
        socket.emit('getExtendedString', zoneid, function(response) {
            // alert(response);
            callback(response);
            return(this);
        });
    }

    this.zcGetSelectedZoneName = function zcGetSelectedZoneName() {
        return selZoneName;
    }

    this.zcGetEfectiveZoneName = function zcGetEfectiveZoneName() {
        return efZoneName;
    }

    this.zcSetTemp = function zcSetTemp(temp) {
        //Actualizo el contexto en el cliente
        filters.temp = temp;

        //Persisto el contexto en el servidor
        socket.emit('setTempToCtx', {
            sessionId: sessionId,
            temp: temp
        }, function(response) {

            });
    }

    this.zcSetTab = function zcSetTab(tab) {
        //Actualizo el contexto en el cliente    
        zTab = tab;

        //Persisto el contexto en el servidor
        if(socket)
            socket.emit('setTabToCtx', {
                sessionId: sessionId,
                tab: tab
            }, function(response) {

                });
    }

    this.zcGetFilters = function zcGetFilters() {
        return filters;
    }
    
    this.zcGetTemp = function zcGetTemp() {
        return filters.temp;
    }

    this.zcGetZTab = function zcGetZTab() {
        return zTab;
    }
    
    this.zcGetZTabs = function zcGetZTabs() {
        return zTabs;
    }

    this.zcGetZone = function zcGetZone() {
        return selZone;
    }   
}