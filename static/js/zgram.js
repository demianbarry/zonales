document.write('<script type="text/javascript" src="utils.js"></script>');
	
var firstIndexTime = null;
var lastIndexTime = null;
var zgram = null;
var metadata = null;
var cantExtract = 0;
var cantUsers = 0;
var users = new Array();


function switchByWorkflow(state){
    var estado;
    if(typeof state == 'undefined' ||	state == null)
        estado = zgram.estado;
    else
        estado = state;
    if(zgram == null) {
        switchButtons(['compilarButton']);
        return;
    }
    switch(estado){
        case 'compiled':
            switchButtons(['publicarButton','extraerButton']);
            break;
        case 'no-compiled':		
            switchButtons(['compilarButton']);
            break;
        case 'published':		
            switchButtons(['despublicarButton','extraerButton']);
            break;
        case 'unpublished':
            switchButtons(['compilarButton','anularButton']);
            break;
        case 'void':
            break;		
    }
}


/*
window.addEvent('domready', function(){
    if(gup('id') != null && gup('id') != '') {
        getZGram(gup('id'));
    } else {
        switchByWorkflow();
    }

});
*/

function refreshZGram(){	
    if($('table_content')){
        $('table_content').getElements('input').each(function(element){
            if(element.get('type') == 'checkbox') {
                element.set('checked', false);
            } else {
                element.set('value','');
            }
        });
        if(zgram == null) {
            $('table_content').setStyle('display','none');
            return;
        } else {
            $('table_content').setStyle('display','block');
        }		
		
        $('descripcion').set('value',zgram.descripcion);
        $('localidad').set('value',zgram.localidad);
        $('fuente').set('value',zgram.fuente);
        if (zgram.fuente =='facebook') 
            $('fbUtilsIcon').setStyle('display','block'); 
        else 
            $('fbUtilsIcon').setStyle('display','none');
			
        if(zgram.fuente == 'feed') {
            $('uriFuente').setStyle('display','');
            $('geoFuente').setStyle('display','');
        }
        else {
            $('uriFuente').setStyle('display','none');
            $('geoFuente').setStyle('display','none');
        }
		
        $('uri').set('value',zgram.uriFuente);
        if($('uri').value.trim() != '') {
            $('geoFuente').setStyle('display','');
        } else {
            $('geoFuente').setStyle('display','none');
        }
	
        $('feedPlace').set('value', zgram.place);        
	
        $('estado').set('value',zgram.estado);
        $('tags').set('value',zgram.tags.join(','));
        if (users.length > 0) {
            $('userstd').removeChild($('users_table'));
        }
        users.empty();
        cantUsers = 0;
        var usuariosCant = -1;
        if(typeOf(zgram.criterios) == 'array') {
            zgram.criterios.each(function(criterio){
            
                if (typeOf(criterio.deLosUsuarios) == 'array') {
                    criterio.deLosUsuarios.each(function(usuario) {
                        usuariosCant++;
                        user = new Array();
                        users[usuariosCant] = user;
                        users[usuariosCant][0] = usuario;
                    });
                }
            
                /*if (typeOf(criterio.deLosUsuariosLatitudes) == 'array') {
                var usuariosLat = -1;
                criterio.deLosUsuariosLatitudes.each(function(usuarioLat) {
                    usuariosLat++;
                    users[usuariosLat][1] = usuarioLat;
                });
            }

            if (typeOf(criterio.deLosUsuariosLongitudes) == 'array') {
                var usuariosLon = -1;
                criterio.deLosUsuariosLongitudes.each(function(usuarioLon) {
                    usuariosLon++;
                    users[usuariosLon][2] = usuarioLon;
                });
            }*/
                if (typeOf(criterio.deLosUsuariosPlaces) == 'array') {
                    var usuariosPlace = -1;
                    criterio.deLosUsuariosPlaces.each(function(usuarioPlace) {
                        usuariosPlace++;
                        users[usuariosPlace][1] = usuarioPlace;
                    });
                }

                /*if(typeOf(criterio.deLosUsuarios) == 'array') {
                $('iusuario').set('value', ($('iusuario').get('value') != '' ? ($('iusuario').get('value') + ',') : '') + criterio.deLosUsuarios.join(','));
            }*/

                if(typeOf(criterio.palabras) == 'array') {
                    $('ipalabras').set('value', ($('ipalabras').get('value') != '' ? ($('ipalabras').get('value') + ',') : '') + criterio.palabras.join(','));
                }
            });
        }
        for (i = 0; i <= usuariosCant; i++) {
            addUser(users[i][0], users[i][1]/*, users[i][2]*/);
        }
        if(typeOf(zgram.noCriterios) == 'array') {
            zgram.noCriterios.each(function(nocriterio){
                if(typeOf(nocriterio.deLosUsuarios) == 'array') {
                    $('eusuario').set('value', ($('eusuario').get('value') != '' ? ($('eusuario').get('value') + ',') : '') + nocriterio.deLosUsuarios.join(','));
                }
                if(typeOf(nocriterio.palabras) == 'array') {
                    $('epalabras').set('value', ($('epalabras').get('value') != '' ? ($('epalabras').get('value') + ',') : '') + nocriterio.palabras.join(','));
                }
            });
        }

        $('commenters').set('value', typeOf(zgram.comentarios) == 'array' ? zgram.comentarios.join(',') : '');
	
        $('allCommenters').set('checked',zgram.incluyeComentarios);

        if(typeOf(zgram.filtros) == 'array') {
            zgram.filtros.each(function(filtro){
                if(filtro.minActions != null) {
                    $('minActions').set('value', filtro.minActions);
                }
                if(filtro.listaNegraDeUsuarios != null) {
                    $('lnegraus').set('checked', filtro.listaNegraDeUsuarios);
                }			
                if(filtro.listaNegraDePalabras != null) {
                    $('lnegrapa').set('checked',filtro.listaNegraDePalabras);
                }
			
            });
        }
        if(zgram.verbatim) {
            $('textExtraction').set('value', zgram.verbatim);
        }    

        $('sourceTags').set('checked',zgram.tagsFuente);
        $('tempExtraccion').set('value', zgram.periodicidad);
    }
}

