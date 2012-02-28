var nodeURL = 'http://192.168.0.2:4000';
var sources = new Array();
//var tags = new Array();
var zones = new Array();
var allZones = new Array();
var tab = "";
var zoneInitiated = false;
var zUserGroups = new Array();
var postInterval = null;
var reverseFlag = false;

window.addEvent('domready', function() {
    if($('postsContainer'))
        initAll();
});

function initVista(zCtx){
    if($('postsContainer'))
        $('postsContainer').empty();
    if($('newPostsContainer'))
        $('newPostsContainer').empty();
    $('verNuevos').setStyle('display','none');
    zirClient.setFirstIndexTime(null);
    zirClient.setLastIndexTime(null);
    zirClient.setMinRelevance(null);
    zirClient.setSearchKeyword("");
    initFilters(zCtx);
    if(zCtx.zTab == ''){
        tab = 'portada';
        zcSetTab('portada');
    }
    if (zCtx.zTab != 'geoActivos') {
        $('postsDiv').set({
            style: 'display:block'
        });
        $('mapDiv').set({
            style: 'display:none'
        });
        $('verMas').setStyle('display','block');
        //loadPost(true);
        //setZone(zCtx.selZone, zcGetSelectedZoneName());
    } else {
        $('postsDiv').set({
            style: 'display:none'
        });
        $('mapDiv').set({
            style: 'display:block'
        });
        $('verMas').setStyle('display','none');
        initMapTab();
        $('enLaRed').set({
            style: 'display:inline'
        });
        $('noticiasEnLaRed').set({
            style: 'display:inline'
        });
        $('tempoDiv').set({
            style: 'display:inline'
        });
    }
    if (zCtx.zTab == 'portada'){
        $('enLaRed').set({
            style: 'display:inline'
        });
        $('noticiasEnLaRed').set({
            style: 'display:inline'
        });
        $('tempoDiv').set({
            style: 'display:inline'
        });
    }
    if (zCtx.zTab == 'relevantes'  || zCtx.zTab == 'enlared'){
        $('enLaRed').set({
            style: 'display:inline'
        });
        $('noticiasEnLaRed').set({
            style: 'display:none'
        });
        if(zCtx.zTab == 'enlared'){
            $('tempoDiv').set({
                style: 'display:none'
            });
        } else {
            $('tempoDiv').set({
                style: 'display:inline'
            });
        }
    }
    if (zCtx.zTab == 'noticiasenlared'  || zCtx.zTab == 'noticiasenlaredrelevantes'){
        $('enLaRed').set({
            style: 'display:none'
        });
        $('noticiasEnLaRed').set({
            style: 'display:inline'
        });
        if(zCtx.zTab == 'noticiasenlared'){
            $('tempoDiv').set({
                style: 'display:none'
            });
        } else {
            $('tempoDiv').set({
                style: 'display:inline'
            });
        }
    }

//zcSetTab(tab);
//alert("SelZoneCode: " + zCtx.selZone + " SelZoneName: " + zcGetSelectedZoneName() + " EfZoneCode: " + zCtx.efZone + " EfZoneNane: " + zcGetEfectiveZoneName());
}

function initAll() {
    initZCtx(function(zCtx) {
        tab = zcGetTab();
        setZone(zCtx.selZone, zcGetSelectedZoneName());
        initZonas(zCtx.selZone);
        initVista(zCtx);
    });
    zUserGroups = loguedUser;
}

function initPost() {
    if (tab != 'geoActivos' && $('postsContainer')) {
        if(postInterval) {
            clearInterval(postInterval);
            postInterval = null;
        }
        postInterval = setInterval(function () {
            loadPost(false);
        }, 60000);
    } else {
        if(postInterval) {
            clearInterval(postInterval);
            postInterval = null;
        }
    }
    //loadPost(true);
    getAllTags();
}

function initFilters(zCtx) {
    initSourceFilters(zCtx);
    initTagFilters(zCtx);
    initTempFilters(zCtx);
    initPost();
}

function initZonas(selZone) {
    if (!zoneInitiated) {
        zoneInitiated = true;
        if (zcGetContext().selZone != '') {
            $('zoneExtended').value = zcGetContext().selZone;
        }
        /*getProvincias(function(provincias) {
            provincias.each(function(provincia) {
                new Element('option', {
                    'value': provincia.id,
                    'html': provincia.name.replace(/_/g, ' ').capitalize()
                }).inject($('provincias'));
            });*/
        /*$('zoneExtended').addEvent('click', function(){
            if($('zoneExtended').value && $('zoneExtended').value.length > 0)
                //alert("ZONA: "+ $('zoneExtended').value);
                setZone($('zoneExtended').value, '', '', '');
        //loadMunicipios($('provincias').selectedIndex == 0 ? '' : $('provincias').value, null);
        //zcSetProvinceName($('provincias').selectedIndex == 0 ? '' : $('provincias').options[$('provincias').selectedIndex].innerHTML);
        });*/
    /*if (selZone != "" && typeof(selZone) != 'undefined') {
                getZoneById(selZone, function(zone) {
                    if (zone.type != 'provincia') {
                        loadMunicipios(zone.parent, zone.id);
                        getZoneById(zone.parent, function(parent) {
                            $('provincias').value = parent.id;
                        });
                    } else {
                        loadMunicipios(zone.id, null);
                        $('provincias').value = zone.id;
                    }
                });
            }
        });*/

    /*getAllZones(function(zones) {
           zones.each(function(zone) {
               allZones.push(zone.name.replace(/_/g, ' ').capitalize());
            });
        });*/
    }
}

