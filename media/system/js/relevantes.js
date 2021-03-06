    <!--
    var searching = false;
    var firstIndexTime = null;
    var lastIndexTime = null;


    function getSolrDate(millis){
        var date = new Date(millis);
        return date.getFullYear() + '-' + complete(date.getMonth() + 1) + '-' + complete(date.getDate()) + 'T' +
            complete(date.getHours()) + ':' + complete(date.getMinutes()) + ':' + complete(date.getSeconds()) + '.' + date.getMilliseconds() + 'Z';
    }

    function complete(number){
        return (number > 9 ? ''+number : '0'+number);
    }

    function loadPost(){
        if(searching)
            return;
        var urlSolr = '/solr/select?indent=on&version=2.2&start=0&fl=*%2Cscore&rows=20&qt=zonalesContent&sort=relevance+desc&wt=json&explainOther=&hl.fl='+(lastIndexTime ? '&fq=modified:['+($('tempoSelect').value != '0' ? 'NOW-'+($('tempoSelect').value) : '*')+'+TO+*]' : '')+ <?php echo strlen($this->zonal_id) > 0 ? "'&q=zone:$this->zonal_id'" : "''"; ?>;
        var urlProxy = '/curl_proxy.php?host=localhost&port=8080&ws_path=' + encodeURIComponent(urlSolr);
        var reqTwitter = new Request.JSON({
            url: urlProxy,
            method: 'get',
            onRequest: function(){
                //status.set('innerHTML', 'Recuperando posts...');
                searching = true;
            },
            onComplete: function(jsonObj) {
                // actualizar pagina
                searching = false;
                if(typeof jsonObj != 'undefined')
                    updatePosts(jsonObj);
            },

            // Our request will most likely succeed, but just in case, we'll add an
            // onFailure method which will let the user know what happened.
            onFailure: function(){
                //status.set('innerHTML', 'Twitter: The request failed.');
            }

        }).send();
    }

    function loadMorePost(){
        var urlSolr = '/solr/select?indent=on&version=2.2&start=0&fl=*%2Cscore&rows=20&qt=zonalesContent&sort=relevance+desc&wt=json&explainOther=&hl.fl=&fq=indexTime:[*+TO+'+reduceMilli(firstIndexTime)+']' + <?php echo strlen($this->zonal_id) > 0 ? "'&q=zone:$this->zonal_id'" : "''"; ?>;
        var urlProxy = '/curl_proxy.php?host=localhost&port=8080&ws_path=' + encodeURIComponent(urlSolr);
        var reqTwitter = new Request.JSON({
            url: urlProxy,
            method: 'get',
            onRequest: function(){
                //status.set('innerHTML', 'Recuperando posts...');
            },
            onComplete: function(jsonObj) {
                // actualizar pagina
                if(typeof jsonObj != 'undefined')
                    updatePosts(jsonObj, true);
            },

            // Our request will most likely succeed, but just in case, we'll add an
            // onFailure method which will let the user know what happened.
            onFailure: function(){
                //status.set('innerHTML', 'Twitter: The request failed.');
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

    function updatePosts(json, more) {
        if(json.response.docs.length == 0)
            return;

        if(typeof(json) == 'undefined')
            return;
        if(typeof more == 'undefined' || !more) {
            //json.response.docs.reverse();
            if(!firstIndexTime) {
                firstIndexTime = json.response.docs.pick().indexTime;
            }
        } else {
            firstIndexTime = json.response.docs.getLast().indexTime;
        }


        json.response.docs.each(function(doc){

            var time = new Date(doc.indexTime).getTime();
            lastIndexTime = time > lastIndexTime ||  lastIndexTime == null ? time : lastIndexTime;

            var post = eval('('+doc.verbatim+')');
            var div_story_item = new Element('div').addClass('story-item').addClass('group').addClass(post.source),
            div_story_item_gutters = new Element('div').addClass('story-item-gutters').inject(div_story_item).addClass('group'),
            div_story_item_zonalesbtn = new Element('div').addClass('story-item-zonalesbtn').inject(div_story_item_gutters),
            div_zonalesbtn_hast = new Element('div').addClass('zonales-btn has-tooltip').inject(div_story_item_zonalesbtn),
            div_zonales_count_wrapper = new Element('div').addClass('zonales-count-wrapper').inject(div_zonalesbtn_hast),
            div_zonales_count_wrapper_up = new Element('div').addClass('zonales-count-wrapper-up').inject(div_zonales_count_wrapper),
            span_relevance = new Element('span').addClass('zonales-count').setHTML(post.relevance).inject(div_zonales_count_wrapper),
            div_zonales_count_wrapper_down = new Element('div').addClass('zonales-count-wrapper-down').inject(div_zonales_count_wrapper),
            div_story_item_content = new Element('div').addClass('story-item-content').addClass('group').inject(div_story_item_zonalesbtn, 'after'),
            div_story_item_details = new Element('div').addClass('story-item-details').inject(div_story_item_content),
            h3_story_item_title = new Element('h3').addClass('story-item-title').inject(div_story_item_details),
            a_title = new Element('a', {
                'target': '_blank',
                'href' : getTarget(post)
            }).setHTML(post.title).inject(h3_story_item_title),
            span_external_link_icon = new Element('span').addClass('external-link-icon').inject(a_title, 'after'),
            p_story_item_description = new Element('p').addClass('story-item-description').inject(h3_story_item_title, 'after'),
            a_story_item_source = new Element('a', {
                'target': '_blank'
            }).setHTML('').addClass('story-item-source').inject(p_story_item_description),
            a_story_item_icon = new Element('a').addClass('story-item-icon').inject(a_story_item_source, 'before'),
            a_story_item_icon_image = new Element('img',{
                'src': 'logo_'+post.source.replace('/','').toLowerCase()+'.png'
            }).inject(a_story_item_icon).addClass('source_logo'),
            a_story_item_teaser = new Element('span', {}).setHTML(post.text ? ' - ' + getVerMas(post.text.trim()) : '').addClass('story-item-teaser').inject(a_story_item_source, 'after'),
            ul_story_item_meta = new Element('ul').addClass('story-item-meta').addClass('group').inject(div_story_item_content),
            li_story_submitter = new Element('li', {}).setHTML('Publicado en  ').addClass('story-item-submitter').inject(ul_story_item_meta).setStyle('display', post.fromUser.name ? 'block' : 'none'),
            a_story_submitter = new Element('a', {
                'target': '_blank',
                'href': post.fromUser.url
            }).setHTML(post.source).inject(li_story_submitter),
            span_storyitem_modified = new Element('span', {}).setHTML(prettyDate(parseInt(post.modified))).addClass('story-item-modified-date').inject(a_story_submitter,'after'),
            span_storyitem_fromuser = new Element('span', {}).setHTML(post.fromUser =((post.fromUser.name).indexOf(post.source )!=-1)? "" : ' por '+post.fromUser.name).addClass('story-item-modified-date').inject(a_story_submitter,'after'),
            div_inline_comment_container = new Element('div').addClass('inline-comment-container').inject(div_story_item_content),
            div_story_item_activity = new Element('div').addClass('story-item-activity').addClass('group').addClass('hidden').inject(div_story_item_content),
            div_story_item_media   = new Element('div').addClass('story-item-media').inject(div_story_item_content, 'after');

            if(typeOf(post.actions) == 'array') {
                post.actions.each(function(action){
                    switch (action.type) {
                        case 'comment':
                            var li_story_item_comments = new Element('li', {}).setHTML(action.cant).addClass('story-item-comments').inject(ul_story_item_meta);
                            new Element('div').addClass('story-item-comments-icon').inject(li_story_item_comments);
                            break;
                        case 'like':
                            var li_story_item_likes = new Element('li', {}).setHTML(action.cant).addClass('story-item-likes').inject(ul_story_item_meta);
                            new Element('div').addClass('story-item-likes-icon').inject(li_story_item_likes);
                            break;
                        case 'retweets':
                            var li_story_item_retweets = new Element('li', {}).setHTML(action.cant).addClass('story-item-retweets').inject(ul_story_item_meta);
                            new Element('div').addClass('story-item-retweets-icon').inject(li_story_item_retweets);
                            break;
                        case 'replies':
                            var li_story_item_replies = new Element('li', {}).setHTML(action.cant).addClass('story-item-replies').inject(ul_story_item_meta);
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
                            if(div_story_item_media.childNodes.length == 0) {
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
                            }).setHTML('Más info...').addClass('story-item-link').inject(li_story_item_link);
                            break;
                    }
                });
            }

            //  var date = new Date(parseInt(post.created));
            //  new Element('li', {}).setHTML('Creado: ' + spanishDate(date)).addClass('story-item-created-date').inject(ul_story_item_meta);

            // date = new Date(parseInt(post.modified));


            var tags = post.tags;
            new Element('li', {}).setHTML(tags).addClass('story-item-tag').inject(ul_story_item_meta);

            if(!$('chk'+post.source)) {
                var tr = new Element('tr');
                new Element('input', {'id': 'chk'+post.source, 'type': 'checkbox', 'checked': true, 'value': post.source, 'onclick':'filtrar(this.value, this.checked);'}).inject(new Element('td').inject(tr));
                new Element('td', {}).setHTML(post.source).inject(tr);
                tr.inject($("chkFilter"));
            }
            var insertado = false;
            div_story_item.setStyle('display',$('chk'+post.source).checked ? 'block' : 'none');

            var counts = $$('span.zonales-count');
            if(typeOf(counts) == 'array') {
                counts.each(function(count){
                    if (parseInt(post.relevance) > parseInt(count.innerHTML)){

			insertado = true;
                        div_story_item.injectBefore(count.getParent().getParent().getParent().getParent().getParent());
                    }

                });
            }

            if(!insertado){
                div_story_item.injectInside($('postsContainer'));
            }
        });
    }

    function spanishDate(d){
        var weekday=["Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sabado"];

        var monthname=["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];

        return fixTime(d.getHours()) + ":" + fixTime(d.getMinutes()) + ":" + fixTime(d.getSeconds()) + ", " + weekday[d.getDay()]+" "+d.getDate()+" de "+monthname[d.getMonth()]+" de "+d.getFullYear();
    }

    function fixTime(i) {
        return (i<10 ? "0" + i : i);
    }

    function filtrar(source, visible) {
        var posts = $$('div#postsContainer div.story-item');

        if(typeOf(posts) == 'array') {
            posts.each(function(post){
                if(post.hasClass(source))
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
            [604800, 'dias', 86400], // 60*60*24*7, 60*60*24
            [1209600, ' ultima semana', 'proxima semana'], // 60*60*24*7*4*2
            [2419200, 'semanas', 604800], // 60*60*24*7*4, 60*60*24*7
            [4838400, 'ultimo mes', 'proximo mes'], // 60*60*24*7*4*2
            [29030400, 'meses', 2419200], // 60*60*24*7*4*12, 60*60*24*7*4
            [58060800, 'ultimo año', 'proximo año'], // 60*60*24*7*4*12*2
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
    //-->
-->
