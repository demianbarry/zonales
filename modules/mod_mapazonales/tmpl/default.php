<?php
// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die ( 'Restricted Access' );
?>

<div id="moduletable_zName">
	<p><?php echo $titleText; ?></p>
	<p>
		<?php if($showLabel) { echo $labelText . ' '; } ?>
		<a rel="lightbox;width:<?php echo $height; ?>;height:<?php echo $width; ?>" title="<?php echo $titleText; ?>" href="index.php?option=com_zonales&view=mapa&ajax=true&tmpl=component_only">Mediabit</a>
	</p>
</div>