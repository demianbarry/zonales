//Definiciones manejo pantalla
var socket;
var edit = false;
var tag = null;
var tagFilter = "";
var stateFilter = "";
var tagTypeFilter = "";

window.addEvent('domready', function() {
	  socket = io.connect();
  	  socket.on('connect', function () { 
	    socket.emit('getTagsTypes', true, function (data) {			  		
	  		data.each(function(type) {
	  			if (typeof(type.name) != 'undefined') { 
	  				new Element('option', {'value' : type.name, 'html' : type.name.replace(/_/g, ' ').capitalize()}).inject($('tipo'));
	  			}
	  		});
	  		if(gup('id') != null && gup('id') != '') {
		        getTag(gup('id'), false);
		     }
		   if(gup('tag') != null && gup('tag') != '') {
		        tagFilter = gup('tag');
		     }
		   if(gup('state') != null && gup('state') != '') {
		        stateFilter = gup('state');
		     }
		   if(gup('tagType') != null && gup('tagType') != '') {
		        tagTypeFilter = gup('tagType');
		     }  
		   $('backAnchor').href = '/CMUtils/tagList?tag=' + tagFilter + '&state=' + stateFilter + '&tagType=' + tagTypeFilter;
	    });
	  });
});

function getTag(id, parent){
	  var jid = '{"id":"' + id + '"}';
	  socket.emit('getTagByFilters', jid, function (data) {
       	var jsonObj = data[0];			  		
	  		if (parent) {
             $('padre').value = jsonObj.name;
         } else {
             if (typeof($('id').value) != 'undefined') {
             	$('id').value = jsonObj.id;
             	edit = true;
             } else {
             	$('id').value = "";
             }
             typeof(jsonObj.name) != 'undefined' ? $('nombre').value = jsonObj.name.replace(/_/g, ' ').capitalize() : $('nombre').value = "";
             if (typeof(jsonObj.type) != 'undefined') {
             	  //alert(typeOf($('tipo').getElement('option[value=' + jsonObj.type + ']'))); //.set('selected');
                 $('tipo').value = jsonObj.type;
                 //alert($('tipo').value);
                 getParentTypes(jsonObj.type);
             } else {
                 $('tipo').value = "";
             }
             if (typeof(jsonObj.parent) != 'undefined') {
                 parent_id = jsonObj.parent;
             }  
         }	
	  });
}

function getParents(type) {
	 console.log(type);
	 var jtype = '{"type":"' + type + '"}';
	 socket.emit('getTagByFilters', jtype, function (jsonObj) {
    		jsonObj.each(function(parent) {
              var el = new Element('option', {'value' : parent.id, 'html' : parent.name.replace(/_/g, ' ').capitalize()}).inject($('padre'));
              if (parent.id == parent_id) {
                  el.selected = true;
              }
         });
    });
}

function getParentTypes(type) {
 	 var jtype = '{"name":"' + type + '"}';
 	 $('padre').empty();
	 socket.emit('getTagParentTypes', jtype, function (jsonObj) {
    		jsonObj.each(function(parentType) {
              getParents(parentType);
         });
    });	  		        
}

function saveTag() {

    var jsonTag = '{"name":"' + $('nombre').value.replace(/ /g, '_').toLowerCase() + '","id":"' + $('id').value + '","parent":"' + $('padre').value + '","type":"' + $('tipo').value + '"';
    jsonTag += '}';
    
	 if (edit) {
    	socket.emit('updateTag', jsonTag, function (data) {
    		var resp = eval('(' + data + ')'); 
    		if (resp.cod == 100) {
    			alert("Se ha actualizado el tag"); 
    		} else {
    			alert("Error al actualizar el tag");
    		}
    	});	
    } else {
    	socket.emit('saveTag', jsonTag, function (data) {
    		var resp = eval('(' + data + ')'); 
    		if (resp.cod == 100) {
    			alert("Se ha guardado el tag"); 
    		} else {
    			alert("Error al guardar el tag");
    		}
    	});
    }    
    
}
