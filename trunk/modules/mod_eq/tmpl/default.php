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
<style media="screen" type="text/css">

    #area {
        background: #ccc;
        height: 20px;
        width: 90%;
    }

    #knob {
        height: 20px;
        width: 20px;
        background: #000;
    }

    #area2 {
        background: #ccc;
        height: 20px;
        width: 90%;
    }

    #knob2 {
        height: 20px;
        width: 20px;
        background: #000;
    }

    .slider {
        margin-bottom:10px;
        margin-top:10px;
    }

</style>
<script language="javascript" type="text/javascript">
    <!--

    window.addEvent('domready', function() {
        /* Slider 1 */
        var mySlide1 = new Slider($('area'), $('knob'), {
            steps: 100,
            onChange: function(step) {
                $('upd').setHTML(step);
            }
        }).set(0);
        /* Slider 2 */
        var mySlide2 = new Slider($('area2'), $('knob2'), {
            steps: 100,
            onChange: function(step){
                $('upd2').setHTML(step);
            }
        }).set(0);
        /* Slider 3 */
        var mySlide3 = new Slider($('area3'), $('knob3'), {
            steps: 100,
            onChange: function(step){
                $('upd3').setHTML(step);
            }
        }).set(0);

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
    //-->
</script>
<!-- form -->
<div class="moduletable_formEq">
    <h1><?php echo $module->title; ?></h1>
    <div style="margin-left:10px; margin-right:10px; margin-bottom:10px;">
        <p><?php echo $description; ?></p>
        <div class="splitter"></div>

        <ul id="accordion">
            <li>
                <div class="toggler_1">Banda 1</div>
                <div  class="collapse_1">
                    <p id="upd">0</p>
                    <div id="area" class="slider">
                        <div id="knob"></div>
                    </div>
                </div>
            </li>

            <li>
                <div class="toggler_1">Banda 2</div>
                <div class="collapse_1">
                    <p id="upd2">0</p>
                    <div id="area2" class="slider">
                        <div id="knob2"></div>
                    </div>
                </div>
            </li>

            <li>
                <div class="toggler_1">Banda 3</div>
                <div class="collapse_1">
                    <p id="upd3">0</p>
                    <div id="area3" class="slider">
                        <div id="knob3"></div>
                    </div>
                </div>
            </li>
            
        </ul>
    </div>
    <form action="index.php" method="post" id="formEq" name="formEq">
        <input id="submit" type="submit" name="submit" class="button" />
    </form>
    <div style="clear:both;" />
</div><!-- end #moduletable_formVecinos -->
<!-- form -->