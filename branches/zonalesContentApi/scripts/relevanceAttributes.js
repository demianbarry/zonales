var zProxy = require('../services/zProxy'),
	url = require('url');
	fs = require('fs'),
	filename = './relevamientoAtributos.csv',
	async = require("async");

var data = 'Fuente,ID/Nombre,Muestra,Existen Comentarios,Promedio Comentarios,SD Comentarios,Promedio Nivel de aceptación,SD Aceptación\n';
console.log(data);

var host = '192.168.0.2',
	port = 38080,
	url = '/ZCrawlSources/getZGram?filtros={}',
	urlSolr = '/solr/select?indent=on&version=2.2&start=0&fl=*,score&qt=zonalesContent&sort=max(modified,created)+desc&wt=json&explainOther=&hl.fl=&fq=indexTime:[2012-04-27T11:15:44.411Z+TO+*]';

function getAttributes(fuente, usuario, callback) {
	var URL = urlSolr + '&q=source:(' + fuente + ')+AND+' + (fuente == 'facebook' ? 'fromUserId' : 'fromUserName') + ':(' + usuario + ')&rows=';
	// console.log(URL);
	zProxy.execute(host, port, URL + '0', 'GET', function(json) {
		// console.log(json);
		var jsonObj = JSON.parse(json);
		var rows = jsonObj.response.numFound;
		console.log("Cantidad de post de la fuente " + fuente + ", usuario " + usuario + ": " + rows);

		zProxy.execute(host, port, URL + rows, 'GET', function(json) {
			jsonObj = JSON.parse(json);
			var comentarios = 0;
			async.series([
				function (callbackSeries) {
					async.forEachSeries(jsonObj.response.docs, function(doc, callback2) {
						var post = JSON.parse(doc.verbatim);
						if (post.actions) {
							async.forEachSeries(post.actions, function(action, callback3) {
								if (fuente == 'facebook' && action.type == 'comment')
									comentarios += action.cant;
								if (fuente == 'twitter' && action.type == 'replies')
									comentarios += action.cant;
								callback3();
							}, function(err) {
								if (err) console.log("Error: " + err);
								callback2();
							});
						}
					}, function(err) {
						if (err) console.log("Error: " + err);
						callbackSeries();
					});
				}
				],
				function(err, results){
					if (err) console.log("Error: " + err);
					else {
						var resp = fuente + ',' + usuario + ',' + rows + ',' + (comentarios > 0 ? 'SI' : '') + ',' + (comentarios > 0 ? comentarios / rows : '') + ',,,\n';
						callback(resp);
					}
				}
			);
		});
	});
}

function getFeedAttributes(source) {

}

async.series([
	function (callbackSeries) {
		zProxy.execute(host, port, url, 'GET', function(json) {
			var jsonObj = JSON.parse(json);
		    async.forEachSeries(jsonObj, function(zgram, callback) {
	    		if (zgram && zgram.fuente) {
		    		if (zgram.fuente != 'feed') {
				    	async.forEachSeries(zgram.criterios, function(criterio, callback2) {
			    			if (criterio.deLosUsuarios) {
				    			async.forEachSeries(criterio.deLosUsuarios, function(usuario, callback3) {
				    				getAttributes(zgram.fuente, trim(usuario), function(resp) {
				    					// console.log(resp);
				    					data += resp;
				    					callback3();
				    				});
				    			}, function(err) {
				    				if (err) console.log("Error: " + err);
				    				callback2();
				    			});
				    		} else {
				    			callback2();
				    		}
			    		}, function(err) {
			    			if (err) console.log("Error: " + err);
			    			callback();
			    		});
				    } else {
				    	callback();
				    }
			    } else {
			    	callback();
			    }
	    	}, function (err) {
	    		if (err) console.log("Error: " + err);
	    		callbackSeries();
	    	});
		});
	}
], function (err, results) {
		if (err) console.log("Error: " + err);
		else {
			console.log("Grabo en archivo");
			fs.writeFileSync(filename, data);
		}
	}
);

function trim (myString)
{
	return myString.replace(/^\s+/g,'').replace(/\s+$/g,'')
}