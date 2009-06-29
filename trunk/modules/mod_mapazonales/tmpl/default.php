<?php
// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die ( 'Restricted Access' );
?>

<script type="text/javascript" defer="defer">
	// <![CDATA[
	window.addEvent('domready', function() {
		Shadowbox.init({
			skipSetup: true
		});

		Shadowbox.setup($('zonal'), {
			onClose:function() { window.location.reload(true); }
		});
	});

	// ]]>
</script>

<div id="moduletable_zMap">
	<?php if($showLabel): ?>
	<p>
		<?php echo $labelText; ?>
	</p>
	<?php endif; ?>
	<p>
		<a id="zonal" rel="lightbox;width=<?php echo $width; ?>;height=<?php echo $height; ?>" title="<?php echo $titleText; ?>" href="index.php?option=com_zonales&view=mapa&ajax=true&tmpl=component_only">
			<img src="templates/<?php echo $template; ?>/images/bot_zonales.gif" />
		</a>
	</p>
</div>