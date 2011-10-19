<script language="javascript" type="text/javascript">
    <!--

        window.addEvent('domready', function() {
            init();
        });

	 var map;
	 var vector_layer;
	 var popup;
	 var sources = new Array();
	 var tags = new Array();

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

	 function init() {
	     //specify proxyhost
	     OpenLayers.ProxyHost = '/cgi-bin/proxy.cgi?url=';

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
	     map = new OpenLayers.Map('map_element');

	     //Create a base layer
	     var google_map = new OpenLayers.Layer.Google(
	         'Google Layer',
	         {}
	     );
	     map.addLayer(google_map);

              map.setCenter(new OpenLayers.LonLat(-64.1905, -31.4098)
                                            .transform(new OpenLayers.Projection("EPSG:4326"), new OpenLayers.Projection("EPSG:900913")), 5);   //12

              strategy = new OpenLayers.Strategy.Cluster();
              strategy.distance = 50;

	     //Add vector layer
	     vector_layer = new OpenLayers.Layer.Vector('Flickr Data',
	     {
	         projection: new OpenLayers.Projection('EPSG:4326'),
	         protocol: new OpenLayers.Protocol.HTTP({
	             url: 'http://localhost:8080/ZCrawlGeoExtractor/getPost',
	             params: {'cant':'3000', 'sortField':'indexTime', 'sortOrder':'desc'},
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

	         //Store the clusters
	         var cluster = event.feature.cluster;

	         //Recorro los elementos para contar las fuentes y los tags
	         for(var i = 0; i < event.feature.attributes.count; i++){
					addSource(cluster[i].attributes.name); //Fuente
					var postTags = JSON.parse(cluster[i].attributes.description);
					postTags.tags.each(function(tag){
						addTag(tag); //Tags
					});
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

	         popupContentHTML += "<br><img src='ampliar.gif' alt='Ampliar' onClick='ampliar(" + event.feature.geometry.x + "," + event.feature.geometry.y +")'>";

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

		  //on unselect function
	     /*function on_dblclick_feature(event){
                 var popupContentHTML = "<br>Prueba</br>";
                 alert("Hice doble click, pero el evento capturado es de JavaScript y no sirve para nada");
	     }*/


	     vector_layer.events.register('featureselected', this, on_select_feature);
	     vector_layer.events.register('featureunselected', this, on_unselect_feature);
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
	         //Change URL based on input tags
	         vector_layer.protocol.options.params['cant'] = document.getElementById('input_cant').value
	         vector_layer.protocol.options.params['sortfield'] = document.getElementById('input_field').value
				vector_layer.protocol.options.params['sortOrder'] = document.getElementById('input_order').value

	         //Refresh the layer with the new params
	         vector_layer.refresh();

	         //Lastly, clear out the div that shows photo info
	         document.getElementById('photo_info_wrapper').innerHTML = '';
	     }

	     //Add events to HTML input element
	     /*document.getElementById('input_submit').addEventListener(
	         'click',
	         update_vector_layer,
	         false);+*/

	 }
    //-->
</script>
<div id='map_element' style="width: 100%; height: 650px"></div>
