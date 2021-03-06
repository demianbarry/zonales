var tagsArray = null;

loadTagsArray(null);

function loadTagsArray(id) {
    if(id) {
        var tagsSearchResults = $('tagsSearchResults'+id);
        tagsSearchResults.addClass('ajax-loading');
    }
    if(typeof(Json)!="undefined") {
        new Json.Remote('index.php?option=com_customproperties&format=raw&task=getAllTags', {
            onComplete: function(tags){
                tagsArray = tags;
                if(id) {
                    tagsSearchResults.removeClass('ajax-loading');
                    searchTags(null, id);
                }
            }
        }).send();
    }
    else if(typeof(Request) != "undefined"){
        new Request.JSON({
            url: 'index.php?option=com_customproperties&format=raw&task=getAllTags',
            onSuccess: function(tags){
                tagsArray = tags;
                if(id) {
                    tagsSearchResults.removeClass('ajax-loading');
                    searchTags(null, id);
                }
            }
        }).send();

    }
}


function deleteTag(value_id, field_id, content_table, content_id) {
    var span = $(value_id+'_'+field_id+'_'+content_table+'_'+content_id);
    var spanContent;

    spanContent = span.innerHTML;

    if(confirm('¿Está seguro de eliminar la etiqueta?')) {
        span.empty().addClass('ajax-loading');
        var url='index.php?option=com_customproperties&format=raw&task=removeTags&valueId='+value_id+'&fieldId='+field_id+'&content_table=\''+content_table+'\'&cid='+content_id;
        if(typeof(Ajax) != "undefined") {
            new Ajax(url, {
                method: 'get',
                onComplete: function(response) {
                    span.remove();
                },
                onFailure: function() {
                    span.innerHTML=(spanContent);
                    span.removeClass('ajax-loading');
                }
                
            }).request();
        } else if (typeof(Request != "undefined")) {
            new Request({
                'url': url,
                method: 'get',
                onComplete: function(response) {
                    span.dispose();
                },
                onFailure: function() {
                    span.innerHTML=(spanContent);
                    span.removeClass('ajax-loading');
                }

            }).send();
        }
    }
}

