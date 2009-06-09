<?php
$session =& JFactory::getSession();
$zonal = $session->get('zonales_zonal_id', NULL);

$view = 'component.php';

if(is_null($zonal))
{
// si no se ha seleccionado un zonal
$view = 'component_map.php';
}
include dirname(__FILE__) . DIRECTORY_SEPARATOR . $view;
?>