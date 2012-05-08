//document.write('<script type="text/javascript" src="/media/system/js/json2-compressed.js"></script>');

var zUtilsJS = true;
var nodeProxy = '/curl_proxy.php?host=localhost&port=4000&ws_path=';
var servletProxy = '/curl_proxy.php?host=localhost&port=38080&ws_path=';
var nodeUri = 'zone';
var servletUri = 'ZCrawlSources';

String.prototype.capitalize = function(){
    return this.replace( /(^|\s)([a-z])/g , function(m,p1,p2){
        return p1+p2.toUpperCase();
    } );
};

String.prototype.trim = function() {
    return this.ltrim().rtrim();
}

String.prototype.ltrim = function() {
    return this.replace(/^\s+/,"");
}

String.prototype.rtrim = function() {
    return this.replace(/\s+$/,"");
}

function gup( name ) {
    name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
    var regexS = "[\\?&]"+name+"=([^&#]*)";
    var regex = new RegExp( regexS );
    var results = regex.exec( window.location.href );
    if( results == null )
        return "";
    else
        return results[1];
}

function fixTime(i) {
    return (i<10 ? "0" + i : i);
}

function spanishDate(d){
    var weekday=["Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sabado"];

    var monthname=["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];

    return fixTime(d.getHours()) + ":" + fixTime(d.getMinutes()) + ":" + fixTime(d.getSeconds()) + ", " + weekday[d.getDay()]+" "+d.getDate()+" de "+monthname[d.getMonth()]+" de "+d.getFullYear();
}

function getSolrDate(d){
    return d.getFullYear() + "-" + fixTime(d.getMonth()) + "-" + fixTime(d.getDate())+"T"+fixTime(d.getHours()) + ":" + fixTime(d.getMinutes()) + ":" + fixTime(d.getSeconds()) + "Z";
}

function getReadableDate(d){
    //alert(d);
    return  fixTime(d.getDate()) +  "/" +fixTime(d.getMonth() + 1) +  "/" + d.getFullYear() +" "+fixTime(d.getHours()) + ":" + fixTime(d.getMinutes()) + ":" + fixTime(d.getSeconds());
}

function GetPortNumber(url) {
    var url_parts = url.split('/');

    var domain_name_parts = url_parts[2].split(':');

    var port_number = domain_name_parts[1];

    return port_number;
}

function GetLocalPath(url) {
    var url_parts = url.split('/');

    var local_path_name = url_parts.slice(3).join('/');

    return local_path_name;
}

function formatJson(val) {
    var retval = '';
    var str = val;
    var pos = 0;
    var strLen = str.length;
    var indentStr = '&nbsp;&nbsp;&nbsp;&nbsp;';
    var newLine = '<br />';
    var character = '';

    for (var i=0; i<strLen; i++) {
        character = str.substring(i,i+1);

        if (character == '}' || character == ']') {
            retval = retval + newLine;
            pos = pos - 1;

            for (var j=0; j<pos; j++) {
                retval = retval + indentStr;
            }
        }

        retval = retval + character;

        if (character == '{' || character == '[' || character == ',') {
            retval = retval + newLine;

            if (character == '{' || character == '[') {
                pos++;
            }

            for (var k=0; k<pos; k++) {
                retval = retval + indentStr;
            }
        }
    }

    return retval;
}

function lookupGeoData(latitude,longitude,localidad,user) {
    var geoObj = new Object();
    var returnFieldMap = new Object();
    geoObj['startAddress'] = localidad;
    returnFieldMap[latitude] = '<LAT>';
    returnFieldMap[longitude] = '<LNG>';
    window.geoPickerCallback= function(data){
        $(latitude).value = data.lat;
        $(longitude).value = data.lng;
        if(typeof user != 'undefined'){
            users[user][1] = data.lat;
            users[user][2] = data.lng;
        }
        switchButtons(['compilarButton']);
    };
    geoObj['returnCallback'] = 'geoPickerCallback';
    myGeoPositionGeoPicker(geoObj);
/*{
		startAddress     : localidad,//($('localidad') ? $('localidad').value +', ' : '') + 'Argentina',
		returnFieldMap   : {
			latitude : '<LAT>',
            longitude : '<LNG>',
            'geoposition1c' : '<CITY>',   ...or <COUNTRY>, <STATE>, <DISTRICT>,
            <CITY>, <SUBURB>, <ZIP>, <STREET>, <STREETNUMBER>
			'geoposition1d' : '<ADDRESS>'
		}
	});*/
}

var zTags = new Array();
function getAllTags(){
    var url = servletUri + "/getTag?name=allNames";
    var urlProxy = servletProxy + encodeURIComponent(url);
    new Request({
        url: urlProxy,
        method: 'get',
        onSuccess: function(response) {
            response.replace('[','').replace(']','').split(',').each(function(tag) {
                zTags.include(tag.replace(/_/g, ' ').capitalize());
            });
        },
        onFailure: function(){
        }
    }).send();
}
var extendedStrings = new Array();
function getAllExtendedStrings(){
    var url = nodeUri + "/getAllExtendedStrings";
    var urlProxy = nodeProxy + encodeURIComponent(url);
    new Request({
        url: urlProxy,
        method: 'get',
        onSuccess: function(response) {
            JSON.parse(response).each(function(zone) {
                if(zone)
                    extendedStrings.include(zone.replace(/_/g, ' ').capitalize());
            });
        },
        onFailure: function(){
        }
    }).send();
}
//getAllTags();
getAllExtendedStrings();
function populateOptions(event, field, add, elmts, callback, fill){
    var container;
    fill = fill ? fill : '';
    //new Event(event).stop();
    if((container = field.getNext()) == null || !container.hasClass('suggestions')) {
        container = new Element('div', {
            'class': 'suggestions'
        }).inject(field, 'after').setStyle('display','none');
        field.addEvent('blur', function(event){
            if(container && !container.getElement('.selected')){
                container.empty();
                container.setStyle('display','none');                
            }
        });
    }
    
    switch(event.keyCode){
        case 13:
            setOption(field, add, container, callback, fill);
            //field.fireEvent('blur');
            break;
        case 27:
            container.setStyle('display','none');
            container.empty();
            break;
        case 38:
            if(container.getElement('.selected')){
                if(container.getElement('.selected') != container.getFirst()){
                    var previous = container.getElement('.selected').getPrevious();
                    container.getElement('.selected').removeClass('selected');
                    previous.addClass('selected');
                }
            } else {
                if(container.childNodes.length > 0) {
                    container.firstChild.addClass('selected');
                }
            }
            break;
        case 40:
            if(container.getElement('.selected')){
                if(container.getElement('.selected') != container.getLast()){
                    var next = container.getElement('.selected').getNext();
                    container.getElement('.selected').removeClass('selected');
                    next.addClass('selected');
                }
            } else {
                if(container.childNodes.length > 0) {
                    container.firstChild.addClass('selected');
                }
            }
            
            break;
        default:
            if(event.keyCode != 8 && (event.keyCode > 126 || event.keyCode < 32 ))
                return;
            container.empty();
            // alert("Field: "+field.get('value'));
            var query = field.get('value').toLowerCase().trim();            
            if(query.length - (add ? query.lastIndexOf(typeof add === 'string' ? add : ',') - 1 : 0 ) < 3)
                return;
            elmts = eval(JSON.stringify(Array.from(elmts)));
            Array.each(elmts,function(el){
                if(add && query.lastIndexOf(typeof add === 'string' ? add : ',') != -1)
                    query = query.substr(query.lastIndexOf(typeof add === 'string' ? add : ',')+1).trim();
                if(checkRegExp(el.toLowerCase(),query)) {
                    new Element('p', {
                        'html': el                        
                    }).inject(container)
                    .addEvent('mouseover', function(){
                        container.getChildren('.selected').removeClass('selected');
                        this.addClass('selected')
                    })
                    .addEvent('mouseout', function(){
                        container.getChildren('.selected').removeClass('selected');
                    })
                    .addEvent('click', function(){
                        setOption(field, add, container, callback, fill);
                    });                    
                }
            });
            if(container.childNodes.length > 0) {
                container.setStyle('display','block');                
            }
            break;
    }
        
}

function setOption(field, add, container, callback, fill){
    var value = "";
    var fieldValue = field.get('value');
    if (container.getElement('.selected')) {
        value = container.getElement('.selected').get('html').trim();
        if(!add) {            
            field.set('value', value);            
        } else {            
            add = (typeof add === 'string' ? add : ',');
            var finalValue = fieldValue.substr(0, fieldValue.lastIndexOf(add)+1).trim();
            field.set('value', finalValue + value);
        }
        if(typeof callback == 'function'){
            callback(field.get('value'));
        }
    } else {
        if(fieldValue){
            if(fieldValue != fill)
                alert("El valor seleccionado no se encuentra en la lista.");
        
        }
        field.set('value',fill);
    }
        
    container.setStyle('display','none');
    container.empty();    
}

function checkRegExp(str, patt){
    var patt1 = new RegExp("^"+patt+"|\ "+patt,"g");
    return patt1.test(str);
}

function switchButtons(buttons){
    $$('input[type=submit]').each(function(submit){
        submit.setStyle('display','none');
    });
    buttons.each(function(button){
        if($(button))
            $(button).setStyle('display','inline');
    });

    if($('generateQuery'))
        $('generateQuery').setStyle('display','inline');
    if($('volverButton'))
        $('volverButton').setStyle('display','inline');
}

function clone(obj) {
    if (null == obj || "object" != typeof obj) return obj;
    var copy = obj.constructor();
    for (var attr in obj) {
        if (obj.hasOwnProperty(attr)) copy[attr] = obj[attr];
    }
    return copy;
}