function searchTags(event, id) {
    var tagsSearchResults = $('tagsSearchResults'+id);
    var ev = new Event(event);
    var key = ev.key;
    switch(key) {
        case 'enter':
            ev.stop();
            tagsSearchResults.getElements('div[class=selected]').each(function(element, index){
                element.childNodes[1].click();
            });
        case 'up':
            ev.stop();
            tagsSearchResults.getElements('div[class=selected]').each(function(element, index){
                if((previous = element.getPrevious())) {
                    element.removeClass('selected');
                    previous.addClass('selected');
                }
            });
            return;
        case 'down':
            ev.stop();
            elmts = tagsSearchResults.getElements('div[class=selected]');
            if(elmts.length == 0) {
                tagsSearchResults.childNodes[0].addClass('selected');
            } else {
                elmts.each(function(element, index){
                    if((next = element.getNext())) {
                        element.removeClass('selected');
                        next.addClass('selected');
                    }
                });
            }
            return;
        default:
            if(key.length != 1 && key != 'backspace' && key != 'delete')
                return;
    }

    var tagsSearchField = $('tagsSearchField'+id)
    var value = tagsSearchField.value;

    if(value.length < 3) {
        tagsSearchResults.empty()
        tagsSearchResults.innerHTML='Indique al menos tres caracteres para la búsqueda.';
        return;
    }
    
    
    if(value != null && (value = trim(value)).length != 0) {

        tagsSearchResults.addClass('ajax-loading');
        
        var selectedIds = new Array();

        var selectedTags = $('selectedTags'+id);

        var elements1 = $('addTagsDiv'+id).getParent().getElements('span[id$=_content_'+id+']');
        elements1.each(function(element, index){
            selectedIds.include(element.id.substring(0,element.id.indexOf("_")));
        });
        
        var elements2 = selectedTags.getElements('input[name^=cp_]');
        elements2.each(function(element, index){
            if(element.checked == false) {
                element.getParent().remove();
            }
            else {
                selectedIds.include(element.name.substr(element.name.lastIndexOf("_")+1));
            }
        });

        var elements3 = tagsSearchResults.getElements('input[name^=cp_]');
        elements3.each(function(element, index){
            if(element.checked) {
                element.getParent().removeClass('selected');
                selectedTags.appendChild(element.getParent());
                selectedIds.include(element.name.substr(element.name.lastIndexOf("_")+1));
            }
        });
        
        selectedTags.setStyle('display', 'none');

        tagsSearchResults.empty();
        var divs = new Array();
        tagsArray.each(function(element, index){
            if(element.value.toLowerCase().indexOf(value.toLowerCase()) != -1 && !selectedIds.contains(element.value_id)) {
                divs.include(new Element('div', {
                    'events': {
                        'mouseover': function() {
                            tagsSearchResults.getElements('div[class=selected]').each(function(element, index){
                                element.removeClass('selected');
                            });
                            this.addClass('selected');
                        },
                        'mouseout': function() {
                            this.removeClass('selected');
                        },
                        'click': function() {
                            this.childNodes[1].click();
                            searchTags(event, id);
                        }
                    },
                    'styles': {
                        'cursor': 'pointer'
                    }                    
                }));
                divs[divs.length-1].adopt(new Element('div', {                    
                    })).innerHTML=element.value;
                divs[divs.length-1].adopt(new Element('input', {
                    'type': "checkbox",
                    'events': {
                        'click': function() {
                            tagsSearchField.focus();
                        },
                        'change': function() {
                            this.value = (this.checked ? 2 : 0);
                        }
                    },
                    'value': '0',
                    'name': 'cp_'+element.field_id+'_'+element.value_id,
                    'styles': {
                        'display': 'none'
                    }
                }));
                tagsSearchResults.adopt(divs[divs.length-1]);
            }
        });

        if(divs.length > 0)
            divs[0].addClass('selected');

        if(divs.length == 0) {
            tagsSearchResults.innerHTML='No se encontraron resultados.';
        }

        if(selectedTags.getElements('div').length != 0)
            selectedTags.setStyle('display', 'block');
        else
            selectedTags.setStyle('display', 'none');

        tagsSearchResults.removeClass('ajax-loading');
    } else
    if(value == null || value == ''){
        tagsSearchResults.innerHTML='Indique al menos tres caracteres para la búsqueda.';
        tagsSearchResults.empty();
    }
    tagsSearchField.focus();
}

function showAddTagsDiv(id) {
    if($('addTagsDiv'+id).getStyle('display') == 'none') {
        $('addTagsDiv'+id).setStyle('display', 'block');
        $('tagsSearchField'+id).focus();
    }
    else {
        $('addTagsDiv'+id).setStyle('display', 'none');
    }
}

function addTags(event, id) {
    new Event(event).stop();

    $('addTagsForm'+id).setStyle('width',"100px");
    $('addTagsForm'+id).setStyle('height',"40px");
    $('addTagsForm'+id).addClass('ajax-loading');
    $('tagsSearchHeader'+id).setStyle('display', 'none');
    $('addTagsButton'+id).setStyle('display','none');
    $('tagsSearchResults'+id).setStyle('display', 'none');
    $('selectedTags'+id).setStyle('display', 'none');

    if(typeof(Json)!="undefined") {
        $('addTagsForm'+id).send({
            onSuccess: function(response) {
                $('addTagsDiv'+id).setStyle('display', 'none');
                window.location.reload();
            }
        });
    }
    else if(typeof(Request) != "undefined"){
        $('addTagsForm'+id).set('send', {
            onComplete: function(response) {
                $('addTagsDiv'+id).setStyle('display', 'none');
                window.location.reload();
            }
        });
        $('addTagsForm'+id).send();
    }
    
}