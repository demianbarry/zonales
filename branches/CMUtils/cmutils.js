window.addEvent('domready', function() {
    getPluginTypes();
    getAllConfig();
});

var cantParams = 0;
var cantPlugins = 0;
var params = new Array();
var plugins = new Array();
var configEdited;
var proxy = 'curl_proxy.php?host=localhost&port=8080&ws_path=';
var servletUri = '';

function getFacebookUserId() {
    if ($('getIdUsername').get('value') != "") {
        var reqId = new Request({
            url: "/fb/index.php?getId=" + $('getIdUsername').get('value'),
            method: 'get',
            onRequest: function(){
                $('results_content').empty();
                $('results_content').addClass('loading');
            },
            onSuccess: function(result) {
                $('results_content').removeClass('loading');
                new Element('p',{
                    'html': result
                }).inject($('results_content'));
            },

            // Our request will most likely succeed, but just in case, we'll add an
            // onFailure method which will let the user know what happened.
            onFailure: function(){

            }

        }).send();
    } else {
        $('results_content').empty();
        new Element('p',{
            'html': "No username specified"
        }).inject($('results_content'));
    }
}

function getAllConfig(){
    var url = servletUri + "/getConfig?name=all";
    var urlProxy = proxy + encodeURIComponent(url);
    var configs_table = new Element('table', {'id' : 'resultTable'}).addClass('resultTable').inject($('list_content'));
    var reqId = new Request.JSON({
        url: urlProxy,
        method: 'get',
        onRequest: function(){
            var config_title_tr = new Element('tr', {'style': 'background-color: lightGreen'}).inject(configs_table);
            new Element('td', {'html' : 'Crawl Config Name'}).inject(config_title_tr);
            new Element('td', {'html' : 'Crawl Config URI'}).inject(config_title_tr);
            new Element('td', {'html' : 'Publish'}).inject(config_title_tr);
            new Element('td', {'html' : 'State'}).inject(config_title_tr);
            new Element('td', {'html' : 'Edit / remove'}).inject(config_title_tr);
        },
        onSuccess: function(jsonObj) {
            jsonObj.each(function(config) {
                var config_tr = new Element('tr', {'id' : 'cl_' + config.name}).inject(configs_table);
                new Element('td', {'html' : config.name, 'id' : 'cl_' + config.name + '_name'}).inject(config_tr);
                new Element('td', {'html' : config.uri, 'id' : 'cl_' + config.name + '_uri'}).inject(config_tr);
                var config_publish_td = new Element('td').inject(config_tr);
                var publish = false;
                if (config.state == "Published") {  //Ver como arreglar esta parte para que no sea duro
                    publish = true;
                }
                new Element('input', {'id' : 'cl_' + config.name + '_pub', 'type' : "checkbox", 'checked' : publish, 'onclick': 'publishConfig("' + config.name + '")'}).inject(config_publish_td);
                new Element('td', {'html' : config.state, 'id' : 'cl_' + config.name + '_state'}).inject(config_tr);
                var config_edit_remove_td = new Element('td').inject(config_tr);
                new Element('img', {'id' : 'cl_' + config.name + '_edit', 'width' : '16', 'height' : '16', 'border': '0', 'alt': config.name, 'title': 'Edit', 'src': 'addedit.png', 'onclick' : 'getConfig("' + config.name + '")'}).inject(config_edit_remove_td);
                new Element('img', {'id' : 'cl_' + config.name + '_rem', 'width' : '16', 'height' : '16', 'border': '0', 'alt': config.name, 'title': 'Remove', 'src': 'publish_x.png', 'onclick' : 'delConfig("' + config.name + '")'}).inject(config_edit_remove_td);
            });
        },

        // Our request will most likely succeed, but just in case, we'll add an
        // onFailure method which will let the user know what happened.
        onFailure: function(){

        }

    }).send();
}

function addConfig() {
    clearEdit();
    $('saveConfigButton').set('value', 'Save');
    $('edit_content').set('style', 'display:block');
}

function saveConfig() {
    if ($('saveConfigButton').get('value') == 'Save') {
        setConfig();
    }
    if ($('saveConfigButton').get('value') == 'Update') {
        updateConfig();
    }
}

