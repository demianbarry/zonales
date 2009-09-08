<?php
// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die ( 'Restricted Access' );

// incluye javascript para objeto flash
JHTML::script('dtfx.js');
?>

<div id="map_content">
	Este DIV es reemplazado por el mapa flash utilizando el js SWFObject<br/>
	Suele utilizarse este espacio para:
	<ul>
		<li>Avisar que aquí se cargará un mapa por ejemplo</li>
		<li>Ofrecer una forma de instalar Flash Player</li>
		<li>Ofrecer otra forma de elegir un zonal (por ejemplo, link a una página con un select)</li>
	</ul>
</div>
<script type="text/javascript" defer="defer">
	// <![CDATA[
	window.addEvent('domready', function() {

	var so = new SWFObject("templates/<?php echo $this->template; ?>/javafx/<?php echo $this->javafxfile; ?>", "Selección de Zonal", "<?php echo $this->width; ?>", "<?php echo $this->height; ?>", "8", "#FFFFFF");
	so.addParam("scale", "noscale");
	so.addParam("allowScriptAccess", "sameDomain");
	so.addVariable("MY_ZONES", "<?php echo $this->zonal; ?>");
	so.addVariable("URL_SET_ZONE", "index.php");
	so.addVariable("task", "<?php echo $this->task; ?>");
	so.addVariable("option", "com_reservas");
	<?php if ($this->ajax) echo "so.addVariable(\"format\",\"raw\");\n"; ?>
	<?php if (!$this->ajax) echo "so.addVariable(\"return\", \"<?php echo $this->return; ?>\");\n"; ?>
	so.addVariable("PHPSESSID", "<?php echo session_id() ?>");
	<?php
	// El array devuelto tiene la forma: $j2d['JOOMLA_ZONE_ID'] = 'FLASH_ID'
	// para todos los reservas activos

	// Entonces ahora le digo al mapa flash cuál es el
	// identificador correspondiente a cada flash_id
	foreach ($this->j2f as $zid => $fid)
	{
		// El resultado de la siguiente acción será que el partido
		// identificado en joomla como $zid e identificado en el
		// mapa flash como $fid se habilite como opción seleccionable
		echo "so.addVariable('$fid','$zid');\n";
	}
	?>

	//window.addEvent('domReady', so.write("map_content"));
	so.write("map_content");
	});
	// ]]>
</script>