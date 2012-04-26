Element.Properties['data-title'] = {

    get: function(){
        return this['data-title'];
    },

    set: function(value){
        this['data-title'] = value;
        this.setAttribute('data-title', value);
    }

};

function ZTabs() {
    this.nodeURL = 'http://192.168.0.2:4000';
    this.detalleURL = 'http://192.168.0.2:82';
    this.sources = new Array();
    this.zones = new Array();
    this.allZones = new Array();
    this.tab = "";
    var zUserGroups = new Array();
    this.zUserGroups = zUserGroups;
    this.postInterval = null;
    this.host = "localhost";
    this.port = "38080";
    this.sessionId = Cookie.read("cfaf9bd7c00084b9c67166a357300ba3"); //Revisar esto!!!
    var socket = io.connect(this.nodeURL);
    this.socket = socket;
    var zCtx = new ZContext(this.socket, this.sessionId, this.nodeURL);
    this.zCtx = zCtx;
    var zirClient = new ZIRClient(this.socket, this.sessionId);    
    this.zirClient = zirClient;
    var htmlPosts;
    var newPosts = new Array();
    this.newPosts = newPosts;
    var firstPostZone = "";
    var newPostTime = 5000; //Tiempo durante el que se destacan los nuevos post en milisegundo
    
    var postsContainer = null;
    var filtersContainer = null;
    var verNuevosButton = null;
    this.setComponents = function(postsContainerParam, filtersContainerParam, verNuevosParam){
        postsContainer = postsContainerParam;
        filtersContainer = filtersContainerParam;
        verNuevosButton = verNuevosParam;
        htmlPosts = Tempo.prepare(postsContainer);
    }    

    socket.on('solrPosts', function (response) {
        console.log("SOLR POSTS: ");
        console.log(response);
        newPosts.empty();
        verNuevosButton.setStyle('display','none');
        updatePosts(response.response.docs);
    //}
    });

    socket.on('solrMorePosts', function (response) {
        updatePosts(response.response.docs, true);
    //}
    });
    socket.on('solrNewPosts', function (response) {
        console.log("NEW SOLR POSTS: ");
        console.log(response);
        newPosts.append(response.response.docs);
        if(newPosts.length > 0){
            verNuevosButton.innerHTML= newPosts.length+' nuevo'+(newPosts.length > 1 ? 's' : '')+'...';
            verNuevosButton.setStyle('display','block');
        } else{
            verNuevosButton.setStyle('display','none');
        }
        updatePostDate();
    });  

    var initAll = function initAll() {            
        zCtx.initZCtx(function() {            
            tab = zCtx.zcGetZTab();
            initZonas(zCtx.zcGetZone());
            initVista(zCtx);
            if(typeof initMapTab == 'function'){
                initMapTab();                
            }
            if(typeof setActiveTab == 'function'){
                setActiveTab(zTab.zCtx.zcGetZTab());
            }
        });
        zUserGroups = loguedUser;
    }
    this.initAll = initAll;
    
    var initZonas = function initZonas(selZone) {        
        if (selZone && selZone != '') {
            $('zoneExtended').value = selZone;
        }
    }
    this.initZonas = initZonas;
    
    var initVista = function initVista(zCtx){
        //        if(postsContainer)
        //            postsContainer.empty();
        if(newPosts)
            newPosts.empty();
        verNuevosButton.setStyle('display','none');
        //this.zirClient.resetStart();
        zirClient.setFirstIndexTime(null);
        zirClient.setLastIndexTime(null);
        zirClient.setMinRelevance(null);
        //initFilters(zCtx);
        initPost();
        zCtx.setSearchKeyword("");
    }
    this.initVista = initVista;
 
    var initPost = function initPost() {
        if (this.tab != 'geoActivos' && postsContainer) {
            if(this.postInterval) {
                clearInterval(this.postInterval);
                this.postInterval = null;
            }
            this.postInterval = setInterval(function () {
                zirClient.loadNewSolrPost();
            }, 60000);
        } else {
            if(this.postInterval) {
                clearInterval(this.postInterval);
                this.postInterval = null;
            }
        }
    //loadPost(true);
    //getAllTags();
    }
    this.initPost = initPost;

    this.initFilters = function initFilters(zCtx) {
        this.initSourceFilters(zCtx);
        this.initTagFilters(zCtx);
        this.initTempFilters(zCtx);
    //this.initPost();
    }   

    this.initSourceFilters = function initSourceFilters(zCtx) {
        //Actualizo filtros de fuente desde contexto
        zCtx.zcGetFilters().sources.each(function(source) {
            var sourceChk = $('chk'+source.name);
            if (!sourceChk) {
                var sourcesTr = new Element('tr');
                var sourceChkBoxTd = new Element('td').inject(sourcesTr);
                new Element('input', {
                    'id': 'chk' + source.name,
                    'type': 'checkbox',
                    'checked': (source.checked ? 'checked' : ''),
                    'name': source.name,
                    'value': source.name,
                    'onclick': 'setSourceVisible(this.value,this.checked);'
                }).inject(sourceChkBoxTd);
                new Element('td', {
                    'html': source.name
                }).inject(sourcesTr);
                sourcesTr.inject($('filtroSources'));
            /*   if (source.name == 'Facebook' || source.name == 'Twitter')
                sourcesTr.inject($('enLaRed'));
            else
                sourcesTr.inject($('noticiasEnLaRed'));*/
            }
        });
    }

    this.initTagFilters = function initTagFilters(zCtx) {
        //Actualizo filtros de tags desde contexto
        zCtx.zcGetFilters().tags.each(function(tag) {
            var tagChk = $('chkt'+tag.name);
            if (!tagChk) {
                var tagTr = new Element('tr');
                var tagChkBoxTd = new Element('td').inject(tagTr);
                tagChk = new Element('input', {
                    'id': 'chkt' + tag.name,
                    'type': 'checkbox',
                    'checked': (tag.checked ? 'checked' : ''),
                    'name': tag.name,
                    'value': tag.name,
                    'onclick': 'setTagVisible(this.value,this.checked);'
                }).inject(tagChkBoxTd).addClass(tag.checked ? 'checked': '');
                new Element('td', {
                    'html': tag.name
                }).inject(tagTr);
                tagTr.inject($('tagsFilterTable'));
            }
            if(tag.checked)
                tagChk.addClass('checked');
        });
    }


    this.initTempFilters = function initTempFilters(zCtx) {
        $('tempoSelect').value = zCtx.zcGetTemp();
        $('tempoSelect').addEventListener('change', onTempoChange, false);
    }

    var setZone = function setZone(zoneExtended, zoneName, parentId, parentName) {
        if (zoneExtended == null || typeof(zoneExtended) == 'undefined')
            zoneExtended = '';
        if (zoneName == null || typeof(zoneName) == 'undefined')
            zoneName = '';
        if (parentId == null || typeof(parentId) == 'undefined')
            parentId = '';
        if (parentName == null || typeof(parentName) == 'undefined')
            parentName = '';

        zirClient.setFirstIndexTime(null);
        zirClient.setLastIndexTime(null);
        zirClient.setMinRelevance(null);
        //$('zonalesSearchword').value = "buscar...";
        $('zoneExtended').value = zoneExtended;
        if (this.tab != 'geoActivos' && this.tab != 'editor' && this.tab != 'list' && postsContainer && newPosts) {
            //            postsContainer.empty();
            newPosts.empty();
        }
        zCtx.setSelectedZone(zoneExtended, zoneName, parentId, parentName, function() {
            });
    }
    this.setZone = setZone;

    this.onTempoChange = function onTempoChange() {
        if (this.tab != 'geoActivos' && postsContainer) {
            //            postsContainer.empty();
            newPosts.empty();
        }
        zirClient.setLastIndexTime(null);
        zCtx.zcSetTemp($('tempoSelect').value);
    /*if (this.tab != 'geoActivos' && postsContainer) {
        loadPost(true);
    }*/
    }

    this.complete = function complete(number){
        return (number > 9 ? ''+number : '0'+number);
    }

    this.loadPost = function loadPost(first){
    //alert("LoadPost: " + JSON.sSy(zcGetContext()));

    }

    this.loadMorePost = function loadMorePost(){
        console.log('loadMorePost');
        this.zirClient.loadMoreSolrPost();
    }

    this.searchPost = function searchPost(keyword, zone) {
        if (keyword != 'buscar...' && keyword != '') {
            zirClient.setFirstIndexTime(null);
            zirClient.setLastIndexTime(null);
            zirClient.setMinRelevance(null);
            zCtx.setSearchKeyword(keyword);
        //this.zirClient.setSearchKeyword(keyword);
        }
    }

    var getTarget = function getTarget(post, id) {
        var ret = '';
        if ((post.source).toLowerCase() == 'twitter') {
            ret = 'http://twitter.com/#!/' + post.fromUser.name;
        } else if ((post.source).toLowerCase() == 'facebook') {
            ret = post.fromUser.url;
        }else if ((post.source).toLowerCase() == 'zonales') {
            ret = this.detalleURL+"/detalle.html?id="+id;
        }
        else {
            if(typeOf(post.links) == 'array') {
                post.links.each(function(link) {
                    if (link.type == 'source') {
                        ret = link.url;
                    }
                });
            }
        }
        return ret;
    }

    var getVerMas = function getVerMas(text){
        if(text.length > 255){
            var i=255;
            for( ;text.charAt(i) != ' ';i--);
            var shortText = text.substring(0,i);
            var otherText = text.substring(i);
            return shortText + "<span id=\"verMasPost\" onclick=\"if (this.getNext()) {this.getNext().innerHTML = unescape(this.getNext().innerHTML); this.getNext().setStyle('display','inline'); this.style.display = 'none';}\" style=\"display: inline;\">... [+]</span><span style=\"display: none;\" id=\"resto\">"+escape(otherText)+"</span>";
        }
        return text;
    }

    var verNuevos = function verNuevos(){
        updatePosts(newPosts, false, true);
        verNuevosButton.setStyle('display','none');
        newPosts.empty();
    }

    this.verNuevos = verNuevos;


    this.incRelevance = function incRelevance(id,relevance){
        var url = '/ZCrawlScheduler/indexPosts?url=http://localhost:38080/solr&doc={"id":"'+encodeURIComponent(id)+'"}&rel='+relevance;
        var urlProxy = '/curl_proxy.php';

        new Request({
            url: urlProxy,
            method: 'post',
            data: {
                'host': this.host ? this.host : "localhost",
                'port': this.port ? this.port : "38080",
                'ws_path':url
            },
            onRequest: function(){
            },
            onSuccess: function(response) {
                if(response && response.length != 0){
                    response = JSON.decode(response);
                    if(response.id && response.id.length > 0 && $('relevance_'+response.id)){
                        $('relevance_'+response.id).innerHTML = parseInt($('relevance_'+response.id).innerHTML)+parseInt(relevance);
                    //                        if(this.zUserGroups.indexOf("4") == -1){
                    //                            $('relevance_'+response.id).getPrevious().removeEvents('click');
                    //                            $('relevance_'+response.id).getNext().removeEvents('click');
                    //                        }
                    }
                }
            },
            // Our request will most likely succeed, but just in case, we'll add an
            // onFailure method which will let the user know what happened.
            onFailure: function(){
            }
        }).send();

    }

    var getPostTitle = function getPostTitle(title, target) {
        var a_title = "<a target=\"_blank\" href=\"" + target + "\">" + title + "</a>";
        return a_title;
    }

    var getZoneForPost = function getZoneForPost(zone) {
        var a_zone = "<a id=\"zonePost\" onclick=\"zTab.setZone('" +zone.replace(/_/g," ").capitalize() + "','','','');drawMap('" +zone + "');ajustMapToExtendedString('" + zone + "');\">" + zone.replace(/_/g, " ").capitalize() + "</a>";
        return a_zone;
    }

    var getRelevanceForPost = function (relevance, id) {
        var r_post = "<spam id='relevance_" + id + "'>" + relevance + "</spam>";
        return r_post;
    }

    var getAvatarForPost = function (links, source) {
        var avatar;

        //Analizo los links, para avatar e imágenes
        if (links) {
            links.each(function(link) {
                //AVATARS
                if (link.type == 'avatar') avatar = link.url;
            });
        }
        //Si no tengo avatars, porgo por defecto
        if (!avatar) {
            if (source == 'Facebook') avatar = '/images/facebook.png';
            else if (source == 'Twitter') avatar = '/images/twitter.png';
            else avatar = '/images/rss.png'
        }

        return '<img class="avatar" src="' + avatar + '"/>';
    }

    var getImgForPost = function (links) {
        var img;
        var display = "none";
        
        if (links) {
            links.each(function(link) {
                if (link.type == 'picture'){
                    img = link.url;
                    display = "inline";
                    return;
                }
            });
        }

        if (img)
            return '<img class="img" style="display:' + display + '" src="' + img + '"/>';
        else
            return '';
    }

    this.removeNewClass = function () {
        $$('li.newPost').removeClass('newPost');
    }

    var updatePosts = function updatePosts(docs, more, newPosts) {
        //Recupero los post del verbatim y realizo los cambios necesarios
        var posts = [];
        var first = true;
        if(docs && typeOf(docs) == 'array')
            docs.each(function (doc) {
                var post = JSON.parse(doc.verbatim);
                $$('#' + post.id).dispose(); //Eliminar el post HTML si ya existe en la página
                post.modifiedPretty = prettyDate(doc.modified);
                post.modified = doc.modified;
                var target = getTarget(post, doc.id);
                post.title = getPostTitle(post.title, target);
                post.text = getVerMas(post.text ? post.text : "");
                var zone = getZoneForPost(post.zone.extendedString);
                if(first){
                    firstPostZone = post.zone.extendedString;
                    first = false;
                }
                post.zone = zone;
                post.actions.each(function (action){
                    if(action.type == 'comment') action.type = 'comentarios';
                    if(action.type == 'comments') action.type = 'comentarios';
                    if(action.type == 'like') action.type = 'me gusta';
                    if(action.type == 'replies') action.type = 'respuestas';
                });
                post.relevance = getRelevanceForPost(post.relevance, post.id);
                post.img = getImgForPost(post.links);
                post.avatar = getAvatarForPost(post.links, post.source);
                posts.push(post);
                if (newPosts)
                    post.clase = 'newPost';
                else
                    post.clase = '';
            });
        if(more){
            htmlPosts.append(posts).notify(function(event) {
                //console.log(JSON.stringify(event));
                if (event.type == TempoEvent.Types.RENDER_STARTING) {
                    postsContainer.addClass('loading');
                }
                if (event.type == TempoEvent.Types.RENDER_COMPLETE) {
                    postsContainer.removeClass('loading');
                }
            });
        } else if(newPosts) {
            htmlPosts.prepend(posts).notify(function(event) {
                //console.log(JSON.stringify(event));
                if (event.type == TempoEvent.Types.RENDER_STARTING) {
                    postsContainer.addClass('loading');
                }
                if (event.type == TempoEvent.Types.RENDER_COMPLETE) {
                    postsContainer.removeClass('loading');
                }
            });
            setTimeout("zTab.removeNewClass();", newPostTime);
        } else {
            htmlPosts.render(posts).notify(function(event) {
                //console.log(JSON.stringify(event));
                if (event.type == TempoEvent.Types.RENDER_STARTING) {
                    postsContainer.addClass('loading');
                }
                if (event.type == TempoEvent.Types.RENDER_COMPLETE) {
                    postsContainer.removeClass('loading');
                }
            });
        }
        armarTitulo(firstPostZone);
    }        
    
    this.updatePosts = updatePosts;

    this.checkTag = function checkTag(tag, bottonId){
        // alert("tag "+tag+" indexOF "+zTags.indexOf(tag) );
        if(zCtx.zTags.indexOf(tag)!= -1 ){

            $(bottonId).setStyle('display','inline');
        } else {
            $(bottonId).setStyle('display','none');
        }
    }

    this.show_confirm = function show_confirm(idInputTag,selectedTag,tags)
    {
        var r=confirm("Esta seguro de Agregar el Tag: "+selectedTag);
        if (r==true)
        {
            addTagToPost(idInputTag,tags,selectedTag);
        }
        else
        {
            alert("Cancelado");
        }
    }
    this.addTagToPost = function addTagToPost(idPost,tags,selectedTag){
        //\"tags\":[\"Espectaculos\"]

        var url = '/ZCrawlScheduler/indexPosts?url=http://localhost:38080/solr&doc={"id":"'+encodeURIComponent(idPost)+'"}&aTags='+tags+','+selectedTag;
        var urlProxy = '/curl_proxy.php';
        new Request({
            url: urlProxy,
            method: 'post',
            data: {
                'host': this.host ? this.host : "localhost",
                'port': this.port ? this.port : "38080",
                'ws_path':url
            },
            onRequest: function(){
            },
            onSuccess: function(response) {
                if(response.length > 0) {
                    response = JSON.decode(response);
                    if(response.id){
                        var span_tags = new Element('span').addClass('cp_tags').inject($('tagsDiv_'+response.id).getLast().getPrevious(),'before');
                        new Element('a', {
                            'html': selectedTag,
                            'onclick': 'ckeckOnlyTag("' + selectedTag + '");'
                        }).inject(span_tags);
                        $('tagsDiv_'+response.id).addClass(selectedTag);
                    }
                }
            },
            // Our request will most likely succeed, but just in case, we'll add an
            // onFailure method which will let the user know what happened.
            onFailure: function(){
            }
        }).send();
    }

    this.delTagFromPost = function delTagFromPost(idPost,selectedTag){
        //\"tags\":[\"Espectaculos\"]

        var url = '/ZCrawlScheduler/indexPosts?url=http://localhost:38080/solr&doc={"id":"'+encodeURIComponent(idPost)+'"}&rTag='+selectedTag;
        var urlProxy = '/curl_proxy.php';
        new Request({
            url: urlProxy,
            method: 'post',
            data: {
                'host': this.host ? this.host : "localhost",
                'port': this.port ? this.port : "38080",
                'ws_path':url
            },
            onRequest: function(){
            },
            onSuccess: function(response) {
                if(response.length > 0) {
                    response = JSON.decode(response);
                    if(response.id){
                        $('tagsDiv_'+response.id).getElements('span.cp_tags a').each(function(tagA){
                            if(tagA.innerHTML == selectedTag)
                                tagA.getParent().dispose();
                        });
                        $('tagsDiv_'+response.id).removeClass(selectedTag);
                    }
                }
            },
            // Our request will most likely succeed, but just in case, we'll add an
            // onFailure method which will let the user know what happened.
            onFailure: function(){
            }
        }).send();
    }

    this.spanishDate = function spanishDate(d){
        var weekday=["Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sabado"];

        var monthname=["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];

        return fixTime(d.getHours()) + ":" + fixTime(d.getMinutes()) + ":" + fixTime(d.getSeconds()) + ", " + weekday[d.getDay()]+" "+d.getDate()+" de "+monthname[d.getMonth()]+" de "+d.getFullYear();
    }

    this.fixTime = function fixTime(i) {
        return (i<10 ? "0" + i : i);
    }

    this.setSourceVisible = function setSourceVisible(source, visible) {

        if (visible) {
            zcAddSource(source);
        } else {
            zcUncheckSource(source);
        }
    //refreshFiltro();
    }

    this.ckeckOnlyTag = function ckeckOnlyTag(tag) {
        var zCtxChkTags = this.zcGetCheckedTags();

        zCtxChkTags.each(function (chkTag) {
            if (chkTag != tag) {
                zcUncheckTag(chkTag);
            }
        });

        var tagFilters = $$('table#tagsFilterTable input');
        tagFilters.each(function(input) {
            if (input.id == 'chkt' + tag) {
                input.checked = true;
            } else {
                input.checked = false;
            }
        });
        setTagVisible(tag, true);
    }

    this.setTagVisible = function setTagVisible(tag, checked) {

        //Actualizo el contexto
        if (checked) {
            if($('chkt'+tag))
                $('chkt'+tag).addClass('checked');
            zcAddTag(tag);
        } else {
            if($('chkt'+tag))
                $('chkt'+tag).removeClass('checked');
            zcUncheckTag(tag);
        }
    //refreshFiltro();
    }

    this.refreshFiltro = function refreshFiltro(){
        //var zCtxTemp = zcGetContext();
        //Refresco visibilidad de Posts
        //var zCtxChkTags = this.zcGetCheckedTags();
        //var zCtxChkSource = this.zcGetCheckedSources();

        var posts = $$('div#postsContainer div.story-item');
        if(typeOf(posts) == 'elements') {
            posts.each(function(post){
                var visible = false;
                zCtxChkSource.each(function (source){
                    if(post.hasClass(source)) {
                        zCtxChkTags.each(function (tag) {
                            if(post.hasClass(tag))
                                visible = true;
                        });
                    }
                });
                post.setStyle('display', visible ? 'block' : 'none');
                post.removeClass(!visible ? 'visible' : 'hidden');
                post.addClass(visible ? 'visible' : 'hidden');
            });
        }

        //    sendFilter(source,visible);
        armarTitulo(this.tab);
    }





    //	Fri Sep 23 2011 09:51:35 GM /0300 (AR )

    //	2010-08-28T20:24:17Z

    var prettyDate = function prettyDate(time){
        var time_formats = [
        [60, 'segundos', 1], // 60
        [120, ' hace 1 minuto', 'hace 1 minuto'], // 60*2
        [3600, 'minutos', 60], // 60*60, 60
        [7200, ' hace 1 hora', 'hace 1 hora'], // 60*60*2
        [86400, 'horas', 3600], // 60*60*24, 60*60
        [172800, '1 dia', 'mañana'], // 60*60*24*2
        [604800, 'días', 86400], // 60*60*24*7, 60*60*24
        [1209600, ' en la ultima semana', 'próxima semana'], // 60*60*24*7*4*2
        [2419200, 'semanas', 604800], // 60*60*24*7*4, 60*60*24*7
        [4838400, ' ultimo mes', 'próximo mes'], // 60*60*24*7*4*2
        [29030400, 'meses', 2419200], // 60*60*24*7*4*12, 60*60*24*7*4
        [58060800, ' en el ultimo año', 'proximo año'], // 60*60*24*7*4*12*2
        [2903040000, 'años', 29030400], // 60*60*24*7*4*12*100, 60*60*24*7*4*12
        [5806080000, 'ultimo siglo', 'proximo siglo'], // 60*60*24*7*4*12*100*2
        [58060800000, 'siglos', 2903040000] // 60*60*24*7*4*12*100*20, 60*60*24*7*4*12*100
        ];
        //var time = ('' + date_str).replace(/-/g,"/").replace(/[TZ]/g," ").replace(/^\s\s*/, '').replace(/\s\s*$/, '');
        //if(time.substr(time.length-4,1)==".") time =time.substr(0,time.length-4);
        var seconds = (new Date - new Date(time)) / 1000;
        var token = ' hace', list_choice = 1;
        if (seconds < 0) {
            //seconds += 10800;
            seconds = Math.abs(seconds);
            //token = 'desde ahora';
            list_choice = 2;
        }
        var i = 0, format;
        while (format = time_formats[i++])
            if (seconds < format[0]) {
                if (typeof format[2] == 'string')
                    return format[list_choice];
                else
                    return (token+ ' ' + Math.floor(seconds / format[2]) + ' ' + format[1]);
            }
        return time;
    }

    var updatePostDate = function () {
        $$('p.fecha').each(function(p) {
           p.innerHTML = prettyDate(p.title);
        });
    }

   
    var armarTitulo = function armarTitulo(){
        var temp = 0 ;
        // tabTemp = this.tab;
        var zoneSeltemp = zCtx.zcGetZone();
        var zoneEfectemp = firstPostZone;
        //console.log("zona seleccionada"+zoneSeltemp);
        //console.log("zona efectiva"+zoneEfectemp);
        $('tituloSup').innerHTML = "";

        if (zoneEfectemp != zoneSeltemp && zoneSeltemp != "" && typeof(zoneSeltemp) != 'undefined'){
            $('tituloSup').innerHTML = "No se encontraron noticias para la zona seleccionada";
        }
    }
    this.armarTitulo = armarTitulo;

    this.popup = function popup(id){
        this.zirClient.loadSolrPost(id, function(doc){
            updatePost(doc);
        });
    }    
}

var zTab = new ZTabs();

window.addEvent('domready', function() {
    //if(postsContainer){
    console.log('domready antes de initAll');
    zTab.setComponents($('postTemplate'), $('filtersContainer'), $('verNuevos'));
    zTab.initAll();        
});