function testExtraction(metadatas){
    if(typeOf(metadatas) == 'array') {
        metadatas.each(function(meta){
            getExtractURL(JSON.encode(meta));
        });
    } else 
    if(metadata != null){
        getExtractURL(metadata);
    } 
}

function publishZgram(publish){
    if (zgram != null) {
        var url = '/ZCrawlSources/'+(publish ? 'publishService' : 'unpublishService')+'?id='+zgram._id.$oid;
        var urlProxy = 'curl_proxy.php';
        new Request({
            url: encodeURIComponent(urlProxy),
            method: 'post',
            data: {
                'host': host,
                'port': port,
                'ws_path':url
            },
            onRequest: function(){
                $('postsContainer').empty();
                $('resultado').empty();
                $('resultado').addClass('loading');
            },
            onSuccess: function(response) {
                // actualizar pagina
                $('resultado').removeClass('loading').removeClass('fallo').addClass('resultado');
                response = JSON.decode(response);

                if(typeof response.cod != 'undefined') {
                    response.cod = parseInt(response.cod);
                    if(response.cod != 100)
                        alert(response.msg);
                }
                getZGram(zgram._id.$oid);
            },

            // Our request will most likely succeed, but just in case, we'll add an
            // onFailure method which will let the user know what happened.
            onFailure: function(){
                $('resultado').removeClass('loading').removeClass('oculto').removeClass('resultado').addClass('fallo');
                $('resultado').innerHTML = this.response.text.substring(this.response.text.indexOf('<h1>')+22, this.response.text.indexOf('</h1>'));
            }

        }).send();
    }
}

function getExtractURL(metadata){
    var url = '/ZCrawlSources/getServiceURL?q='+metadata;
    var urlProxy = 'curl_proxy.php';
    new Request({
        url: encodeURIComponent(urlProxy),
        method: 'post',
        data: {
            'host':host,
            'port': port,
            'ws_path':url
        },
        onRequest: function(){
            $('postsContainer').empty();
            $('resultado').empty();
            $('resultado').addClass('loading');
            cantExtract++;
        },
        onComplete: function(){
            cantExtract--;
        },
        onSuccess: function(jsonObj) {
            // actualizar pagina
	
            if (cantExtract == 0)	
                $('resultado').removeClass('loading').removeClass('fallo').addClass('resultado');            
            jsonObj = eval('('+jsonObj+')');
            jsonObj.cod = parseInt(jsonObj.cod);
            switch(jsonObj.cod) {
                case 100:
                    extract(jsonObj.url);
                    break;                 
                default:
                    $('resultado').removeClass('loading').removeClass('oculto').removeClass('resultado').addClass('fallo');
                    $('resultado').innerHTML = jsonObj.msg;
                    break;
            }
        },

        // Our request will most likely succeed, but just in case, we'll add an
        // onFailure method which will let the user know what happened.
        onFailure: function(){
            $('resultado').removeClass('loading').removeClass('oculto').removeClass('resultado').addClass('fallo');
            $('resultado').innerHTML = this.response.text.substring(this.response.text.indexOf('<h1>')+22, this.response.text.indexOf('</h1>'));
        }

    }).send();
}

function extract(url){
    var urlProxy = 'curl_proxy.php';
    new Request({
        url: urlProxy,
        method: 'post',
        data: {
            'host': host,
            'port': GetPortNumber(url),
            'ws_path':GetLocalPath(url)
        },
        onRequest: function(){
            $('postsContainer').empty();
            $('resultado').empty();
            $('resultado').addClass('loading');
            cantExtract++;	
        },
        onComplete: function(){
            cantExtract--;
        },
        onSuccess: function(jsonObj) {
            // actualizar pagina
            if (cantExtract == 0)	
                $('resultado').removeClass('loading').removeClass('fallo').addClass('resultado');
            jsonObj = eval('('+jsonObj+')');
            if(typeof jsonObj.post != 'undefined' && typeOf(jsonObj.post) == 'array') {
                jsonObj.post.each(function(post){
                    updatePost(post);
                });
            }
        },

        // Our request will most likely succeed, but just in case, we'll add an
        // onFailure method which will let the user know what happened.
        onFailure: function(){
            $('resultado').removeClass('loading').removeClass('oculto').removeClass('resultado').addClass('fallo');
            $('resultado').innerHTML = this.response.text.substring(this.response.text.indexOf('<h1>')+22, this.response.text.indexOf('</h1>'));
        }

    }).send();
}

