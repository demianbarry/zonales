<script language="javascript" type="text/javascript">
    <!--

        //Parámetros globales
        var cantMax = '1000';
        var maxZoomOut = 4;
        var openLayersProxyHost = '/cgi-bin/proxy.cgi?url=';
        var proxyUri = '/curl_proxy.php?host=localhost&port=8080&ws_path=';
        var defaultSortField = 'modified';
        var strategyDistance = 30;

        //BOUNDS - Definidos para Argentina
        var minLon = -9289807.96207;
        var minLat = -8036517.191029;
        var maxLon = -5053362.107152;
        var maxLat = -2019394.325498;

        //CENTER - Definidos para Argentina
        var centerLon = -64.423444488507;
        var centerLat = -41.105773412891;

        window.addEvent('domready', function() {
            init();
        });

	 var map;
	 var vector_layer;
	 var popup;
	 var sources = new Array();
	 var tags = new Array();
         var ids = new Array();
         var cant = 0;

         function updatePosts(json, component) {
                if(json.response.docs.length == 0)
                    return;

                json.response.docs.each(function(doc){

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

                    if(typeof(post.actions) == 'array') {
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
                            postLinks = post.links;
                            break;
                        default:
                            postLinks = post.links;
                    }

                    if(typeof(postLinks) == 'array') {
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

                    //var counts = $$('span.zonales-count');
                    var counts = component.getElements('span.zonales-count');
                    if(typeof(counts) == 'array') {
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
                });
            }

	 function setZone(lon, lat, zoomLevel) {
			map.setCenter(new OpenLayers.LonLat(lon, lat), zoomLevel);
		}

	 function ampliar(lon, lat) {
	 		if (typeof popup != 'undefined') {
					popup.destroy();
				}
	   	var zoomLevel = map.getZoom() + 3;
	   	setZone(lon, lat, zoomLevel);
	   }

           function getTarget(post) {
                var ret = '';
                if ((post.source).toLowerCase() == 'twitter') {
                    ret = 'http://twitter.com/#!/' + post.fromUser.name;
                } else if ((post.source).toLowerCase() == 'facebook') {
                    ret = post.fromUser.url;
                } else {
                    if(typeof(post.links) == 'array') {
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


           function loadPost(){
                    var urlSolr = '/solr/select?indent=on&version=2.2&start=0&fl=*%2Cscore&qt=zonalesContent&sort=relevance+desc&wt=json&explainOther=&q=id:(';
                    for (var i = 0; i < cant; i++) {
                        if (i == cant - 1) {
                            urlSolr += ids[i];
                        } else {
                            urlSolr += ids[i] + " OR ";
                        }
                    }
                    urlSolr += ')&rows=' + cant;

                    var urlProxy = proxyUri + encodeURIComponent(urlSolr);

                    new Ajax(urlProxy, {
                        method: 'post',
                        //update: 'alias_progress',
                        onComplete: function(response) {
                            var jsonObj = JSON.parse(response);
                            document.getElementById('map_element').style.display = "none";
                            document.getElementById('postsContainer').style.display = "inline";

                            if(typeof jsonObj != 'undefined'){
                                    updatePosts(jsonObj,$('postsContainer'));
                            }
                        }
                    }).request();
                }

	 function init() {
	     //specify proxyhost
	     OpenLayers.ProxyHost = openLayersProxyHost;

             /*map = new OpenLayers.Map('map_element', {
                    maxExtent : new OpenLayers.Bounds(-128 * 156543.0339,
                                    -128 * 156543.0339, 128 * 156543.0339, 128 * 156543.0339),
                    maxResolution : 156543.0339,
                    units : 'm',
                    projection : new OpenLayers.Projection('EPSG:4326'),
                    displayProjection : new OpenLayers.Projection("EPSG:4326")
            });
            var mapnik = new OpenLayers.Layer.OSM();
            map.addLayer(mapnik);*/
            //Create a map with an empty array of controls
	     map = new OpenLayers.Map('map_element', {
                    maxExtent : new OpenLayers.Bounds(minLon, minLat, maxLon, maxLat),
                    restrictedExtent : new OpenLayers.Bounds(minLon, minLat, maxLon, maxLat),
                    units : 'm',
                    projection : new OpenLayers.Projection('EPSG:4326'),
                    displayProjection : new OpenLayers.Projection("EPSG:4326")
            });

	     //Create a base layer
	     var google_map = new OpenLayers.Layer.Google(
	         'Google Layer',
	         {}
	     );
	     map.addLayer(google_map);

              map.setCenter(new OpenLayers.LonLat(centerLon, centerLat)
                                            .transform(new OpenLayers.Projection("EPSG:4326"), new OpenLayers.Projection("EPSG:900913")), maxZoomOut);

              strategy = new OpenLayers.Strategy.Cluster();
              strategy.distance = strategyDistance;

	     //Add vector layer
	     vector_layer = new OpenLayers.Layer.Vector('Flickr Data',
	     {
	         projection: new OpenLayers.Projection('EPSG:4326'),
	         protocol: new OpenLayers.Protocol.HTTP({
	             url: 'http://localhost:8080/ZCrawlGeoExtractor/getPost',
	             params: {'cant':cantMax, 'sortField':defaultSortField, 'sortOrder':'desc',  'since':'NOW-24HOURS', 'minLon':'-78.969515', 'minLat':'-59.155008', 'maxLon':'-48.295686','maxLat':'-17.017761'},
	             format: new OpenLayers.Format.KML({
	                 extractAttributes: true, extractStyles:true
	             })
	         }),
	         strategies: [new OpenLayers.Strategy.Fixed(), strategy]   //new OpenLayers.Strategy.Fixed(),
	     });
	     map.addLayer(vector_layer);

	     //Let's style the features
	     //Create a style object to be used by a StyleMap object
	     var vector_style = new OpenLayers.Style({
	         'fillColor': '#669933',
	         'fillOpacity': .8,
	         'fontColor': '#f0f0f0',
	         'fontFamily': 'arial, sans-serif',
	         'fontSize': '.9em',
	         'fontWeight': 'bold',
	         'label': '${num_points}',
	         'pointRadius': '${point_radius}',
	         'strokeColor': '#aaee77',
	         'strokeWidth': 3
	         },
	         //Second parameter contains a context parameter
	         {
	             context: {
	                 num_points: function(feature){ return feature.attributes.count; },
	                 point_radius: function(feature){
	                     return 9 + (Math.log(feature.attributes.count) * 2)
	                 }
	             }
	     });

	     var vector_style_select = new OpenLayers.Style({
	         'fillColor': '#cdcdcd',
	         'fillOpacity': .9,
	         'fontColor': '#232323',
	         'strokeColor': '#ffffff'
	     })

	     //Create a style map object and set the 'default' intent to the
	     var vector_style_map = new OpenLayers.StyleMap({
	         'default': vector_style,
	         'select': vector_style_select
	     });

	     //Add the style map to the vector layer
	     vector_layer.styleMap = vector_style_map;

	     //Add a select feature control
	     var select_feature_control = new OpenLayers.Control.SelectFeature(
	         vector_layer,
	         {
	         }
	     )
	     map.addControl(select_feature_control);
	     select_feature_control.activate();


	     //Functions to call for the select feature control
	     function on_select_feature(event){
                //Vacio las listas de contadores
	         sources = new Array();
	         tags = new Array();
                 ids = new Array();
                 cant = 0;

	         //Store the clusters
	         var cluster = event.feature.cluster;

	         //Recorro los elementos para contar las fuentes y los tags
	         for(var i = 0; i < event.feature.attributes.count; i++){
					addSource(cluster[i].attributes.name); //Fuente
					var postDetail = JSON.parse(cluster[i].attributes.description);
					postDetail.tags.each(function(tag){
						addTag(tag); //Tags
					});
                                        ids[i] = postDetail.id;
                                        cant++;
                                }

	         var popupContentHTML = "<br>";

				//Muestro los contadores de fuentes
	         for(var i = 0; i < sources.length; i++) {
	         	popupContentHTML += "<strong>"
	                 + sources[i][0]
	                 + ": </strong>"
	                 + sources[i][1]
	                 + "<br>";
	         }

	         popupContentHTML += "<br>";

	         //Muestro los contadores de tags
	         for(var i = 0; i < tags.length; i++) {
	         	popupContentHTML += "<strong>"
	                 + tags[i][0]
	                 + ": </strong>"
	                 + tags[i][1]
	                 + "<br>";
	         }

	         popupContentHTML += "<br><div style='verticalAlign: center'><img src='/images/ampliar.gif' alt='Ampliar' onClick='ampliar(" + event.feature.geometry.x + "," + event.feature.geometry.y +")'>";

                 if (cant < 200) {
                    popupContentHTML += "<br><img src='/images/ver_post.gif' alt='Ver posts' onClick='loadPost()'>";
                 }

                 popupContentHTML += "</div>";

				//Armo el popup
	         var lonlat = new OpenLayers.LonLat(event.feature.geometry.x,event.feature.geometry.y);
	         var size = new OpenLayers.Size(150, 300);
	         popup = new OpenLayers.Popup.FramedCloud("countPopup", lonlat, size, popupContentHTML, null, true, null);
	         popup.displayClass = OpenLayers.Popup.FramedCloud
	         map.addPopup(popup);

	         //"<img src='" + cluster[i].style.externalGraphic + "' />"

	     }


		function addSource(valor){
			var ind, pos;
			var find = false;
			for(ind=0; ind<sources.length; ind++)
			{
			 	if (typeof sources[ind] != 'undefined' && sources[ind][0] == valor) {
			 		sources[ind][1] = sources[ind][1] + 1;
			 		find = true;
			 		break;
			 	}
			}
			if (!find) {
				sources[sources.length] = new Array(valor, 1);
			}
		}

		function addTag(valor){
			var ind, pos;
			var find = false;
			for(ind=0; ind<tags.length; ind++)
			{
			 	if (typeof tags[ind] != 'undefined' && tags[ind][0] == valor) {
			 		tags[ind][1] = tags[ind][1] + 1;
			 		find = true;
			 		break;
			 	}
			}
			if (!find) {
				tags[tags.length] = new Array(valor, 1);
			}
		}

	     //on unselect function
	     function on_unselect_feature(event){
                    if (typeof popup != 'undefined') {
                            popup.destroy();
                    }
	     }

             function on_features_added(event){
                 document.getElementById('ajaxLoader').style.display = "none";
	     }

		  //on unselect function
	     /*function on_dblclick_feature(event){
                 var popupContentHTML = "<br>Prueba</br>";
                 alert("Hice doble click, pero el evento capturado es de JavaScript y no sirve para nada");
	     }*/

             function on_zoom(event) {
                 if (map.getZoom() < maxZoomOut) map.zoomIn();

                 //"http://nominatim.openstreetmap.org/reverse?
                 var url = "/reverse?lat=" + map.getCenter().transform(new OpenLayers.Projection("EPSG:900913"), new OpenLayers.Projection("EPSG:4326")).lat + "&lon=" + map.getCenter().transform(new OpenLayers.Projection("EPSG:900913"), new OpenLayers.Projection("EPSG:4326")).lon + "&format=json";

                 var urlProxy = '/curl_proxy.php?host=nominatim.openstreetmap.org&port=80&ws_path=' + encodeURIComponent(url);

                    new Ajax(urlProxy, {
                        method: 'get',
                        //update: 'alias_progress',
                        onComplete: function(response) {
                            var jsonObj = JSON.parse(response);
                            document.getElementById('cercaDe').innerHTML = jsonObj.display_name;
                        }
                    }).request();
             }

	     vector_layer.events.register('featureselected', this, on_select_feature);
	     vector_layer.events.register('featureunselected', this, on_unselect_feature);
             vector_layer.events.register('featuresadded', this, on_features_added);
             map.events.register('moveend', this, on_zoom);
	     //vector_layer.events.register('dblclick', this, on_dblclick_feature);

	     if(!map.getCenter()){
	         map.zoomToMaxExtent();
	     }


	     //-------------------------------
	     //HTML Related
	     //-------------------------------
	     //Function to be called that updates vector layer when submit
	     //  is clicked
	     function update_vector_layer(){
                 document.getElementById('ajaxLoader').style.display = "inline";
                 vector_layer.protocol.options.params['since'] = document.getElementById('tempoSelect').value;
	         vector_layer.refresh();

	     }

	     //Add events to HTML input element
	     document.getElementById('tempoSelect').addEventListener(
	         'change',
	         update_vector_layer,
	         false);

	 }
    //-->
</script>
<label>Seleccione temporalidad:</label>
<select id="tempoSelect" class="tempoclass">
                    <option value="NOW-24HOURS">Hoy</option>
                    <option value="NOW-7DAYS">Ultima Semana</option>
                    <option value="NOW-30DAYS">Ultimo Mes</option>
                    <option value="0">Historico</option>
                </select>
<img id="ajaxLoader" src="/images/ajax_loader_bar.gif" style="display: none"/>
<br>
<label>Ud. está cerca de: </label><label id="cercaDe"></label>
<!--
<br>
<label>Localdiad: </label><label id="localidad"/>
-->
<div id='map_element' style="width: 96%; height: 650px; border: solid 9px #F1F2F3;"></div>
<div id="postsContainer" style="display: none"></div>
