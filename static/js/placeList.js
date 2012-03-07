var socket;
var places = Array();
var zones = Array();

window.addEvent('domready', function() {
	  $('newButton').addEvent('click', function() {
	  		var urlPlaceEdit = "placeEdit";
  			if ($('resultslist_content').getChildren().length > 0) {
  				urlPlaceEdit += "?place=" + $('placeNameFilter').value 
								 + "&state=" + $('placeStateFilter').value
								 + "&placeType=" + $('tipo').value
                                 + "&placeZone=" + $('placeZoneFilter').value
  			}
  			document.location.href=urlPlaceEdit;
	  });
 	  socket = io.connect();
  	  socket.on('connect', function () { 
	    socket.emit('getPlaces', true, function (data) {			  		
	  		data.each(function(place) {
	  			if (typeof(place.name) != 'undefined') { 
	  				places.include(place.name.replace(/_/g, ' ').capitalize());
	  			}
	  		});
	    });
	    socket.emit('getPlaceTypes', true, function (data) {			  		
	  		data.each(function(type) {
	  			if (typeof(type.name) != 'undefined') { 
	  				new Element('option', {'value' : type.name, 'html' : type.name.replace(/_/g, ' ').capitalize()}).inject($('tipo'));
	  			}
	  		});
	  		if(gup('placeType') != null && gup('placeType') != '') {
	        $('tipo').value = gup('placeType');
	        if ($('placeNameFilter').value == '' && $('placeStateFilter').value == 'all') {
			   	searchPlaces();
			   }
	      }
	  	 });
         socket.emit('getZonesExtendedString', true, function (data) {                    
            data.each(function(zone) {
                if (typeof(zone.extendedString) != 'undefined') { 
                    zones.include(zone.extendedString.replace(/_/g, ' ').capitalize());
                }
            });
        });
	  });	  	  
	  if(gup('place') != null && gup('place') != '') {
	        $('placeNameFilter').value = gup('place').replace(/_/g, ' ').capitalize();
	     }
	  if(gup('state') != null && gup('state') != '') {
	        $('placeStateFilter').value = gup('state');
	     }
	   if ($('placeNameFilter').value != '' || $('placeStateFilter').value != 'all') {
	   	searchPlaces();
	   }
});

function searchPlaces(){

	 var filters = '{"name": "' + $('placeNameFilter').value.replace(/ /g, '_').toLowerCase() + '"';
     filters += ',"zone":"' + $('placeZoneFilter').value + '"';
 	 if ($('placeStateFilter').value != 'all') {
 	 	filters += ', "state": "' + $('placeStateFilter').value + '"'; 
 	 }
 	 if ($('tipo').value != 'all') {
 	 	filters += ', "type": "' + $('tipo').value.replace(/ /g, '_').toLowerCase() + '"'; 
 	 }
 	 filters += '}';
 	 
    socket.emit('searchPlaces', eval('(' + filters + ')'), function (data) {			  		
  		makePlacesTable(data);
  	 });
 	 
}

function removePlace(id) {
	if (confirm("¿Seguro que deseas eliminar la zona?")) {
	 	socket.emit('removePlace', id, function (resp) {
    		//var resp = eval('(' + data + ')'); 
    		if (resp.cod == 100) {
    			$('resultTable').removeChild($('tr_'+ id)); 
    		} else {
    			alert("Error al eliminar la zona");
    		}
    	});
   }	
}

function makePlacesTable(jsonObj){

    $('resultslist_content').empty();

    var configs_table = new Element('table', {
        'id' : 'resultTable'
    }).addClass('resultTable').inject($('resultslist_content'));
    var config_title_tr = new Element('tr', {
        'class': 'tableRowHeader'
    }).inject(configs_table);
    new Element('td', {
        'html' : 'Id'
    }).inject(config_title_tr);
    new Element('td', {
        'html' : 'Nombre'
    }).inject(config_title_tr);
    new Element('td', {
        'html' : 'Descripción'
    }).inject(config_title_tr);
    new Element('td', {
        'html' : 'Tipo'
    }).inject(config_title_tr);
    new Element('td', {
        'html' : 'Estado'
    }).inject(config_title_tr);
    new Element('td', {
        'html' : 'Editar/Eliminar'
    }).inject(config_title_tr);

    jsonObj.each(function(place){
        var config_title_tr = new Element('tr', {
				'id': 'tr_' + place.id,            
            'class': 'tableRow'
        }).inject(configs_table);
        new Element('td', {
            'html' : place.id
            }).inject(config_title_tr);
        new Element('td', {
            'html' : place.name.replace(/_/g, ' ').capitalize()
            }).inject(config_title_tr);
        new Element('td', {
            'html' : place.description
            }).inject(config_title_tr);
        new Element('td', {
            'html' : place.type
        }).inject(config_title_tr);
        new Element('td', {
            'html' : place.state
            }).inject(config_title_tr);
        var editRemoveTd = new Element('td').inject(config_title_tr);
        new Element('img', {
        		'width' : '16', 
        		'height' : '16',
        		'border': '0', 
        		'alt': place.name, 
        		'title': 'Editar', 
        		'src': '/images/addedit.png', 
        		'onclick' : "window.location.href = 'placeEdit?id=" + place.id 
        						+ "&place=" + $('placeNameFilter').value 
        						+ "&state=" + $('placeStateFilter').value 
        						+ "&placeType=" + $('tipo').value + "';"
        		}).inject(editRemoveTd);
        new Element('img', {
        		'width' : '16', 
        		'height' : '16', 
        		'border': '0', 
        		'alt': place.name, 
        		'title': 'Eliminar', 
        		'src': '/images/publish_x.png', 
        		'onclick' : 'removePlace(' + place.id + ')'
        		}).inject(editRemoveTd);
    });
}