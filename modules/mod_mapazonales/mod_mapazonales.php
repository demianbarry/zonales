<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

require_once (JPATH_BASE.DS.'components'.DS.'com_zonales'.DS.'helper.php');

// parametros
$showLabel = $params->get('show_label', 'show_label');
$labelText = $params->get('label_text', 'label_text');
$titleText = $params->get('title_text', 'title_text');
$height = $params->get('height_popup');
$width = $params->get('width_popup');

// template
$app =& JFactory::getApplication();
$template = $app->getTemplate();

require(JModuleHelper::getLayoutPath('mod_mapazonales'));