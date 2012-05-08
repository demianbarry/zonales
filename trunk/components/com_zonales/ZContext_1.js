var zCtx;

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
    this.selZoneName = "";
    this.socket = null;
    this.sessionId = null;
    
    this.initZCtx = function initZCtx(nodeUrl, callback) {
        this.sessionId = Cookie.read("cfaf9bd7c00084b9c67166a357300ba3"); //Revisar esto!!!
        this.socket = io.connect(nodeURL);
        this.socket.on('connect', function () {
            this.socket.emit('getCtx', this.sessionId, function(zCtxFromServer) {
                this.zCtx = zCtxFromServer;
                getZoneById(this.selZone, function(selZone) {
                    if (typeof(selZone) != 'undefined' && selZone != null) {
                        this.selZoneName = selZone.name.replace(/_/g, ' ').capitalize();
                    }
                    callback(this.zCtx);
                    return(this);                    
                });
            });
        });
        this.socket.on('solrPosts', function (response) {
            if($('postsContainer')){
                $('postsContainer').empty();
                updatePosts(response, $('postsContainer'));
            }
        });
    }

    /*function zcSetContext(context) {
    zCtx = context;
}*/

    this.setSelectedZone = function setSelectedZone(zone, zoneName, parent, parentName, callback) {
        //alert("EN CONTEXT: SetZone. zoneId: " + zone + " zoneName: " + zoneName + " parendId: " + parent + " parentName: " + parentName);
        //Actualizo en contexto en el cliente
        if (zone == '' && parent == '') {
            this.selZone = '';
            selZoneName = '';
        } else if (zone == '' && parent != '') {
            this.selZone = parent;
            selZoneName = parentName;
            zone = parent;
        } else {
            this.selZone = zone;
            selZoneName = zoneName;
        }

        //Persisto el contexto en el servidor
        //alert("ZONE: " + zone + " ZONE NAME: " + zoneName);
        socket.emit('setSelectedZoneToCtx', {
            sessionId: sessionId,
            zone: zone
        }, function() {
            //alert(JSON.stringify(response));
            callback();
            return(this);
        });
    }

    this.zcGetContext=function zcGetContext() {
        return this;
    }

    this.zcSetProvinceName=function zcSetProvinceName(name) {
        provinceName = name;
    }

    this.zcGetProvinceName=function zcGetProvinceName(){
        return provinceName;
    }

    this.zcAddSource=function zcAddSource(source){
        //Actualizo el contexto en el mismo cliente
        var index = zcSearchSource(zCtx, source);
        if (index == -1) {
            var sourceObj = new Source();
            sourceObj.name = source;
            sourceObj.checked = true;
            this.filters.sources.push(sourceObj);
        } else {
            this.filters.sources[index].checked = true;
        }

        //Persisto el contexto en el servidor
        this.socket.emit('addSourceToZCtx', {
            sessionId: this.sessionId,
            source: source
        }, function(response) {});
    }

    this.zcUncheckSource=function zcUncheckSource(source){
        //Actualizo el contexto en el mismo cliente
        var index = zcSearchSource(this, source);
        if (index == -1) {
            var sourceObj = new Source();
            sourceObj.name = source;
            sourceObj.checked = false;
            this.filters.sources.push(sourceObj);
        } else {
            this.filters.sources[index].checked = false;
        }

        //Persisto el contexto en el servidor
        this.socket.emit('uncheckSourceFromZCtx', {
            sessionId: this.sessionId,
            source: source
        }, function(response) {
        
            });
    }

    this.zcAddTag=function zcAddTag(tag){
        //Actualizo el contexto en el mismo cliente
        var index = zcSearchTag(this, tag);
        if (index == -1) {
            var tagObj = new Tag();
            tagObj.name = tag;
            tagObj.checked = true;
            this.filters.tags.push(tagObj);
        } else {
            this.filters.tags[index].checked = true;
        }

        //Persisto el contexto en el servidor
        this.socket.emit('addTagToZCtx', {
            sessionId: this.sessionId,
            tag: tag
        }, function(response) {
       
            });
    }

    this.zcUncheckTag=function zcUncheckTag(tag){
        //Actualizo el contexto en el mismo cliente
        var index = zcSearchTag(this, tag);
        if (index == -1) {
            var tagObj = new Tag();
            tagObj.name = tag;
            tagObj.checked = false;
            this.filters.tags.push(tagObj);
        } else {
            this.filters.tags[index].checked = false;
        }

        //Persisto el contexto en el servidor
        this.socket.emit('uncheckTagFromZCtx', {
            sessionId: this.sessionId,
            tag: tag
        }, function(response) {
        
            });
    }

    this.zcGetTags=function zcGetTags() {
        return this.filters.tags;
    }

    this.zcGetCheckedTags=function zcGetCheckedTags() {
        var tags = new Array();
        this.filters.tags.each(function (tag) {
            if (tag.checked)
                tags.push(tag.name);
        });
        return tags;
    }

    this.zcGetCheckedSources=function zcGetCheckedSources() {
        var sources = new Array();
        this.filters.sources.each(function (source) {
            if (source.checked)
                sources.push(source.name);
        });
        return sources;
    }

    //Retorn el índice en el array si la fuente ya existe, o -1 en caso contrario
    this.zcSearchSource=function zcSearchSource(zCtx, sourceStr) {
        if (this.filters.sources.length > 0) {
            for (var i = 0; i < this.filters.sources.length; i++){
                if (this.filters.sources[i].name == sourceStr)
                    return i;
            }
        }
        return -1;
    }

    //Retorn el índice en el array si el tag ya existe, o -1 en caso contrario
    this.zcSearchTag=function zcSearchTag(zCtx, tagStr) {
        if (this.filters.tags.length > 0) {
            for (var i = 0; i < this.filters.tags.length; i++){
                if (this.filters.tags[i].name == tagStr)
                    return i;
            }
        }
        return -1;
    }

    this.getAllZones=function getAllZones(callback) {
        this.socket.emit('getZones', true, function(response) {
            callback(response);
            return(this);
        });
    }

    this.getProvincias=function getProvincias(callback) {
        this.socket.emit('getZoneByFilters', {
            "type":"provincia"
        }, function(response) {
            callback(response);
            return(this);
        });
    }

    this.getZonesByProvincia=function getZonesByProvincia(id_provincia, callback) {
        this.socket.emit('getZoneByFilters', {
            "parent":id_provincia
        }, function(response) {
            callback(response);
            return(this);
        });
    }

    this.getZoneById=function getZoneById(id, callback) {
        this.socket.emit('getZoneByFilters', {
            "id":id
        }, function(response) {
            callback(response[0]);
            return(this);
        });
    }

    this.zcGetSelectedZoneName=function zcGetSelectedZoneName() {
        return selZoneName;
    }

    this.zcGetEfectiveZoneName=function zcGetEfectiveZoneName() {
        return efZoneName;
    }

    this.zcSetTemp=function zcSetTemp(temp) {
        //Actualizo el contexto en el cliente
        this.filters.temp = temp;

        //Persisto el contexto en el servidor
        this.socket.emit('setTempToCtx', {
            sessionId: this.sessionId,
            temp: temp
        }, function(response) {
        
            });
    }

    this.zcSetTab=function zcSetTab(tab) {
        //Actualizo el contexto en el cliente
        this.zTab = tab;

        //Persisto el contexto en el servidor
        if(this.socket)
            this.socket.emit('setTabToCtx', {
                sessionId: this.sessionId,
                tab: tab
            }, function(response) {
            
                });
    }

    this.zcGetTab=function zcGetTab() {
        return this.zTab;
    }
}

