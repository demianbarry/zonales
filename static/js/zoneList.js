var socket;
var zones = Array();

window.addEvent('domready', function() {
 	  socket = io.connect();
  	  socket.on('connect', function () { 
	    socket.emit('getZones', '', function (data) {			  		
	  		data.each(function(zone) {
	  			if (typeof(zone.name) != 'undefined') { 
	  				zones.include(zone.name.replace(/_/g, ' ').capitalize());
	  			}
	  		});
	    });
	  });	  	  
});

function searchZones(){

    if ($('zoneNameFilter').value.length > 0) {
	      socket.emit('getZonesByName', $('zoneNameFilter').value, function (data) {			  		
		  		makeZonesTable(data);
		  	});
    }
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
        'html' : 'Editar'
    }).inject(config_title_tr);

    jsonObj.each(function(zone){
        var config_title_tr = new Element('tr', {
            'class': 'tableRow'
        }).inject(configs_table);
        new Element('td', {
            'html' : zone.id
            }).inject(config_title_tr);
        new Element('td', {
            'html' : zone.name
            }).inject(config_title_tr);
        new Element('td', {
            'html' : zone.type
        }).inject(config_title_tr);
        new Element('td', {
            'html' : zone.state
            }).inject(config_title_tr);
        var editbutton = new Element('td').inject(config_title_tr);
        new Element ('input',{
            'type':'submit',
            'value':'Editar',
            'name':zone.id,
            'onclick':"window.location.href = 'zoneEdit?id="+zone.id+"';"
            }).inject(editbutton);
    });
}