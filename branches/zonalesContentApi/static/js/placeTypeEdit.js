//Definiciones manejo pantalla
var socket;
var edit = false;
var parents = new Array();
var cantParents = 0;
var placeTypeFilter = "";
var stateFilter = "";

window.addEvent('domready', function() {
	  socket = io.connect();
  	  socket.on('connect', function () { 
	    socket.emit('getPlaceTypes', true, function (data) {			  		
	  		data.each(function(type) {
	  			if (typeof(type.name) != 'undefined') { 
	  				new Element('option', {'value' : type.name, 'html' : type.name.replace(/_/g, ' ').capitalize()}).inject($('parent'));
	  			}
	  		});
	  		if(gup('name') != null && gup('name') != '') {
		        getPlaceType(gup('name'));
		        edit = true;
		     }
		   if(gup('placeType') != null && gup('placeType') != '') {
		        placeTypeFilter = gup('placeType');
		     }
		   if(gup('state') != null && gup('state') != '') {
		        stateFilter = gup('state');
		     }
		   $('backAnchor').href = '/CMUtils/placeTypeList?placeType=' + placeTypeFilter + '&state=' + stateFilter;
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


function getPlaceType(name){
	  //var jname = '{"name":"' + name + '"}';
	  socket.emit('getPlaceTypeByFilters', {name: name}, function (data) {
       	var jsonObj = data[0];			  		
          typeof(jsonObj.name) != 'undefined' ? $('nombre').value = jsonObj.name.replace(/_/g, ' ').capitalize() : $('nombre').value = "";
          if (typeof(jsonObj.parents) != 'undefined') {
              jsonObj.parents.each(function(parent){
              		addParent(parent);
              });
          }
            
	  });
}

function savePlaceType() {

    var jsonPlaceType = '{"name":"' + $('nombre').value.replace(/ /g, '_').toLowerCase() + '"';

    jsonPlaceType += ',"description":"' + $('description').value + '"';
    
    if (parents.length > 0) {
         jsonPlaceType += ',"parents":[';
         for (i = 0; i < parents.length; i++) {
             if (parents[i] != null) {
                 jsonPlaceType += '"' + parents[i] + '",';
             }
         }
         jsonPlaceType = jsonPlaceType.substring(0, jsonPlaceType.length - 1);
         jsonPlaceType += ']';
     }
    jsonPlaceType += '}';
    
	 var objPlaceType = eval('(' + jsonPlaceType + ')');
    
	 if (edit) {
    	socket.emit('updatePlaceType', objPlaceType, function (resp) {
    		//var resp = eval('(' + data + ')'); 
    		if (resp.cod == 100) {
    			alert("Se ha actualizado el tipo de place"); 
    		} else {
    			alert("Error al actualizar el tipo de place");
    		}
    	});	
    } else {
    	socket.emit('savePlaceType', objPlaceType, function (resp) {
    		//var resp = eval('(' + data + ')'); 
    		if (resp.cod == 100) {
    			alert("Se ha guardado el tipo de place"); 
    		} else {
    			alert("Error al guardar el tipo de place");
    		}
    	});
    }    
    
}

