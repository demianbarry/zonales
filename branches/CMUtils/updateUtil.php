<?php require_once("config.php") ?>
<html>
    <head>
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
        <title>Content Manager Utilities - Zonales</title>
        <link href="ZoneStyle.css" rel="stylesheet" type="text/css" />
        <link type="text/css" rel="stylesheet" href="content.css">
        <link type="text/css" rel="stylesheet" href="global.css">
        <script type="text/javascript" src="mootools.js"></script>
        <script type="text/javascript" src="http://api.mygeoposition.com/api/geopicker/api.js"></script>
        <script type="text/javascript">
            String.prototype.capitalize = function(){
                return this.replace( /(^|\s)([a-z])/g , function(m,p1,p2){
                    return p1+p2.toUpperCase();
                } );
            };
            var host = '<?php echo $tomcat_host; ?>';
            var port = '<?php echo $tomcat_port; ?>';
            window.addEvent('domready', function(){
                $('actualizar').addEvent('click', function(){
                    getZGram(false, function(docs){
                        docs = JSON.parse(docs);
                        if(typeOf(docs) == 'array'){
                            var localidadesLatlons = 0;
                            var latlons = 0;
                            var resto = 0;
                            
                            docs.each(function(doc){
                                var start = doc.verbatim.indexOf("para la localidad \"")+19;
                                var finish = doc.verbatim.indexOf("\"", start);
                                var localidad = doc.verbatim.substring(start, finish);
                                if(false && localidad.indexOf(',') == -1){
                                    if(doc.localidad.indexOf(',') != -1){
                                        doc.verbatim = doc.verbatim.replace(/para la localidad \"[^\"]+\"/g,'para la localidad "'+doc.localidad+'"');
                                        compileZGram(doc.verbatim.replace(/\[[^\]]+\]/g,""), doc._id.$oid);
                                    } else {
                                        var url = "zone/get?filters={name:'"+localidad.replace(/, /g,",+").replace(/ /g,"_").replace(/\+/g," ").toLowerCase()+"'}";
                                        var urlProxy = 'curl_proxy.php';
                                        new Request({
                                            url: encodeURIComponent(urlProxy),
                                            method: 'get',
                                            data: {
                                                'host': 'localhost',
                                                'port': '4000',
                                                'ws_path':url
                                            },
                                            onRequest: function(){                                            
                                                if($('resultado')){
                                                    $('resultado').empty();
                                                    $('resultado').addClass('loading');
                                                }
                                            },
                                            onSuccess: function(response) {
                                                // actualizar pagina
                                                if($('resultado'))
                                                    $('resultado').removeClass('loading').removeClass('fallo').addClass('resultado');
                                                response = JSON.parse(response);

                                                if(typeOf(response) == 'array' && response.length > 0) {
                                                    doc.verbatim = doc.verbatim.replace(/para la localidad \"[^\"]+\"/g,'para la localidad "'+response[0].extendedString.replace(/_/g," ").capitalize()+'"');
                                                }
                                                compileZGram(doc.verbatim.replace(/\[[^\]]+\]/g,""), doc._id.$oid);
                                                localidadesLatlons++;
                                            },

                                            // Our request will most likely succeed, but just in case, we'll add an
                                            // onFailure method which will let the user know what happened.
                                            onFailure: function(){
                                                if($('resultado')){
                                                    $('resultado').removeClass('loading').removeClass('oculto').removeClass('resultado').addClass('fallo');
                                                    $('resultado').innerHTML = this.response.text.substring(this.response.text.indexOf('<h1>')+22, this.response.text.indexOf('</h1>'));
                                                }
                                            }

                                        }).send();
                                    }
                                } else /*if(doc.verbatim.search(/\[[^\]]+\]/) != -1) {                                    
                                    compileZGram(doc.verbatim.replace(/\[[^\]]+\]/g,""), doc._id.$oid);
                                    latlons++;
                                }
                                resto++;
                                compileZGram(doc.verbatim, doc._id.$oid);*/
                                if(doc.estado == 'compiled') {                                    
                                    publishZgram(true, doc._id.$oid);
                                    latlons++;
                                }
                            });
                        }
                    });
                });
            });
        </script>		        
        <script type="text/javascript" src="zgram.js"></script>
        <script type="text/javascript" src="viewutils.js"></script>				
    </head>
    <body>
        <div id="container">
            <div>
                <textarea id="textExtraction" cols="50" rows="5" style="float: left; display: none"></textarea>
                <input id="actualizar" type="button" value="Actualizar">
            </div>
        </div>
    </body>
</html>
