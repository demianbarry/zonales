<?php
// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die ( 'Restricted Access' );
?>

<!-- glassmenu -->
<div id="navigation">
	<ul id="mymenu">
		<li id="s0"><a href="<?php echo JURI::base(); ?>"><?php echo JText::_('Inicio'); ?></a></li>
		<?php foreach ($menues as $menu): ?>
		<li id="s<?php echo $menu->id; ?>"><a href="#"><?php echo $menu->label; ?></a></li>
		<?php endforeach; ?>
	</ul>
</div>
<div id="sublinks">
	<?php foreach ($menues as $menu): ?>
	<ul id="s<?php echo $menu->id; ?>_m">
		<?php foreach ($menuValues[$menu->id] as $menuValue): ?>
		<a href="#"><?php echo $menuValue->label; ?></a>
		<?php endforeach; ?>
	</ul>
	<?php endforeach; ?>
</div>
<!-- glassmenu -->