var zCtx;
var socket;
var sessionId;
var selZoneName = "";
var efZoneName = "";
var provinceName = "";

function Source() {
    this.name = "";
    this.checked = false;
}

function Tag() {
    this.name = "";
    this.checked = false;
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
}

function initZCtx(callback) {
    sessionId = Cookie.read("cfaf9bd7c00084b9c67166a357300ba3"); //Revisar esto!!!
    socket = io.connect(nodeURL);
    socket.on('connect', function () {
        socket.emit('getCtx', sessionId, function(zCtxFromServer) {
            zCtx = zCtxFromServer;
            getZoneById(zCtx.selZone, function(selZone) {
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
            });
        });
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
    }, function(response) {
        //alert(JSON.stringify(response));
        if (typeof(response) != 'undefined' && response != null) {
            zCtx.efZone = response.id;
            efZoneName = response.name.replace(/_/g, ' ').capitalize();
        } else {
            zCtx.efZone = "";
            efZoneName = "";
        }
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
        var resp = eval('(' + response + ')');
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
        var resp = eval('(' + response + ')');
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
    }, function(response) {
        var resp = eval('(' + response + ')');
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
        var resp = eval('(' + response + ')');
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

//Retorn el índice en el array si la fuente ya existe, o -1 en caso contrario
function zcSearchSource(zCtx, sourceStr) {
    if (zCtx.filters.sources.length > 0) {
        for (var i = 0; i < zCtx.filters.sources.length; i++){
            if (zCtx.filters.sources[i].name == sourceStr)
                return i;
        }
    }
    return -1;
}

//Retorn el índice en el array si el tag ya existe, o -1 en caso contrario
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
        var resp = eval('(' + response + ')');
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
        var resp = eval('(' + response + ')');
    });
}

function zcGetTab() {
    return zCtx.zTab;
}