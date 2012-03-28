var zProxyService = require('../services/zProxy');
var wikimapiaCategoriesService = require('../services/wikimapiaCategories');

//argentinaBBox = '-75.014265,-55.919607,-54.359968,-21.853251';
var northLimit;
var southLimit;
var westLimit;
var eastLimit;
var lonDelta;
var latDelta;
var count;
var category;

module.exports.fetchCategories = function fetchCategories(westL, northL, eastL, southL, lonD, latD, cnt, cat) {
	northLimit = northL;
	southLimit = southL;
	westLimit = westL;
	eastLimit = eastL;
	lonDelta = lonD;
	latDelta = latD;
	count = cnt;
	category = cat;
	fetchBox(westLimit, northLimit, westLimit + lonDelta, northLimit + latDelta);
}

function fetchBox(west, north, east, south) {
	var bbox = setBbox(west, north, east, south);
	var page = 1;
	console.log("Buscando página 1 en bbox: " + bbox);
	zProxyService.wikimapiaBboxSearch(bbox, category, count, page, function(data) {
		fetchObjects(data.folder, function(response) {
			if (response == 'ok') {
				var cantPages = Math.floor(data.found / 100) + 1;
				if (page > cantPages) {
					while (page < cantPages) {
						page++;
						console.log("Buscando página " + page + " en bbox: " + bbox);
						zProxyService.wikimapiaBboxSearch(bbox, category, count, page, function(data) {
							fetchObjects(data.folder, function(response) {
								if (response == 'ok') {
									//console.log("Hay más páginas...");
									west = east;
									east += lonDelta;
									if (east > eastLimit) {
										west = westLimit;
										east = west + lonDelta;
										north = south;
										south += latDelta;
									}
									if (south > southLimit) {
										return 'Fin de ejecución del script';
									}
									fetchBox(west, north, east, south);
								}
							});
						});
					}
				} else {
					//console.log("No hay más páginas...");
					west = east;
					east += lonDelta;
					console.log("Bbox: " + setBbox(west, north, east, south));
					if (east > eastLimit) {
						west = westLimit;
						east = west + lonDelta;
						north = south;
						south += latDelta;
					}
					if (south > southLimit) {
						//console.log("Fin script");
						return 'Fin de ejecución del script';
					}
					//console.log("Llamado recursivo");
					fetchBox(west, north, east, south);
				}
			}
		});
	});
}


function setBbox(west, north, east, south) {
	return '' + west + ',' + north + ',' + east + ',' + south;
}

function fetchObjects(wikipamiaFolder, callback) {
	if (typeof(wikipamiaFolder) != 'undefined' && wikipamiaFolder != null && wikipamiaFolder.length > 0) {
		var count = 0;
		console.log("Extrayendo objetos");
		wikipamiaFolder.forEach(function (place) {
			count++;
			console.log("Call fetchObject");
			fetchObject(place.id);
			if (count >= wikipamiaFolder.length) {
				callback('ok');
			}
		});
	} else {
		callback('ok');
	}
}

function fetchObject(wikipamiaId) {
	zProxyService.wikimapiaGetObject(wikipamiaId, function(place) {
		console.log("Obteniendo objeto " + place.id);
		fetchTags(place.tags);
	});
}

function fetchTags(wikimapiaTags) {
	if (typeof(wikimapiaTags) != 'undefined' && wikimapiaTags != null) {
		console.log("Tags obtenidos " + JSON.stringify(wikimapiaTags));
		wikimapiaTags.forEach(function (tag) {
			console.log("Busco category " + tag.id);
			wikimapiaCategoriesService.getById(tag.id, function(wikimapiaCategory) {
				if (!wikimapiaCategory) {
					console.log("Guardo category " + tag.id);
					wikimapiaCategoriesService.set(tag, function(response) {
						console.log("Categoría de wikimapia insertada, id: " + tag.id);
					});
				}
			})			
		});
	}
}