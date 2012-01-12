var nodeURL = 'http://192.168.0.2:4000';
var sources = new Array();
//var tags = new Array();
var zones = new Array();
var allZones = new Array();
var tab = "";
var zoneInitiated = false;
var zUserGroups = new Array();

window.addEvent('domready', function() {
    init();
});

function init() {
    initZCtx(function(zCtx) {
        initFilters(zCtx);
        initZonas(zCtx.selZone);
        zcSetTab(tab);
        if (typeof(zCtx.selZone) != 'undefined' && zCtx.selZone != '' && zCtx.selZone != null) {
            setZone(zCtx.selZone, zcGetSelectedZoneName());
        }
    });
}

function initPost() {
   setInterval(function () {
       loadPost(false);
   }, 60000);
   loadPost(true);
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
        getProvincias(function(provincias) {
            provincias.each(function(provincia) {
                new Element('option', {
                       'value': provincia.id,
                       'html': provincia.name.replace(/_/g, ' ').capitalize(),
                       'onclick': 'loadMunicipios(this.value, null);'
                   }).inject($('provincias'));
            });
        });

        getAllZones(function(zones) {
           zones.each(function(zone) {
               allZones.push(zone.name.replace(/_/g, ' ').capitalize());
            });
        });

        if (selZone != "") {
            getZoneById(selZone, function(zone) {
                loadMunicipios(zone.parent, zone.id);
                getZoneById(zone.parent, function(parent) {
                    $('provincias').value = parent.id;
                });
            });
        }
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
           new Element('td', {'html': source.name}).inject(sourcesTr);
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
           new Element('td', {'html': tag.name}).inject(tagTr);
           tagTr.inject($('tagsFilterTable'));
       }
    });
}

function initTempFilters(zCtx) {
    $('tempoSelect').value = zCtx.filters.temp;
    $('tempoSelect').addEventListener('change', onTempoChange, false);
}

function loadMunicipios(id_provincia, selZone) {
    $('zonalid').empty();
    getZonesByProvincia(id_provincia, function(zones) {
        zones.each(function(zone) {
            new Element('option', {
                   'value': zone.id,
                   'html': zone.name.replace(/_/g, ' ').capitalize(),
                   'onclick': "setZone(this.value, this.innerHTML.replace(/ /g, '_').toLowerCase())"
               }).inject($('zonalid'));
        });
        if (selZone != null) {
            $('zonalid').value = selZone;
        }
    });
}

function setZone(zoneId, zoneName) {
    setFirstIndexTime(null);
    setLastIndexTime(null);
    setMinRelevance(null);
    $('postsContainer').empty();
    $('newPostsContainer').empty();
    setSelectedZone(zoneId, zoneName, function() {
        loadPost(true);
    });
}

function onTempoChange() {
    $('postsContainer').empty();
    $('newPostsContainer').empty();
    setLastIndexTime(null);
    zcSetTemp($('tempoSelect').value);
    loadPost(true);
}

function complete(number){
    return (number > 9 ? ''+number : '0'+number);
}

function loadPost(first){
    //alert("LoadPost: " + JSON.stringify(zcGetContext()));
    loadSolrPost(tab, false, function(jsonObj) {
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
        }
    });
}

