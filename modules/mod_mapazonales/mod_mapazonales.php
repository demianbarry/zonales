<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

require_once (JPATH_BASE.DS.'components'.DS.'com_zonales'.DS.'helper.php');
jimport ('joomla.filesystem.file');

// parametros
//$showLabel = $params->get('show_label', 'show_label');
//$labelText = $params->get('label_text', 'label_text');
//$titleText = $params->get('title_text', 'title_text');
//$height = $params->get('height_popup');
//$width = $params->get('width_popup');
//$useFlash = $params->get('use_flash', false);

// template
$app =& JFactory::getApplication();
$template = $app->getTemplate();

//// valores para los parametros del template
//$tparams = new JParameter(JFile::read(JPATH_BASE.DS.'templates'.DS.
//		$template.DS.'params.ini'));
//
//$mainColor = $tparams->get('mainColor', null);
//if ($mainColor) $mainColor = '_' . $mainColor;

require(JModuleHelper::getLayoutPath('mod_mapazonales'));