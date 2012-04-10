//document.write('<script type="text/javascript" src="utils.js"></script>');

/*
 *  <field name="indexTime" type="date" stored="true" indexed="true" default="NOW-3HOURS"/>
    <field name="source" type="text_general" stored="false" indexed="true"/>
    <field name="id" type="string" stored="true" indexed="true" required="true"/>
    <field name="fromUserName" type="string" stored="false" indexed="true" />
    <field name="fromUserCategory" type="string" stored="false" indexed="true" />
    <field name="fromUserId" type="string" stored="false" indexed="true" />
    <field name="fromUserUrl" type="string" stored="false" indexed="true" />
    <field name="title" type="text_general" stored="false" indexed="true"/>
    <field name="text" type="text_general" stored="false" indexed="true"/>
    <field name="created" type="date" stored="false" indexed="true"/>
    <field name="modified" type="date" stored="false" indexed="true"/>
    <field name="relevance" type="int" stored="false" indexed="true"/>
    <field name="tags" type="string" stored="false" indexed="true" multiValued="true"/>
    <field name="zone" type="string" stored="false" indexed="true" />
    <field name="state" type="string" stored="false" indexed="true" />
    <field name="verbatim" type="string" stored="true" indexed="false"/>
 *
 **/

var zContent = new Content(), searching = false ;

function User(){
    this.name = '';
    this.category = '';
    this.id = '';
    this.url = '';
}

function Zone(){
    this.id = '';
    this.name = '';
    this.type = '';
    this.extendedString = '';
}

function Content(id){
    this.source = 'Zonales';
    this.id = id ? id : null;
    this.fromUser = new User();    
    this.title = '';
    this.text = '';
    this.created = (new Date()).getTime();
    this.modified = '';    
    this.relevance = 0;
    this.tags = new Array();
    this.zone = new Zone();
    this.state = 'created';    
}

function SolrContent(id){
    this.source = '';
    this.id = id;
    this.fromUserName = '';
    this.fromUserCategory = '';
    this.fromUserId = '';
    this.fromUserUrl = '';
    this.title = '';
    this.text = '';
    this.created = zirClient.getSolrDate(new Date());
    this.modified = '';    
    this.relevance = 0;
    this.tags = '';
    this.zone = '';
    this.state = 'created';    
}

function contentToSolr(content){
    var solrContent = new SolrContent(content.id);
    solrContent.source = content.source;
    solrContent.fromUserName = content.fromUser.name;
    solrContent.fromUserCategory = content.fromUser.catgory;
    solrContent.fromUserId = content.fromUser.id;
    solrContent.fromUserUrl = content.fromUser.url;
    solrContent.title = content.title;
    solrContent.text = content.text;
    solrContent.created = content.created;
    solrContent.modified = content.modified;
    solrContent.relevance = content.relevance;
    solrContent.tags = content.tags;
    solrContent.state = content.state;    
    return solrContent;
} 

function refreshContent(){
    if(zContent === undefined){
        zContent = new Content();
    }
    if(zContent.fromUser === undefined){
        zContent.fromUser = new User();
    }
    zContent.fromUser.name = $('fromUserName').value;
    zContent.fromUser.id = $('fromUserId').value;
    
    zContent.id = $('id').value;
    zContent.title = $('title').value;    
    zContent.text = CKEDITOR.instances.text.getData();
    if (!$('created').value) {
        $('created').value = getReadableDate(new Date());
    }
    zContent.created = (new Date($('created').value.substr(3,2) + "/" + $('created').value.substr(0,2) + "/" + $('created').value.substr(6))).getTime();
    //zContent.modified = (new Date()).getTime();
    if (!$('modified').value) {
        $('modified').value = getReadableDate(new Date());
    }
    zContent.modified = (new Date($('modified').value.substr(3,2) + "/" + $('modified').value.substr(0,2) + "/" + $('modified').value.substr(6))).getTime();
    zContent.tags = $('tags').value.split(',');

    if(zContent.zone === undefined){
        zContent.zone = new Zone();
    }
    zContent.zone.extendedString = $('zone').value;
}

function refreshForm(){
    //alert(JSON.encode(zContent));
    $('fromUserName').value = zContent.fromUser ? zContent.fromUser.name : '';
    $('fromUserId').value = zContent.fromUser ? zContent.fromUser.id : '';
    $('id').value = zContent.id;
    $('title').value = zContent.title ? zContent.title : '';    
    $('text').value = zContent.text ? zContent.text : '';
    $('created').value = getReadableDate(zContent.created ? new Date(parseInt(zContent.created)) : new Date());
    $('modified').value = getReadableDate(zContent.modified ? new Date(parseInt(zContent.modified)) : new Date());
    $('tags').value = zContent.tags ? zContent.tags.join(',') : '';
    $('zone').value = zContent.zone ? zContent.zone.extendedString : '';
}

