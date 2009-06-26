<?php
// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die ( 'Restricted Access' );
?>

<script type="text/javascript" defer="defer">
	// <![CDATA[

	window.addEvent('domready', function() {
		Shadowbox.setup($('zonal'), {
			onClose:function() { window.location.reload(true); }
		});
	});

	// ]]>
</script>

<div id="moduletable_zName">
	<p><?php echo $titleText; ?></p>
	<p>
		<?php if($showLabel) { echo $labelText . ' '; } ?>
		<?php if(!$useFlash): ?>
		<a id="zonal" rel="lightbox;width=<?php echo $width; ?>;height=<?php echo $height; ?>" title="<?php echo $titleText; ?>" href="index.php?option=com_zonales&view=mapa&ajax=true&tmpl=component_only">Mediabit</a>
		<?php else: ?>
		<a id="zonal" rel="lightbox;width=<?php echo $width; ?>;height=<?php echo $height; ?>" title="<?php echo $titleText; ?>" href="http://www.zonales.com.ar/zonales-devel/templates/z1/swf/map_1.0.swf">Mediabit</a>
		<?php endif; ?>
	</p>
</div>