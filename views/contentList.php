<?php require_once("config.php")?>
<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <title>Content Manager Utilities - Zonales</title>
    <link href="ZoneStyle.css" rel="stylesheet" type="text/css" />
    <link type="text/css" rel="stylesheet" href="content.css">
    <link type="text/css" rel="stylesheet" href="global.css">
	<script type="text/javascript" src="mootools.js"></script>
	<script type="text/javascript">
		var host = '<?php echo $tomcat_host;?>';
		var port = '<?php echo $tomcat_port;?>';
        window.addEvent('domready', function() {
            loadPost($('resultado'));
        });
    </script>    
    <script type="text/javascript" src="content.js"></script>    
</head>

<body>    
    <div class="clearfix" id="wrapper">        
        <div id="resultado">
        </div>
    </div>
</body>
