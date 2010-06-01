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

<!-- Validacion -->
<script language="javascript" type="text/javascript">
    <!--

    window.addEvent('domready', function() {
        $('provincias').addEvent('change', function(value) {
            var url='index.php?option=com_zonales&format=raw&task=getItemsAjax&id='+$('provincias').value+'&name=zonalid';
            new Ajax(url, {
                method: 'get',
                onComplete: function(response) {
                    $('z_localidad_container').setHTML(response);
                    $('municipio_container').setStyle('display','block');
                }
            }).request();
        });
    });
    //-->
</script>

<div id="mod_combozonal">
    <form action="index.php" method="post" id="formComboZona" name="formComboZona">
        <div id="z_provincias_container">
            <p><label>Provincia</label></p>
            <?php echo $lists['provincias_select']; ?>
        </div>
        <div id="municipio_container" style="display: none;">
            <p><label>Municipio</label></p>
            <div id="z_localidad_container"></div>
            <input type="submit" value="Cambiar Zonal" id="changeZonal" name="changeZonal" />
        <br/>
        </div>

        <input type="hidden" name="task" value="setZonalById" />
        <input type="hidden" name="option" value="com_zonales" />
        <?php echo JHTML::_('form.token'); ?>
    </form>
</div>