function getContent(id){
    var url = '/solr/select?qt=zonalesContent&wt=json&q=id:'+encodeURIComponent(id);
    var urlProxy = '/curl_proxy.php';
    new Request.JSON({
        url: urlProxy,
        method: 'get',
        data: {
            'host': host,
            'port': port,
            'ws_path':url
        },        
        onRequest: function(){
        },
        onSuccess: function(response) {            
            var doc = response.response.docs[0];
            zContent = JSON.decode(doc.verbatim);
            if(!zContent.id)
                zContent.id = doc.id;
            refreshForm();
            workflow();
        },
        // Our request will most likely succeed, but just in case, we'll add an
        // onFailure method which will let the user know what happened.
        onFailure: function(){            
        }
    }).send();
}

function saveContent(){
    refreshContent();    
    var url = '/ZCrawlScheduler/indexPosts?url=http://localhost:38080/solr&doc='+encodeURIComponent(JSON.encode(zContent));
    var urlProxy = '/curl_proxy.php';
    new Request.JSON({
        url: urlProxy,
        method: 'post',
        data: {
            'host': host,
            'port': port,
            'ws_path':url
        },        
        onRequest: function(){
        },
        onSuccess: function(response) {			
            //        commit();
            if(response && response.length != 0){
                if(response.id && response.id.length > 0){
                    zContent.id = response.id;
                    refreshForm();
                    alert("Se guard? correctamente el documento con el ID "+zContent.id);                    
                } else {
                    alert("Ocurri? un error al intentar guardar el documento: "+response);
                    zContent.state = 'created';
                }
            }
            workflow();
        },
        // Our request will most likely succeed, but just in case, we'll add an
        // onFailure method which will let the user know what happened.
        onFailure: function(){            
        }
    }).send();
}

function commit(){
    var url = '/solr/update/json?commit=true';
    var urlProxy = 'curl_proxy.php';
    new Request({
        url: encodeURIComponent(urlProxy),
        method: 'post',
        data: {
            'host': host,
            'port': port,
            'ws_path':url
        },        
        onRequest: function(){            
        },
        onSuccess: function(response) {            
        },
        // Our request will most likely succeed, but just in case, we'll add an
        // onFailure method which will let the user know what happened.
        onFailure: function(){            
        }
    }).send();
}

function publishContent(publish){
    zContent.state = publish ? 'published' : 'saved';
     zContent.modified = (new Date()).getTime();
    saveContent();
}

function voidContent(){
    zContent.state = 'void';
    saveContent();
}

