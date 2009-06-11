<?php
require_once (JPATH_BASE.DS.'components'.DS.'com_zonales'.DS.'helper.php');
$helper = new comZonalesHelper();

$view = 'component.php';

if(is_null($helper->getZonal()))
{
// si no se ha seleccionado un zonal
$view = 'component_map.php';
}
include dirname(__FILE__) . DIRECTORY_SEPARATOR . $view;
?>