function loadMorePost(){
    loadSolrPost(tab, true, function(jsonObj) {
        if(typeof jsonObj != 'undefined')
            updatePosts(jsonObj, $('postsContainer'),true);
        armarTitulo(tab);
    });
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
        return shortText + "<span id=\"verMas\" onclick=\"if (this.getNext())  this.getNext().setStyle('display','inline'); this.style.display = 'none';\" style=\"display: inline;\">... [+]</span><span style=\"display: none;\" id=\"resto\">"+otherText+"</span>";
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
        if (tab == "enlared" || tab == "relevantes" || tab == "portada"){
            var post = post.clone(true, true);
            post.setStyle('display',$('chk'+(post.getElement("div.story-item-gutters div.story-item-content ul.story-item-meta li.story-item-submitter a").innerHTML)).checked ? 'block' : 'none');
            post.injectTop($('postsContainer'));
        }

        if (tab == "noticiasenlared" || tab == "noticiasenlaredrelevantes"){
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
// $('postsContainer').setStyle('backgroundColor','#FFFFFF');
}

function updatePosts(json, component, more) {
    if(json.response.docs.length == 0)
        return;
    if(typeof(json) == 'undefined')
        return;
    if(typeof more == 'undefined' || !more) {
        json.response.docs.reverse();
        if(!getFirstIndexTime()) {
            setFirstIndexTime(json.response.docs.pick().indexTime);
        }
    } else {
        setFirstIndexTime(json.response.docs.getLast().indexTime);
    }
    json.response.docs.each(function(doc){
        var time = new Date(doc.indexTime).getTime();
        setLastIndexTime((time > getLastIndexTime()) ||  getLastIndexTime() == null ? time : getLastIndexTime());
        var post = eval('('+doc.verbatim+')');
        var div_story_item = new Element('div').addClass('story-item').addClass('group').addClass(post.source),
        div_story_item_gutters = new Element('div').addClass('story-item-gutters').inject(div_story_item).addClass('group'),
        div_story_item_zonalesbtn = new Element('div').addClass('story-item-zonalesbtn').inject(div_story_item_gutters),
        div_zonalesbtn_hast = new Element('div').addClass('zonales-btn has-tooltip').inject(div_story_item_zonalesbtn),
        div_zonales_count_wrapper = new Element('div').addClass('zonales-count-wrapper').inject(div_zonalesbtn_hast),
        div_zonales_count_wrapper_up = new Element('div').addClass('zonales-count-wrapper-up').inject(div_zonales_count_wrapper),
        span_relevance = new Element('span').addClass('zonales-count').set('html',post.relevance).inject(div_zonales_count_wrapper),
        div_zonales_count_wrapper_down = new Element('div').addClass('zonales-count-wrapper-down').inject(div_zonales_count_wrapper),
        div_story_item_content = new Element('div').addClass('story-item-content').addClass('group').inject(div_story_item_zonalesbtn, 'after'),
        div_story_item_details = new Element('div').addClass('story-item-details').inject(div_story_item_content),
        div_story_item_idPost = new Element('div', {
            'html': doc.id,
            'id':'idPostDiv'
        }).addClass('group').inject(div_story_item).setStyle('display','none'),
        div_story_item_header = new Element('div').addClass('story_item_header').inject(div_story_item_details),
        h3_story_item_title = new Element('h3').addClass('story-item-title').inject(div_story_item_header),
        a_title = new Element('a', {
            'target': '_blank',
            'href' : getTarget(post)
        }).set('html',post.title).inject(h3_story_item_title),
        a_edit = new Element('a', {
            'target': '_blank',
            'href' : 'index.php?option=com_zonales&task=zonal&view=editor&tmpl=component_edit&id='+doc.id
        }).setStyle('display',post.source == 'Zonales' ? 'inline' : 'none').inject(div_story_item_header),
        a_edit_image = new Element('img',{
            'src': '/media/system/images/edit.png'
        }).inject(a_edit).addClass('edit_img'),
        span_external_link_icon = new Element('span').addClass('external-link-icon').inject(a_title, 'after'),
        p_story_item_description = new Element('p').addClass('story-item-description').inject(h3_story_item_title, 'after'),
        a_story_item_source = new Element('a', {
            'target': '_blank'
        }).set('html','').addClass('story-item-source').inject(p_story_item_description),
        a_story_item_icon = new Element('a').addClass('story-item-icon').inject(a_story_item_source, 'before'),
        a_story_item_icon_image = new Element('img',{
            'src': 'logo_'+post.source.replace('/','').toLowerCase()+'.png'
        }).inject(a_story_item_icon).addClass('source_logo'),
        a_story_item_teaser = new Element('span', {}).set('html',post.text ? ' - ' + getVerMas(post.text.trim()) : '').addClass('story-item-teaser').inject(a_story_item_source, 'after'),
        ul_story_item_meta = new Element('ul').addClass('story-item-meta').addClass('group').inject(div_story_item_content),
        li_story_submitter = new Element('li', {}).set('html','Publicado en  ').addClass('story-item-submitter').inject(ul_story_item_meta).setStyle('display', post.fromUser.name ? 'block' : 'none'),
        a_story_submitter = new Element('a', {
            'target': '_blank',
            'href': post.fromUser.url
        }).set('html',post.source).inject(li_story_submitter),
        span_storyitem_modified = new Element('span', {}).set('html',prettyDate(parseInt(post.modified))).addClass('story-item-modified-date').inject(a_story_submitter,'after'),
        span_storyitem_fromuser = new Element('span', {}).set('html',post.fromUser =((post.fromUser.name).indexOf(post.source )!=-1)? "" : ' por '+post.fromUser.name).addClass('story-item-modified-date').inject(a_story_submitter,'after'),
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

        var postLinks;

        switch(post.source.toLowerCase()) {
            case 'facebook':
                postLinks =	post.links;
                break;
            default:
                postLinks =	post.links;
        }

        if(typeOf(postLinks) == 'array') {
            postLinks.each(function(link){
                switch (link.type) {
                    case 'picture':
                        if(div_story_item_media.childNodes.length == 0 && link.url) {
                            var a_thumb = new Element('a', {
                                'href': getTarget(post),
                                'target': '_blank'
                            }).inject(div_story_item_media).addClass('thumb').addClass('thumb-s'),
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
                        }).set('html','Más info...').addClass('story-item-link').inject(li_story_item_link);
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
            });
        }
        var idInputTag = doc.id;
        var idButtonAddTags = 'buttonTags_'+doc.id;
        if(zUserGroups.indexOf("4") != -1){
            var span_addTags = new Element('span',{
                'id':'addTags_'+doc.id
            }).inject(div_story_tags);
            new Element('a',{
                'onclick':'if ( $("'+idInputTag+'").style.display == "none"){ $("'+idInputTag+'").setStyle("display","inline"); $("'+idButtonAddTags+'").setStyle("display","inline"); }else {$("'+idInputTag+'").setStyle("display","none"); $("'+idButtonAddTags+'").setStyle("display","none");}'
            }).set('html','Add Tags').inject(span_addTags);
            var selectedTag = new Element('input',{
                'id':idInputTag,
                'style':'display:none',
                'onkeyup':'populateOptions(event,this,true,zTags)',
                'value':''
            }).inject(span_addTags);

            new Element('img', {
                'id':idButtonAddTags,
                'style':'display:none',
                'src': '/CMUtils/addIcon.gif',
                'onclick':'show_confirm("' + idInputTag + '",$(\'' + idInputTag + '\').value,"'+tags+'")'
            }).set('html','Add').addClass('story-item-button').inject(div_story_tags);
        }
        var zone = post.zone;
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

        if(zUserGroups.indexOf("4") != -1){
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
        }

        if(!$('chk'+post.source)) {
            var tr = new Element('tr');
            new Element('input', {
                'id': 'chk'+post.source,
                'type': 'checkbox',
                'checked': 'checked',
                'value': post.source,
                'onclick':'setSourceVisible(this.value, this.checked);'
            }).inject(new Element('td').inject(tr));
            new Element('td', {'html': post.source}).inject(tr);
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
                new Element('td', {'html': tag}).inject(tr);
                tr.inject($('tagsFilterTable'));

                zcAddTag(tag);
            }
          });
        }

        if (getMinRelevance() != null) {
            if (parseInt(post.relevance) < getMinRelevance()) {
                setMinRelevance(parseInt(post.relevance));
            }
        } else {
            setMinRelevance(parseInt(post.relevance));
        }

        div_story_item.setStyle('display',$('chk'+post.source) && $('chk'+post.source).checked ? 'block' : 'none');

        if (tab == "enlared" || tab == "relevantes" || tab == "portada"){
            if(typeof more == 'undefined' || !more) {
                div_story_item.injectTop(component);
            } else {
                div_story_item.injectInside(component);
            }
        }

        if (tab == "noticiasenlared" || tab == "noticiasenlaredrelevantes"){
            var insertado = false;
            var counts = component.getElements('span.zonales-count');
            if(typeOf(counts) == 'array') {
                counts.each(function(count){
                    if (parseInt(post.relevance) > parseInt(count.innerHTML) && !insertado){
                        insertado = true;
                        div_story_item.injectBefore(count.getParent().getParent().getParent().getParent().getParent());
                    }

                });
            }

            if(!insertado){
                div_story_item.injectInside(component);
            }
        }

    });
}

