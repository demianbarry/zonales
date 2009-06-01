<?php
// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die ( 'Restricted Access' );
?>

<div id="moduletable_zName">
    <p>
	<?php if($showLabel) { echo $labelText; } ?>
	<?php if($zonal_name): ?>
		<?php echo ' ' . $zonal_name->label; ?></p>
	<?php endif; ?>
</div>
