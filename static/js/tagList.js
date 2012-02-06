var socket;
var tags = Array();

window.addEvent('domready', function() {
	  $('newButton').addEvent('click', function() {
	  		var urlTagEdit = "tagEdit";
  			if ($('resultslist_content').getChildren().length > 0) {
  				urlTagEdit += "?tag=" + $('tagNameFilter').value 
										+ "&state=" + $('tagStateFilter').value 
  										+ "&tagType=" + $('tipo').value
  			}
  			document.location.href=urlTagEdit;
	  });
 	  socket = io.connect();
  	  socket.on('connect', function () { 
	    socket.emit('getTags', true, function (data) {			  		
	  		data.each(function(tag) {
	  			if (typeof(tag.name) != 'undefined') { 
	  				tags.include(tag.name.replace(/_/g, ' ').capitalize());
	  			}
	  		});
	    });
	  	 socket.emit('getTagsTypes', true, function (data) {			  		
	  		data.each(function(type) {
	  			if (typeof(type.name) != 'undefined') { 
	  				new Element('option', {'value' : type.name, 'html' : type.name.replace(/_/g, ' ').capitalize()}).inject($('tipo'));
	  			}
	  		});
	  		if(gup('tagType') != null && gup('tagType') != '') {
	        $('tipo').value = gup('tagType');
	        if ($('tagNameFilter').value == '' && $('tagStateFilter').value == 'all') {
			   	searchTags();
			   }
	      }
	  	 });
	  });	  	  
	  if(gup('tag') != null && gup('tag') != '') {
	        $('tagNameFilter').value = gup('tag').replace(/_/g, ' ').capitalize();
	     }
	  if(gup('state') != null && gup('state') != '') {
	        $('tagStateFilter').value = gup('state');
	     }
	   if ($('tagNameFilter').value != '' || $('tagStateFilter').value != 'all') {
	   	searchTags();
	   }	  	  
});

function searchTags(){

	 var filters = '{"name": "' + $('tagNameFilter').value.replace(/ /g, '_').toLowerCase() + '"';
 	 if ($('tagStateFilter').value != 'all') {
 	 	filters += ', "state": "' + $('tagStateFilter').value + '"'; 
 	 }
 	 if ($('tipo').value != 'all') {
 	 	filters += ', "type": "' + $('tipo').value + '"'; 
 	 }
 	 filters += '}';
 	 
    socket.emit('searchTags', eval('(' + filters + ')'), function (data) {			  		
  		makeTagsTable(data);
  	 });
}

function removeTag(id) {
	if (confirm("¿Seguro que deseas eliminar el tag?")) {
	  socket.emit('removeTag', id, function (resp) {
    		//var resp = eval('(' + data + ')'); 
    		if (resp.cod == 100) {
    			$('resultTable').removeChild($('tr_'+ id)); 
    		} else {
    			alert("Error al eliminar el tag");
    		}
    	});	
    }
}

function makeTagsTable(jsonObj){

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

    jsonObj.each(function(tag){
        var config_title_tr = new Element('tr', {
        		'id': 'tr_' + tag.id,
            'class': 'tableRow'
        }).inject(configs_table);
        new Element('td', {
            'html' : tag.id
            }).inject(config_title_tr);
        new Element('td', {
            'html' : tag.name.replace(/_/g, ' ').capitalize()
            }).inject(config_title_tr);
        new Element('td', {
            'html' : tag.type
        }).inject(config_title_tr);
        new Element('td', {
            'html' : tag.state
            }).inject(config_title_tr);
        var editRemoveTd = new Element('td').inject(config_title_tr);
        new Element('img', {
        		'width' : '16', 
        		'height' : '16',
        		'border': '0', 
        		'alt': tag.name, 
        		'title': 'Editar', 
        		'src': '/images/addedit.png', 
        		'onclick' : "window.location.href = 'tagEdit?id="+tag.id
        						+ "&tag=" + $('tagNameFilter').value 
        						+ "&state=" + $('tagStateFilter').value 
        						+ "&tagType=" + $('tipo').value + "';"
        		}).inject(editRemoveTd);
        new Element('img', {
        		'width' : '16', 
        		'height' : '16', 
        		'border': '0', 
        		'alt': tag.name, 
        		'title': 'Eliminar', 
        		'src': '/images/publish_x.png', 
        		'onclick' : 'removeTag(' + tag.id + ');'
        		}).inject(editRemoveTd);
    });
}