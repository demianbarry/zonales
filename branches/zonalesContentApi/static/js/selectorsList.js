var socket;
var selectors = Array();

window.addEvent('domready', function() {
    $('newButton').addEvent('click', function() {
        var urlSelectorsEdit = "selectorsEdit";
        if ($('resultslist_content').getChildren().length > 0) {
            urlSelectorsEdit += "?url=" + $('selectorUrlFilter').value
        }
        document.location.href=urlSelectorsEdit;
    });
    socket = io.connect();
    socket.on('connect', function () { 
        socket.emit('getSelectors', true, function (data) {			  		
            data.each(function(selector) {
                if (typeof(selector.url) != 'undefined') { 
                    selectors.include(selector.url);
                }
            });
        });
    });
    if(gup('selector') != null && gup('selector') != '') {
        $('selectorUrlFilter').value = gup('selector');
    }
    
    if ($('selectorUrlFilter').value != '' || $('selectorUrlFilter').value != 'all') {
        searchSelectors();
    }	  	  	  	  
});

function searchSelectors(){
    var filters = '{';
    if ($('selectorUrlFilter').value.trim() != '') {
        filters = '{"url": "' + $('selectorUrlFilter').value + '"';
    }
    filters += '}';
 	 
    socket.emit('searchSelectors', JSON.parse(filters), function (data) {			  		
        makeSelectorsTable(data);
    });
  	 
}

function removeSelectors(name) {
    if (confirm("¿Seguro que deseas eliminar el selector?")) {
        socket.emit('removeSelectors', name, function (resp) {
            if (resp.cod == 100) {
                $('resultTable').removeChild($('tr_'+ name)); 
            } else {
                alert("Error al eliminar el selector");
            }
        });
    }	
}

function makeSelectorsTable(jsonObj){

    $('resultslist_content').empty();

    var configs_table = new Element('table', {
        'id' : 'resultTable'
    }).addClass('resultTable').inject($('resultslist_content'));
    var config_title_tr = new Element('tr', {
        'class': 'tableRowHeader'
    }).inject(configs_table);
    new Element('td', {
        'html' : 'Url'
    }).inject(config_title_tr);
    new Element('td', {
        'html' : 'Selectores'
    }).inject(config_title_tr);    
    new Element('td', {
        'html' : 'Editar/Eliminar'
    }).inject(config_title_tr);

    jsonObj.each(function(selector){
        var config_title_tr = new Element('tr', {
            'id': 'tr_' + selector.name,
            'class': 'tableRow'
        }).inject(configs_table);
        new Element('td', {
            'html' : selector.url
        }).inject(config_title_tr);
        new Element('td', {
            'html' : JSON.stringify(selector.selectors)
        }).inject(config_title_tr);        
        var editRemoveTd = new Element('td').inject(config_title_tr);
        new Element('img', {
            'width' : '16', 
            'height' : '16',
            'border': '0', 
            'alt': selector.name, 
            'title': 'Editar', 
            'src': '/images/addedit.png', 
            'onclick' : "window.location.href = 'selectorsEdit?url="+selector.url+"'"            
        }).inject(editRemoveTd);
        new Element('img', {
            'width' : '16', 
            'height' : '16', 
            'border': '0', 
            'alt': selector.name, 
            'title': 'Eliminar', 
            'src': '/images/publish_x.png', 
            'onclick' : 'removeSelectors("' + selector.name + '")'
        }).inject(editRemoveTd);
    });
}