function getConfig(name) {
    clearEdit();
    $('saveConfigButton').set('value', 'Update');
    $('edit_content').set('style', 'display:block');
    configEdited = name;
    var url = servletUri + "/getConfig?name=" + name;
    var urlProxy = proxy + encodeURIComponent(url);
    var reqId = new Request.JSON({
        url: urlProxy,
        method: 'get',
        onRequest: function(){

        },
        onSuccess: function(jsonObj) {
            $('getNameFuente').set('value', jsonObj.name);
            $('getUriFuente').set('value', jsonObj.uri);
            if (jsonObj.params) {
                jsonObj.params.each(function(param) {
                   addParam(param.name, param.required);
                });
            }
            if (jsonObj.plugins) {
                jsonObj.plugins.each(function(plugin) {
                   addPlugin(plugin.class_name, plugin.type);
                });
            }
        },

        // Our request will most likely succeed, but just in case, we'll add an
        // onFailure method which will let the user know what happened.
        onFailure: function(){

        }

    }).send();
}

function getPluginTypes(){
    var url = servletUri + "/getPluginTypes";
    var urlProxy = proxy + encodeURIComponent(url);
    var reqId = new Request.JSON({
        url: urlProxy,
        method: 'get',
        onRequest: function(){

        },
        onSuccess: function(jsonObj) {
            jsonObj.each(function(plugintype) {
                new Element('option', {'html': plugintype}).inject($('plugintypes'));
            });
        },

        // Our request will most likely succeed, but just in case, we'll add an
        // onFailure method which will let the user know what happened.
        onFailure: function(){

        }

    }).send();

}

function addParam(name, required){
    if (cantParams == 0) {
        var params_table = new Element('table', {'id' : 'params_table'}).addClass('configTable').inject($('configParams'));
                var params_title_tr = new Element('tr', {
                    'style': 'background-color: lightGreen'
                }).inject(params_table);
                new Element('td', {
                    'html' : 'Name'
                }).inject(params_title_tr);
                new Element('td', {
                    'html' : 'Required'
                }).inject(params_title_tr);
                new Element('td', {
                    'html' : 'Remove'
                }).inject(params_title_tr);
    }
    var param_line = new Element('tr', {'id' : 'pl' + cantParams}).inject($('params_table'));
    new Element('td', {'html': name}).inject(param_line);
    new Element('td', {'html': required ? 'Si' : 'No'}).inject(param_line);
    var removeParam_td = new Element('td').inject(param_line);
    new Element('img', {'width' : '16', 'height' : '16', 'border': '0', 'alt': cantParams, 'title' : 'Remove Param', 'src': 'publish_x.png', 'onclick' : 'removeParam('+ cantParams + ')'}).inject(removeParam_td);
    var param = new Array();
    param[0] = name;
    param[1] = required;
    params[cantParams] = param;
    cantParams++;
    $('getNameParam').set('value', '');
    $('getReq').set('checked', false);
}

function removeParam(paramLine){
    $('params_table').removeChild($('pl'+paramLine));
    delete params[paramLine];
}

function addPlugin(name, type){
    if (cantPlugins == 0) {
        var plugins_table = new Element('table', {'id' : 'plugins_table'}).addClass('configTable').inject($('configPlugins'));
                var plugin_title_tr = new Element('tr', {
                    'style': 'background-color: lightGreen'
                }).inject(plugins_table);
                new Element('td', {
                    'html' : 'Name'
                }).inject(plugin_title_tr);
                new Element('td', {
                    'html' : 'Type'
                }).inject(plugin_title_tr);
                new Element('td', {
                    'html' : 'Remove'
                }).inject(plugin_title_tr);
    }
    var plugin_line = new Element('tr', {'id' : 'pll' + cantPlugins}).inject($('plugins_table'));
    new Element('td', {'html': name}).inject(plugin_line);
    new Element('td', {'html': type}).inject(plugin_line);
    var removePlugin_td = new Element('td').inject(plugin_line);
    new Element('img', {'width' : '16', 'height' : '16', 'border': '0', 'alt': cantPlugins, 'title' : 'Remove Plugin', 'src': 'publish_x.png', 'onclick' : 'removePlugin('+ cantPlugins + ')'}).inject(removePlugin_td);
    var plugin = new Array();
    plugin[0] = name;
    plugin[1] = type;
    plugins[cantPlugins] = plugin;
    cantPlugins++;
    $('getClassName').set('value', '');
}

