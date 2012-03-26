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
JHTML::_('behavior.formvalidation');
?>

<style>
    span#z_provincias_container{
        margin-left:10px;
        color : black;
        text-align: left;
    }


</style>
<div style="margin: 0; padding: 0;" class="combo_zonal_form" name="formComboZona" id="formComboZona" method="post" action="index.php" autocomplete="off">

    <span id="z_provincias_container" style="position: absolute; left: 49%;">
        <input class="text" id="zoneExtended" type="text" value="Seleccione una zona..." style="width: 300px;" onkeyup="populateOptions(event, this, false, extendedStrings,function(zone){setZone(zone, '', '', '');drawMap(zone);ajustMapToExtendedString(zone);});"
               onfocus="if(this.value=='Seleccione una zona...') this.value='';" >

    </span>
    <span style="top: 3.4%;">
        <img src="/images/reset.gif" onclick="setSelectedZone(''); $('zoneExtended').value = 'Seleccione una zona...';drawMap('Argentina');ajustMapToExtendedString('Argentina');">
    </span>



    <input type="hidden" value="setZonalById" name="task">
    <input type="hidden" value="com_zonales" name="option">
    <?php echo JHTML::_('form.token'); ?>
</div>