function compileZGram(value, id){
    var url = '/ZCrawlParserServlet/servlet/ZCrawlParser?q='+(value ? value : $('textExtraction').value);
    var urlProxy = 'curl_proxy.php';
    new Request({
        url: encodeURIComponent(urlProxy),
        method: 'post',
        data: {
            'host':host,
            'port': port,
            'ws_path':url
        },
        onRequest: function(){
            if($('postsContainer'))
                $('postsContainer').empty();
            if($('resultado')){
                $('resultado').empty();
                $('resultado').addClass('loading');
            }
        },
        onSuccess: function(jsonObj) {
            // actualizar pagina
            if($('resultado'))
                $('resultado').removeClass('loading').removeClass('fallo').addClass('resultado');
            jsonObj = eval("("+jsonObj+")");
            if(typeof jsonObj.cod != 'undefined') {                
                var metadata = jsonObj.meta ? eval("("+jsonObj.meta+")") : null;
                if($('tempExtraccion') && isNaN(parseInt($('tempExtraccion').value)))
                    alert('La Periodicidad ingresada no es valida');
                else
                {
                    if(metadata && $('tempExtraccion'))
                        metadata.periodicidad = parseInt($('tempExtraccion').value);
                    
                    jsonObj.meta = JSON.stringify(metadata);
                    var state = 'no-compiled';
                    jsonObj.cod = parseInt(jsonObj.cod);
                    switch(jsonObj.cod) {
                        case 100:
                            state = 'compiled';
                            switchByWorkflow(state);
                            break;
                        case 220:
                            state = 'no-compiled';                        
                        default:
                            switchByWorkflow(state);
                            if($('resultado'))
                                $('resultado').innerHTML = jsonObj.msg;
                            break;
                    }

                    if(jsonObj.cod == 100 || jsonObj.cod == 220) {
                        metadata = jsonObj.meta;
                        if(value || zgram != null) {
                            updateZGram(zgram ? zgram._id.$oid : id, jsonObj.cod, jsonObj.msg, jsonObj.meta, value ? value : $('textExtraction').value, state, value ? false : true);
                        } else {
                            saveZGram(jsonObj.cod, jsonObj.msg, jsonObj.meta, value ? value : $('textExtraction').value, state);
                        }
                    }
                }
            }
        },

        // Our request will most likely succeed, but just in case, we'll add an
        // onFailure method which will let the user know what happened.
        onFailure: function(){
            $('resultado').removeClass('loading').removeClass('oculto').removeClass('resultado').addClass('fallo');
            $('resultado').innerHTML = this.response.text.substring(this.response.text.indexOf('<h1>')+22, this.response.text.indexOf('</h1>'));
        }

    }).send();
}

function saveZGram(cod, msg, meta, verbatim, state){
    var url = '/ZCrawlSources/setZGram?cod='+cod+'&msg='+msg+ '&metadata='+ meta +'&verbatim='+verbatim + '&state=' + state;
    var urlProxy = 'curl_proxy.php';
    new Request.JSON({
        url: urlProxy,
        method: 'post',
        data: {
            'host':host,
            'port': port,
            'ws_path':url
        },
        onRequest: function(){
            $('postsContainer').empty();
            $('resultado').empty();
            $('resultado').addClass('loading');
        },
        onSuccess: function(jsonObj) {
            // actualizar pagina
            $('resultado').removeClass('loading').removeClass('fallo').addClass('resultado');
            if(typeof jsonObj.cod != 'undefined') {
                jsonObj.cod = parseInt(jsonObj.cod);
                switch(jsonObj.cod) {
                    case 100:
                        getZGram(jsonObj.id);                    
                        break;
                    default:
                        switchByWorkflow();
                        $('resultado').innerHTML = jsonObj.msg;
                        break;
                }
            }
        },

        // Our request will most likely succeed, but just in case, we'll add an
        // onFailure method which will let the user know what happened.
        onFailure: function(){
            $('resultado').removeClass('loading').removeClass('oculto').removeClass('resultado').addClass('fallo');
            $('resultado').innerHTML = this.response.text.substring(this.response.text.indexOf('<h1>')+22, this.response.text.indexOf('</h1>'));
        }

    }).send();
}