function initSourceFilters(zCtx) {
    //Actualizo filtros de fuente desde contexto
    zCtx.filters.sources.each(function(source) {
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
            if (source.name == 'Facebook' || source.name == 'Twitter')
                sourcesTr.inject($('enLaRed'));
            else
                sourcesTr.inject($('noticiasEnLaRed'));
        }
    });
}

function initTagFilters(zCtx) {
    //Actualizo filtros de tags desde contexto
    zCtx.filters.tags.each(function(tag) {
        var tagChk = $('chkt'+tag.name);
        if (!tagChk) {
            var tagTr = new Element('tr');
            var tagChkBoxTd = new Element('td').inject(tagTr);
            new Element('input', {
                'id': 'chkt' + tag.name,
                'type': 'checkbox',
                'checked': (tag.checked ? 'checked' : ''),
                'name': tag.name,
                'value': tag.name,
                'onclick': 'setTagVisible(this.value,this.checked);'
            }).inject(tagChkBoxTd);
            new Element('td', {
                'html': tag.name
            }).inject(tagTr);
            tagTr.inject($('tagsFilterTable'));
        }
    });
}

function initTempFilters(zCtx) {
    $('tempoSelect').value = zCtx.filters.temp;
    $('tempoSelect').addEventListener('change', onTempoChange, false);
}

/*function loadMunicipios(id_provincia, selZone) {
    $('zonalid').empty();
    new Element('option', {
        'value': '',
        'html': 'Seleccione un municipio...',
        'onclick': "setZone(this.value, '', $('provincias').value, zcGetProvinceName())"
    }).inject($('zonalid'));

    if (id_provincia != '') {
        getZonesByProvincia(id_provincia, function(zones) {
            zones.each(function(zone) {
                new Element('option', {
                    'value': zone.id,
                    'html': zone.name.replace(/_/g, ' ').capitalize()
                }).inject($('zonalid'));
            });
            $('zonalid').addEvent('change', function(){
                setZone($('zonalid').value, $('zonalid').selectedIndex == 0 ? '' : $('zonalid').options[$('zonalid').selectedIndex].innerHTML, $('provincias').value, zcGetProvinceName())
            });
            if (selZone != null) {
                $('zonalid').value = selZone;
            }
        });
    }
}*/

function setZone(zoneExtended, zoneName, parentId, parentName) {
    //alert("SetZone. zoneId: " + zoneId + " zoneName: " + zoneName + " parendId: " + parentId + " parentName: " + parentName);
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
    zirClient.setSearchKeyword("");
    $('zonalesSearchword').value = "buscar...";
    if (tab != 'geoActivos' && tab != 'editor' && tab != 'list' && $('postsContainer') && $('newPostsContainer')) {
        $('postsContainer').empty();
        $('newPostsContainer').empty();
    }
    console.log('Antes de setear: ' + zoneExtended);
    setSelectedZone(zoneExtended, zoneName, parentId, parentName, function() {
        console.log('Despu�s de setear: ' + zCtx.selZone);

        //alert("CUANDO VUELVO DEL setSelectedZone. SelZoneCode: " + zCtx.selZone + " SelZoneName: " + zcGetSelectedZoneName() + " EfZoneCode: " + zCtx.efZone + " EfZoneNane: " + zcGetEfectiveZoneName());
        /*if (tab != 'geoActivos' && $('postsContainer')) {
            loadPost(true);
        }*/
        });
}

function onTempoChange() {
    if (tab != 'geoActivos' && $('postsContainer')) {
        $('postsContainer').empty();
        $('newPostsContainer').empty();
    }
    setLastIndexTime(null);
    zcSetTemp($('tempoSelect').value);
/*if (tab != 'geoActivos' && $('postsContainer')) {
        loadPost(true);
    }*/
}

function complete(number){
    return (number > 9 ? ''+number : '0'+number);
}

function loadPost(first){
    //alert("LoadPost: " + JSON.stringify(zcGetContext()));
    zirClient.loadSolrPost(tab, zcGetEfectiveZoneName(), false, function(jsonObj) {
        if(typeof jsonObj != 'undefined'){
            if(first){
                updatePosts(jsonObj,$('postsContainer'));
                armarTitulo(tab);
            }
            else {
                updatePosts(jsonObj,$('newPostsContainer'));
                if($('newPostsContainer').childNodes.length > 0){
                    $('verNuevos').value= $('newPostsContainer').getChildren('div').length+' nuevo'+($('newPostsContainer').getChildren('div').length > 1 ? 's' : '')+'...';
                    $('verNuevos').setStyle('display','block');
                } else{
                    $('verNuevos').setStyle('display','none');
                }
            }
            zirClient.searching = false;
        }
    //console.log('loadPost callback ' + zirClient.searching);
    });
}

