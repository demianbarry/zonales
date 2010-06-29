<?php
require_once (JPATH_BASE.DS.'components'.DS.'com_zonales'.DS.'helper.php');
$helper = new comZonalesHelper();

//$view = 'component.php'; Comentado por Nacho

if(is_null($helper->getZonal()) && !$helper->isAuthenticationOnProgress() && $helper->showMap())
{
// si no se ha seleccionado un zonal o no hay ninguna autenticacion en progreso
//$view = 'component_map.php';
    $view = 'component_home.php';
}
/************** Agregado Nacho ****************/
else {
    $view = 'component.php';
}
include dirname(__FILE__) . DIRECTORY_SEPARATOR . $view;
?>