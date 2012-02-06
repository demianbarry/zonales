var socket;
var placeTypes = Array();

window.addEvent('domready', function() {
	  $('newButton').addEvent('click', function() {
	  		var urlPlaceTypeEdit = "placeTypeEdit";
  			if ($('resultslist_content').getChildren().length > 0) {
  				urlPlaceTypeEdit += "?placeType=" + $('placeTypeNameFilter').value + "&state=" + $('placeTypeStateFilter').value;
  			}
  			document.location.href=urlPlaceTypeEdit;
	  });
 	  socket = io.connect();
  	  socket.on('connect', function () { 
	    socket.emit('getPlacesTypes', true, function (data) {			  		
	  		data.each(function(placeType) {
	  			if (typeof(placeType.name) != 'undefined') { 
	  				placeTypes.include(placeType.name.replace(/_/g, ' ').capitalize());
	  			}
	  		});
	    });
	  });
	  if(gup('placeType') != null && gup('placeType') != '') {
	        $('placeTypeNameFilter').value = gup('placeType').replace(/_/g, ' ').capitalize();
	     }
	  if(gup('state') != null && gup('state') != '') {
	        $('placeTypeStateFilter').value = gup('state');
	     }
	   if ($('placeTypeNameFilter').value != '' || $('placeTypeStateFilter').value != 'all') {
	   	searchPlaceTypes();
	   }	  	  
});

function searchPlaceTypes(){

	 var filters = '{"name": "' + $('placeTypeNameFilter').value.replace(/ /g, '_').toLowerCase() + '"';
 	 if ($('placeTypeStateFilter').value != 'all') {
 	 	filters += ', "state": "' + $('placeTypeStateFilter').value + '"'; 
 	 }
 	 filters += '}';
 	 
    socket.emit('searchPlaceTypes', eval('(' + filters + ')'), function (data) {			  		
  		makePlaceTypesTable(data);
  	 });
  	 
}

function removePlaceType(name) {
	if (confirm("¿Seguro que deseas eliminar el tipo de place?")) {
	 	socket.emit('removePlaceType', name, function (resp) {
    		//var resp = eval('(' + data + ')'); 
    		if (resp.cod == 100) {
    			$('resultTable').removeChild($('tr_'+ name)); 
    		} else {
    			alert("Error al eliminar el tipo de place");
    		}
    	});	
    }
}

function makePlaceTypesTable(jsonObj){

    $('resultslist_content').empty();

    var configs_table = new Element('table', {
        'id' : 'resultTable'
    }).addClass('resultTable').inject($('resultslist_content'));
    var config_title_tr = new Element('tr', {
        'class': 'tableRowHeader'
    }).inject(configs_table);
    new Element('td', {
        'html' : 'Nombre'
    }).inject(config_title_tr);
    new Element('td', {
        'html' : 'Descripción'
    }).inject(config_title_tr);
    new Element('td', {
        'html' : 'Padres'
    }).inject(config_title_tr);
    new Element('td', {
        'html' : 'Estado'
    }).inject(config_title_tr);
    new Element('td', {
        'html' : 'Editar/Eliminar'
    }).inject(config_title_tr);

    jsonObj.each(function(placeType){
        var config_title_tr = new Element('tr', {
        		'id': 'tr_' + placeType.name,
            'class': 'tableRow'
        }).inject(configs_table);
        new Element('td', {
            'html' : placeType.name.replace(/_/g, ' ').capitalize()
            }).inject(config_title_tr);
        new Element('td', {
            'html' : placeType.description
            }).inject(config_title_tr);
        new Element('td', {
            'html' : JSON.stringify(placeType.parents)
        }).inject(config_title_tr);
        new Element('td', {
            'html' : placeType.state
            }).inject(config_title_tr);
        var editRemoveTd = new Element('td').inject(config_title_tr);
        new Element('img', {
        		'width' : '16', 
        		'height' : '16',
        		'border': '0', 
        		'alt': placeType.name, 
        		'title': 'Editar', 
        		'src': '/images/addedit.png', 
        		'onclick' : "window.location.href = 'placeTypeEdit?name="+placeType.name
        						+ "&placeType=" + $('placeTypeNameFilter').value 
        						+ "&state=" + $('placeTypeStateFilter').value  + "';"
        		}).inject(editRemoveTd);
        new Element('img', {
        		'width' : '16', 
        		'height' : '16', 
        		'border': '0', 
        		'alt': placeType.name, 
        		'title': 'Eliminar', 
        		'src': '/images/publish_x.png', 
        		'onclick' : 'removePlaceType("' + placeType.name + '")'
        		}).inject(editRemoveTd);
    });
}

