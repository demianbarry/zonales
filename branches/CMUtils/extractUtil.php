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
            var host = '<?php echo $tomcat_host; ?>';
            var port = '<?php echo $tomcat_port; ?>';
            window.addEvent('domready', function(){
                if(gup('id') != null && gup('id') != '') {
                    getZGram(gup('id'));
                } else {
                    switchByWorkflow('no-compiled');
                }
				
                getAllZones();
                getAllTags();
				
                $('table_content').getElements('input').each(function(el){
                    el.addEvent('change', function(){						
                        switchButtons(['compilarButton']);
                    });
                });

                if ($('fuente').options[$('fuente').selectedIndex].value =='facebook') 
                    $('fbUtilsIcon').setStyle('display','block'); 
                else 
                    $('fbUtilsIcon').setStyle('display','none');
            });
        </script>		        
        <script type="text/javascript" src="zgram.js"></script>
        <script type="text/javascript" src="viewutils.js"></script>				
    </head>
    <body>
        <div id="container">
            <div id="header">
                <div id="logo">
                    <a href="/CMUtils"><img alt="Zonales" src="logo.gif"></a>
                </div>
                <a style="float:right" href="crud_crawl_configs.php">Gestión de Fuentes de Extracción</a>
                <br>
                <a style="float:right" href="http://<?php echo $host; ?>:<?php echo $tomcat_port; ?>/ZCrawlScheduler/listJobs">Lista de extracciones programadas en Scheduler</a>
                <br>
                <a style="float:right" href="fbutils.php">Facebook Utilities</a>
                <br>
                <a style="float:right" href="twutils.php">Twitter Utilities</a>
            </div>

            <div class="clearfix" id="wrapper">
                <div id="container_content">
                    <table class="advanced_search">
                        <tbody>
                            <tr>
                                <td><h2>Validar y extraer consultas</h2></td>
                            </tr>
                            <tr>
                                <td>	
                                    <div class="leftDiv">
                                        <div class="textExtractionDiv">
                                            <textarea id="textExtraction" cols="50" rows="5" style="float: left;" onchange="switchButtons(['compilarButton']);"></textarea>
                                        </div>
                                        <div class="generateButtonDiv">
                                            <input id="generateQuery" value="< Generar consulta" type="submit" onclick="generateQuery();">
                                        </div>
                                    </div>					
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div id="resultado" style="width: 50%; float: left; height: 100px; background-repeat: no-repeat;"></div>
                                </td>
                            </tr>						
                            <tr>
                                <td>
                                    <input id="compilarButton" value="Compilar" onclick="compileZGram();" type="submit">
                                    <input id="anularButton" value="Anular" type="submit">
                                    <input id="publicarButton" value="Publicar" type="submit" onclick="publishZgram(true);">
                                    <input id="despublicarButton" value="Despublicar" type="submit" onclick="publishZgram(false);">
                                    <input id="extraerButton" value="Extraer" type="submit" onclick="testExtraction();">
                                    <input id="volverButton" value="Volver" type="submit" onclick="history.back();">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div id="postsContainer"></div>
                                </td>
                            </tr>

                        </tbody>
                    </table>					
                </div>
                <div id="table_content">
                    <table class="advanced_search">
                        <tbody>
                            <tr>
                                <td class="label"><label>Descripción</label></td>
                                <td class="input"><input type="text" id="descripcion" value=""></td>
                            </tr>
                            <tr>
                                <td class="label"><label>Localidad</label></td>
                                <td class="input"><input type="text" id="localidad" value="" onblur="if(this.getNext() != null) this.getNext().empty();" onkeyup="populateOptions(event, this, false, zones);"></td>
                            </tr>
                            <tr>
                                <td class="label"><label>Fuente</label></td>
                                <td class="input">
                                    <select id="fuente" onchange="if (this.value=='facebook') $('fbUtilsIcon').setStyle('display','block'); else $('fbUtilsIcon').setStyle('display','none'); if(this.value == 'feed') {$('uriFuente').setStyle('display','');$('geoFuente').setStyle('display','');} else {$('uriFuente').setStyle('display','none');$('geoFuente').setStyle('display','none');}">
                                        <option value="facebook">Facebook</option>
                                        <option value="twitter">Twitter</option>
                                        <option value="feed">Feed</option>
                                        <!-- carga todas las fuentes disponibles para mostrar -->
                                    </select>
                            <!--<input type="text" id="fuente" value="">-->
                                </td>
                            </tr>
                            <tr id="uriFuente">
                                <td class="label"><label>URI Fuente</label></td>
                                <td class="input">
                                    <input type="text" id="uri" value="" onchange="if(this.value.trim() != '') {$('geoFuente').setStyle('display','');} else {$('geoFuente').setStyle('display','none');}">
                                </td>
                            </tr>                        
                            <tr id="geoFuente">
                                <td class="label"><label>Geolocalización Fuente</label></td>
                                <td class="input">									
                                    <label style="width: 10%">Latitud: </label>
                                    <input type="text" id="latfeed" value="" style="width: 12%">
                                    <label style="width: 10%">Longitud: </label>
                                    <input type="text" id="lonfeed" value="" style="width: 12%">
                                    <img width="16" height="16" border="0" onclick="lookupGeoData('latfeed','lonfeed',($('localidad') ? $('localidad').value +', ' : '') + 'Argentina');" title="Geolocalizar feed" src="geolocation.png" alt="Geolocalizar feed">                                    
                                </td>
                            </tr>                        
                            <tr>
                                <td class="label">
                                    <label>Estado</label>
                                </td>
                                <td class="input">
                                    <select id="estado" disabled="true">
                                        <option value="compiled">Compilado</option>
                                        <option value="no-compiled">No compilado</option>
                                        <option value="published">Publicado</option>
                                        <option value="unpublished">Despublicado</option>
                                        <option value="void">Anulado</option>
                                    </select>
                                    <!--<input type="text" id="estado" value="">-->
                                </td>
                            </tr>
                            <tr>
                                <td class="label"><label>Tags</label></td>
                                <td class="input"><input type="text" id="tags" value="" onblur="if(this.getNext() != null) this.getNext().empty();" onkeyup="populateOptions(event, this, true, zTags);"></td>
                            </tr>
                            <tr>
                                <td class="label"><label>Incluir Usuario</label></td>
                                <td class="input">
                                    <div>
                                        <input type="text" id="iusuario" value="" style="width: 50%">
                                        <label style="width: 10%">Latitud: </label>
                                        <input type="text" id="latusuario" value="" style="width: 12%">
                                        <label style="width: 10%">Longitud: </label>
                                        <input type="text" id="lonusuario" value="" style="width: 12%">
                                        <img width="16" height="16" border="0" onclick="lookupGeoData('latusuario','lonusuario',($('localidad') ? $('localidad').value +', ' : '') + 'Argentina');" title="Geolocalizar usuario" src="geolocation.png" alt="Geolocalizar usuario">
                                        <img border="0" width="16" height="16" alt="Agregar usuario" src="addIcon.gif" title="Agregar usuario" onclick="addUser($('iusuario').get('value'), $('latusuario').get('value'), $('lonusuario').get('value'));">
                                    </div>
                                </td>
                                <td>
                                    <a id="fbUtilsIcon" href="http://200.69.225.53:30082/CMUtils/fbutils.php" target="_blank"><img id="cl_fb_search" border="0" width="16" height="16" title="search" src="lupa.jpeg"></a>
                                </td>
                            </tr>
                            <tr>
                                <td class="label"></td>
                                <td id="userstd" class="input">

                                </td>
                            </tr>
                            <tr>
                                <td class="label"><label>Incluir Palabras</label></td>
                                <td class="input"><input type="text" id="ipalabras" value=""></td>
                            </tr>
                            <tr>
                                <td class="label"><label>Excluir Usuario</label></td>
                                <td class="input"><input type="text" id="eusuario" value=""></td>
                            </tr>
                            <tr>
                                <td class="label"><label>Excluir Palabras</label></td>
                                <td class="input"><input type="text" id="epalabras" value=""></td>
                            </tr>

                            <tr>
                                <td class="label"><label>Commenters</label></td>
                                <td class="input"><input type="text" id="commenters" value=""></td>
                            </tr>
                            <tr>
                                <td class="label"><label>Incluye Comentarios</label></td>
                                <td class=""><input id="allCommenters" type="checkbox" onclick=""></td>
                            </tr>
                            <tr>
                                <td class="label"><label>Lista Negra de Usuarios</label></td>
                                <td class=""><input id="lnegraus" type="checkbox"></td>
                            </tr>
                            <tr>
                                <td class="label"><label>Lista Negra de Palabras</label></td>
                                <td class=""><input id="lnegrapa" type="checkbox"></td>
                            </tr>
                            <tr>
                                <td class="label"><label>Min Actions</label></td>
                                <td class="input"><input type="text" id="minActions" value=""></td>
                            </tr>

                            <tr>
                                <td class="label"><label>Incluye tags de Fuente</label></td>
                                <td class=""><input id="sourceTags" type="checkbox"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>
