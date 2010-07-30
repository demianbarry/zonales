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
<div id="mod_combozonal" class="moduletable_combozonal">
    <h1><?php echo $module->title; ?></h1>

    <form action="index.php" method="post" id="formComboZona" name="formComboZona" class="combo_zonal_form">
        <?php if(!$zonal): ?>
        <p>
                <?php echo JText::_('MOD_COMBOZONA_WELCOME');?>
        </p>
        <?php endif;?>
        <div id="z_provincias_container">
            <p><label class="combo_zonal_label">Provincia</label></p>
            <?php echo $lists['provincias_select']; ?>
        </div>
        <div id="municipio_container" style="display: none;">
            <p><label class="combo_zonal_label">Municipio</label></p>
            <div id="z_localidad_container"></div>
            <input class="combo_zonal_button" type="submit" value="<?php if($zonal) echo JText::_('MOD_COMBOZONA_CHANGE_ZONAL'); else echo JText::_('MOD_COMBOZONA_CHOOSE_ZONAL')?>" id="changeZonal" name="changeZonal" />
            <input class="combo_zonal_button" type="<?php echo ($zonal ? 'submit' : 'hidden')?>" value="<?php echo JText::_('MOD_COMBOZONA_CLEAR_ZONAL');?>" id="clearZonal" name="clearZonal" onclick="$('formComboZona').task.value='clearZonal'"/>
            <br/>
        </div>

        <input type="hidden" name="task" value="setZonalById" />
        <input type="hidden" name="option" value="com_zonales" />
        <?php echo JHTML::_('form.token'); ?>
    </form>
</div>
<!-- Validacion -->
<script language="javascript" type="text/javascript">
    <!--

    window.addEvent('domready', function() {
        $('provincias').addEvent('change', function() {
            loadMunicipios();
        });
        loadMunicipios(<?php echo "$selectedOption" ?>);
    });

    function loadMunicipios(selected){
        $('z_localidad_container').empty().addClass('ajax-loading');
        var url='index.php?option=com_zonales&format=raw&task=getItemsAjax&id='+$('provincias').value+'&name=zonalid&selected='+selected;
        new Ajax(url, {
            method: 'get',
            onComplete: function(response) {
                $('z_localidad_container').removeClass('ajax-loading').setHTML(response);
                $('municipio_container').setStyle('display','block');
            }
        }).request();
    }
    //-->
</script>