function updateZGram(id, cod, msg, meta, verbatim, state, retrieve){
    var url = '/ZCrawlSources/updateZGram?id='+id+'&newcod='+cod+'&newmsg='+msg+ '&newmetadata='+ meta +'&newverbatim='+verbatim + '&newstate=' + state;
    var urlProxy = 'curl_proxy.php';
    new Request.JSON({
        url: urlProxy,
        method: 'post',
        data: {
            'host':host,
            'port': port,
            'ws_path':url
        },
        onRequest: function(){
            if($('postsContainer'))
                $('postsContainer').empty();
            if($('resultado')){
                $('resultado').empty();
                $('resultado').addClass('loading');
            }
        },
        onSuccess: function(jsonObj) {
            // actualizar pagina
            if($('resultado'))
                $('resultado').removeClass('loading').removeClass('fallo').addClass('resultado');
            if(typeof jsonObj.cod != 'undefined') {
                jsonObj.cod = parseInt(jsonObj.cod);
                switch(jsonObj.cod) {
                    case 100:
                        if(retrieve)
                            getZGram(jsonObj.id);
                        break;
                    default:
                        switchByWorkflow();
                        if($('resultado'))
                            $('resultado').innerHTML = jsonObj.msg;
                        break;
                }
            }
        },

        // Our request will most likely succeed, but just in case, we'll add an
        // onFailure method which will let the user know what happened.
        onFailure: function(){
            if($('resultado')){
                $('resultado').removeClass('loading').removeClass('oculto').removeClass('resultado').addClass('fallo');
                $('resultado').innerHTML = this.response.text.substring(this.response.text.indexOf('<h1>')+22, this.response.text.indexOf('</h1>'));
            }
        }

    }).send();
}

