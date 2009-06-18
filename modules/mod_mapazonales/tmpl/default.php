<?php
// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die ( 'Restricted Access' );
?>

<script type="text/javascript">
	// <![CDATA[

	window.addEvent('domready', function() {
		Shadowbox.init({
			skipSetup: true,
			language: "es",
			players: ['swf'],
			flashParams: {
				scale: "noscale",
				allowScriptAccess: "sameDomain",
				width: 400,
				height: 500
			},
			flashVars: {
				MY_ZONES: "bue_avellaneda",
				URL_SET_ZONES: "index.php"
			}
		});

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