<?php
// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die ( 'Restricted Access' );
?>

<script type="text/javascript" defer="defer">
	// <![CDATA[
	window.addEvent('domready', function(){

		// hide all submenu links
		$('sublinks').getElements('ul').setStyle('display', 'none');
		// show by default the first submenu
		$('s0_m').setStyle('display', 'block');

		$$('#mymenu li').each(function(el){
			el.getElement('a').addEvent('mouseover', function(subLinkId){
				var layer = subLinkId+"_m";
				$('sublinks').getElements('ul').setStyle('display', 'none');
				$(layer).setStyle('display', 'block');
			}.pass(el.id));
		});

	});
	// ]]>
</script>

<!-- glassmenu -->
<div id="navigation">
	<ul id="mymenu">
		<li id="s0"><a href="<?php echo JURI::base(); ?>"><?php echo JText::_('Inicio'); ?></a></li>
		<?php foreach ($menues as $menu): ?>
		<li id="s<?php echo $menu->id; ?>"><a href="<?php echo($menu->link ? $menu->link : '#'); ?>"><?php echo $menu->label; ?></a></li>
		<?php endforeach; ?>
	</ul>
</div>
<div id="sublinks">
	<ul id="s0_m"><li /></ul>
	<?php foreach ($menues as $menu): ?>
	<ul id="s<?php echo $menu->id; ?>_m">
		<?php foreach ($menu->submenus as $submenu): ?>
		<li><a href="<?php echo $submenu->link; ?>"><?php echo $submenu->label; ?></a></li>
		<?php endforeach; ?>
	</ul>
	<?php endforeach; ?>
</div>
<!-- glassmenu -->