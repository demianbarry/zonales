//Definiciones manejo pantalla
var socket;
var edit = false;
var parents = new Array();
var cantParents = 0;
var tagTypeFilter = "";
var stateFilter = "";

window.addEvent('domready', function() {
	  socket = io.connect();
  	  socket.on('connect', function () { 
	    socket.emit('getTagsTypes', true, function (data) {			  		
	  		data.each(function(type) {
	  			if (typeof(type.name) != 'undefined') { 
	  				new Element('option', {'value' : type.name, 'html' : type.name.replace(/_/g, ' ').capitalize()}).inject($('parent'));
	  			}
	  		});
	  		if(gup('name') != null && gup('name') != '') {
		        getTagType(gup('name'));
		        edit = true;
		     }
		   if(gup('tagType') != null && gup('tagType') != '') {
		        tagTypeFilter = gup('tagType');
		     }
		   if(gup('state') != null && gup('state') != '') {
		        stateFilter = gup('state');
		     }
		   $('backAnchor').href = '/CMUtils/tagTypeList?tagType=' + tagTypeFilter + '&state=' + stateFilter;
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


function getTagType(name){
	  //var jname = '{"name":"' + name + '"}';
	  socket.emit('getTagTypeByFilters', {name: name}, function (data) {
       	var jsonObj = data[0];			  		
          typeof(jsonObj.name) != 'undefined' ? $('nombre').value = jsonObj.name.replace(/_/g, ' ').capitalize() : $('nombre').value = "";
          if (typeof(jsonObj.parents) != 'undefined') {
              jsonObj.parents.each(function(parent){
              		addParent(parent);
              });
          }
            
	  });
}

function saveTagType() {

    var jsonTagType = '{"name":"' + $('nombre').value.replace(/ /g, '_').toLowerCase() + '"';
    if (parents.length > 0) {
         jsonTagType += ',"parents":[';
         for (i = 0; i < parents.length; i++) {
             if (parents[i] != null) {
                 jsonTagType += '"' + parents[i] + '",';
             }
         }
         jsonTagType = jsonTagType.substring(0, jsonTagType.length - 1);
         jsonTagType += ']'
     }
    jsonTagType += '}';
    
	 var objTagType = eval('(' + jsonTagType + ')');
    
	 if (edit) {
    	socket.emit('updateTagType', objTagType, function (resp) {
    		//var resp = eval('(' + data + ')'); 
    		if (resp.cod == 100) {
    			alert("Se ha actualizado el tipo de tag"); 
    		} else {
    			alert("Error al actualizar el tipo de tag");
    		}
    	});	
    } else {
    	socket.emit('saveTagType', objTagType, function (resp) {
    		//var resp = eval('(' + data + ')'); 
    		if (resp.cod == 100) {
    			alert("Se ha guardado el tipo de tag"); 
    		} else {
    			alert("Error al guardar el tipo de tag");
    		}
    	});
    }    
    
}