function removePlugin(pluginLine){
    $('plugins_table').removeChild($('pll'+pluginLine));
    delete plugins[pluginLine];
}

function setConfig(){
    if (($('getNameFuente').get('value') != "") && ($('getUriFuente').get('value') != "") && plugins.length > 0){
        var url = servletUri + "/setConfig?name="+$('getNameFuente').get('value')+"&uri="+$('getUriFuente').get('value');
        if (params.length > 0) {
            url += '&params=';
            for (i = 0; i < params.length; i++) {
                if (params[i] != null) {
                    url += params[i][0] + ',' + params[i][1] + ';';
                }
            }
        }
        if (plugins.length > 0) {
            url += '&plugins=';
            for (i = 0; i < plugins.length; i++) {
                if (plugins[i] != null) {
                    url += plugins[i][0] + ',' + plugins[i][1] + ';';
                }
            }
        }
        var urlProxy = proxy + encodeURIComponent(url);

        var reqId = new Request.JSON({
            url: urlProxy,
            method: 'get',
            onRequest: function(){
            },
            onSuccess: function(jsonObj) {
                if (jsonObj.cod == 100) {
                    $('list_content').removeChild($('resultTable'));
                    getAllConfig();
                    /*var name = $('getNameFuente').get('value');
                    var config_tr = new Element('tr', {'id' : 'cl_' + name}).inject($('resultTable'));
                    new Element('td', {'html' : name, 'id' : 'cl_' + name + '_name'}).inject(config_tr);
                    new Element('td', {'html' : $('getUriFuente').get('value'), 'id' : 'cl_' + name + '_uri'}).inject(config_tr);
                    var config_publish_td = new Element('td').inject(config_tr);
                    new Element('input', {'type' : "checkbox", 'id' : 'cl_' + name + '_pub', 'checked' : false, 'onclick': 'publishConfig("' + name + '")'}).inject(config_publish_td);
                    var config_edit_remove_td = new Element('td').inject(config_tr);
                    new Element('img', {'width' : '16', 'height' : '16', 'border': '0', 'id' : 'cl_' + name + '_edit', 'alt': 'Edit', 'title': 'Edit', 'src': 'addedit.png', 'onclick' : 'getConfig("' + name + '")'}).inject(config_edit_remove_td);
                    new Element('img', {'width' : '1', 'height' : '16', 'border': '0', 'id' : 'cl_' + name + '_rem', 'alt': 'Remove', 'title': 'Remove', 'src': 'publish_x.png', 'onclick' : 'delConfig("' + name + '")'}).inject(config_edit_remove_td);
                    */
                    alert("Configuración guardada");
                    clearEdit();
                } else {
                    alert("No se pudo agregar la nueva configuración");
                }
            },

            // Our request will most likely succeed, but just in case, we'll add an
            // onFailure method which will let the user know what happened.
            onFailure: function(){

            }

        }).send();
    } else {
        alert("Faltan parámetros requeridos");
    }
}

function publishConfig(name) {
    var publish = $('cl_' + name + '_pub').get('checked');
    var url = servletUri + "/publishConfig?name="+name+"&publish=" + publish;
    var urlProxy = proxy + encodeURIComponent(url);

    var reqId = new Request.JSON({
        url: urlProxy,
        method: 'get',
        onRequest: function(){
        },
        onSuccess: function(jsonObj) {
            if (jsonObj.cod == 100) {
                if (publish) {
                    $('cl_' + name + '_state').set('html', 'Published')
                } else {
                    $('cl_' + name + '_state').set('html', 'Unpublished')
                }
            } else {
                $('cl_' + name + '_pub').set('checked', false);
                alert("No se pudo publicar la configuración. Error: "+ jsonObj.msg);
            }
        },

        // Our request will most likely succeed, but just in case, we'll add an
        // onFailure method which will let the user know what happened.
        onFailure: function(){

        }

    }).send();
}

function clearEdit() {
    $('getNameFuente').set('value', "");
    $('getUriFuente').set('value', "");
    $('getClassName').set('value', '');
    $('getNameParam').set('value', '');
    $('getReq').set('checked', false);
    if (cantParams > 0) {
        $('configParams').removeChild($('params_table'));
    }
    if (cantPlugins > 0) {
        $('configPlugins').removeChild($('plugins_table'));
    }
    params.empty();
    plugins.empty();
    cantParams = 0;
    cantPlugins = 0;
    $('edit_content').set('style', 'display:none')
}

