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
// no direct access
defined('_JEXEC') or die('Restricted access');
?>
<!--<div id="mod_combozonal" class="moduletable_combozonal">-->
    <!--h1><?php echo $module->title; ?></h1-->

<!--<form action="index.php" method="post" id="formComboZona" name="formComboZona" class="combo_zonal_form">
<?php //if(!$zonal):  ?>
    <p>
<?php //echo JText::_('MOD_COMBOZONA_WELCOME'); ?>
    </p>
<?php //endif; ?>
    <div id="z_provincias_container">
        <p><label class="combo_zonal_label">Provincia</label></p>
<?php //echo $lists['provincias_select'];  ?>
    </div>
    <div id="municipio_container" style="display: none;">
        <p><label class="combo_zonal_label">Municipio</label></p>
        <div id="z_localidad_container"></div>
        <input class="combo_zonal_button" type="submit" value="<?php //if($zonal) echo JText::_('MOD_COMBOZONA_CHANGE_ZONAL'); else echo JText::_('MOD_COMBOZONA_CHOOSE_ZONAL') ?>" id="changeZonal" name="changeZonal" />
        <input class="combo_zonal_button" type="<?php //echo ($zonal ? 'submit' : 'hidden') ?>" value="<?php //echo JText::_('MOD_COMBOZONA_CLEAR_ZONAL'); ?>" id="clearZonal" name="clearZonal" onclick="$('formComboZona').task.value='clearZonal'"/>
        <br/>
    </div>

    <input type="hidden" name="task" value="setZonalById" />
    <input type="hidden" name="option" value="com_zonales" />
<?php //echo JHTML::_('form.token');  ?>
</form>-->
<style>
span#z_provincias_container{
	margin-right:10px;
}

span#municipio_container{
	float:right;
}
</style>
<form action="index.php" method="post" id="formComboZona" name="formComboZona" class="combo_zonal_form" style="margin: 0; padding: 0;">
    <!--<strong>UBICACIÓN: </strong>-->
    <span id="z_provincias_container" style="width:219px;">
        <?php echo $lists['provincias_select']; ?>
    </span>
    <span id="municipio_container" style="display: none;width:219px;">
        <span id="z_localidad_container" ></span>
        <?php /*
          <input class="combo_zonal_button" type="<?php echo ($zonal ? 'submit' : 'hidden')?>" value="<?php echo JText::_('Ver portada');?>" id="clearZonal" name="clearZonal" onclick="$('formComboZona').task.value='clearZonal'"/>
         */ ?>
    </span>
    <!--input class="combo_zonal_button" type="submit" value="Enviar"/-->

    <input type="hidden" name="task" value="setZonalById" />
    <input type="hidden" name="option" value="com_zonales" />
    <?php echo JHTML::_('form.token'); ?>

</form>

<!--</div>-->
<!-- Validacion -->
<script language="javascript" type="text/javascript">
    <!--

    window.addEvent('domready', function() {
        $('provincias').addEvent('change', function() {
            loadMunicipios();
        });

        var newElement =  new Element('option');
	newElement.inject($('provincias'), 'top');
	newElement.setProperty('value','0');
	newElement.appendText('Seleccione una provincia...');
	$('provincias').selectedIndex = 0;

	<?php if (!empty($selectedParent)) {?>
            $('provincias').value = <?php echo $selectedParent;?>;
        <?php } ?>

        loadMunicipios(<?php echo "$selectedOption" ?>);

    });



    function loadMunicipios(selected){
        $('z_localidad_container').empty().addClass('ajax-loading');
        var url='index.php?option=com_zonales&tmpl=component&task=getItemsAjax&id='+$('provincias').value+'&name=zonalid&selected='+selected;
        new Request({
	    url:url,
            method: 'get',
            onComplete: function(response) {
                $('z_localidad_container').removeClass('ajax-loading').set('html',response);
                $('zonalid').setStyle('width','219px');
                var newElement =  new Element('option');
                newElement.inject($('zonalid'), 'top');
                newElement.setProperty('value','0');
                newElement.appendText('Seleccione un municipio...');
                $('zonalid').selectedIndex = 0;
                if($('provincias').value==0){
                    $('zonalid').disabled = true;
                }else{
                    $('zonalid').value = selected;
                }
                $('municipio_container').setStyles({display:'',width:'219px'});
                $('zonalid').addEvent('change', function() {
                    $('formComboZona').submit();
                });

            }

        }).send();
    }
    //-->
</script>
