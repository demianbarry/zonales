<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
-->
<html xmlns="http://www.w3.org/1999/xhtml">
    <head> 
        <title>Carga de noticias Zonales</title>
        <meta content="text/html; charset=utf-8" http-equiv="content-type" />
        <!--<link type="text/css" rel="stylesheet" href="components/com_zonales/content.css"></link>
        <link type="text/css" rel="stylesheet" href="components/com_zonales/global.css"></link>
        <link type="text/css" rel="stylesheet" href="components/com_zonales/sample.css"></link>-->
        <script language="javascript" type="text/javascript" src="components/com_zonales/utils.js"></script>
        <script language="javascript" type="text/javascript" src="components/com_zonales/ckeditor.js"></script>
        <script language="javascript" type="text/javascript" src="components/com_zonales/content.js"></script>
        <script language="javascript" type="text/javascript" src="components/com_zonales/sample.js"></script>        
        <script type="text/javascript">
            var host = 'localhost';
            var port = '38080';
            window.addEvent('domready', function() {
                loadPost($('resultado'));
            });
        </script>        
    </head>
    <body>    
        <div class="clearfix" id="wrapper">        
            <div id="resultado">
            </div>
        </div>
    </body>
</html>