function updateConfig(){
    if (($('getNameFuente').get('value') != "") && ($('getUriFuente').get('value') != "") && plugins.length > 0){
        var url = servletUri + "/updateConfig?name="+configEdited+"&newname="+$('getNameFuente').get('value')+"&newuri="+$('getUriFuente').get('value');
        if (params.length > 0) {
            url += '&newparams=';
            for (i = 0; i < params.length; i++) {
                if (params[i] != null) {
                    url += params[i][0] + ',' + params[i][1] + ';';
                }
            }
        }
        if (plugins.length > 0) {
            url += '&newplugins=';
            for (i = 0; i < plugins.length; i++) {
                if (plugins[i] != null) {
                    url += plugins[i][0] + ',' + plugins[i][1] + ';';
                }
            }
        }
        var urlProxy = proxy + encodeURIComponent(url);

        var reqId = new Request.JSON({
            url: urlProxy,
            method: 'get',
            onRequest: function(){
            },
            onSuccess: function(jsonObj) {
                if (jsonObj.cod == 100) {
                    $('list_content').removeChild($('resultTable'));
                    getAllConfig();
                    /*var newname = $('getNameFuente').get('value');
                    $('cl_' + configEdited + '_name').set('html', newname);
                    $('cl_' + configEdited + '_uri').set('html', $('getUriFuente').get('value'));
                    $('cl_' + configEdited + '_pub').set('onclick', 'publishConfig("' + newname + '",' + false + ')');
                    $('cl_' + configEdited + '_edit').set('onclick', 'getConfig("' + newname + '")');
                    $('cl_' + configEdited + '_pub').set('onclick', 'delConfig("' + newname + ')');*/
                    alert("Configuración actualizada");
                    clearEdit();
                } else {
                    alert("No se pudo actualizar la configuración");
                }
            },

            // Our request will most likely succeed, but just in case, we'll add an
            // onFailure method which will let the user know what happened.
            onFailure: function(){

            }

        }).send();
    } else {
        alert("Faltan parámetros requeridos");
    }
}

function showFacebookUserProfile() {
    if ($('getProfileUserId').get('value') != "") {
        $('results_content').empty();
        window.open('http://www.facebook.com/profile.php?id=' + $('getProfileUserId').get('value'));
    //new Element('iframe',{'src': 'http://www.facebook.com/profile.php?id=' + $('getProfileUserId').get('value'), 'style': 'width: 100%;height: 100%'}).inject($('results_content'));
    } else {
        $('results_content').empty();
        new Element('p',{
            'html': "No UserId specified"
        }).inject($('results_content'));
    }
}

function showFacebookUserCommenters() {
    if ($('getCommentersUserId').get('value') != "") {
        var reqId = new Request.JSON({
            url: "/fb/index.php?getCommenters=true&users=" + $('getCommentersUserId').get('value'),
            method: 'get',
            onRequest: function(){
                $('results_content').empty();
                $('results_content').addClass('loading');
            },
            onSuccess: function(jsonObj) {
                $('results_content').removeClass('loading');
                var commenters_table = new Element('table').addClass('resultTable').inject($('results_content'));
                var commenter_title_tr = new Element('tr', {
                    'style': 'background-color: lightGreen'
                }).inject(commenters_table);
                new Element('td', {
                    'html' : 'Commenter ID'
                }).inject(commenter_title_tr);
                new Element('td', {
                    'html' : 'Commenter Name'
                }).inject(commenter_title_tr);
                new Element('td', {
                    'html' : 'Number of Comments'
                }).inject(commenter_title_tr);
                new Element('td', {
                    'html' : 'Category'
                }).inject(commenter_title_tr);
                jsonObj.commenters.each(function(commenter) {
                    var commenter_tr = new Element('tr').inject(commenters_table);
                    new Element('td', {
                        'html' : commenter.id
                        }).inject(commenter_tr);
                    var commenter_name = new Element('td').inject(commenter_tr);
                    new Element('a', {
                        'html': commenter.name,
                        'href' : commenter.url,
                        'onclick' : "javascrtipt:window.open(this.href);return false;"
                    }).inject(commenter_name);
                    new Element('td', {
                        'html' : commenter.cant
                        }).inject(commenter_tr);
                    new Element('td', {
                        'html' : commenter.category
                        }).inject(commenter_tr);
                });
            },

            // Our request will most likely succeed, but just in case, we'll add an
            // onFailure method which will let the user know what happened.
            onFailure: function(){

            }

        }).send();
    } else {
        $('results_content').empty();
        new Element('p',{
            'html': "No UserId specified"
        }).inject($('results_content'));
    }
}

