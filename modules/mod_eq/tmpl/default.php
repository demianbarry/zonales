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
<?php if (!is_null($eq)): ?>
<script language="javascript" type="text/javascript">
    <!--

    window.addEvent('domready', function() {

        /* Slider 1 */
    <?php foreach ($eq->fields as $group): ?>
        <?php foreach ($group->bands as $band): ?>
                var mySlide<?php echo $band->id;?> = new Slider($('area<?php echo $band->id;?>'), $('knob<?php echo $band->id;?>'), {
                    steps: 100,
                    onChange: function(step) {
                        $('upd<?php echo $band->id;?>').setHTML(step);
                        $('<?php echo $band->id;?>-<?php echo $band->cp_value_id;?>').value = '<?php echo $band->id;?>-<?php echo $band->cp_value_id;?>-' + step +'-MOD';
                    }
                }).set(<?php echo $band->peso;?>);
        <?php endforeach;?>
    <?php endforeach;?>

            // En caso IE6
            if(window.ie6) var heightValue='100%';
            else var heightValue='';

            // Divs para el título (toggler) y los contenedores (collapse)
            var togglerName='div.toggler_';
            var contentName='div.collapse_';

            // Recuperamos divs toggler y collapse
            var counter=1;
            var toggler=$$(togglerName+counter);
            var content=$$(contentName+counter);

            while(toggler.length>0)
            {
                // Acordeon
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

            $('formEq').addEvent('submit', function(e) {
                new Event(e).stop();
                $('respuesta').empty().addClass('ajax-loading');
                this.send({
                    onSuccess: function(response) {
                        var resp = Json.evaluate(response);
                        if (resp.status == 'SUCCESS') {

                        }
                        $('respuesta').removeClass('ajax-loading').setHTML(resp.msg);
                        window.location.reload();
                    }
                });
            });

            //var myEqSlider = new Fx.Slide('mod_eq_main_div', {fps: 20});
            // lo oculto por defecto
            //myEqSlider.hide();

            // clickeando en el título muestro u oculto el formulario
            //$('title_eq').addEvent('click', function(e) {
            //    myEqSlider.toggle();
            //});
            $('mod_eq_main_div').setStyle('display','none');
            $('title_eq').addClass('show');

            // clickeando en el título muestro u oculto el formulario
            $('title_eq').addEvent('click', function(e) {
                if($('mod_eq_main_div').getStyle('display') == 'none') {
                    $('mod_eq_main_div').setStyle('display','block');
                    $('title_eq').removeClass('show').addClass('hide');
                } else  {
                    $('mod_eq_main_div').setStyle('display','none');
                    $('title_eq').removeClass('hide').addClass('show');
                }
            });


        });
        //-->
</script>
<?php endif;?>
<!-- form -->
<div class="moduletable_formEq">
    <h1 id="title_eq"><?php echo $use_module_title ? $module->title : $title; ?></h1>
    <div id="mod_eq_main_div" class="moduletable_formEq_bodyDiv">
        <p><?php echo $description; ?></p>
        <div class="splitter"></div>

        <form action="index.php" method="post" id="formEq" name="formEq">
            <?php if (!is_null($eq)): ?>
            <ul id="accordion">
                    <?php foreach ($eq->fields as $group): ?>
                <li>
                    <div class="toggler_1"><?php echo $group->label;?></div>
                    <div  class="collapse_1">
                                <?php foreach ($group->bands as $band): ?>
                        <div class="slider-container">
                            <div class="slider-title">
                                <p><?php echo $band->band_label;?></p>
                            </div>
                            <div class="slider-value">
                                <p id="upd<?php echo $band->id;?>">0</p>
                            </div>
                            <div id="area<?php echo $band->id;?>" class="slider">
                                <div id="knob<?php echo $band->id;?>" class="knob"></div>
                            </div>
                            <!-- <input type="hidden" id="<?php echo $band->id.'-'.$band->cp_value_id; ?>"
                                   name="<?php echo $band->id.'-'.$band->cp_value_id; ?>" value="" /> -->
                            <input type="hidden" id="<?php echo $band->id.'-'.$band->cp_value_id; ?>"
                                   name="slider[]" value="" />
                        </div>
                                <?php endforeach; ?>
                    </div>
                </li>
                    <?php endforeach; ?>
            </ul>

            <input id="eqid" name="eqid" type="hidden" value="<?php echo $eq->eq->id; ?>" />

            <input type="hidden" name="task" value="band.modifyBandAjax" />
            <input type="hidden" name="option" value="com_eqzonales" />
            <input type="hidden" name="format" value="raw" />
                <?php echo JHTML::_('form.token'); ?>

            <input id="submit" type="submit" name="submit" class="button" />
            <?php else: ?>
            <p><?php echo $error_no_eq; ?></p>
            <?php endif;?>
        </form>

        <br/>
        <p id="respuesta"></p>
    </div>

</div><!-- end #moduletable_formVecinos -->
<!-- form -->