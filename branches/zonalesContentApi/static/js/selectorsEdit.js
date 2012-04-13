//Definiciones manejo pantalla
var socket;
var selectors = new Array();
var cantSelectors = 0;
var urlFilter = "";

window.addEvent('domready', function() {
    socket = io.connect();
    socket.on('connect', function () {
        if(gup('url') != null && gup('url') != '') {
            getSelectors(gup('url'));
            urlFilter = gup('url');
        }            
        $('backAnchor').href = '/CMUtils/selectorsList?selector=' + urlFilter;        
    });
});

function addSelector(selector) {
    console.log(selector);
    if (cantSelectors == 0) {
        var selectors_table = new Element('table', {
            'id' : 'selectors_table'
        }).addClass('configTable').inject($('selectorsDiv'));
        var selector_title_tr = new Element('tr', {
            'style': 'background-color: lightGreen'
        }).inject(selectors_table);
        new Element('td', {
            'html' : 'Tipo'
        }).inject(selector_title_tr);
        new Element('td', {
            'html' : 'Selector CSS'
        }).inject(selector_title_tr);
        new Element('td', {
            'html' : 'Eliminar'
        }).inject(selector_title_tr);
    }
    var selector_line = new Element('tr', {
        'id' : 'pl' + cantSelectors
    }).inject($('selectors_table'));
    new Element('td', {
        'html': selector.type
    }).inject(selector_line);
    new Element('td', {
        'html': selector.selector
    }).inject(selector_line);
    var removeSelector_td = new Element('td').inject(selector_line);
    new Element('img', {
        'width' : '16', 
        'height' : '16', 
        'border': '0', 
        'alt': cantSelectors, 
        'title' : 'Eliminar seleto', 
        'src': '/images/publish_x.png', 
        'onclick' : 'removeSelector('+ cantSelectors + ')'
    }).inject(removeSelector_td);
    selectors[cantSelectors] = selector;
    cantSelectors++;
    if($(selector.type))
        $(selector.type).setStyle('display','none');
    $('selector').value = '';
}

function removeSelector(selectorLine){
    console.log(selectorLine);
    $('selectors_table').removeChild($('pl'+selectorLine));    
    if($(selectors[selectorLine].type))
        $(selectors[selectorLine].type).setStyle('display','auto');
    console.log($(selectors[selectorLine].type));
    delete selectors[selectorLine];
    checkSelectorTypes();
}


function getSelectors(url){
    console.log(url);
    socket.emit('getSelector', url, function (data) {                
        console.log(data);
        $('url').value = data.url === undefined ? "" : data.url;
        if (typeOf(data.selectors) == 'array') {
            data.selectors.each(function(selector){
                addSelector(selector);
            });
            checkSelectorTypes();
        }            
    });
}

function checkSelectorTypes(){
    if($('selectorsRow')) {
        var empty = true;                
        if($('selectorType')) {
            $('selectorType').getChildren().each(function(option){
                if(empty && option.getStyle('display') != 'none') {
                    empty = false;
                    option.selected = true;                            
                }
            });
        }
        if(empty)   
            $('selectorsRow').setStyle('display','none');
        else
            $('selectorsRow').setStyle('display','auto');
    }
}

function saveSelectors() {
    var jsonSelectors = '{"url":"' + $('url').value.replace(/ /g, '_').toLowerCase() + '"';
    if (selectors.length > 0) {
        jsonSelectors += ',"selectors":[';
        for (x in selectors) {
            if (selectors[x] != null && selectors[x].type != undefined && selectors[x].selector != undefined) {
                jsonSelectors += '{"type":"' + selectors[x].type + '","selector": "'+ selectors[x].selector + '"},';
            }
        }
        jsonSelectors = jsonSelectors.substring(0, jsonSelectors.length - 1);
        jsonSelectors += ']'
    }
    jsonSelectors += '}';
    console.log(jsonSelectors);
    var objSelectors = JSON.parse(jsonSelectors);
    
    if (gup('url') != null && gup('url') != '') {
        socket.emit('updateSelector', objSelectors, function (resp) {
            //var resp = eval('(' + data + ')'); 
            if (resp.cod == 100) {
                alert("Se ha actualizado el selector"); 
            } else {
                alert("Error al actualizar el tipo de tag");
            }
        });	
    } else {
        socket.emit('saveSelector', objSelectors, function (resp) {
            //var resp = eval('(' + data + ')'); 
            if (resp.cod == 100) {
                alert("Se ha guardado el tipo de tag"); 
            } else {
                alert("Error al guardar el tipo de tag");
            }
        });
    }    
    
}