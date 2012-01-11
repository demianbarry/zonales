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
	margin-right:10px;
}

span#municipio_container{
	float:right;
}
</style>
<form action="index.php" method="post" id="formComboZona" name="formComboZona" class="combo_zonal_form" style="margin: 0; padding: 0;">

    <span id="z_provincias_container" style="width:219px;">
        <select class="provincias_select required" size="1" name="provincias" id="provincias"><option value="0">Seleccione una provincia...</option>
        </select>
    </span>
    <span id="municipio_container" style="width:219px;">
        <span id="z_localidad_container" ></span>
        <select class="item_ajax_select required" size="1" name="zonalid" id="zonalid" style="width: 219px;"><option value="0">Seleccione un municipio...</option>
        </select>
    </span>


    <input type="hidden" name="task" value="setZonalById" />
    <input type="hidden" name="option" value="com_zonales" />
    <?php echo JHTML::_('form.token'); ?>

</form>

