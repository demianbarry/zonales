<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
-->
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Carga de noticias Zonales</title>
        <meta content="text/html; charset=utf-8" http-equiv="content-type" />
        <script language="javascript" type="text/javascript" src="components/com_zonales/utils.js"></script>
        <script language="javascript" type="text/javascript" src="components/com_zonales/ckeditor.js"></script>
        <script language="javascript" type="text/javascript" src="components/com_zonales/content.js"></script>
        <script language="javascript" type="text/javascript" src="components/com_zonales/sample.js"></script>
        <script type="text/javascript">
            var host = 'localhost';
            var port = '38080';
            window.addEvent('domready', function(){
                $('table_content').getElements('input').each(function(el){
                    el.addEvent('change', function(){
                        workflow();
                    });
                });
                workflow();
<?php if (array_key_exists('id', $_GET)): ?>
            getContent('<?php echo $_GET['id'] ?>');
<?php endif; ?>
    });
        </script>
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
            <input type="hidden" id="id" value=""></input>
            <input type="hidden" id="fromUserName" value="<?php echo $this->user->get('username') ?>"></input>
            <input type="hidden" id="fromUserId" value="<?php echo $this->user->get('id') ?>"></input>
            <div id="table_content">
                <table class="advanced_search">
                    <tbody>
                        <tr>
                            <td class="label"><label>Título</label></td>
                            <td class="input"><input type="text" id="title" value=""></input></td>
                        </tr>
                        <tr>
                            <td class="label"><label>Creado</label></td>
                            <td class="input"><input type="text" id="created" value="" readonly="true"></input></td>
                        </tr>
                        <tr>
                            <td class="label"><label>Modificado</label></td>
                            <td class="input"><input type="text" id="modified" value="" readonly="true"></input></td>
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
                            <td class="label">
                                <label>Tags</label>
                            </td>
                            <td class="input">
                                <input type="text" id="tags" value="" onblur="if(this.getNext() != null) this.getNext().empty();" onkeyup="populateOptions(event, this, true, zTags);" autocomplete="off">
                                </input>
                            </td>
                        </tr>
                        <tr>
                            <td class="label">
                                <label>Zona</label>
                            </td>
                            <td class="input">
                                <input type="text" id="zone" value="" onblur="if(this.getNext() != null) this.getNext().empty();" onkeyup="populateOptions(event, this, false, zones);" autocomplete="off">
                                </input>
                            </td>
                        </tr>
                        <tr>
                            <td class="label"><label>Contenido</label></td>
                            <td><textarea class="ckeditor" cols="80" id="text" name="editor1" rows="10"></textarea></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div id="buttons">
                <input id="guardarButton" value="Guardar" type="button" onclick="if(zContent.state == 'created')zContent.state = 'saved';saveContent();"></input>
                <input id="anularButton" value="Anular" type="button" onclick="voidContent();"></input>
                <input id="publicarButton" value="Publicar" type="button" onclick="publishContent(true);"></input>
                <input id="despublicarButton" value="Despublicar" type="button" onclick="publishContent(false);"></input>
                <input id="volverButton" value="Volver" type="button" onclick="history.back();"></input>
            </div>
        </form>
    </body>
</html>