var socket;
var zoneTypes = Array();

window.addEvent('domready', function() {
 	  socket = io.connect();
  	  socket.on('connect', function () { 
	    socket.emit('getZonesTypes', true, function (data) {			  		
	  		data.each(function(zoneType) {
	  			if (typeof(zoneType.name) != 'undefined') { 
	  				zoneTypes.include(zoneType.name.replace(/_/g, ' ').capitalize());
	  			}
	  		});
	    });
	  });
	  if(gup('zoneType') != null && gup('zoneType') != '') {
	        $('zoneTypeNameFilter').value = gup('zoneType').replace(/_/g, ' ').capitalize();
	     }
	  if(gup('state') != null && gup('state') != '') {
	        $('zoneTypeStateFilter').value = gup('state');
	     }
	   if ($('zoneTypeNameFilter').value != '' && $('zoneTypeStateFilter').value != 'all') {
	   	searchZoneTypes();
	   }	  	  
});

function searchZoneTypes(){

	 var filters = '{"name": "' + $('zoneTypeNameFilter').value.replace(/ /g, '_').toLowerCase() + '"';
 	 if ($('zoneTypeStateFilter').value != 'all') {
 	 	filters += ', "state": "' + $('zoneTypeStateFilter').value + '"'; 
 	 }
 	 filters += '}';
 	 
    socket.emit('searchZoneTypes', eval('(' + filters + ')'), function (data) {			  		
  		makeZoneTypesTable(data);
  	 });
  	 
}

function removeZoneType(name) {
	 socket.emit('removeZoneType', name, function (data) {
    		var resp = eval('(' + data + ')'); 
    		if (resp.cod == 100) {
    			$('resultTable').removeChild($('tr_'+ name)); 
    		} else {
    			alert("Error al eliminar el tipo de zona");
    		}
    	});	
}

function makeZoneTypesTable(jsonObj){

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
        'html' : 'Padres'
    }).inject(config_title_tr);
    new Element('td', {
        'html' : 'Estado'
    }).inject(config_title_tr);
    new Element('td', {
        'html' : 'Editar/Eliminar'
    }).inject(config_title_tr);

    jsonObj.each(function(zoneType){
        var config_title_tr = new Element('tr', {
        		'id': 'tr_' + zoneType.name,
            'class': 'tableRow'
        }).inject(configs_table);
        new Element('td', {
            'html' : zoneType.name.replace(/_/g, ' ').capitalize()
            }).inject(config_title_tr);
        new Element('td', {
            'html' : JSON.stringify(zoneType.parents)
        }).inject(config_title_tr);
        new Element('td', {
            'html' : zoneType.state
            }).inject(config_title_tr);
        var editRemoveTd = new Element('td').inject(config_title_tr);
        new Element('img', {
        		'width' : '16', 
        		'height' : '16',
        		'border': '0', 
        		'alt': zoneType.name, 
        		'title': 'Editar', 
        		'src': '/images/addedit.png', 
        		'onclick' : "window.location.href = 'zoneTypeEdit?name="+zoneType.name
        						+ "&zoneType=" + $('zoneTypeNameFilter').value 
        						+ "&state=" + $('zoneTypeStateFilter').value  + "';"
        		}).inject(editRemoveTd);
        new Element('img', {
        		'width' : '16', 
        		'height' : '16', 
        		'border': '0', 
        		'alt': zoneType.name, 
        		'title': 'Eliminar', 
        		'src': '/images/publish_x.png', 
        		'onclick' : 'removeZoneType("' + zoneType.name + '")'
        		}).inject(editRemoveTd);
    });
}