function makeContentTable(jsonObj, container){

    if(typeOf(container) == 'element')
        container.empty();

    var configs_table = new Element('table', {
        'id' : 'resultTable'
    }).addClass('resultTable').inject(container);

    var filters_tr = new Element('tr', {
        'class': 'tableRow'
    }).inject(configs_table);
    var titleFilterTd = new Element('td').inject(filters_tr);
    new Element('input', {
        'id': 'titleFilter',
        'type': 'text'
    }).inject(titleFilterTd);
    var statusFilterTd = new Element('td').inject(filters_tr);
    var statusSelect = new Element('select', {
        'id': 'statusFilter'
    }).inject(statusFilterTd);
    new Element('option', {
        'value' : 'all',
        'html' : 'Todos'
    }).inject(statusSelect);
    new Element('option', {
        'value' : 'new',
        'html' : 'Nuevo'
    }).inject(statusSelect);
    new Element('option', {
        'value' : 'saved',
        'html' : 'Guardado'
    }).inject(statusSelect);
    new Element('option', {
        'value' : 'published',
        'html' : 'Publicado'
    }).inject(statusSelect);
    new Element('option', {
        'value' : 'unpublished',
        'html' : 'Despublicado'
    }).inject(statusSelect);
    new Element('option', {
        'value' : 'void',
        'html' : 'Anulado'
    }).inject(statusSelect);
    var zoneFilterTd = new Element('td').inject(filters_tr);
    new Element('input', {
        'id': 'zoneFilter',
        'type': 'text',
        'onkeyup' : "populateOptions(event, this, false, extendedStrings);"
    }).inject(zoneFilterTd);
    var tagFilterTd = new Element('td').inject(filters_tr);
    new Element('input', {
        'id': 'tagFilter',
        'type': 'text',
        'onkeyup' : "populateOptions(event, this, false, zTags);"
    }).inject(tagFilterTd);
    var createdFilterTd = new Element('td').inject(filters_tr);
    var modifiedFilterTd = new Element('td').inject(filters_tr);
    var chkFilterTd = new Element('td').inject(filters_tr);
    var filterButtonTd = new Element('td').inject(filters_tr);
    new Element ('input',{
        'type':'submit',
        'value':'Filtrar',
        'id': 'filterContenButton',
        'onclick': "filterContent();"
    }).inject(filterButtonTd);

    var config_title_tr = new Element('tr', {
        'class': 'tableRowHeader'
    }).inject(configs_table);
    new Element('td', {
        'html' : 'Título'
    }).inject(config_title_tr);
    new Element('td', {
        'html' : 'Estado'
    }).inject(config_title_tr);
    new Element('td', {
        'html' : 'Zona'
    }).inject(config_title_tr);    
    new Element('td', {
        'html' : 'Tags'
    }).inject(config_title_tr);        
    new Element('td', {
        'html' : 'Creado'
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

    jsonObj.each(function(post){
        var id = post.id;
        post = eval("("+post.verbatim+")");
        var config_title_tr = new Element('tr', {
            'class': 'tableRow'
        }).inject(configs_table);
        new Element('td', {
            'html' : post.title
        }).inject(config_title_tr);
        new Element('td', {
            'html' : post.state
        }).inject(config_title_tr);
        new Element('td', {
            'html' : post.zone.extendedString
        }).inject(config_title_tr);
        new Element('td', {
            'html' : post.tags.join(',')
        }).inject(config_title_tr);
        new Element('td', {
            'html' : getReadableDate(new Date(parseInt(post.created)))
        }).inject(config_title_tr);
        new Element('td', {
            'html' : getReadableDate(new Date(parseInt(post.modified)))
        }).inject(config_title_tr);        
        var checktd = new Element('td').inject(config_title_tr);
        new Element ('input',{
            'type':'checkbox'
        }).inject(checktd);
        
        var editbutton = new Element('td').inject(config_title_tr);
        new Element ('input',{
            'type':'submit',
            'value':'Editar',
            'name':post.id,
            'onclick':"window.location.href = 'index.php?option=com_zonales&task=zonal&view=editor&tmpl=component_edit&id="+id+"';"
        }).inject(editbutton);
        				
    /*tags = tags.toLowerCase().replace(/ /g,'_');				
        config_title_tr.addClass(post.source).addClass(tags.replace(/,/g, ' ')).addClass(post.descripcion).addClass(post.estado).addClass(post.modificado);*/
    });
}

function loadPosts(container){
    if(searching)
        return;
    urlSolr = "/solr/select?indent=on&version=2.2&start=0&fl=*%2Cscore&rows=200&qt=zonalesContent&sort=max(modified,created)+desc&wt=json&explainOther=&hl.fl=&q=source:(Zonales)";
    var urlProxy = '/curl_proxy.php?host='+(host ? host : "localhost")+'&port='+(port ? port : "38080")+'&ws_path=' + encodeURIComponent(urlSolr);

    new Request.JSON({
        url: urlProxy,
        method: 'get',
        onRequest: function(){
            //status.set('innerHTML', 'Recuperando posts...');
            searching = true;
        },
        onComplete: function(jsonObj) {            
            searching = false;            
        },
        onSuccess: function(jsonObj) {
            // actualizar pagina            
            if(typeof jsonObj != 'undefined'){
                makeContentTable(jsonObj.response.docs, container);
            }
        },
        // Our request will most likely succeed, but just in case, we'll add an
        // onFailure method which will let the user know what happened.
        onFailure: function(){
        //status.set('innerHTML', 'Twitter: The request failed.');
        }
    }).send();
}

function workflow(buttons){
    $$('#buttons input[type=button]').each(function(submit){
        submit.setStyle('display','none');
    });
    if(buttons)
        buttons.each(function(button){
            if($(button))
                $(button).setStyle('display','inline');
        });
    if(zContent.state == 'created' || zContent.state == 'saved'){
        $('guardarButton').setStyle('display','inline');   
    } 
    if(zContent.state == 'saved'){
        $('publicarButton').setStyle('display','inline');   
        $('anularButton').setStyle('display','inline');   
    }
    if(zContent.state == 'published'){
        $('despublicarButton').setStyle('display','inline');   
        $('anularButton').setStyle('display','inline');   
    }
    
    $('volverButton').setStyle('display','inline');
}