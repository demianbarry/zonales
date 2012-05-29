<?php require_once("config.php") ?>
<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <title>Content Manager Utilities - Zonales</title>
    <link href="ZoneStyle.css" rel="stylesheet" type="text/css" />
    <link type="text/css" rel="stylesheet" href="content.css">
    <link type="text/css" rel="stylesheet" href="global.css">
    <script type="text/javascript" src="/media/system/js/json2-compressed.js"></script>
    <script type="text/javascript" src="mootools.js"></script>
    <script type="text/javascript">
        var host = '<?php echo $tomcat_host; ?>';
        var port = '<?php echo $tomcat_port; ?>';
        window.addEvent('domready', function() {
            //getAllZones();
            getAllTags();
            //getFuenteTypes();
            //getResultTimes();
            //getAllConfigs();
        });
    </script>    
    <script type="text/javascript" src="viewutils.js"></script>
    <script type="text/javascript" src="zgram.js"></script>    
</head>

<body>
    <div id="container"/>
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
        <div id="view_content">
            <div id="search_div">
                <table class="advanced_search">
                    <tbody>
                        <tr><td colspan="2"><h2 style="margin-top: 0.7em;">Criterios</h2></td></tr>
                        <tr>
                            <td width="35%" class="label"><label>Zona</label></td>
                            <td>
                                <div> 
                                    <input id="getZones" onkeyup="populateOptions(event, this, false, extendedStrings);">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td width="35%" class="label"><label>Resultado por Fuente:</label></td>
                            <td>
                                <select id="getFuente">
                                    <option value="all">Todas</option>
                                    <option value="facebook">Facebook</option>
                                    <option value="twitter">Twitter</option>
                                    <option value="feed">Feed</option>
                                    <!-- carga todas las fuentes disponibles para mostrar -->
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td width="35%" class="label"><label>Periodo:</label></td>
                            <td><select id="resulttimes">
                                    <option value="all">Todos</option>
                                    <option value="hoy">Hoy</option>
                                    <option value="w">Ultima Semana</option>
                                    <option value="m">Ultimo Mes</option>
                                    <!-- combo con la lista de los periodos de tiempo -->
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td width="35%" class="label"><label>Tags</label></td>
                            <td class="input">
                                <div>
                                    <input type="text" style="width: 50%;" id="getTags" value="" onblur="if(this.getNext() != null) this.getNext().empty();" onkeyup="populateOptions(event, this, true, zTags);">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td width="35%" class="label"><label>Estado</label></td>
                            <td>
                                <select id="getEstado">
                                    <option value="all">Todos</option>
                                    <option value="generated">Generado</option>
                                    <option value="compiled">Compilado</option>
                                    <option value="no_compiled">No compilado</option>
                                    <option value="published">Publicado</option>
                                    <option value="unpublished">Despublicado</option>
                                    <option value="void">Anulado</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="input">
                                <input type="button" style="width: 33%; float: left;" value="Buscar" onclick="searchConfigs();">
                                <input type="button" style="width: 33%;  float: left;" value="Nuevo" onclick="window.location.href = 'extractUtil.php'">
                                <input type="button" style="width: 33%;  float: left;" value="Extraer" onclick="extractFromChecked();">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div id="resultado" style="width: 100%; float: left; height: 100px; background-repeat: no-repeat;"></div>
            <div id="postsContainer"></div>
        </div>
        <div id="resultslist_content" style="float: right;">
        </div>
    </div>
</body>
