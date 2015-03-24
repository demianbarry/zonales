# Introduction #

Estructuras de datos para gestión de los contenidos georreferenciados

# Estructuras #

## Clase de zonas ##

Identifican los tipos de zonas existentes y sus posibles padres.

**Ejemplos**: Pais, Provincia, Partido, Comarca, Localidad, Comuna.

```
// zoneType    ->    Clases de zonas
{
    "name" : "String", //Este campo debe ser único
    "parents" : [
        "String" //Nombres de los tipos que son padre del actual
    ],
    "state" : "String"  //Generado, publicado, despublicado, anulado
}
```

## Zonas ##

Datos alfanuméricos de cada zona

**Ejemplos**: Berazategui, Chubut, Puerto Madryn, Quilmes, Avellaneda

```
// zone    ->    Zonas
{
    "id" : "String", //Este campo debe ser único. No es igual al ID que le pone MongoDB
    "name" : "String",
    "parent" : "String", //ID de la zona padre
    "type" : "String", //Debe ser el nombre de un tipo de zona existente (zoneType)
    "state" : "String", ////Generado, publicado, despublicado, anulado
    "center_lat" : -34.1125, //Tipo de dato float  --  OPCIONAL
    "center_lon" : -64.7445, //Tipo de dato float  --  OPCIONAL
    "zoom_level" : 9 //Tipo de datos entero positivo  --  OPCIONAL
}
```

## Clases de lugares (places) ##

Identifican los tipos de lugares posibles. Esta estructura es definida por un administrador en Zonales, independiente de los tipos de lugares que puedan traer los extractores.

**Ejemplos**: plaza, edificio público, monumento histórico, centro turístico, club, centro cultural, alojamiento

```
// placeType    ->    Clases de lugares
{
    "name" : "String", //Este campo debe ser único
    "description" : "String", //Descripción del tipo de lugar
    "state" : "String"  //Generado, publicado, despublicado, anulado
}
```

## Lugares (places) ##

Datos alfanuméricos de cada lugar.

**Ejemplos**: Plaza San Martín, Obelisco, El Doradillo, EcoClub, Club Atlético Boca Junior

```
// place   ->    Lugares
{
    "id" : "String", //Este campo debe ser único. No es igual al ID que le pone MongoDB
    "name" : "String",
    "description" : "String", //Descripción del lugar  --  OPCIONAL
    "address" : "String", //Domicilio alfanumérico  --  OPCIONAL
    "links" : [  //Array de links a sitios relacionados con la zona  --  OPCIONAL
        "String",
        "String"
    ],
    "image" : "String", //URL de una imagen del lugar  --  OPCIONAL
    "zone" : "String", //ID de la zona a la que pertenece el lugar
    "type" : "String", //Debe ser el nombre de un tipo de lugar existente
    "typeTrash" : "String", //Tipo de lugar original, obtenido desde el extractor de lugares  --  OPCIONAL
    "state" : "String" ////Generado, publicado, despublicado, anulado
}
```

## [GeoJson](http://geojson.org/) ##

Representa la locación geográfica de un tipo de datos. Puede ser asociado a zonas, lugares o cualquier otro tipo de dato que se defina en el futuro.
Respeta el formato GeoJson definido en http://geojson.org/geojson-spec.html.

La especificación permite utilizar varios tipos de datos geográficos, se listan a continuación ejemplos de cada uno.

