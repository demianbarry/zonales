if(typeof zUtilsJS === 'undefined' || !zUtilsJS)
document.write('<script type="text/javascript" src="../components/com_zonales/utils.js"></script>');

/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

var proxy = 'curl_proxy.php?host='+host+'&port='+port+'&ws_path=';
var servletUri = 'ZCrawlSources';

function addCriterios() {
    $('resultslist_content').set('style', 'display:block');
}

function getAllConfigs(){
    var url = servletUri + "/getZGram?id=all";
    var urlProxy = proxy + encodeURIComponent(url);
    var configs_table = new Element('table', {
        'id' : 'resultTable'
    }).addClass('resultTable').inject($('resultslist_content'));
    var reqId = new Request.JSON({
        url: urlProxy,
        method: 'get',
        onSuccess: function(jsonObj, text) {
            makeTable(jsonObj);

			
        },
        // Our request will most likely succeed, but just in case, we'll add an
        // onFailure method which will let the user know what happened.
        onError: function(text, error) {
            alert(error + ' - ' + text);
        }

    }).send();
}

function searchConfigs(){

    var query = '';
    query += ($('getZones').value.length >0 ?"localidad:'"+$('getZones').value.replace(/ /g, "%20") +"'" : "");
    query += ($('getFuente').options[$('getFuente').selectedIndex].value.toLowerCase() != 'all' ? (query.length >0 ? "," : "")+"fuente:'"+$('getFuente').options[$('getFuente').selectedIndex].value.toLowerCase() + "'" : "");
    query += ($('getEstado').options[$('getEstado').selectedIndex].value.toLowerCase() != 'all' ? (query.length >0 ? "," : "")+"estado:'"+$('getEstado').options[$('getEstado').selectedIndex].value.toLowerCase() + "'" : "");
	
    query += ($('getTags').value.length > 0 ? (query.length > 0 ? "," : "")+"tags:"+ JSON.encode($('getTags').value.split(',')) : "");	
	

    var url = servletUri + "/getZGram?filtros={"+query+"}";
    var urlProxy = proxy + encodeURIComponent(url);
    
    var reqId = new Request.JSON({
        url: urlProxy,
        method: 'get',
        onSuccess: function(jsonObj, text) {
            makeTable(jsonObj);
			
        },
        // Our request will most likely succeed, but just in case, we'll add an
        // onFailure method which will let the user know what happened.
        onError: function(text, error) {
            alert(error + ' - ' + text);
        }

    }).send();
}
function makeTable(jsonObj){

    $('resultslist_content').empty();

    var configs_table = new Element('table', {
        'id' : 'resultTable'
    }).addClass('resultTable').inject($('resultslist_content'));	
    var config_title_tr = new Element('tr', {
        'class': 'tableRowHeader'
    }).inject(configs_table);
    new Element('td', {
        'html' : 'Zona'
    }).inject(config_title_tr);
    new Element('td', {
        'html' : 'Fuente'
    }).inject(config_title_tr);
    new Element('td', {
        'html' : 'Tag'
    }).inject(config_title_tr);
    new Element('td', {
        'html' : 'Desc'
    }).inject(config_title_tr);
    new Element('td', {
        'html' : 'Estado'
    }).inject(config_title_tr);
    new Element('td', {
        'html' : 'Modificado'
    }).inject(config_title_tr);
    var checktd = new Element('td').inject(config_title_tr);	
    var checkbox = new Element ('input',{
        'type':'checkbox'		
    }).inject(checktd).addEvent('click', function(){		
        $$("table#resultTable.resultTable tr.tableRow td input").each(function(check){
            check.checked = checkbox.checked;
        });
    });
    new Element('td', {
        'html' : 'Editar'
    }).inject(config_title_tr);	

    jsonObj.each(function(config){
        var tags = config.tags.join(',');
        var config_title_tr = new Element('tr', {
            'class': 'tableRow'
        }).inject(configs_table);
        new Element('td', {
            'html' : config.localidad
        }).inject(config_title_tr);
        new Element('td', {
            'html' : config.fuente
        }).inject(config_title_tr);
        new Element('td', {
            'html' : tags
        }).inject(config_title_tr);
        new Element('td', {
            'html' : typeof config.descripcion != 'undefined' ? config.descripcion :'Sin Descipcion'
        }).inject(config_title_tr);
        new Element('td', {
            'html' : config.estado
        }).inject(config_title_tr);
        new Element('td', {
            'html' : config.modificado ? new Date(parseInt(config.modificado)) :'Sin Fecha'
        }).inject(config_title_tr);

        var checktd = new Element('td').inject(config_title_tr);
        new Element ('input',{
            'type':'checkbox'
        }).inject(checktd);
        var meta = Object.clone(config);
        delete meta._id;
        delete meta.cod;
        delete meta.msg;
        delete meta.verbatim;
        delete meta.estado;        
        new Element ('input',{
            'type':'hidden',
            'html': JSON.encode(meta)
        }).inject(checktd);		
        var editbutton = new Element('td').inject(config_title_tr);
        new Element ('input',{
            'type':'submit',
            'value':'Editar',
            'name':config._id.$oid,
            'onclick':"window.location.href = 'extractUtil.php?id="+config._id.$oid+"';"
        }).inject(editbutton);
								
        //new Element('td', {'html' : config.estado}).inject(config_title_tr);//modificado
				
        tags = tags.toLowerCase().replace(/ /g,'_');				
        config_title_tr.addClass(config.fuente).addClass(tags.replace(/,/g, ' ')).addClass(config.descripcion).addClass(config.estado).addClass(config.modificado);
    });
}

function extractFromChecked(){
    $$('input[type=checkbox]:checked').each(function(checkbox){
        if(checkbox.getNext() != null)
            getExtractURL(checkbox.getNext().get('html'));
    });
}

function applyFilters(){	
    $('resultTable').getChildren('tr').each(function(el){		
        if(($('getEstado').options[$('getEstado').selectedIndex].value.toLowerCase() == 'all' || el.hasClass($('getEstado').options[$('getEstado').selectedIndex].value.toLowerCase()))
            && ($('getFuente').options[$('getFuente').selectedIndex].value.toLowerCase() == 'all' || el.hasClass($('getFuente').options[$('getFuente').selectedIndex].value.toLowerCase()))
            && ($('getZones').get('value').length == 0 || $('getZones').get('value').toLowerCase().split(',').some(function(elem, index){
                return el.hasClass(elem)
            }))
            && ($('getTags').get('value').length == 0 || $('getTags').get('value').toLowerCase().split(',').some(function(elem, index){
                return el.hasClass(elem)
            }))
            || el.hasClass('tableRowHeader'))		
            el.setStyle('display', '');
        else
            el.setStyle('display', 'none');
    });	
}
