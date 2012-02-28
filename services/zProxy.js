var http = require('http');
var querystring = require('querystring');

module.exports.execute = function execute(host, port, path, method, callback){

	 console.log("PROXY EXECUTION: HOST: " + host + ", PORT: " + port + ", PATH: " + path + ", METHOD: " + method);

         var options = {
                  host: host,
                  port: port,
                  path: path,
                  method: method
         };

         var response = "";

         http.request(options, function(res) {
            console.log('STATUS: ' + res.statusCode);
            console.log('HEADERS: ' + JSON.stringify(res.headers));
            res.setEncoding('utf8');
            
            res.on('data', function (json) {
    				   response += json;
            });
  		      
            res.on('end', function() {
    		       console.log('BODY: ' + response);
               console.log("------------------------------------------------------------------------------------------");
               if (res.statusCode == 200) {
                    var jsonObj = JSON.parse(response);
               }
               callback(jsonObj);
               return(this);
  		      });
    }).end();
}

module.exports.solrCommand = function solrCommand(data, commit, callback) {

  var post_data = querystring.stringify(data);

  /*callback(post_data);
  return(this);*/

  //var post_data = data;

  var post_options = {
      host: 'localhost',
      port: '38080',
      path: commit ? '/solr/update/json' : '/solr/update/json?commit=true',
      method: 'POST',
      headers: {
          'Content-Type': 'application/json',
          'Content-Length': post_data.length
      }
  };

  var response = "";

  var post_req = http.request(post_options, function(res) {
        console.log('STATUS: ' + res.statusCode);
        console.log('HEADERS: ' + JSON.stringify(res.headers));
        res.setEncoding('utf8');
        
        res.on('data', function (json) {
           response += json;
        });
        
        res.on('end', function() {
           console.log('BODY: ' + response);
           console.log("------------------------------------------------------------------------------------------");
           //var jsonObj = JSON.parse(response);
           callback(response);
           return(this);
        });
  });

  if (!commit) {
    post_req.write(post_data);
  }
  post_req.end();
}