function loadMorePost(){
    console.log('loadMorePost');
    zirClient.loadSolrPost(tab, zcGetEfectiveZoneName(), true, function(jsonObj) {
        if(typeof jsonObj != 'undefined') {
            //console.log('not undefined');
            updatePosts(jsonObj, $('postsContainer'),true);
            zirClient.searching = false;
        } else {
        //console.log('undefined!');
        }
        armarTitulo(tab);
    //console.log('loadMorePost callback ' + zirClient.searching);
    });
}

function searchPost(keyword, zone) {
    if (keyword != 'buscar...' && keyword != '') {
        zirClient.setFirstIndexTime(null);
        zirClient.setLastIndexTime(null);
        zirClient.setMinRelevance(null);
        zirClient.setSearchKeyword(keyword);
        if (tab != 'geoActivos' && $('postsContainer')) {
            $('postsContainer').empty();
            $('newPostsContainer').empty();
        }
        zirClient.loadSolrPost(tab, zone, false, function(jsonObj) {
            if(jsonObj.response.docs.length > 0) {
                updatePosts(jsonObj, $('postsContainer'),true);
            } else {
                if (zcGetContext().efZone != '') {
                    new Element('label', {
                        'html': 'No se encontraron resultados para su búsqueda en la zona seleccionada'
                    }).inject($('postsContainer'));
                    new Element('input', {
                        'type': 'button',
                        'onclick': 'searchPost("' + keyword + '","")',
                        'value': 'Buscar en todas las zonas'
                    }).inject($('postsContainer'));
                } else {
                    new Element('label', {
                        'html': 'No se encontraron resultados para su búsqueda'
                    }).inject($('postsContainer'));
                }
            }
            armarTitulo(tab);
        });
    }
}