function show_confirm(idInputTag,selectedTag,tags)
{
    var r=confirm("Esta seguro de Agregar el Tag: "+selectedTag);
    if (r==true)
    {
        saveContent(idInputTag,tags,selectedTag);
    }
    else
    {
        alert("Cancelado");
    }
}
function saveContent(idPost,tags,selectedTag){
    //\"tags\":[\"Espectaculos\"]

    var url = '/ZCrawlScheduler/indexPosts?url=http://localhost:38080/solr&doc={"id":"'+idPost+'"}&aTags='+tags+','+selectedTag;
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
        onSuccess: function() {
        //commit();
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
    var posts = $$('div#postsContainer div.story-item');
    if(typeOf(posts) == 'elements') {
        posts.each(function(post){
            if(post.hasClass(source))
                post.setStyle('display', visible ? 'block' : 'none');
        });
    }
    //    sendFilter(source,visible);
    armarTitulo(tab);
    if (visible) {
        zcAddSource(source);
    } else {
        zcUncheckSource(source);
    }
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
    
    //Refresco visibilidad de Posts
    var zCtxChkTags = zcGetCheckedTags();
    var posts = $$('div#postsContainer div.story-item');
    if(typeOf(posts) == 'elements') {
        posts.each(function(post){
            var visible = false;
            zCtxChkTags.each(function (tag) {
                if(post.hasClass(tag))
                    visible = true;
            });
            post.setStyle('display', visible ? 'block' : 'none');
        });
    }
   
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
    [4838400, 'ultimo mes', 'próximo mes'], // 60*60*24*7*4*2
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

/*function armarTitulo(){
    document.getElementById('tituloSup').innerHTML = "";
    $('enLaRed').getElements('input[id^=chk]').each(function(element) {
        if(element.checked) {
            document.getElementById('tituloSup').innerHTML += element.value + ", ";
        }
    });
*/


function armarTitulo(tabTemp){
    var temp;
    tabTemp = tab
    document.getElementById('tituloSup').innerHTML = "";

    if (tabTemp == 'relevantes'){
        $('enLaRed').getElements('input[id^=chk]').each(function(element, index) {
            if(element.checked) {
                document.getElementById('titulo1').innerHTML = "Ud. esta viendo Noticias mas Relevantes de la Red Social: "
                if(index != 0)
                    document.getElementById('tituloSup').innerHTML += ", ";
                document.getElementById('tituloSup').innerHTML += element.value+" ";

            }
        });
    }

    if (tabTemp == 'noticiasenlared'){
        
        temp = 0;
        $('noticiasEnLaRed').getElements('input[id^=chk]').each(function(element, index) {
           temp++;
           if(temp < 5 && element.checked ) {
                
              //  alert (temp);
                document.getElementById('titulo1').innerHTML = "Ud. esta viendo Noticias de los diarios OnLine: "
                if(index != 0)
                    document.getElementById('tituloSup').innerHTML += ", ";
                document.getElementById('tituloSup').innerHTML += element.value;
                
            }

            else if (temp > 5 && element.checked ){
                document.getElementById('tituloSup').innerHTML = "";
                document.getElementById('titulo1').innerHTML = "Ud. esta viendo noticias OnLine de mas de 5 diarios";

            }
        });
    }
    if (tabTemp == 'noticiasenlaredrelevantes'){
        temp = 0;
        $('noticiasEnLaRed').getElements('input[id^=chk]').each(function(element, index) {
            temp++;
            if(element.checked && temp < 5 ) {
                document.getElementById('titulo1').innerHTML = "Ud. esta viendo Noticias mas Relevantes de los diarios OnLine: "
                if(index != 0)
                    document.getElementById('tituloSup').innerHTML += ", ";
                document.getElementById('tituloSup').innerHTML += element.value;
                
            }
            else if (temp > 5 && element.checked ){
                document.getElementById('tituloSup').innerHTML = "";
                document.getElementById('titulo1').innerHTML = "Ud. esta viendo noticias OnLine de Mayor Relevancia de mas de 5 diarios";

            }
        });
    }
    if (tabTemp == 'enlared'){

        $('enLaRed').getElements('input[id^=chk]').each(function(element, index) {
            if(element.checked) {
                document.getElementById('titulo1').innerHTML = "Ud. esta viendo Noticias de la Red Social: "
                if(index != 0)
                    document.getElementById('tituloSup').innerHTML += ", ";
                document.getElementById('tituloSup').innerHTML += element.value+" ";
                
            }
        });

    }
   /* if (tabTemp == 'releventes'){
        $('enLaRed').getElements('input[id^=chk]').each(function(element, index) {
            if(element.checked) {
                document.getElementById('titulo1').innerHTML = "Ud. esta viendo las Noticias mas Relevantes de la Red Social: "
                if(index != 0)
                    document.getElementById('tituloSup').innerHTML += ", ";
                document.getElementById('tituloSup').innerHTML += element.value;
            }
        });
    }*/
    
}

//zcSetTemp($('tempoSelect').value);