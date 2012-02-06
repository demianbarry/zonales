var http = require('http');

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
           var jsonObj = JSON.parse(response);
           callback(jsonObj);
           return(this);
		  });
    }).end();
}
