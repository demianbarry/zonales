window.addEvent('domready', function() {
    getPluginTypes();
    getAllConfig();
});

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
    var url = "/getConfig?name=all";
    var urlProxy = 'curl_proxy.php?host=localhost&port=8080&ws_path=' + encodeURIComponent(url);
    var reqId = new Request.JSON({
        url: urlProxy,
        method: 'get',
        onRequest: function(){
        },
        onSuccess: function(jsonObj) {
            var configs_table = new Element('table').addClass('resultTable').inject($('results_content'));
            var config_title_tr = new Element('tr', {'style': 'background-color: lightGreen'}).inject(configs_table);
            new Element('td', {'html' : 'Crawl Config Name'}).inject(config_title_tr);
            new Element('td', {'html' : 'Crawl Config URI'}).inject(config_title_tr);
            new Element('td', {'html' : 'Publish'}).inject(config_title_tr);
            new Element('td', {'html' : 'Edit / remove'}).inject(config_title_tr);
            jsonObj.each(function(config) {
                var config_tr = new Element('tr').inject(configs_table);
                new Element('td', {'html' : config.name}).inject(config_tr);
                new Element('td', {'html' : config.uri}).inject(config_tr);
                var config_publish_td = new Element('td').inject(config_tr);
                var publish = false;
                if (config.state == "Publicada") {
                    publish = true;
                }
                new Element('input', {'type' : "checkbox", 'checked' : publish}).inject(config_publish_td);
                var config_edit_remove_td = new Element('td').inject(config_tr);
                new Element('img', {'width' : '16', 'height' : '16', 'border': '0', 'alt': 'Edit', 'src': 'addedit.png'}).inject(config_edit_remove_td);
                new Element('img', {'width' : '16', 'height' : '16', 'border': '0', 'alt': 'Edit', 'src': 'publish_x.png'}).inject(config_edit_remove_td);
            });
        },

        // Our request will most likely succeed, but just in case, we'll add an
        // onFailure method which will let the user know what happened.
        onFailure: function(){

        }

    }).send();
}

function getPluginTypes(){
    var url = "/getPluginTypes";
    var urlProxy = 'curl_proxy.php?host=localhost&port=8080&ws_path=' + encodeURIComponent(url);
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