function getTarget(post) {
    ret = '';
    if ((post.source).toLowerCase() == 'twitter') {
        ret = 'http://twitter.com/#!/' + post.fromUser.name;
    } else if ((post.source).toLowerCase() == 'facebook') {
        ret = post.fromUser.url;
    } else {
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

function getVerMas(text){
    if(text.length > 255){
        var i=255;
        for( ;text.charAt(i) != ' ';i--);
        var shortText = text.substring(0,i);
        var otherText = text.substring(i);
        return shortText + "<span id=\"verMas\" onclick=\"if (this.getNext()) {this.getNext().innerHTML = unescape(this.getNext().innerHTML); this.getNext().setStyle('display','inline'); this.style.display = 'none';}\" style=\"display: inline;\">... [+]</span><span style=\"display: none;\" id=\"resto\">"+escape(otherText)+"</span>";
    }
    return text;
}

function verNuevos(){
    $$('div#postsContainer div.story-item').set({
        style: 'background:#FFFFFF'
    });
    $$('div#newPostsContainer div.story-item').set({
        style: 'background:#DCEFF4'
    }).reverse().each(function(post){

        $('postsContainer').getElements('div[id^=si]').each(function(element) {
            if (element.id == post.id) {
                $('postsContainer').removeChild(element);
            }
        });

        if (tab == "enlared" || tab == "noticiasenlared" || tab == "portada"){
            var newPost = post.clone(true, true);
            newPost.setStyle('display',$('chk'+(newPost.getElement("div.story-item-gutters div.story-item-content ul.story-item-meta li.story-item-submitter a").innerHTML)).checked ? 'block' : 'none');
            newPost.injectTop($('postsContainer'));
        /*$('newPostsContainer').getElements('span.story-item-real-modified-date').each(function(newCount){
                var insertado = false;
                newCountModifiedDate = new Date(newCount.innerHTML).getMilliseconds();
                $('postsContainer').getElements('span.story-item-real-modified-date').each(function(count){
                    countModifiedDate = new Date(count.innerHTML).getMilliseconds();
                    if (newCountModifiedDate > countModifiedDate && !insertado) {
                        insertado = true;
                        newCount.getParent().getParent().getParent().getParent().getParent().injectBefore(count.getParent().getParent().getParent().getParent().getParent());
                    }
                });
                if(!insertado){
                    newCount.getParent().getParent().getParent().getParent().getParent().injectInside($('postsContainer'));
                }
            });*/
        }

        if (tab == "relevantes" || tab == "noticiasenlaredrelevantes"){
            $('newPostsContainer').getElements('span.zonales-count').each(function(newCount){
                var insertado = false;
                $('postsContainer').getElements('span.zonales-count').each(function(count){
                    if (parseInt(newCount.innerHTML) > parseInt(count.innerHTML) && !insertado) {
                        insertado = true;
                        newCount.getParent().getParent().getParent().getParent().getParent().injectBefore(count.getParent().getParent().getParent().getParent().getParent());
                    }
                });
                if(!insertado){
                    newCount.getParent().getParent().getParent().getParent().getParent().injectInside($('postsContainer'));
                }
            });
        }
    });
    //$('postsContainer').set({ style: 'background:#FFFFFF'});
    $('verNuevos').setStyle('display','none');
    $('newPostsContainer').empty();
    refreshFiltro();
// $('postsContainer').setStyle('backgroundColor','#FFFFFF');
}

function incRelevance(id,relevance){
    var url = '/ZCrawlScheduler/indexPosts?url=http://localhost:38080/solr&doc={"id":"'+encodeURIComponent(id)+'"}&rel='+relevance;
    var urlProxy = '/curl_proxy.php';

    new Request({
        url: urlProxy,
        method: 'post',
        data: {
            'host': host ? host : "localhost",
            'port': port ? port : "38080",
            'ws_path':url
        },
        onRequest: function(){
        },
        onSuccess: function(response) {
            if(response && response.length != 0){
                response = JSON.decode(response);
                if(response.id && response.id.length > 0 && $('relevance_'+response.id)){
                    $('relevance_'+response.id).innerHTML = parseInt($('relevance_'+response.id).innerHTML)+parseInt(relevance);
                    if(zUserGroups.indexOf("4") == -1){
                        $('relevance_'+response.id).getPrevious().removeEvents('click');
                        $('relevance_'+response.id).getNext().removeEvents('click');
                    }
                }
            }
        },
        // Our request will most likely succeed, but just in case, we'll add an
        // onFailure method which will let the user know what happened.
        onFailure: function(){
        }
    }).send();

}


function updatePosts(json, component, more) {
    if (reverseFlag) {
        var docs = json.response.docs.reverse();
        reverseFlag = false;
        console.log(docs);

        if(docs.length == 0)
            return;
        if(typeof(json) == 'undefined')
            return;
        if(typeof more == 'undefined' || !more) {
            //docs = docs/.reverse();
            if(!zirClient.getFirstIndexTime()) {
                zirClient.setFirstIndexTime(docs.pick().indexTime);
            }
        } else {
            zirClient.setFirstIndexTime(docs.getLast().indexTime);
        }
        //alert(JSON.stringify(docs));
        docs.each(function(doc){
            var time = new Date(doc.indexTime).getTime();
            zirClient.setLastIndexTime((time > zirClient.getLastIndexTime()) ||  zirClient.getLastIndexTime() == null ? time : zirClient.getLastIndexTime());
            var modified = doc.modified;
            zirClient.setFirstModifiedTime((modified < zirClient.getFirstModifiedTime()) ||  zirClient.getFirstModifiedTime() == null ? modified : zirClient.getFirstModifiedTime());
            var post = eval('('+doc.verbatim+')');
            var div_story_item = new Element('div', {
                'id': 'si_' + doc.id
            }).addClass('story-item').addClass('group').addClass(post.source),
            div_story_item_gutters = new Element('div').addClass('story-item-gutters').inject(div_story_item).addClass('group'),
            div_story_item_zonalesbtn = new Element('div').addClass('story-item-zonalesbtn').inject(div_story_item_gutters),
            div_zonalesbtn_hast = new Element('div').addClass('zonales-btn has-tooltip').inject(div_story_item_zonalesbtn),
            div_zonales_count_wrapper = new Element('div').addClass('zonales-count-wrapper').inject(div_zonalesbtn_hast),
            div_zonales_count_wrapper_up = new Element('div').addClass('zonales-count-wrapper-up').inject(div_zonales_count_wrapper),
            span_relevance = new Element('span',{
                "id":"relevance_"+doc.id
            }).addClass('zonales-count').set('html',post.relevance).inject(div_zonales_count_wrapper),
            div_zonales_count_wrapper_down = new Element('div').addClass('zonales-count-wrapper-down').inject(div_zonales_count_wrapper),
            div_story_item_content = new Element('div').addClass('story-item-content').addClass('group').inject(div_story_item_zonalesbtn, 'after'),
            div_story_item_details = new Element('div').addClass('story-item-details').inject(div_story_item_content),
            div_story_item_idPost = new Element('div', {
                'html': doc.id,
                'id':'idPostDiv'
            }).addClass('group').inject(div_story_item).setStyle('display','none'),
            div_story_item_header = new Element('div').addClass('story_item_header').inject(div_story_item_details),
            table_story_item = new Element('table').inject(div_story_item_header),
            tr_story_title = new Element('tr').inject(table_story_item),
            td_story_title = new Element('td').inject(tr_story_title),
            h3_story_item_title = new Element('h3').addClass('story-item-title').inject(td_story_title),
            a_title = new Element('a', {
                'target': '_blank',
                'href' : getTarget(post)
            }).set('html',post.title).inject(h3_story_item_title),
            span_external_link_icon = new Element('span').addClass('external-link-icon').inject(a_title, 'after'),
            tr_story_description = new Element('tr').inject(table_story_item),
            //td_story_image = new Element('td').inject(tr_story_description),
            td_story_description = new Element('td').inject(tr_story_description),
            p_story_item_description = new Element('p').addClass('story-item-description').inject(td_story_description),
            a_story_item_source = new Element('a', {
                'target': '_blank'
            }).set('html','').addClass('story-item-source').inject(p_story_item_description),
            a_story_item_icon = new Element('a').addClass('story-item-icon').inject(a_story_item_source, 'before'),
            a_story_item_icon_image = new Element('img',{
                'src': '/logo_'+post.source.replace('/','').toLowerCase()+'.png'
            }).inject(a_story_item_icon).addClass('source_logo'),
            a_story_item_teaser = new Element('span', {}).set('html',post.text ? ' - ' + getVerMas(post.text.trim()) : '').addClass('story-item-teaser').inject(a_story_item_source, 'after'),
            ul_story_item_meta = new Element('ul').addClass('story-item-meta').addClass('group').inject(div_story_item_content),
            li_story_submitter = new Element('li', {}).set('html','Publicado en  ').addClass('story-item-submitter').inject(ul_story_item_meta).setStyle('display', post.fromUser.name ? 'block' : 'none'),
            a_story_submitter = new Element('a', {
                'target': '_blank',
                'href': post.fromUser.url
            }).set('html',post.source).inject(li_story_submitter),
            span_storyitem_modified_real = new Element('span', {
                'html': modified,
                'style': 'display:none'
            }).addClass('story-item-real-modified-date').inject(a_story_submitter,'after'),
            span_storyitem_modified = new Element('span', {}).set('html',prettyDate(modified)).addClass('story-item-modified-date').inject(a_story_submitter,'after'),
            span_storyitem_fromuser = new Element('span', {}).set('html',post.fromUser =((post.fromUser.name).indexOf(post.source )!=-1)? "" : ' por '+post.fromUser.name).inject(a_story_submitter,'after'),
            div_inline_comment_container = new Element('div').addClass('inline-comment-container').inject(div_story_item_content),
            div_story_item_activity = new Element('div').addClass('story-item-activity').addClass('group').addClass('hidden').inject(div_story_item_content),
            div_story_item_media   = new Element('div').addClass('story-item-media').inject(div_story_item_content, 'after');

            if(typeOf(post.actions) == 'array') {
                post.actions.each(function(action){
                    switch (action.type) {
                        case 'comment':
                            var li_story_item_comments = new Element('li', {}).set('html',action.cant).addClass('story-item-comments').inject(ul_story_item_meta);
                            new Element('div').addClass('story-item-comments-icon').inject(li_story_item_comments);
                            break;
                        case 'like':
                            var li_story_item_likes = new Element('li', {}).set('html',action.cant).addClass('story-item-likes').inject(ul_story_item_meta);
                            new Element('div').addClass('story-item-likes-icon').inject(li_story_item_likes);
                            break;
                        case 'retweets':
                            var li_story_item_retweets = new Element('li', {}).set('html',action.cant).addClass('story-item-retweets').inject(ul_story_item_meta);
                            new Element('div').addClass('story-item-retweets-icon').inject(li_story_item_retweets);
                            break;
                        case 'replies':
                            var li_story_item_replies = new Element('li', {}).set('html',action.cant).addClass('story-item-replies').inject(ul_story_item_meta);
                            new Element('div').addClass('story-item-replies-icon').inject(li_story_item_replies);
                            break;
                    }
                });
            }

            div_zonales_count_wrapper_up.addEvent('click',function(){
                var inc = 1;
                if(zUserGroups.length == 0 || zUserGroups[0] == ''){
                    if(confirm('Debe registrarse para otorgar puntos!')) {
                        window.location.href='/index.php/component/users/?view=registration';
                    }
                }else {
                    if(zUserGroups.indexOf("4") != -1){
                        inc = prompt('Indique en cuanto desea incrementar la relevancia','1');
                    }
                    incRelevance(doc.id,inc);
                }
            });

            div_zonales_count_wrapper_down.addEvent('click',function(){
                var inc = 1;
                if(zUserGroups.length == 0 || zUserGroups[0] == ''){
                    if(confirm('Debe registrarse para otorgar puntos!')) {
                        window.location.href='/index.php/component/users/?view=registration';
                    }
                }else{
                    if(zUserGroups.indexOf("4") != -1){
                        inc = prompt('Indique en cuanto desea decrementar la relevancia','1');
                    }
                    incRelevance(doc.id,inc*(parseInt(inc) > 0 ? (-1): 1));
                }
            });

            var postLinks;

            switch(post.source.toLowerCase()) {
                case 'facebook':
                    postLinks =	post.links;
                    break;
                default:
                    postLinks =	post.links;
            }

            var a_thumb = new Element('a', {
                'href': getTarget(post),
                'target': '_blank'
            });

            if(typeOf(postLinks) == 'array') {
                postLinks.each(function(link){
                    switch (link.type) {
                        case 'picture':
                            if(a_thumb.childNodes.length == 0 && link.url) {
                                a_thumb.inject(a_story_item_icon, 'before').addClass('thumb').addClass('thumb-s'),
                                img = new Element('img', {
                                    'src': link.url.indexOf('/') == 0 ? 'http://' + post.source + link.url.substr(1) : (link.url.indexOf('http://') == 0 ? link.url : 'http://' + post.source + link.url)
                                }).inject(a_thumb);
                            }
                            break;
                        case 'video':
                            var li_story_item_video = new Element('li').addClass('story-item-video').inject(ul_story_item_meta),
                            a_story_item_video = new Element('a', {
                                'href': link.url,
                                'target': '_blank'
                            }).addClass('story-item-video').inject(li_story_item_video);
                            new Element('img', {
                                'src': 'http://www.prophecycoal.com/images/video_icon.jpg',
                                'alt': 'Video',
                                'title': 'Video'
                            }).addClass('story-item-video-icon').inject(a_story_item_video);
                            break;
                        case 'link':
                            var li_story_item_link = new Element('li').addClass('story-item-link').inject(ul_story_item_meta),
                            a_story_item_link = new Element('a', {
                                'href': link.url,
                                'target': '_blank'
                            }).set('html','Mas info...').addClass('story-item-link').inject(li_story_item_link);
                            break;
                    }
                });
            }

            //  var date = new Date(parseInt(post.created));
            //  new Element('li', {}).set('html','Creado: ' + spanishDate(date)).addClass('story-item-created-date').inject(ul_story_item_meta);

            // date = new Date(parseInt(post.modified));


            var tags = post.tags;
            var div_story_tags = new Element('div',{
                'id':'tagsDiv_'+doc.id
            }).addClass('cp_tags').inject(div_story_item_content);
            new Element('span').set('html','Tags: ').inject(div_story_tags);
            if(typeOf(tags) == 'array') {
                tags.each(function(tag){
                    var span_tags = new Element('span').addClass('cp_tags').inject(div_story_tags);
                    new Element('a', {
                        'html': tag,
                        'onclick': 'ckeckOnlyTag("' + tag + '");'
                    }).inject(span_tags);
                    div_story_item.addClass(tag);
                    if(zUserGroups.indexOf("4") != -1) {
                        var del_tag_img = new Element('img',{
                            'src': '/images/eliminar.png'
                        }).inject(span_tags).addClass('delete_tag');
                        del_tag_img.addEvent('click', function(){
                            if(confirm('Realmente desea eliminar el tag '+tag)){
                                delTagFromPost(doc.id, tag);
                            }
                        });
                    }
                });
            }
            var idInputTag = doc.id;
            var idButtonAddTags = 'buttonTags_'+doc.id;
            if(zUserGroups.indexOf("4") != -1){
                var a_edit = new Element('a', {
                    'target': '_blank',
                    'href' : 'index.php?option=com_zonales&task=zonal&view=editor&tmpl=component_edit&id='+doc.id
                }).setStyle('display',post.source == 'Zonales' ? 'inline' : 'none').inject(div_story_item_header),
                a_edit_image = new Element('img',{
                    'src': '/media/system/images/edit.png'
                }).inject(a_edit).addClass('edit_img');
                var span_addTags = new Element('span',{
                    'id':'addTags_'+doc.id
                }).inject(div_story_tags);
                var addTagsButton = new Element('a').set('html','Add Tags').inject(span_addTags);
                addTagsButton.addEvent('click',function(){
                    if ( $(idInputTag).style.display == "none"){
                        $(idInputTag).setStyle("display","inline");
                        $(idButtonAddTags).setStyle("display","inline");
                        $(idInputTag).focus();
                    }else {
                        $(idInputTag).setStyle("display","none");
                        $(idButtonAddTags).setStyle("display","none");
                    }
                });

                var selectedTag = new Element('input',{
                    'id':idInputTag,
                    'style':'display:none',
                    'onkeyup':'populateOptions(event,this,true,zTags)',
                    'value':''
                }).inject(span_addTags);

                var confimAddTagButton = new Element('img', {
                    'id':idButtonAddTags,
                    'style':'display:none',
                    'src': '/CMUtils/addIcon.gif'
                }).set('html','Add').addClass('story-item-button').inject(div_story_tags);
                confimAddTagButton.addEvent('click',function(){
                    show_confirm(idInputTag,$(idInputTag).value,tags);
                    $(idInputTag).value = '';
                });

            }
            var zone = post.zone.extendedString;
            var idButtonSetZone = 'buttonZone'+doc.id;
            var div_story_zone = new Element('div').addClass('cp_tags').inject(div_story_item_content);
            new Element('span').set('html','Zona: ').inject(div_story_zone);
            var span_zone = new Element('span').inject(div_story_zone);
            new Element('a', {
                'id':'zonePost',
                'href': ''
            }).set('html',zone).inject(span_zone);
            var idInputZone = 'zone_'+doc.id;
            var span_addZone = new Element('span').inject(div_story_zone);

            /*if(zUserGroups.indexOf("4") != -1){
                new Element('a',{
                    'onclick':'if ( $("'+idInputZone+'").style.display == "none"){ $("'+idInputZone+'").setStyle("display","inline"); $("'+idButtonSetZone+'").setStyle("display","inline");}else{ $("'+idInputZone+'").setStyle("display","none"); $("'+idButtonSetZone+'").setStyle("display","none");}'
                }).set('html','Set Zone').inject(span_addZone);
                var selectedZone = new Element('input',{
                    'id':idInputZone,
                    'style':'display:none',
                    'onkeyup':'populateOptions(event,this,true,allZones)',
                    'value':''
                }).inject(span_addZone);
                new Element('img', {
                    'id':idButtonSetZone,
                    'style':'display:none',
                    'src': '/CMUtils/addIcon.gif',
                    'onclick':'show_confirm("' + idInputZone + '",$(\'' + idInputZone + '\').value,"'+zone+'")'
                }).set('html','Add').addClass('story-item-button').inject(div_story_zone);
            }*/

            if(!$('chk'+post.source)) {
                var tr = new Element('tr');
                new Element('input', {
                    'id': 'chk'+post.source,
                    'type': 'checkbox',
                    'checked': 'checked',
                    'value': post.source,
                    'onclick':'setSourceVisible(this.value, this.checked);'
                }).inject(new Element('td').inject(tr));
                new Element('td', {
                    'html': post.source
                }).inject(tr);
                if (tab == "enlared" || tab == "relevantes" )
                    tr.inject($('enLaRed'));
                else
                    tr.inject($('noticiasEnLaRed'));

                zcAddSource(post.source);
            }

            if (typeof (post.tags) != 'undefined') {
                post.tags.each(function(tag) {
                    if(!$('chkt'+tag)) {
                        var tr = new Element('tr');
                        new Element('input', {
                            'id': 'chkt'+tag,
                            'type': 'checkbox',
                            'checked': 'checked',
                            'value': tag,
                            'onclick':'setTagVisible(this.value, this.checked);'
                        }).inject(new Element('td').inject(tr));
                        new Element('td', {
                            'html': tag
                        }).inject(tr);
                        tr.inject($('tagsFilterTable'));

                        zcAddTag(tag);
                    }
                });
            }

            if (zirClient.getMinRelevance() != null) {
                if (parseInt(post.relevance) < zirClient.getMinRelevance()) {
                    zirClient.setMinRelevance(parseInt(post.relevance));
                }
            } else {
                zirClient.setMinRelevance(parseInt(post.relevance));
            }

            //div_story_item.setStyle('display',$('chk'+post.source) && $('chk'+post.source).checked ? 'block' : 'none');

            /*var insertado = false;
            var modifiedDate = new Date(modified).getMilliseconds();
            component.getElements('span.story-item-real-modified-date').each(function(count){
                postModifiedDate = new Date(count.innerHTML).getMilliseconds();
                if (modifiedDate > postModifiedDate && !insertado) {
                    insertado = true;
                    div_story_item.injectBefore(count.getParent().getParent().getParent().getParent().getParent());
                }
            });
            if(!insertado){
                div_story_item.injectInside($('postsContainer'));
            }*/

            if(typeof more == 'undefined' || !more) {
                div_story_item.injectTop(component);
            } else {
                div_story_item.injectInside(component);
            }

        });
        refreshFiltro();
    } else {
        reverseFlag = true;
    }
}

function show_confirm(idInputTag,selectedTag,tags)
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
function addTagToPost(idPost,tags,selectedTag){
    //\"tags\":[\"Espectaculos\"]

    var url = '/ZCrawlScheduler/indexPosts?url=http://localhost:38080/solr&doc={"id":"'+encodeURIComponent(idPost)+'"}&aTags='+tags+','+selectedTag;
    var urlProxy = '/curl_proxy.php';
    new Request({
        url: urlProxy,
        method: 'post',
        data: {
            'host': host ? host : "localhost",
            'port': port ? port : "38080",
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

function delTagFromPost(idPost,selectedTag){
    //\"tags\":[\"Espectaculos\"]

    var url = '/ZCrawlScheduler/indexPosts?url=http://localhost:38080/solr&doc={"id":"'+encodeURIComponent(idPost)+'"}&rTag='+selectedTag;
    var urlProxy = '/curl_proxy.php';
    new Request({
        url: urlProxy,
        method: 'post',
        data: {
            'host': host ? host : "localhost",
            'port': port ? port : "38080",
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

function spanishDate(d){
    var weekday=["Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sabado"];

    var monthname=["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];

    return fixTime(d.getHours()) + ":" + fixTime(d.getMinutes()) + ":" + fixTime(d.getSeconds()) + ", " + weekday[d.getDay()]+" "+d.getDate()+" de "+monthname[d.getMonth()]+" de "+d.getFullYear();
}

function fixTime(i) {
    return (i<10 ? "0" + i : i);
}

function setSourceVisible(source, visible) {

    if (visible) {
        zcAddSource(source);
    } else {
        zcUncheckSource(source);
    }
    refreshFiltro();
}

function ckeckOnlyTag(tag) {
    var zCtxChkTags = zcGetCheckedTags();

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

function setTagVisible(tag, checked) {

    //Actualizo el contexto
    if (checked) {
        zcAddTag(tag);
    } else {
        zcUncheckTag(tag);
    }
    refreshFiltro();


}

function refreshFiltro(){
    //var zCtxTemp = zcGetContext();
    //Refresco visibilidad de Posts
    var zCtxChkTags = zcGetCheckedTags();
    var zCtxChkSource = zcGetCheckedSources();

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
        });
    }

    //    sendFilter(source,visible);
    armarTitulo(tab);
}

function addMilli(date) {
    var milli = date.substring(date.lastIndexOf('.')+1, date.lastIndexOf('Z')-1);
    var finalDate = date.substr(0, date.lastIndexOf('.')+1) + (milli + 1) + 'Z';
    return finalDate;
}

function reduceMilli(date) {
    var milli = date.substring(date.lastIndexOf('.')+1, date.lastIndexOf('Z')-1);
    var finalDate = date.substr(0, date.lastIndexOf('.')+1) + (milli - 1) + 'Z';
    return finalDate;
}

//	Fri Sep 23 2011 09:51:35 GM /0300 (AR )

//	2010-08-28T20:24:17Z

function prettyDate(time){
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


function armarTitulo(tabTemp){
    var temp = 0 ;
    tabTemp = tab;
    var zoneSeltemp = zcGetContext().selZone;
    var zoneEfectemp = zcGetContext().selZone;

    $('tituloSup').innerHTML = "";

    if (tabTemp == 'relevantes'){
        $('enLaRed').getElements('input[id^=chk]').each(function(element, index) {
            if(element.checked) {
                $('titulo1').innerHTML = "Ud. esta viendo Noticias mas Relevantes de la Red Social: "
                if(index != 0)
                    $('tituloSup').innerHTML += ", ";
                $('tituloSup').innerHTML += element.value+" ";

            }
        });

    }

    if (tabTemp == 'noticiasenlared'){

        temp = 0;
        $('noticiasEnLaRed').getElements('input[id^=chk]').each(function(element, index) {

            //alert (element.checked);
            if(element.checked){
                temp++;
                if(temp < 5  ) {

                    //alert (index);
                    $('titulo1').innerHTML = "Ud. esta viendo Noticias de los diarios OnLine: "
                    if(index != 0)
                        $('tituloSup').innerHTML += ", ";
                    $('tituloSup').innerHTML += element.value;

                }

                else if (temp > 5 ){
                    $('tituloSup').innerHTML = "";
                    $('titulo1').innerHTML = "Ud. esta viendo noticias OnLine de mas de 5 diarios";

                }
            }
        });
    }
    if (tabTemp == 'noticiasenlaredrelevantes'){
        temp = 0;
        $('noticiasEnLaRed').getElements('input[id^=chk]').each(function(element, index) {
            if(element.checked){
                temp++;
                if(temp < 5 ) {
                    $('titulo1').innerHTML = "Ud. esta viendo Noticias mas Relevantes de los diarios OnLine: "
                    if(index != 0)
                        $('tituloSup').innerHTML += ", ";
                    $('tituloSup').innerHTML += element.value;

                }
                else if (temp > 5){
                    $('tituloSup').innerHTML = "";
                    $('titulo1').innerHTML = "Ud. esta viendo noticias OnLine de Mayor Relevancia de mas de 5 diarios";

                }
            }
        });
    }
    if (tabTemp == 'enlared'){

        $('enLaRed').getElements('input[id^=chk]').each(function(element, index) {
            if(element.checked) {
                $('titulo1').innerHTML = "Ud. esta viendo Noticias de la Red Social: "
                if(index != 0)
                    $('tituloSup').innerHTML += ", ";
                $('tituloSup').innerHTML += element.value+" ";

            }
        });


    }

    if (zoneEfectemp == zoneSeltemp){
        $('tituloZone').innerHTML = "Ud. esta viendo "+zoneEfectemp;
    }
    if (zoneEfectemp != zoneSeltemp && zoneSeltemp != ""){
        $('tituloZone').innerHTML = "No existen noticias para la zona seleccionada. Mostrando ";
        if(zoneEfectemp == "")
            $('tituloZone').innerHTML += "todas las noticias";
        else $('tituloZone').innerHTML += "noticias de "+zoneEfectemp;
    }
    if (zoneSeltemp == ""){
        $('tituloZone').innerHTML = "Mostrando todas las noticias";
    }

}

//zcSetTemp($('tempoSelect').value);
