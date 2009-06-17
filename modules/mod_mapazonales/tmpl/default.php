<?php
// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die ( 'Restricted Access' );
?>

<script type="text/javascript">
	// <![CDATA[

	window.addEvent('domready', function() {
		Shadowbox.init();

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
		<a id="zonal" rel="lightbox;width=<?php echo $width; ?>;height=<?php echo $height; ?>" title="<?php echo $titleText; ?>" href="index.php?option=com_zonales&view=mapa&ajax=true&tmpl=component_only">Mediabit</a>
	</p>
</div>