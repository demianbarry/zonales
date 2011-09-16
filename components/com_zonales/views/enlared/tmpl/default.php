<script language="javascript" type="text/javascript">
    <!--
           
    var searching = false;
    var firstIndexTime = null;
    var lastIndexTime = null;

    window.addEvent('domready', function() {
        setInterval(function () {
            loadPost();
        }, 60000);
        loadPost();
    });

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
        var urlSolr = '/solr/select?indent=on&version=2.2&start=0&fl=*%2Cscore&rows=20&qt=zonalesContent&sort=max(modified,created)+desc&wt=json&explainOther=&hl.fl='+(lastIndexTime ? '&fq=indexTime:['+getSolrDate(lastIndexTime + 10800001)+'+TO+*]' : '')+ <?php echo strlen($this->zonal_id) > 0 ? "'&q=zone:$this->zonal_id'" : "''"; ?>;
        var urlProxy = '/curl_proxy.php?host=localhost&port=38080&ws_path=' + encodeURIComponent(urlSolr);
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
        var urlSolr = '/solr/select?indent=on&version=2.2&start=0&fl=*%2Cscore&rows=20&qt=zonalesContent&sort=max(modified,created)+desc&wt=json&explainOther=&hl.fl=&fq=indexTime:[*+TO+'+reduceMilli(firstIndexTime)+']' + <?php echo strlen($this->zonal_id) > 0 ? "'&q=zone:$this->zonal_id'" : "''"; ?>;
        var urlProxy = '/curl_proxy.php?host=localhost&port=38080&ws_path=' + encodeURIComponent(urlSolr);
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
            
    function updatePosts(json, more) {        
        if(json.response.docs.length == 0)
            return;
        
        if(typeof(json) == 'undefined') 
            return;
        if(typeof more == 'undefined' || !more) {
            json.response.docs.reverse();
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
            }).setHTML(post.source).addClass('story-item-source').inject(p_story_item_description),
            a_story_item_icon = new Element('a').addClass('story-item-icon').inject(a_story_item_source, 'before'),
            a_story_item_icon_image = new Element('img',{
                'src': 'logo_'+post.source.replace('/','').toLowerCase()+'.png'
            }).inject(a_story_item_icon).addClass('source_logo'),
            a_story_item_teaser = new Element('span', {}).setHTML(post.text ? ' - ' + post.text.trim() : '').addClass('story-item-teaser').inject(a_story_item_source, 'after'),
            ul_story_item_meta = new Element('ul').addClass('story-item-meta').addClass('group').inject(div_story_item_content),
            li_story_submitter = new Element('li', {}).setHTML('from ').addClass('story-item-submitter').inject(ul_story_item_meta).setStyle('display', post.fromUser.name ? 'block' : 'none'),
            a_story_submitter = new Element('a', {
                'target': '_blank',
                'href': post.fromUser.url
            }).setHTML(post.fromUser.name).inject(li_story_submitter),
            div_inline_comment_container = new Element('div').addClass('inline-comment-container').inject(div_story_item_content),
            div_story_item_activity = new Element('div').addClass('story-item-activity').addClass('group').addClass('hidden').inject(div_story_item_content),
            div_story_item_media   = new Element('div').addClass('story-item-media').inject(div_story_item_content, 'after');

            if(typeOf(post.actions) == 'array') {
                post.actions.each(function(action){
                    switch (action.type) {
                        case 'comment':
                            var li_story_item_comments = new Element('li', {}).setHTML(action.cant + ' comments').addClass('story-item-comments').inject(ul_story_item_meta);
                            break;
                        case 'like':
                            var li_story_item_likes = new Element('li', {}).setHTML(action.cant).addClass('story-item-likes').inject(ul_story_item_meta);
                            new Element('div').addClass('story-item-likes-icon').inject(li_story_item_likes);
                            break;
                        case 'retweets':
                            var li_story_item_retweets = new Element('li', {}).setHTML(action.cant + ' retweets').addClass('story-item-retweets').inject(ul_story_item_meta);
                            break;
                        case 'replies':
                            var li_story_item_replies = new Element('li', {}).setHTML(action.cant + ' replies').addClass('story-item-replies').inject(ul_story_item_meta);
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

            var date = new Date(parseInt(post.created));    
            new Element('li', {}).setHTML('Creado: ' + spanishDate(date)).addClass('story-item-created-date').inject(ul_story_item_meta);

            date = new Date(parseInt(post.modified));    
            new Element('li', {}).setHTML('Modificado: ' + spanishDate(date)).addClass('story-item-modified-date').inject(ul_story_item_meta);

            if(!$('chk'+post.source)) {
                var tr = new Element('tr');
                new Element('input', {'id': 'chk'+post.source, 'type': 'checkbox', 'checked': true, 'value': post.source, 'onclick':'filtrar(this.value, this.checked);'}).inject(new Element('td').inject(tr));
                new Element('td', {}).setHTML(post.source).inject(tr);
                tr.inject($("chkFilter"));
            }
			
            div_story_item.setStyle('display',$('chk'+post.source).checked ? 'block' : 'none');
            if(typeof more == 'undefined' || !more) {
                div_story_item.injectTop($('postsContainer'));
            } else {
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
			
    //-->
</script>
<table>
    <tbody id="chkFilter">
        <tr>
            <td>						
                <input type="checkbox" id="chkFacebook" checked="true" value="Facebook" onclick="filtrar(this.value, this.checked);">
            </td>
            <td>
                Facebook
            </td>					
        </tr>
        <tr>					
            <td>						
                <input type="checkbox" id="chkTwitter" checked="true" value="Twitter" onclick="filtrar(this.value, this.checked);">
            </td>
            <td>
                Twitter
            </td>
        </tr>
    </tbody>
</table>
<div id="postsContainer">
</div>
<div>
    <input value="Ver Mas" onclick="loadMorePost();" type="button">
</div>