function getZGram(id, callback){
    var url = '';
    if(id)
        url = '/ZCrawlSources/getZGram?id=' + id;
    else 
        url = '/ZCrawlSources/getZGram?filtros={}';
    var urlProxy = 'curl_proxy.php';
    new Request({
        url: urlProxy,
        method: 'post',
        data: {
            'host':host,
            'port': port,
            'ws_path':url
        },
        onRequest: function(){
            if($('postsContainer'))
                $('postsContainer').empty();
            if($('resultado')){
                $('resultado').empty();
                $('resultado').addClass('loading');
            }
        },
        onSuccess: function(jsonObj) {
            if(typeof callback == 'function'){
                callback(jsonObj);
            } else {
                // actualizar pagina
                if($('resultado'))
                    $('resultado').removeClass('loading').removeClass('fallo').addClass('resultado');
                jsonObj = eval('('+jsonObj+')');
                if(typeof jsonObj.cod != 'undefined') {
                    jsonObj.cod = parseInt(jsonObj.cod);
                
                    switch(jsonObj.cod) {
                        case 100:
                        case 220:
                            if($('resultado'))
                                $('resultado').innerHTML = jsonObj.msg;

                            zgram = jsonObj;
                            if(metadata == null) {
                                metadata = Object.clone(zgram);
                                delete metadata._id;
                                delete metadata.cod;
                                delete metadata.msg;
                                delete metadata.verbatim;
                                delete metadata.estado;
                                metadata = JSON.encode(metadata);
                            }
                            refreshZGram();                    
                            break;
                        default:
                            if($('resultado'))
                                $('resultado').innerHTML = jsonObj.msg;
                            break;
                    }
                }
                switchByWorkflow();
                toggleInputs();
            }
        },

        // Our request will most likely succeed, but just in case, we'll add an
        // onFailure method which will let the user know what happened.
        onFailure: function(){
            if($('resultado')){            
                $('resultado').removeClass('loading').removeClass('oculto').removeClass('resultado').addClass('fallo');
                $('resultado').innerHTML = this.response.text.substring(this.response.text.indexOf('<h1>')+22, this.response.text.indexOf('</h1>'));
            }
        }

    }).send();
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


function updatePost(post) {
    var div_story_item = new Element('div.story-item').addClass('group').addClass(post.source),	
    div_story_item_gutters = new Element('div.story-item-gutters').inject(div_story_item).addClass('group'),
    div_story_item_zonalesbtn = new Element('div.story-item-zonalesbtn').inject(div_story_item_gutters),
    div_zonalesbtn_hast = new Element('div.zonales-btn has-tooltip').inject(div_story_item_zonalesbtn),
    div_zonales_count_wrapper = new Element('div.zonales-count-wrapper').inject(div_zonalesbtn_hast),
    span_relevance = new Element('span.zonales-count').inject(div_zonales_count_wrapper),
    span_relevance_int = new Element('span', {
        'html': post.relevance
    }).inject(span_relevance),
    div_story_item_content = new Element('div.story-item-content group').inject(div_story_item_zonalesbtn, 'after'),
    div_story_item_details = new Element('div.story-item-details').inject(div_story_item_content),
    h3_story_item_title = new Element('h3.story-item-title').inject(div_story_item_details),
    a_title = new Element('a', {
        'html': post.title,
        'target': '_blank',
        'href' : getTarget(post)
    }).inject(h3_story_item_title),
    span_external_link_icon = new Element('span.external-link-icon').inject(a_title, 'after'),
    p_story_item_description = new Element('p.story-item-description').inject(h3_story_item_title, 'after'),
    a_story_item_source = new Element('a.story-item-source', {
        'html': post.source,
        'target': '_blank'
    }).inject(p_story_item_description),
    a_story_item_icon = new Element('a.story-item-icon').inject(a_story_item_source, 'before'),
    a_story_item_icon_image = new Element('img',{
        'src': 'logo_'+post.source.replace('/','').toLowerCase()+'.png'
    }).inject(a_story_item_icon).addClass('source_logo'),
    a_story_item_teaser = new Element('span.story-item-teaser', {
        'html': post.text ? ' - ' + post.text.trim() : ''
    }).inject(a_story_item_source, 'after'),
    ul_story_item_meta = new Element('ul.story-item-meta group').inject(div_story_item_content),
    li_story_submitter = new Element('li.story-item-submitter', {
        'html': 'from '		
    }).inject(ul_story_item_meta).setStyle('display', post.fromUser.name ? 'block' : 'none'),
    a_story_submitter = new Element('a', {
        'html': post.fromUser.name,
        'target': '_blank',
        'href': post.fromUser.url
    }).inject(li_story_submitter),
    div_inline_comment_container = new Element('div.inline-comment-container').inject(div_story_item_content),
    div_story_item_activity = new Element('div.story-item-activity group hidden').inject(div_story_item_content),
    div_story_item_media   = new Element('div.story-item-media').inject(div_story_item_content, 'after');

    if(typeOf(post.actions) == 'array') {
        post.actions.each(function(action){
            switch (action.type) {
                case 'comment':
                    var li_story_item_comments = new Element('li.story-item-comments', {
                        'html': action.cant + ' comments'
                    }).inject(ul_story_item_meta);
                    break;
                case 'like':
                    var li_story_item_likes = new Element('li.story-item-likes', {
                        'html': action.cant + ' likes'
                    }).inject(ul_story_item_meta);
                    break;
                case 'retweets':
                    var li_story_item_retweets = new Element('li.story-item-retweets', {
                        'html': action.cant + ' retweets'
                    }).inject(ul_story_item_meta);
                    break;
                case 'replies':
                    var li_story_item_replies = new Element('li.story-item-replies', {
                        'html': action.cant + ' replies'
                    }).inject(ul_story_item_meta);
                    break;
            }
        });
    }

    var postLinks;
	
    switch(post.source.toLowerCase()) {
        case 'facebook':
            postLinks =	post.links;			
            break;
        default:
            postLinks =	post.links.link;
    }
	
    if(typeOf(postLinks) == 'array') {		
        postLinks.each(function(link){
            switch (link.type) {
                case 'picture':
                    if(div_story_item_media.childNodes.length == 0) {
                        var a_thumb = new Element('a.thumb', {
                            'href': getTarget(post),
                            'target': '_blank'
                        }).inject(div_story_item_media).addClass('thumb-s'),
                        img = new Element('img', {
                            'src': link.url
                        }).inject(a_thumb);
                    }
                    break;
                case 'video':
                    var li_story_item_video = new Element('li.story-item-video').inject(ul_story_item_meta),
                    a_story_item_video = new Element('a.story-item-video', {
                        'html': 'Video',
                        'href': link.url,
                        'target': '_blank'
                    }).inject(li_story_item_video)
                    break;
                case 'link':
                    var li_story_item_link = new Element('li.story-item-link').inject(ul_story_item_meta),
                    a_story_item_link = new Element('a.story-item-link', {
                        'html': 'Máinfo...',
                        'href': link.url,
                        'target': '_blank'						
                    }).inject(li_story_item_link);
                    break;
            }
        });
    }

    var date = new Date(parseInt(post.created));    
    li_story_item_created_date = new Element('li.story-item-created-date', {
        'html': 'Creado: ' + spanishDate(date)
    }).inject(ul_story_item_meta);

    date = new Date(parseInt(post.modified));    
    li_story_item_modified_date = new Element('li.story-item-modified-date', {
        'html': 'Modificado: ' + spanishDate(date)
    }).inject(ul_story_item_meta);

    /*
                                        if(!$('chk'+post.source)) {
                                                var tr = new Element('tr');
                                                new Element('input', {'id': 'chk'+post.source, 'type': 'checkbox', 'checked': true, 'value': post.source, 'onclick':'filtrar(this.value, this.checked);'}).inject(new Element('td').inject(tr));
                                                new Element('td', {'html':post.source}).inject(tr);
                                                tr.inject($("chkFilter"));
                                        }*/

    //div_story_item.setStyle('display',$('chk'+post.source).checked ? 'block' : 'none');
    if(typeof more == 'undefined' || !more) {
        if($('postsContainer').hasChildNodes()){
            var element = $('postsContainer').firstChild;
            while(element != null && parseInt(element.get('id')) > parseInt(post.created)){
                element = element.getNext();
            }			
            div_story_item.inject(new Element('div', {
                'id': post.created
            }).inject(element != null ? element : $('postsContainer').lastChild, element != null && parseInt(element.get('id')) > parseInt(post.created) ? 'before' : 'after'));
        }
        else
            div_story_item.inject(new Element('div', {
                'id': post.created
            }).inject($('postsContainer')));
    }
    else {
        if($('postsContainer').hasChildNodes()) {
            var element = $('postsContainer').lastChild;
            while(element != null && parseInt(element.get('id')) > parseInt(post.created)){
                element = element.getNext();
            }
            div_story_item.inject(new Element('div', {
                'id': post.created
            }).inject(element != null ? element : $('postsContainer').firstChild, 'after'));
        }
        else
            div_story_item.inject(new Element('div', {
                'id': post.created
            }).inject($('postsContainer')));
    }
//solrIds.include(doc.id);
//}
}

function generateQuery() {
    var oldValue = $('textExtraction').value;
	
    $('textExtraction').empty();

    if ($('localidad').get('value').trim() == "") {
        new Element('p',{
            'html': "No city specified"
        }).inject($('resultado'));
        return;
    }

    if ($('fuente').get('value').trim() == "") {
        new Element('p',{
            'html': "No source specified"
        }).inject($('resultado'));
        return;
    }

    if ($('tags').get('value').trim() == "") {
        new Element('p',{
            'html': "No tags specified"
        }).inject($('resultado'));
        return;
    }

    var query = "";

    if($('descripcion').get('value').trim() != "")
        query = '**"'+$('descripcion').get('value').trim()+'"**';

    query += (query.length > 0 ? ' ' : '') + 'extraer para la localidad "' + $('localidad').get('value').trim() + '"';

    query += ' mediante la fuente ' + $('fuente').get('value').trim();

    if ($('uri').get('value').trim() != "") {
        query += ' ubicado en "' + $('uri').get('value').trim() + '"';
    }
	
    if($('fuente').get('value').trim() == "feed" && ($('feedPlace').get('value').trim() != "")){
        query += ' ["'+$('feedPlace').get('value').trim()+'"]';
    }

    if ($('tags').get('value').trim() != "") {
        query += ' asignando los tags ';
        var tags = $('tags').get('value').trim().split(",");
        tags.each(function(tag) {
            query += '"' + tag + '",';
        });
        query = query.substring(0, query.length-1);
    }

    if(users.length > 0
        || $('ipalabras').get('value').trim() != "")
        query += ' a partir';

    if(users.length > 0) {
        var first = true;
        query += ' de los usuarios';
        for (i = 0; i < users.length; i++) {
            if (users[i] != null) {
                if (first) {
                    first = false;
                } else {
                    query += ",";
                }
                query += ' "' + users[i][0] + '"';
                if (typeof users[i][1] != 'undefined' && users[i][1] && users[i][1] != "") {
                    query += ' ["' + users[i][1]+ '"]';
                }
            }
        }
    }

    if ($('ipalabras').get('value').trim() != "") {
        if (users.length > 0) {
            query += ' y';
        }
        query += ' de las palabras ';
        var ipalabras = $('ipalabras').get('value').trim().split(",");
        ipalabras.each(function(includeKeyword) {
            if(ipalabras.indexOf(includeKeyword) != 0) {
                query += ",";
            }
            query += '"'+includeKeyword+'"';
        });
    }

    if ($('eusuario').get('value').trim() != "" || $('epalabras').get('value').trim() != "") {
        query += ' pero no';
    }

    if ($('eusuario').get('value').trim() != "") {
        query += ' de los usuarios ';
        var skipUsers = $('eusuario').get('value').trim().split(",");
        skipUsers.each(function(skipUser) {
            if(skipUsers.indexOf(skipUser) != 0) {
                query += ",";
            }
            query += '"'+skipUser+'"';
        });
    }

    if ($('epalabras').get('value').trim() != "") {
        if ($('eusuario').get('value').trim() != "") {
            query += ' y'
        }
        query += ' de las palabras ';
        var skipKeywords = $('epalabras').get('value').trim().split(",");
        skipKeywords.each(function(skipKeyword) {
            if(skipKeywords.indexOf(skipKeyword) != 0) {
                query += ",";
            }
            query += '"'+skipKeyword+'"';
        });
    }

    //commenters allCommenters

    if ($('allCommenters').checked == true || $('commenters').get('value').trim() != "") {
        query += ' incluye comentarios';
        if ($('allCommenters').checked == false && $('commenters').get('value').trim() != "") {
            query += ' de los usuarios:'
            var commenters = $('commenters').get('value').trim().split(" ");
            commenters.each(function(commenter) {
                if(commenters.indexOf(commenter) != 0) {
                    query += ",";
                }
                query += '"' + commenter + '"';
            });
        }
    }
	
    var filtros = '';
	
    if ($('lnegraus').checked == true || $('lnegrapa').checked == true || $('minActions').get('value').trim() != "") {
        query += ' y filtrando por';
    }
    if ($('lnegraus').checked == true) {
        filtros += ' lista negra de usuarios';
    }
    if ($('lnegrapa').checked == true) {
        filtros += (filtros.length > 0 ? ' y' : '') + ' lista negra de palabras';
    }
    if ($('minActions').get('value').trim() != "") {
        filtros += (filtros.length > 0 ? ' y' : '') + ' con al menos ' + $('minActions').get('value').trim() + ' actions';
    }

    query += filtros;
	
    if ($('sourceTags').checked == true) {
        query += (filtros.length > 0 ? ' y' : '') + ' incluye los tags de la fuente';
    }

    query += '.';

    $('textExtraction').set('value', query);
    if(oldValue != query){
        switchButtons(['compilarButton']);
    }
}

function addUser(name, place){
    /*if ((lat != "" && lon == "") || (lat == "" && lon != "")) {
        alert("Debe ingresar ambas coordenadas o ninguna");
    } else {*/
    if (cantUsers == 0) {
        var users_table = new Element('table', {
            'id' : 'users_table'
        }).addClass('configTable').inject($('userstd'));
        var users_title_tr = new Element('tr', {
            'style': 'background-color: lightGreen'
        }).inject(users_table);
        new Element('td', {
            'html' : 'Name'
        }).inject(users_title_tr);
        /*new Element('td', {
                'html' : 'Latitud'
            }).inject(users_title_tr);
            new Element('td', {
                'html' : 'Longitud'
            }).inject(users_title_tr);*/
        new Element('td', {
            'html' : 'Place'
        }).inject(users_title_tr);
        new Element('td', {						
            }).setStyle('min-width','40px').inject(users_title_tr);
    }
    var user_line = new Element('tr', {
        'id' : 'ul' + cantUsers
    }).inject($('users_table'));
    new Element('td', {
        'html': name
    }).inject(user_line);
    /*new Element('input', {
        'id': 'lat'+cantUsers,
        'type':'input',
        'disabled':true,
        'value': lat
    }).inject(new Element('td').inject(user_line));
    new Element('input', {
        'id': 'lon'+cantUsers,
        'type':'input',
        'disabled':true,
        'value': lon
    }).inject(new Element('td').inject(user_line));*/
    new Element('input', {
        'id': 'place'+cantUsers,
        'type':'input',
        'disabled':true,
        'value': place
    }).inject(new Element('td').inject(user_line));
    var removeUser_td = new Element('td').inject(user_line);
    new Element('img', {
        'width' : '16', 
        'height' : '16', 
        'border': '0', 
        'alt': cantUsers, 
        'title' : 'Eliminar usuario', 
        'src': 'publish_x.png', 
        'onclick' : 'removeUser('+ cantUsers + ')'
    }).setStyle('padding-right','5px').inject(removeUser_td);
    new Element('img', {
        'width' : '16', 
        'height' : '16', 
        'border': '0', 
        'alt': cantUsers, 
        'title' : 'Geolocalizar usuario', 
        'src': 'geolocation.png', 
        'onclick' : '$("iusuario").set("value",this.getParent().getPrevious().getPrevious().get("html"));$("userPlace").set("value",$("place'+cantUsers+'").get("value"));removeUser('+ cantUsers + ');'
    }).inject(removeUser_td);
    var user = new Array();
    user[0] = name;
    /*user[1] = lat;
        user[2] = lon;*/
    user[1] = place;
    users[cantUsers] = user;
    cantUsers++;
    $('iusuario').set('value', '');
    $('userPlace').set('value', '');
//$('latusuario').set('value', '');
//$('lonusuario').set('value', '');
//}
}

function removeUser(userLine){
    $('users_table').removeChild($('ul'+userLine));
    delete users[userLine];
    switchButtons(['compilarButton']);
}

function toggleInputs(){
    if ($('fuente').value =='facebook') 
        $('fbUtilsIcon').setStyle('display','block'); 
    else 
        $('fbUtilsIcon').setStyle('display','none');
                
    if($('fuente').value == 'feed') {
        $$('.usuarios').each(function(el){
            el.setStyle('display', 'none');
        });
        $('uriFuente').setStyle('display','');
        $('geoFuente').setStyle('display','');
    } else {
        $$('.usuarios').each(function(el){
            el.setStyle('display', '');
        });
        $('uriFuente').setStyle('display','none');
        $('geoFuente').setStyle('display','none');
    }
}

document.write('<script type="text/javascript" src="utils.js"></script>');

/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

var proxy = 'curl_proxy.php?host='+host+'&port='+port+'&ws_path=';
var servletUri = 'ZCrawlSources';

function addCriterios() {
    $('resultslist_content').set('style', 'display:block');
}

function getAllConfigs(){
    var url = servletUri + "/getZGram?id=all";
    var urlProxy = proxy + encodeURIComponent(url);
    var configs_table = new Element('table', {
        'id' : 'resultTable'
    }).addClass('resultTable').inject($('resultslist_content'));
    var reqId = new Request.JSON({
        url: urlProxy,
        method: 'get',
        onSuccess: function(jsonObj, text) {
            makeTable(jsonObj);

			
        },
        // Our request will most likely succeed, but just in case, we'll add an
        // onFailure method which will let the user know what happened.
        onError: function(text, error) {
            alert(error + ' - ' + text);
        }

    }).send();
}

function searchConfigs(){

    var query = '';
    query += ($('getZones').value.length >0 ?"localidad:'"+$('getZones').value.replace(/ /g, "%20") +"'" : "");
    query += ($('getFuente').options[$('getFuente').selectedIndex].value.toLowerCase() != 'all' ? (query.length >0 ? "," : "")+"fuente:'"+$('getFuente').options[$('getFuente').selectedIndex].value.toLowerCase() + "'" : "");
    query += ($('getEstado').options[$('getEstado').selectedIndex].value.toLowerCase() != 'all' ? (query.length >0 ? "," : "")+"estado:'"+$('getEstado').options[$('getEstado').selectedIndex].value.toLowerCase() + "'" : "");
	
    query += ($('getTags').value.length > 0 ? (query.length > 0 ? "," : "")+"tags:"+ JSON.encode($('getTags').value.split(',')) : "");	
	

    var url = servletUri + "/getZGram?filtros={"+query+"}";
    var urlProxy = proxy + encodeURIComponent(url);
    
    var reqId = new Request.JSON({
        url: urlProxy,
        method: 'get',
        onSuccess: function(jsonObj, text) {
            makeTable(jsonObj);
			
        },
        // Our request will most likely succeed, but just in case, we'll add an
        // onFailure method which will let the user know what happened.
        onError: function(text, error) {
            alert(error + ' - ' + text);
        }

    }).send();
}
function makeTable(jsonObj){

    $('resultslist_content').empty();

    var configs_table = new Element('table', {
        'id' : 'resultTable'
    }).addClass('resultTable').inject($('resultslist_content'));	
    var config_title_tr = new Element('tr', {
        'class': 'tableRowHeader'
    }).inject(configs_table);
    new Element('td', {
        'html' : 'Zona'
    }).inject(config_title_tr);
    new Element('td', {
        'html' : 'Fuente'
    }).inject(config_title_tr);
    new Element('td', {
        'html' : 'Tag'
    }).inject(config_title_tr);
    new Element('td', {
        'html' : 'Desc'
    }).inject(config_title_tr);
    new Element('td', {
        'html' : 'Estado'
    }).inject(config_title_tr);
    new Element('td', {
        'html' : 'Modificado'
    }).inject(config_title_tr);
    var checktd = new Element('td').inject(config_title_tr);	
    var checkbox = new Element ('input',{
        'type':'checkbox'		
    }).inject(checktd).addEvent('click', function(){		
        $$("table#resultTable.resultTable tr.tableRow td input").each(function(check){
            check.checked = checkbox.checked;
        });
    });
    new Element('td', {
        'html' : 'Editar'
    }).inject(config_title_tr);	

    jsonObj.each(function(config){
        var tags = config.tags.join(',');
        var config_title_tr = new Element('tr', {
            'class': 'tableRow'
        }).inject(configs_table);
        new Element('td', {
            'html' : config.localidad
        }).inject(config_title_tr);
        new Element('td', {
            'html' : config.fuente
        }).inject(config_title_tr);
        new Element('td', {
            'html' : tags
        }).inject(config_title_tr);
        new Element('td', {
            'html' : typeof config.descripcion != 'undefined' ? config.descripcion :'Sin Descipcion'
        }).inject(config_title_tr);
        new Element('td', {
            'html' : config.estado
        }).inject(config_title_tr);
        new Element('td', {
            'html' : config.modificado ? new Date(parseInt(config.modificado)) :'Sin Fecha'
        }).inject(config_title_tr);

        var checktd = new Element('td').inject(config_title_tr);
        new Element ('input',{
            'type':'checkbox'
        }).inject(checktd);
        var meta = Object.clone(config);
        delete meta._id;
        delete meta.cod;
        delete meta.msg;
        delete meta.verbatim;
        delete meta.estado;        
        new Element ('input',{
            'type':'hidden',
            'html': JSON.encode(meta)
        }).inject(checktd);		
        var editbutton = new Element('td').inject(config_title_tr);
        new Element ('input',{
            'type':'submit',
            'value':'Editar',
            'name':config._id.$oid,
            'onclick':"window.location.href = '/zgram/extractUtil.html?id="+config._id.$oid+"';"
        }).inject(editbutton);
								
        //new Element('td', {'html' : config.estado}).inject(config_title_tr);//modificado
				
        tags = tags.toLowerCase().replace(/ /g,'_');				
        config_title_tr.addClass(config.fuente).addClass(tags.replace(/,/g, ' ')).addClass(config.descripcion).addClass(config.estado).addClass(config.modificado);
    });
}

function extractFromChecked(){
    $$('input[type=checkbox]:checked').each(function(checkbox){
        if(checkbox.getNext() != null)
            getExtractURL(checkbox.getNext().get('html'));
    });
}

function applyFilters(){	
    $('resultTable').getChildren('tr').each(function(el){		
        if(($('getEstado').options[$('getEstado').selectedIndex].value.toLowerCase() == 'all' || el.hasClass($('getEstado').options[$('getEstado').selectedIndex].value.toLowerCase()))
            && ($('getFuente').options[$('getFuente').selectedIndex].value.toLowerCase() == 'all' || el.hasClass($('getFuente').options[$('getFuente').selectedIndex].value.toLowerCase()))
            && ($('getZones').get('value').length == 0 || $('getZones').get('value').toLowerCase().split(',').some(function(elem, index){
                return el.hasClass(elem)
            }))
            && ($('getTags').get('value').length == 0 || $('getTags').get('value').toLowerCase().split(',').some(function(elem, index){
                return el.hasClass(elem)
            }))
            || el.hasClass('tableRowHeader'))		
            el.setStyle('display', '');
        else
            el.setStyle('display', 'none');
    });	
}