function showFacebookCommentersByKeywords() {
    if ($('getCommentersKeywords').get('value') != "") {
        var reqId = new Request.JSON({
            url: "/fb/index.php?getCommenters=true&keywords=" + $('getCommentersKeywords').get('value'),
            method: 'get',
            onRequest: function(){
                $('results_content').empty();
                $('results_content').addClass('loading');
            },
            onSuccess: function(jsonObj) {
                $('results_content').removeClass('loading');
                var commenters_table = new Element('table').addClass('resultTable').inject($('results_content'));
                var commenter_title_tr = new Element('tr', {
                    'style': 'background-color: lightGreen'
                }).inject(commenters_table);
                new Element('td', {
                    'html' : 'Commenter ID'
                }).inject(commenter_title_tr);
                new Element('td', {
                    'html' : 'Commenter Name'
                }).inject(commenter_title_tr);
                new Element('td', {
                    'html' : 'Number of Comments'
                }).inject(commenter_title_tr);
                new Element('td', {
                    'html' : 'Category'
                }).inject(commenter_title_tr);
                jsonObj.commenters.each(function(commenter) {
                    var commenter_tr = new Element('tr').inject(commenters_table);
                    new Element('td', {
                        'html' : commenter.id
                        }).inject(commenter_tr);
                    var commenter_name = new Element('td').inject(commenter_tr);
                    new Element('a', {
                        'html': commenter.name,
                        'href' : commenter.url,
                        'onclick' : "javascrtipt:window.open(this.href);return false;"
                    }).inject(commenter_name);
                    new Element('td', {
                        'html' : commenter.cant
                        }).inject(commenter_tr);
                    new Element('td', {
                        'html' : commenter.category
                        }).inject(commenter_tr);
                });
            },

            // Our request will most likely succeed, but just in case, we'll add an
            // onFailure method which will let the user know what happened.
            onFailure: function(){

            }

        }).send();
    } else {
        $('results_content').empty();
        new Element('p',{
            'html': "No Keywords specified"
        }).inject($('results_content'));
    }
}

function showTwitterCommentersByKeywords() {
    if ($('getTwitterCommentersKeywords').get('value') != "") {
        var url = "/search.json?q=" + $('getTwitterCommentersKeywords').get('value') + "&result_type=mixed&rpp=100";
        var urlProxy = 'curl_proxy.php?host=search.twitter.com&port=80&ws_path=' + encodeURIComponent(url);
        var reqId = new Request.JSON({
            url: urlProxy,
            method: 'get',
            onRequest: function(){
                $('results_content').empty();
                $('results_content').addClass('loading');
            },
            onSuccess: function(jsonObj) {
                $('results_content').removeClass('loading');
                var commenters_table = new Element('table').addClass('resultTable').inject($('results_content'));
                var commenter_title_tr = new Element('tr', {
                    'style': 'background-color: lightGreen'
                }).inject(commenters_table);
                new Element('td', {
                    'html' : 'Commenter ID'
                }).inject(commenter_title_tr);
                new Element('td', {
                    'html' : 'Commenter Name'
                }).inject(commenter_title_tr);
                new Element('td', {
                    'html' : 'Text',
                    'colspan': "2"
                }).inject(commenter_title_tr);
                jsonObj.results.each(function(commenter) {
                    var commenter_tr = new Element('tr').inject(commenters_table);
                    new Element('td', {
                        'html' : commenter.from_user_id
                        }).inject(commenter_tr);
                    var commenter_name = new Element('td').inject(commenter_tr);
                    new Element('a', {
                        'html': commenter.from_user,
                        'href' : "http://twitter.com/#!/" + commenter.from_user,
                        'onclick' : "javascrtipt:window.open(this.href);return false;"
                    }).inject(commenter_name);
                    new Element('td', {
                        'html' : commenter.text,
                        'colspan': "2"
                    }).inject(commenter_tr);
                });
            },

            // Our request will most likely succeed, but just in case, we'll add an
            // onFailure method which will let the user know what happened.
            onFailure: function(){

            }

        }).send();
    } else {
        $('results_content').empty();
        new Element('p',{
            'html': "No Keywords specified"
        }).inject($('results_content'));
    }
}

