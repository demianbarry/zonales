<?php
// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die ( 'Restricted Access' );
?>

<div id="map" style="float: left;">
	No se puede cargar la pel&iacute;cula.
</div><!-- END #map -->

<script type="text/javascript" defer="defer">
	// <![CDATA[
	window.addEvent('domready', function() {

	var so = new SWFObject("templates/<?php echo $template; ?>/swf/inicio.swf", "Mapa de Inicio", "<?php echo $width; ?>", "<?php echo $height; ?>", "8", "#FFFFFF");
	so.addParam("scale", "noscale");
	<?php if($wmode): ?>
	so.addParam("wmode", "transparent");
	<?php endif; ?>
	so.addParam("allowScriptAccess", "sameDomain");
	so.addVariable("URL_SET_ZONE", 'index.php');
	so.addVariable("task", 'setZonal');
	so.addVariable("option", 'com_zonales');

	<?php
	// El array devuelto tiene la forma: $j2d['JOOMLA_ZONE_ID'] = 'FLASH_ID'
	// para todos los zonales activos

	// Entonces ahora le digo al mapa flash cuál es el
	// identificador correspondiente a cada flash_id
	foreach ($j2f as $zid => $fid)
	{
		// El resultado de la siguiente acción será que el partido
		// identificado en joomla como $zid e identificado en el
		// mapa flash como $fid se habilite como opción seleccionable
		echo "so.addVariable('$fid','$zid');\n";
	}
	?>

	//window.addEvent('domReady', so.write("map"));
	so.write("map");
	});
	// ]]>
</script>
