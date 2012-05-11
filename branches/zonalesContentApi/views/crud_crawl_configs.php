<?php require_once("config.php")?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Content Manager Utilities - Zonales</title>
        <link href="ZoneStyle.css" rel="stylesheet" type="text/css" />
        <link type="text/css" rel="stylesheet" href="content.css">
        <link type="text/css" rel="stylesheet" href="global.css">
		<script src="mootools.js" type="text/javascript"></script>
		<script type="text/javascript">
			var host = '<?php echo $tomcat_host;?>';
			var port = '<?php echo $tomcat_port;?>';
                        window.addEvent('domready', function() {
                            getPluginTypes();
                            getAllConfig();
                        });
		</script>        
        <script src="cmutils.js" type="text/javascript"></script>		
    </head>
    <body>
        <div id="container">
            <div id="header">
                <div id="logo">
                    <a href="/CMUtils"><img src="logo.gif" alt="Zonales"></a>
                </div>
                <a style="float:right" href="index.php">Gestión de Extracciones</a>
                <br>
                <a style="float:right" href="http://<?php echo $host; ?>:<?php echo $tomcat_port; ?>/ZCrawlScheduler/listJobs">Lista de extracciones programadas en Scheduler</a>
                <br>
                <a style="float:right" href="fbutils.php">Facebook Utilities</a>
                <br>
                <a style="float:right" href="twutils.php">Twitter Utilities</a>
            </div>
            <div id="wrapper" class="clearfix">
                
                <div id="list_content">
                    <div id="title" width="100%">
                        <table>
                            <tbody>
                                <tr>
                                    <td width="70%">
                                        <h2>Configuracion de Fuentes de Extraccion</h2>
                                    </td>
                                    <td width="30%">
                                        <img alt="Add Crawl Config" style="float: right" src="addIcon.gif" title="Add Crawl Config" onclick="addConfig();">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="edit_content" style="display:none">
                    <table  cellspacing="0" cellpadding="0" border="0" width="100%" class="advanced_search">
                        <tbody>
                            <tr>
                                <td colspan="2"><h2 style="margin-top: 0.7em;">Edicion</h2></td>
                            </tr>
                            <tr>
                                <td width="35%" class="label"><label>Name (*)</label></td>
                                <td class="input"><input type="text" style="width: 100%;" id="getNameFuente" value=""></td>
                            </tr>
                            <tr>
                                <td width="35%" class="label"><label>URI (*)</label></td>
                                <td class="input"><input type="text" style="width: 100%;" id="getUriFuente" value=""></td>
                            </tr>
                            <tr>
                                <td colspan="2"><h2 style="margin-top: 0.7em;">Parametros</h2></td>
                            </tr>
                            <tr>
                                <td width="35%" class="label"><label>Name</label></td>
                                <td class="input"><input type="text" style="width: 100%;" id="getNameParam" value=""></td>
                            </tr>
                            <tr>
                                <td width="35%" class="label"><label>Requerido</label></td>
                                <td class="input"><input type="checkbox" id="getReq"><img alt="Add Param" style="float: right" src="addIcon.gif" title="Add Param" onclick="addParam($('getNameParam').get('value'),$('getReq').get('checked'));"></td>
                            </tr>                            
                            
                            <tr>
                                <td colspan="2">
                                    <div id="configParams"/>
                                </td>
                            </tr>
                                
                            
                            <tr>
                                <td colspan="2"><h2 style="margin-top: 0.7em;">Plugins (*)</h2></td>
                            </tr>
                            <tr>
                                <td width="35%" class="label"><label>ClassName</label></td>
                                <td class="input"><input type="text" style="width: 100%;" id="getClassName" value=""></td>
                            </tr>
                            <tr>
                                <td width="35%" class="label"><label>Type</label></td>
                                <td>
                                    <select id="plugintypes" >
                                        
                                    </select>
                                    <img alt="Add Plugin" style="float: right" title="Add Plugin" src="addIcon.gif" onclick="addPlugin($('getClassName').get('value'),$('plugintypes').options[$('plugintypes').selectedIndex].get('text'));">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <div id="configPlugins"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="input"><input id="saveConfigButton" type="button" style="width: 100%;" value="Save" onclick="saveConfig();"></td>
                                <td class="input"><input type="button" style="width: 100%;" value="Cancel" onclick="clearEdit();"></td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    (*) Parámetros requeridos
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>