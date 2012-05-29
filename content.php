<?php require_once 'config.php';?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
-->
<html xmlns="http://www.w3.org/1999/xhtml">
    <head> 
        <title>Carga de noticias Zonales</title>
        <meta content="text/html; charset=utf-8" http-equiv="content-type" />
        <link type="text/css" rel="stylesheet" href="content.css"></link>
        <link type="text/css" rel="stylesheet" href="global.css"></link>
        <script type="text/javascript" src="ckeditor.js"></script>
        <script type="text/javascript" src="sample.js"></script>        
        <script type="text/javascript" src="mootools.js"></script>        
        <script type="text/javascript">
            var host = '<?php echo $tomcat_host; ?>';
            var port = '<?php echo $tomcat_port; ?>';
            window.addEvent('domready', function(){                
                $('table_content').getElements('input').each(function(el){
                    el.addEvent('change', function(){
                        switchButtons(['guardarButton']);
                    });
                });
<?php if (array_key_exists('id', $_GET)): ?>
                            getContent('<?php echo $_GET['id'] ?>');
<?php endif; ?>
                    });
        </script>
        <script type="text/javascript" src="content.js"></script>
        <link href="sample.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <h1 class="samples">
            Carga de noticias Zonales
        </h1>
        <!--<div class="description">
            <p>
                This sample shows how to automatically replace all <code>&lt;textarea&gt;</code> elements
                of a given class with a CKEditor instance.
            </p>
            <p>
                To replace a <code>&lt;textarea&gt;</code> element, simply assign it the <code>ckeditor</code>
                class, as in the code below:
            </p>
            <pre class="samples">&lt;textarea <strong>class="ckeditor</strong>" name="editor1"&gt;&lt;/textarea&gt;</pre>
            <p>
                Note that other <code>&lt;textarea&gt;</code> attributes (like <code>id</code> or <code>name</code>) need to be adjusted to your document.
            </p>
        </div>-->

        <!-- This <div> holds alert messages to be display in the sample page. -->
        <div id="alerts">
            <noscript>
                <p>
                    <strong>CKEditor requires JavaScript to run</strong>. In a browser with no JavaScript
                    support, like yours, you should still see the contents (HTML data) and you should
                    be able to edit it normally, without a rich editor interface.
                </p>
            </noscript>
        </div>
        <form action="" method="post">
            <input type="hidden" id="id" value="">
                <div id="table_content">
                    <table class="advanced_search">
                        <tbody>  
                            <tr>
                                <td class="label"><label>Título</label></td>
                                <td class="input"><input type="text" id="title" value=""></td>
                            </tr>
                            <tr>
                                <td class="label"><label>Autor</label></td>
                                <td class="input"><input type="text" id="autor" value=""></td>
                            </tr>
                            <tr>
                                <td class="label"><label>Creado</label></td>
                                <td class="input"><input type="text" id="created" value="" readonly="true"></td>
                            </tr>
                            <tr>
                                <td class="label"><label>Modificado</label></td>
                                <td class="input"><input type="text" id="modified" value=""></td>
                            </tr>                                                    
                            <tr id="geoFuente">
                                <td class="label"><label>Geolocalización</label></td>
                                <td class="input">									
                                    <label style="width: 10%">Latitud: </label>
                                    <input type="text" id="lat" value="" style="width: 12%">
                                        <label style="width: 10%">Longitud: </label>
                                        <input type="text" id="lon" value="" style="width: 12%">
                                            <img width="16" height="16" border="0" onclick="lookupGeoData('lat','lon',($('localidad') ? $('localidad').value +', ' : '') + 'Argentina');" title="Geolocalizar noticia" src="geolocation.png" alt="Geolocalizar noticia">
                                                </td>
                                                </tr>                        
                                                <tr>
                                                    <td class="label">
                                                        <label>Estado</label>
                                                    </td>
                                                    <td class="input">
                                                        <select id="state" disabled="true">
                                                            <option value="new">Nuevo</option>
                                                            <option value="saved">Guardado</option>
                                                            <option value="published">Publicado</option>
                                                            <option value="unpublished">Despublicado</option>
                                                            <option value="void">Anulado</option>
                                                        </select>
                                                        <!--<input type="text" id="estado" value="">-->
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="label"><label>Tags</label></td>
                                                    <td class="input"><input type="text" id="tags" value="" onblur="if(this.getNext() != null) this.getNext().empty();" onkeyup="populateOptions(event, this, false, zTags);" autocomplete="off"></td>
                                                </tr>
                                                <tr>
                                                    <td class="label"><label>Localidad</label></td>
                                                    <td class="input"><input type="text" id="zone" value="" onblur="if(this.getNext() != null) this.getNext().empty();" onkeyup="populateOptions(event, this, false, extendedStrings);" autocomplete="off"></td>
                                                </tr>
                                                <tr>
                                                    <td class="label"><label>Contenido</label></td>
                                                    <td><textarea class="ckeditor" cols="80" id="text" name="editor1" rows="10">&lt;p&gt;This is some &lt;strong&gt;sample text&lt;/strong&gt;. You are using &lt;a href="http://ckeditor.com/"&gt;CKEditor&lt;/a&gt;.&lt;/p&gt;</textarea></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <input id="guardarButton" value="Guardar" type="button" onclick="saveContent();">
                                                            <input id="anularButton" value="Anular" type="button" onclick="voidContent();">
                                                                <input id="publicarButton" value="Publicar" type="button" onclick="publishContent(true);">
                                                                    <input id="despublicarButton" value="Despublicar" type="button" onclick="publishContent(false);">                                    
                                                                        <input id="volverButton" value="Volver" type="button" onclick="history.back();">
                                                                            </td>
                                                                            </tr>
                                                                            </tbody>
                                                                            </table>
                                                                            </div>
                                                                            </form>
                                                                            <div id="footer">
                                                                                <hr />
                                                                                <p>
                                                                                    Zonales - La información sos vos - <a class="samples" href="http://zonales.com/">http://zonales.com</a>
                                                                                </p>
                                                                                <p id="copy">
                                                                                    Copyright &copy; 2003-2011, <a class="samples" href="http://www.mediabit.com.ar/">Mediabit S.A. All rights reserved.
                                                                                </p>
                                                                            </div>
                                                                            </body>
                                                                            </html>
