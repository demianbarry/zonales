//Definiciones manejo pantalla
var socket;
var edit = false;
var parents = new Array();
var cantParents = 0;
var zoneTypeFilter = "";
var stateFilter = "";

window.addEvent('domready', function() {
	  socket = io.connect();
  	  socket.on('connect', function () { 
	    socket.emit('getZonesTypes', true, function (data) {			  		
	  		data.each(function(type) {
	  			if (typeof(type.name) != 'undefined') { 
	  				new Element('option', {'value' : type.name, 'html' : type.name.replace(/_/g, ' ').capitalize()}).inject($('parent'));
	  			}
	  		});
	  		if(gup('name') != null && gup('name') != '') {
		        getZoneType(gup('name'));
		        edit = true;
		     }
		   if(gup('zoneType') != null && gup('zoneType') != '') {
		        zoneTypeFilter = gup('zoneType');
		     }
		   if(gup('state') != null && gup('state') != '') {
		        stateFilter = gup('state');
		     }
		   $('backAnchor').href = '/CMUtils/zoneTypeList?zoneType=' + zoneTypeFilter + '&state=' + stateFilter;
	    });
	  });
});

function addParent(parent) {
	if (cantParents == 0) {
        var parents_table = new Element('table', {'id' : 'parents_table'}).addClass('configTable').inject($('parentsDiv'));
        var parent_title_tr = new Element('tr', {'style': 'background-color: lightGreen'}).inject(parents_table);
        new Element('td', {'html' : 'Padres'}).inject(parent_title_tr);
        new Element('td', {'html' : 'Eliminar'}).inject(parent_title_tr);
    }
    var parent_line = new Element('tr', {'id' : 'pl' + cantParents}).inject($('parents_table'));
    new Element('td', {'html': parent.replace(/_/g, ' ').capitalize()}).inject(parent_line);
    var removeParent_td = new Element('td').inject(parent_line);
    new Element('img', {'width' : '16', 'height' : '16', 'border': '0', 'alt': cantParents, 'title' : 'Eliminar Padre', 'src': '/images/publish_x.png', 'onclick' : 'removeParent('+ cantParents + ')'}).inject(removeParent_td);
    parents[cantParents] = parent;
    cantParents++;
}

function removeParent(parentLine){
    $('parents_table').removeChild($('pl'+parentLine));
    delete parents[parentLine];
}


function getZoneType(name){
	  var jname = '{"name":"' + name + '"}';
	  socket.emit('getZoneTypeByFilters', jname, function (data) {
       	var jsonObj = data[0];			  		
          typeof(jsonObj.name) != 'undefined' ? $('nombre').value = jsonObj.name.replace(/_/g, ' ').capitalize() : $('nombre').value = "";
          if (typeof(jsonObj.parents) != 'undefined') {
              jsonObj.parents.each(function(parent){
              		addParent(parent);
              });
          }
            
	  });
}

function saveZoneType() {

    var jsonZoneType = '{"name":"' + $('nombre').value.replace(/ /g, '_').toLowerCase() + '"';
    if (parents.length > 0) {
         jsonZoneType += ',"parents":[';
         for (i = 0; i < parents.length; i++) {
             if (parents[i] != null) {
                 jsonZoneType += '"' + parents[i] + '",';
             }
         }
         jsonZoneType = jsonZoneType.substring(0, jsonZoneType.length - 1);
         jsonZoneType += ']'
     }
    jsonZoneType += '}';
    
	 if (edit) {
    	socket.emit('updateZoneType', jsonZoneType, function (data) {
    		var resp = eval('(' + data + ')'); 
    		if (resp.cod == 100) {
    			alert("Se ha actualizado el tipo de zona"); 
    		} else {
    			alert("Error al actualizar el tipo de zona");
    		}
    	});	
    } else {
    	socket.emit('saveZoneType', jsonZoneType, function (data) {
    		var resp = eval('(' + data + ')'); 
    		if (resp.cod == 100) {
    			alert("Se ha guardado el tipo de zona"); 
    		} else {
    			alert("Error al guardar el tipo de zona");
    		}
    	});
    }    
    
}

