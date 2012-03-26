var http = require('http');
var querystring = require('querystring');
var wikimapiaAPIKey = '7B86479B-8F9EF6D2-3A7530C8-D6BBDD38-6DE385E3-D86991CD-6BB9FCDF-A6726FCB';

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

module.exports.wikimapiaBboxSearch = function wikimapiaBboxSearch(bbox, category, count, page, callback) {

  var post_options = {
      host: 'api.wikimapia.org',
      port: '80',
      path: '/?function=box&key=' + wikimapiaAPIKey + '&bbox=' + bbox + '&disable=polygon&format=json&category=' + (category != '' ? category : 'all') + '&count=' + count + '&page=' + page,
      method: 'GET'
  };

  console.log("------------------>>>>>>" + JSON.stringify(post_options));

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
           var jsonObj = JSON.parse(response);
           callback(jsonObj);
           return(this);
        });
  });

  //post_req.write(post_data);
  post_req.end();
}

module.exports.googleMapSearch = function googleMapSearch(query, callback) {

  var post_options = {
      host: 'maps.google.com',
      port: '80',
      path: '/maps/api/geocode/json?address=' + encodeURIComponent(query) + '&sensor=false',
      method: 'GET'
  };

  console.log("---->" + JSON.stringify(post_options));

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
           var jsonObj = JSON.parse(response);
           callback(jsonObj);
           return(this);
        });
  });

  //post_req.write(post_data);
  post_req.end();
}

module.exports.wikimapiaGetObject = function wikimapiaGetObject(id, callback) {

  var post_options = {
      host: 'api.wikimapia.org',
      port: '80',
      path: '/?function=object&key=' + wikimapiaAPIKey + '&id=' + id + '&format=json',
      method: 'GET'
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
           var jsonObj = JSON.parse(response);
           callback(jsonObj);
           return(this);
        });
  });

  //post_req.write(post_data);
  post_req.end();
}
