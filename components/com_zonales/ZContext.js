var zCtx;
var socket;
var sessionId;
var selZoneName = "";
var efZoneName = "";
var provinceName = "";

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

function ZContext(){
    this.filters = new Filters();
    this.zTabs = new Array();
    this.zTab = "";
    this.selZone = "";
    this.efZone = "";
    this.searchKeyword = "";
    
    this.setSearchKeyword=function(keyword) {
        //Persisto el contexto en el servidor
        socket.emit('addKeywordToZCtx', {
            sessionId: sessionId,
            keyword: keyword
        },function(){
            this.searchKeyword = keyword;
        });
        
    }

    this.getSearchKeyword=function() {
        return this.searchKeyword;
    }
}

function initZCtx(callback) {
    sessionId = Cookie.read("cfaf9bd7c00084b9c67166a357300ba3"); //Revisar esto!!!
    socket = io.connect(nodeURL);
    socket.on('connect', function () {
        socket.emit('getCtx', sessionId, function(zCtxFromServer) {
            if(!zCtx)
                zCtx = new ZContext();
            zCtxFromServer.filters.sources.each(function(source){
                zCtx.filters.sources.push(new Source(source.name, source.checked));
            });
            zCtxFromServer.filters.tags.each(function(tag){
                zCtx.filters.tags.push(new Tag(tag.name, tag.checked));
            });
            zCtx.filters.temp = zCtxFromServer.filters.temp;
            //zCtx.filters = zCtxFromServer.filters;
            zCtx.zTabs = zCtxFromServer.zTabs;
            zCtx.zTab = zCtxFromServer.zTab;
            zCtx.selZone = zCtxFromServer.selZone;
            zCtx.efZone = zCtxFromServer.efZone;                            
            zCtx.searchKeyword = zCtxFromServer.searchKeyword;
            if(!zCtx.selZone)
                zCtx.selZone = '';
            selZoneName = efZoneName = zCtx.selZone.replace(/_/g, ' ').capitalize();
            callback(zCtx);
            /*getZoneById(zCtx.selZone, function(selZone) {
                if (typeof(selZone) != 'undefined' && selZone != null) {
                    selZoneName = selZone.name.replace(/_/g, ' ').capitalize();;
                }
                getZoneById(zCtx.efZone, function(efZone) {
                    if (typeof(efZone) != 'undefined' && efZone != null) {
                        efZoneName = efZone.name.replace(/_/g, ' ').capitalize();;
                    }
                    callback(zCtx);
                    return(this);
                });
            });*/
        });
    });
    socket.on('solrPosts', function (response) {
        if($('postsContainer')){
            zirClient.searching=false;            
            updatePosts(response, $('postsContainer'));
        }
    });
    
    socket.on('solrMorePosts', function (response) {
        if($('postsContainer')){            
            zirClient.searching=false;
            updatePosts(response, $('postsContainer'), true);
        }
    });
    socket.on('solrNewPosts', function (response) {
        if($('newPostsContainer')){            
            zirClient.searching=false;
            updatePosts(response,$('newPostsContainer'));
            if($('newPostsContainer').childNodes.length > 0){
                $('verNuevos').value= $('newPostsContainer').getChildren('div').length+' nuevo'+($('newPostsContainer').getChildren('div').length > 1 ? 's' : '')+'...';
                $('verNuevos').setStyle('display','block');
            } else{
                $('verNuevos').setStyle('display','none');
            }
        }
    });
}

/*function zcSetContext(context) {
    zCtx = context;
}*/

