var zProxy = require('../services/zProxy'),
    url = require('url'),
    async = require("async"),
    host = '192.168.0.2',
    port = 38080,
    rows = 200,
    start = 0,
    urlSolr = '/solr/select?indent=on&version=2.2&fl=*,score&qt=zonalesContent&sort=max(modified,created)+desc&wt=json&explainOther=&hl.fl=&rows=' + rows + '&fq=indexTime:[2012-04-27T11:15:44.411Z+TO+*]&start=';

console.log('Fuente,Nombre,Links,Comments,Like,ReTweets,Reply\n');

var cantPost = rows;

async.whilst(
    function () {
        return cantPost < rows;
    },
    function (callbackWL) {
        zProxy.execute(host, port, urlSolr + start , 'GET', function(jsonObj) {
            cantPost = jsonObj.response.docs.length;
            start += cantPost + 1;
            async.forEachSeries(jsonObj.response.docs, function(doc, callback) {
                var post = JSON.parse(doc.verbatim);
                if (post) {
                    if (post.source != 'Facebook' && post.source != 'Twitter')
                        post.source = 'Feed';
                    var user = "";
                    if (post.toUsers != null) {
                        post.toUsers.forEach(function(toUser) {
                            user += toUser.name + ",";
                        });
                        user = user.substring(0, user.length-1);
                    } else {
                        user = post.fromUser.name;
                    }
                    var resp = post.source + ',' + user + ',' + post.links.length + ',';
                    var comments = 0, likes = 0 , retweets = 0, reply = 0;
                    if(post.actions){
                        async.forEachSeries(post.actions, function(action, callback2) {
                            if (action.type == 'replies')
                                reply++;
                            if (action.type == 'comment')
                                comments++;
                            if (action.type == 'retweets')
                                retweets++;
                            if (action.type == 'like')
                                likes++;
                            callback2();
                        }, function(err) {
                            if (err) console.log("Error: " + err);
                            resp += comments + ',' + likes + ',' + retweets + ',' + reply + '\n';
                            console.log(resp);
                            callback();
                        });
                    }
                }
            }, function(err) {
                if (err) console.log("Error: " + err);
                callbackWL();
            });
        });
    },
    function (err) {
        if (err) console.log("Error: " + err);
    }
);