```
//geoJson - FeatureCollection
{
    "type": "FeatureCollection",
    "features": [
        {
            "type": "Feature",
            "properties": { //Libre, pueden setearse las propiedades necesarios
            },
            "geometry": {  //Un feature contiene un objeto geométrico
                "type": "Polygon",
                "coordinates": [
                    [
                        [  //Par de datos de tipo float
                            -71.742538422754,
                            -41.971943762034
                        ],
                        [
                            -65.106796235254,
                            -42.00460750892
                        ],
                        [
                            -71.764511079004,
                            -42.151386926728
                        ],
                        [
                            -71.742538422754,
                            -41.971943762034
                        ]
                    ]
                ]
            }
        }
    ],
    "ref": [  //Todos las zonas, lugares, etc, a los que hace referencia el objeto geográfico
        {
            "zone": [ //Array de zonas a la que hace referencia el objeto geográfico
                "String",
                "String"
            }
        ],
        {
            "place": [ //Array de lugares a la que hace referencia el objeto geográfico
                "String",
                "String"
            ]
        }
    ]
}

//geoJson - Point
{
    "type": "Point",
    "coordinates": [100.0, 0.0],
    "ref": []  //Iden FeaturesCollection
}

//geoJson - LineString
{
    "type": "LineString",
    "coordinates": [ [100.0, 0.0], [101.0, 1.0] ],
    "ref": []  //Iden FeaturesCollection
}

//geoJson - Polygon
{
    "type": "Polygon",
    "coordinates": [
        [ [100.0, 0.0], [101.0, 0.0], [101.0, 1.0], [100.0, 1.0], [100.0, 0.0] ],    //no Holes
        [ [100.2, 0.2], [100.8, 0.2], [100.8, 0.8], [100.2, 0.8], [100.2, 0.2] ] //OPCIONAL - with holes
    ],
    "ref": []  //Iden FeaturesCollection
}

//geoJson - Multipoint
{
    "type": "MultiPoint",
    "coordinates": [ [100.0, 0.0], [101.0, 1.0] ],
    "ref": []  //Iden FeaturesCollection
}

//geoJson - MultiLineString
{
    "type": "MultiLineString",
    "coordinates": [
        [ [100.0, 0.0], [101.0, 1.0] ],
        [ [102.0, 2.0], [103.0, 3.0] ]
    ],
    "ref": []  //Iden FeaturesCollection
}

//geoJson - MultiPolygon
{
    "type": "MultiPolygon",
    "coordinates": [
        [[[102.0, 2.0], [103.0, 2.0], [103.0, 3.0], [102.0, 3.0], [102.0, 2.0]]],
        [[[100.0, 0.0], [101.0, 0.0], [101.0, 1.0], [100.0, 1.0], [100.0, 0.0]],
        [[100.2, 0.2], [100.8, 0.2], [100.8, 0.8], [100.2, 0.8], [100.2, 0.2]]]
    ],
    "ref": []  //Iden FeaturesCollection
}

//geoJson - GeometryCollection
{
    "type": "GeometryCollection",
    "geometries": [
        {
            "type": "Point",
            "coordinates": [100.0, 0.0]
        },
        {
            "type": "LineString",
            "coordinates": [ [101.0, 0.0], [102.0, 1.0] ]
        }
    ],
    "ref": []  //Iden FeaturesCollection
}
```

## Formato devuelto por la [API de Wikimapia](http://wikimapia.org/api) ##

```
{
	"version":"1.0",
	"language":"en",
	"has_deeper_tiles":false,
	"folder": [
		{
			"id":"18219856",
			"name":"Güemes (es)",
			"url":"http://wikimapia.org/18219856/en/Güemes_(es)",
			"location":
				{
					"north":-31.4180063,
					"south":-31.439337,
					"east":-64.1888665,
					"west":-64.199826,
					"lon":null,
					"lat":null
				},
			"polygon":[
				{
					"x":-64.1967094,
					"y":-31.4180063
				},
				{
					"x":-64.1922569,
					"y":-31.4192149
				},
				{
					"x":-64.1888666,
					"y":-31.4202403
				},
				{
					"x":-64.1913986,
					"y":-31.4266124
				}
			]
		},
		{
			"id":"18228899","name":"Alberdi (es)",
			"url":"http://wikimapia.org/18228899/en/Alberdi_(es)",
			"location": ...
		}
	]
}
```