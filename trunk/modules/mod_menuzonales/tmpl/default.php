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
                <?php if (isset ($eq->fields)): ?>
		<?php foreach ($eq->fields as $field): ?>
		<li id="s<?php echo $field->id; ?>"><a href="<?php echo($field->link ? $field->link : '#'); ?>"><?php echo $field->label; ?></a></li>
		<?php endforeach; ?>
                <?php endif;?>
	</ul>
</div>
<div id="sublinks">
	<ul id="s0_m"><li /></ul>
        <?php if (isset ($eq->fields)): ?>
	<?php foreach ($eq->fields as $field): ?>
	<ul id="s<?php echo $field->id; ?>_m">
		<?php foreach ($field->bands as $band): ?>
		<li><a href="<?php echo $band->link; ?>"><?php echo $band->band_label; ?></a></li>
		<?php endforeach; ?>
	</ul>
	<?php endforeach; ?>
        <?php endif;?>
</div>
<!-- glassmenu -->