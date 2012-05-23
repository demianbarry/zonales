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
            'html' : 'Validador'
        }).inject(selector_title_tr);
        new Element('td', {
            'html' : 'Formato'
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
    new Element('td', {
        'html': selector.validator
    }).inject(selector_line);
     new Element('td', {
        'html': selector.format
    }).inject(selector_line);
    var removeSelector_td = new Element('td').inject(selector_line);
    new Element('img', {
        'width' : '16', 
        'height' : '16', 
        'border': '0', 
        'alt': cantSelectors, 
        'title' : 'Eliminar selector',
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
        if (data) {
            console.log(data);
            $('url').value = data.url === undefined ? "" : data.url;
            $('urlLogo').value = data.urlLogo === undefined ? "" : data.urlLogo;
            if (typeOf(data.selectors) == 'array') {
                data.selectors.each(function(selector){
                    addSelector(selector);
                });
                checkSelectorTypes();
            }
        } else {
            $('url').value = url;
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
    var objSelectors = {
        url: $('url').value,
        selectors: [],
        urlLogo: $('urlLogo').value
    }

    if (selectors.length > 0) {
        for (x in selectors) {
            if (selectors[x] != null && selectors[x].type != undefined && (selectors[x].selector != undefined || selectors[x].validator != undefined || selectors[x].format != undefined) ) {
                var selector = {
                    type: selectors[x].type,
                    selector: selectors[x].selector,
                    validator: selectors[x].validator,
                    format: selectors[x].format
                };
                objSelectors.selectors.push(selector);
            }
        }
    }
    
    socket.emit('upsertSelector', objSelectors, function (resp) {
        //var resp = eval('(' + data + ')'); 
        if (resp.cod == 100) {
            alert("Se ha actualizado el selector"); 
        } else {
            alert("Error al actualizar el tipo de tag");
        }
    });	 
    
}