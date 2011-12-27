var socket;
var zones = Array();

window.addEvent('domready', function() {
 	  socket = io.connect();
  	  socket.on('connect', function () { 
	    socket.emit('getZones', true, function (data) {			  		
	  		data.each(function(zone) {
	  			if (typeof(zone.name) != 'undefined') { 
	  				zones.include(zone.name.replace(/_/g, ' ').capitalize());
	  			}
	  		});
	    });
	    socket.emit('getZonesTypes', true, function (data) {			  		
	  		data.each(function(type) {
	  			if (typeof(type.name) != 'undefined') { 
	  				new Element('option', {'value' : type.name, 'html' : type.name.replace(/_/g, ' ').capitalize()}).inject($('tipo'));
	  			}
	  		});
	  		if(gup('zoneType') != null && gup('zoneType') != '') {
	        $('tipo').value = gup('zoneType');
	        if ($('zoneNameFilter').value == '' && $('zoneStateFilter').value == 'all') {
			   	searchZones();
			   }
	      }
	  	 });
	  });	  	  
	  if(gup('zone') != null && gup('zone') != '') {
	        $('zoneNameFilter').value = gup('zone').replace(/_/g, ' ').capitalize();
	     }
	  if(gup('state') != null && gup('state') != '') {
	        $('zoneStateFilter').value = gup('state');
	     }
	   if ($('zoneNameFilter').value != '' && $('zoneStateFilter').value != 'all') {
	   	searchZones();
	   }
});

function searchZones(){

	 var filters = '{"name": "' + $('zoneNameFilter').value.replace(/ /g, '_').toLowerCase() + '"';
 	 if ($('zoneStateFilter').value != 'all') {
 	 	filters += ', "state": "' + $('zoneStateFilter').value + '"'; 
 	 }
 	 if ($('tipo').value != 'all') {
 	 	filters += ', "type": "' + $('tipo').value.replace(/ /g, '_').toLowerCase() + '"'; 
 	 }
 	 filters += '}';
 	 
    socket.emit('searchZones', eval('(' + filters + ')'), function (data) {			  		
  		makeZonesTable(data);
  	 });
 	 
}

function removeZone(id) {
	 socket.emit('removeZone', id, function (data) {
    		var resp = eval('(' + data + ')'); 
    		if (resp.cod == 100) {
    			$('resultTable').removeChild($('tr_'+ id)); 
    		} else {
    			alert("Error al eliminar la zona");
    		}
    	});	
}

function makeZonesTable(jsonObj){

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
        'html' : 'Tipo'
    }).inject(config_title_tr);
    new Element('td', {
        'html' : 'Estado'
    }).inject(config_title_tr);
    new Element('td', {
        'html' : 'Editar/Eliminar'
    }).inject(config_title_tr);

    jsonObj.each(function(zone){
        var config_title_tr = new Element('tr', {
				'id': 'tr_' + zone.id,            
            'class': 'tableRow'
        }).inject(configs_table);
        new Element('td', {
            'html' : zone.id
            }).inject(config_title_tr);
        new Element('td', {
            'html' : zone.name.replace(/_/g, ' ').capitalize()
            }).inject(config_title_tr);
        new Element('td', {
            'html' : zone.type
        }).inject(config_title_tr);
        new Element('td', {
            'html' : zone.state
            }).inject(config_title_tr);
        var editRemoveTd = new Element('td').inject(config_title_tr);
        new Element('img', {
        		'width' : '16', 
        		'height' : '16',
        		'border': '0', 
        		'alt': zone.name, 
        		'title': 'Editar', 
        		'src': '/images/addedit.png', 
        		'onclick' : "window.location.href = 'zoneEdit?id=" + zone.id 
        						+ "&zone=" + $('zoneNameFilter').value 
        						+ "&state=" + $('zoneStateFilter').value 
        						+ "&zoneType=" + $('tipo').value + "';"
        		}).inject(editRemoveTd);
        new Element('img', {
        		'width' : '16', 
        		'height' : '16', 
        		'border': '0', 
        		'alt': zone.name, 
        		'title': 'Eliminar', 
        		'src': '/images/publish_x.png', 
        		'onclick' : 'removeZone(' + zone.id + ')'
        		}).inject(editRemoveTd);
    });
}