function allCommenterChange() {
    if($('allCommenters').checked == true){
        $('commenters').set('disabled', true);
    } else {
        $('commenters').set('disabled', false);
    }
}

function generateQuery() {
    
    $('results_content').empty();

    if ($('city').get('value') == "") {
        new Element('p',{
            'html': "No city specified"
        }).inject($('results_content'));
        return;
    }

    if ($('source').get('value') == "") {
        new Element('p',{
            'html': "No source specified"
        }).inject($('results_content'));
        return;
    }

    if ($('includeUsers').get('value') == "" && $('includeKeywords').get('value') == "") {
        new Element('p',{
            'html': "No criteria specified"
        }).inject($('results_content'));
        return;
    }

    var query = 'extraer para la localidad "' + $('city').get('value') + '"';

    if ($('tags').get('value') != "") {
        query += ' asignando los tags ';
        var tags = $('tags').get('value').split(",");
        tags.each(function(tag) {
            query += '"' + tag + '",';
        });
        query = query.substring(0, query.length-1);
    }

    query += ' mediante la fuente ' + $('source').get('value');

    if ($('uriSource').get('value') != "") {
        query += ' ubicada en "' + $('uriSource').get('value') + '"';
    }

    query += ' a partir';

    if ($('includeUsers').get('value') != "") {
        var includeUsers = $('includeUsers').get('value').split(" ");
        includeUsers.each(function(includeUser) {
            query += ' del usuario "' + includeUser + '" y';
        });
        query = query.substring(0, query.length-2);
    }

    if ($('includeKeywords').get('value') != "") {
        if ($('includeUsers').get('value') != "") {
            query += ' y'
        }
        query += ' de las palabras'
        var includeKeywords = $('includeKeywords').get('value').split(",");
        includeKeywords.each(function(includeKeyword) {
            query += ' "' + includeKeyword + '",';
        });
        query = query.substring(0, query.length-1);
    }

    if ($('skipUsers').get('value') != "" || $('skipKeywords').get('value') != "") {
        query += ' pero no';
    }

    if ($('skipUsers').get('value') != "") {
        var skipUsers = $('skipUsers').get('value').split(" ");
        skipUsers.each(function(skipUser) {
            query += ' del usuario "' + skipUser + '" y';
        });
        query = query.substring(0, query.length-2);
    }

    if ($('skipKeywords').get('value') != "") {
        if ($('skipUsers').get('value') != "") {
            query += ' y'
        }
        query += ' de las palabras'
        var skipKeywords = $('skipKeywords').get('value').split(",");
        skipKeywords.each(function(skipKeyword) {
            query += ' "' + skipKeyword + '",';
        });
        query = query.substring(0, query.length-1);
    }

    //commenters allCommenters

    if ($('allCommenters').checked == true || $('commenters').get('value') != "") {
        query += ' incluye comentarios';
        if ($('allCommenters').checked == false && $('commenters').get('value') != "") {
            query += ' de los usuarios:'
            var commenters = $('commenters').get('value').split(" ");
            commenters.each(function(commenter) {
                query += ' "' + commenter + '",';
            });
            query = query.substring(0, query.length-1);
        }
    }

    if ($('usersBlackList').ckecked == true || $('keywordsBlackList').ckecked == true || $('minActions').get('value') != "") {
        query += ' y filtrando por';
    }
    if ($('usersBlackList').ckecked == true) {
        query += ' lista negra de usuarios';
    }
    if ($('keywordsBlackList').ckecked == true) {
        query += ' lista negra de palabras';
    }
    if ($('minActions').get('value') != "") {
        query += ' con al menos ' + $('minActions').get('value') + ' actions';
    }

    if ($('sourceTags').checked == true) {
        query += ' incluye los tags de la fuente';
    }

    query += '.';

    new Element('p',{
        'html': query
    }).inject($('results_content'));

}
