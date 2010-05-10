<?php
/**
 * @version	$Id$
 * @package	Zonales
 * @copyright	Copyright (C) 2009 Mediabit. All rights reserved.
 * @license	GNU/GPL, see LICENSE.php
 *
 * Zonales is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die ( 'Restricted Access' );
JHTML::_('behavior.formvalidation');

?>
<script language="javascript" type="text/javascript">
    <!--

    <?php if (!is_null($eq)): ?>
    window.addEvent('domready', function() {
        /* Slider 1 */
        <?php foreach ($eq->bands as $band): ?>
        var mySlide<?php echo $band->id;?> = new Slider($('area<?php echo $band->id;?>'), $('knob<?php echo $band->id;?>'), {
            steps: 100,
            onChange: function(step) {
                $('upd<?php echo $band->id;?>').setHTML(step);
            }
        }).set(<?php echo $band->peso;?>);
        <?php endforeach;?>

        // Anpassung IE6
	if(window.ie6) var heightValue='100%';
	else var heightValue='';

	// Selektoren der Container für Schalter und Inhalt
	var togglerName='div.toggler_';
	var contentName='div.collapse_';


	// Selektoren setzen
	var counter=1;
	var toggler=$$(togglerName+counter);
	var content=$$(contentName+counter);

	while(toggler.length>0)
	{
		// Accordion anwenden
		new Accordion(toggler, content, {
			opacity: false,
			display: -1,
			alwaysHide: true,
			onComplete: function() {
				var element=$(this.elements[this.previous]);
				if(element && element.offsetHeight>0) element.setStyle('height', heightValue);
			},
			onActive: function(toggler, content) {
				toggler.addClass('open');
			},
			onBackground: function(toggler, content) {
				toggler.removeClass('open');
			}
		});

		// Selektoren für nächstes Level setzen
		counter++;
		toggler=$$(togglerName+counter);
		content=$$(contentName+counter);
	}
    });
    <?php endif; ?>
    //-->
</script>
<!-- form -->
<div class="moduletable_formEq">
    <h1><?php echo $module->title; ?></h1>
    <div style="margin-left:10px; margin-right:10px; margin-bottom:10px;">
        <p><?php echo $description; ?></p>
        <div class="splitter"></div>

        <?php if (!is_null($eq)): ?>
        <form action="index.php" method="post" id="formEq" name="formEq">
            <ul id="accordion">

                <?php foreach ($eq->bands as $band): ?>
                <li>
                    <div class="toggler_1">Banda <?php echo $band->id;?></div>
                    <div  class="collapse_1">
                        <div class="slider-container">
                            <div class="slider-value">
                                <p id="upd<?php echo $band->id;?>">0</p>
                            </div>
                            <div id="area<?php echo $band->id;?>" class="slider">
                                <div id="knob<?php echo $band->id;?>" class="knob"></div>
                            </div>
                        </div>
                    </div>
                </li>
                <?php endforeach; ?>

            </ul>
            <input id="submit" type="submit" name="submit" class="button" />
        </form>
        <?php else: ?>
        <p>Ud. no cuenta con un Ecualizador!</p>
        <?php endif;?>
    </div>
    
</div><!-- end #moduletable_formVecinos -->
<!-- form -->