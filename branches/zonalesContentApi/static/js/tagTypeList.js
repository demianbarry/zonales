var socket;
var tagTypes = Array();

window.addEvent('domready', function() {
 	  socket = io.connect();
  	  socket.on('connect', function () { 
	    socket.emit('getTagsTypes', true, function (data) {			  		
	  		data.each(function(tagType) {
	  			if (typeof(tagType.name) != 'undefined') { 
	  				tagTypes.include(tagType.name.replace(/_/g, ' ').capitalize());
	  			}
	  		});
	    });
	  });
	  if(gup('tagType') != null && gup('tagType') != '') {
	        $('tagTypeNameFilter').value = gup('tagType').replace(/_/g, ' ').capitalize();
	     }
	  if(gup('state') != null && gup('state') != '') {
	        $('tagTypeStateFilter').value = gup('state');
	     }
	   if ($('tagTypeNameFilter').value != '' && $('tagTypeStateFilter').value != 'all') {
	   	searchTagTypes();
	   }	  	  	  	  
});

function searchTagTypes(){

	 var filters = '{"name": "' + $('tagTypeNameFilter').value.replace(/ /g, '_').toLowerCase() + '"';
 	 if ($('tagTypeStateFilter').value != 'all') {
 	 	filters += ', "state": "' + $('tagTypeStateFilter').value + '"'; 
 	 }
 	 filters += '}';
 	 
    socket.emit('searchTagTypes', eval('(' + filters + ')'), function (data) {			  		
  		makeTagTypesTable(data);
  	 });
  	 
}

function removeTagType(name) {
	 socket.emit('removeTagType', name, function (data) {
    		var resp = eval('(' + data + ')'); 
    		if (resp.cod == 100) {
    			$('resultTable').removeChild($('tr_'+ name)); 
    		} else {
    			alert("Error al eliminar el tipo de tag");
    		}
    	});	
}

function makeTagTypesTable(jsonObj){

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

    jsonObj.each(function(tagType){
        var config_title_tr = new Element('tr', {
        		'id': 'tr_' + tagType.name,
            'class': 'tableRow'
        }).inject(configs_table);
        new Element('td', {
            'html' : tagType.name.replace(/_/g, ' ').capitalize()
            }).inject(config_title_tr);
        new Element('td', {
            'html' : JSON.stringify(tagType.parents)
        }).inject(config_title_tr);
        new Element('td', {
            'html' : tagType.state
            }).inject(config_title_tr);
        var editRemoveTd = new Element('td').inject(config_title_tr);
        new Element('img', {
        		'width' : '16', 
        		'height' : '16',
        		'border': '0', 
        		'alt': tagType.name, 
        		'title': 'Editar', 
        		'src': '/images/addedit.png', 
        		'onclick' : "window.location.href = 'zoneTypeEdit?name="+tagType.name
        						+ "&tagType=" + $('tagTypeNameFilter').value 
        						+ "&state=" + $('tagTypeStateFilter').value  + "';"
        		}).inject(editRemoveTd);
        new Element('img', {
        		'width' : '16', 
        		'height' : '16', 
        		'border': '0', 
        		'alt': tagType.name, 
        		'title': 'Eliminar', 
        		'src': '/images/publish_x.png', 
        		'onclick' : 'removeTagType("' + tagType.name + '")'
        		}).inject(editRemoveTd);
    });
}