function setSelectedZone(zone, zoneName, parent, parentName, callback) {
    //alert("EN CONTEXT: SetZone. zoneId: " + zone + " zoneName: " + zoneName + " parendId: " + parent + " parentName: " + parentName);
    //Actualizo en contexto en el cliente
    if (zone == '' && parent == '') {
        zCtx.selZone = '';
        selZoneName = '';
    } else if (zone == '' && parent != '') {
        zCtx.selZone = parent;
        selZoneName = parentName;
        zone = parent;
    } else {
        zCtx.selZone = zone;
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

function zcGetContext() {
    return zCtx;
}

function zcSetProvinceName(name) {
    provinceName = name;
}

function zcGetProvinceName(){
    return provinceName;
}

function zcAddSource(source){
    //Actualizo el contexto en el mismo cliente
    var index = zcSearchSource(zCtx, source);
    if (index == -1) {
        var sourceObj = new Source();
        sourceObj.name = source;
        sourceObj.checked = true;
        zCtx.filters.sources.push(sourceObj);
    } else {
        zCtx.filters.sources[index].checked = true;
    }

    //Persisto el contexto en el servidor
    socket.emit('addSourceToZCtx', {
        sessionId: sessionId,
        source: source
    }, function(response) {

        });
}

function zcUncheckSource(source){
    //Actualizo el contexto en el mismo cliente
    var index = zcSearchSource(zCtx, source);
    if (index == -1) {
        var sourceObj = new Source();
        sourceObj.name = source;
        sourceObj.checked = false;
        zCtx.filters.sources.push(sourceObj);
    } else {
        zCtx.filters.sources[index].checked = false;
    }

    //Persisto el contexto en el servidor
    socket.emit('uncheckSourceFromZCtx', {
        sessionId: sessionId,
        source: source
    }, function(response) {
        
        });
}

function zcAddTag(tag){
    //Actualizo el contexto en el mismo cliente
    var index = zcSearchTag(zCtx, tag);
    if (index == -1) {
        var tagObj = new Tag();
        tagObj.name = tag;
        tagObj.checked = true;
        zCtx.filters.tags.push(tagObj);
    } else {
        zCtx.filters.tags[index].checked = true;
    }

    //Persisto el contexto en el servidor
    socket.emit('addTagToZCtx', {
        sessionId: sessionId,
        tag: tag
    });
}

function zcUncheckTag(tag){
    //Actualizo el contexto en el mismo cliente
    var index = zcSearchTag(zCtx, tag);
    if (index == -1) {
        var tagObj = new Tag();
        tagObj.name = tag;
        tagObj.checked = false;
        zCtx.filters.tags.push(tagObj);
    } else {
        zCtx.filters.tags[index].checked = false;
    }

    //Persisto el contexto en el servidor
    socket.emit('uncheckTagFromZCtx', {
        sessionId: sessionId,
        tag: tag
    }, function(response) {
        
        });
}

function zcGetTags() {
    return zCtx.filters.tags;
}

function zcGetCheckedTags() {
    var tags = new Array();
    zCtx.filters.tags.each(function (tag) {
        if (tag.checked)
            tags.push(tag.name);
    });
    return tags;
}

function zcGetCheckedSources() {
    var sources = new Array();
    zCtx.filters.sources.each(function (source) {
        if (source.checked)
            sources.push(source.name);
    });
    return sources;
}

//Retorn el Ã­ndice en el array si la fuente ya existe, o -1 en caso contrario
function zcSearchSource(zCtx, sourceStr) {
    if (zCtx.filters.sources.length > 0) {
        for (var i = 0; i < zCtx.filters.sources.length; i++){
            if (zCtx.filters.sources[i].name == sourceStr)
                return i;
        }
    }
    return -1;
}

//Retorn el Ã­ndice en el array si el tag ya existe, o -1 en caso contrario
function zcSearchTag(zCtx, tagStr) {
    if (zCtx.filters.tags.length > 0) {
        for (var i = 0; i < zCtx.filters.tags.length; i++){
            if (zCtx.filters.tags[i].name == tagStr)
                return i;
        }
    }
    return -1;
}

function getAllZones(callback) {
    socket.emit('getZones', true, function(response) {
        callback(response);
        return(this);
    });
}

function getProvincias(callback) {
    socket.emit('getZoneByFilters', {
        "type":"provincia"
    }, function(response) {
        callback(response);
        return(this);
    });
}

function getZonesByProvincia(id_provincia, callback) {
    socket.emit('getZoneByFilters', {
        "parent":id_provincia
    }, function(response) {
        callback(response);
        return(this);
    });
}

function getZoneById(id, callback) {
    socket.emit('getZoneByFilters', {
        "id":id
    }, function(response) {
        callback(response[0]);
        return(this);
    });
}
function getIdByZone(extendedString, callback) {
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

function getPlaces(zoneid, callback) {
    //alert(typeof(zoneid));
    socket.emit('getExtendedString', zoneid, function(response) {
       // alert(response);
        callback(response);
        return(this);
    });
}

function zcGetSelectedZoneName() {
    return selZoneName;
}

function zcGetEfectiveZoneName() {
    return efZoneName;
}

function zcSetTemp(temp) {
    //Actualizo el contexto en el cliente
    zCtx.filters.temp = temp;

    //Persisto el contexto en el servidor
    socket.emit('setTempToCtx', {
        sessionId: sessionId,
        temp: temp
    }, function(response) {
        
        });
}

function zcSetTab(tab) {
    //Actualizo el contexto en el cliente
    if(!zCtx)
        zCtx = new ZContext();
    zCtx.zTab = tab;

    //Persisto el contexto en el servidor
    if(socket)
        socket.emit('setTabToCtx', {
            sessionId: sessionId,
            tab: tab
        }, function(response) {
            
            });
}

function zcGetTab() {
    return zCtx.zTab;
}

function zcGetZone() {
    return zCtx.selZone;
}