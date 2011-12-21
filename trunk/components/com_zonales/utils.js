document.write('<script type="text/javascript" src="http://api.mygeoposition.com/api/geopicker/api.js"></script>');

var proxy = 'curl_proxy.php?host='+host+'&port='+port+'&ws_path=';
var servletUri = 'ZCrawlSources';

String.prototype.capitalize = function(){
    return this.replace( /(^|\s)([a-z])/g , function(m,p1,p2){
        return p1+p2.toUpperCase();
    } );
};

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

function emptyChildren(comp){
    alert(typeOf(comp));
    if ( comp.hasChildNodes()) {
        while ( comp.childNodes.length >= 1 ) {
            comp.removeChild( comp.firstChild );       
        } 
    }
    return comp;
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
    return  fixTime(d.getDate()) +  "/" +fixTime(d.getMonth()) +  "/" + d.getFullYear() +" "+fixTime(d.getHours()) + ":" + fixTime(d.getMinutes()) + ":" + fixTime(d.getSeconds());
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

var zones = new Array();
var zTags = new Array();

function getAllZones(){
    var url = servletUri + "/getZone?name=allNames";
    var urlProxy = proxy + encodeURIComponent(url);
    new Request({
        url: urlProxy,
        method: 'get',        
        onSuccess: function(response) {
            response.replace('[','').replace(']','').split(',').each(function(zone) {
                zones.include(zone.replace(/_/g, ' ').capitalize());
            });
        },
        onFailure: function(){
        }
    }).send();
}

function getAllTags(){
    var url = servletUri + "/getTag?name=allNames";
    var urlProxy = proxy + encodeURIComponent(url);
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
getAllZones();
getAllTags();
function populateOptions(event, field, add, elmts){	
    var container;
    if((container = field.getNext()) == null) {
        container = new Element('div', {
            'class': 'suggestions'
        }).inject(field, 'after');
    }
    switch(event.keyCode){
        case 13:			
            if(!add) {
                field.set('value', container.getElement('.selected').get('html'));
            } else {
                var value = container.getElement('.selected').get('html');
                var fieldValue = field.get('value');
                if(fieldValue.indexOf(',') != -1)
                    field.set('value', fieldValue.substr(0, fieldValue.lastIndexOf(',')+1).trim() + value);
                else
                    field.set('value', value);
            }
            emptyChildren(container);
            break;
        case 27:
            emptyChildren(container);
            break;
        case 38:
            if(container.getElement('.selected') != container.getFirst()){
                var previous = container.getElement('.selected').getPrevious();
                container.getElement('.selected').removeClass('selected');
                previous.addClass('selected');
            }
            break;
        case 40:
            if(container.getElement('.selected') != container.getLast()){
                var next = container.getElement('.selected').getNext();
                container.getElement('.selected').removeClass('selected');
                next.addClass('selected');
            }
            break;
        default:
            
            emptyChildren(container);
            var query = field.get('value').toLowerCase();
            if(query.length - (add ? query.lastIndexOf(',') - 1 : 0 ) < 3)
                return;
            elmts = eval(JSON.stringify(Array.from(elmts)));
            Array.each(elmts,function(el){
                if(add && query.lastIndexOf(',') != -1)
                    query = query.substr(query.lastIndexOf(',')+1).trim();                                
                if(el.toLowerCase().indexOf(query) != -1) {
                    new Element('p', {
                        'html': el
                    }).inject(container);				
                }
            });
            if(container.childNodes.length > 0)
                container.firstChild.addClass('selected');
            break;            
    }    
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