<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

class TOOLBAR_eqzonales_default
{
	function _DEFAULT() {
		JToolBarHelper::title( JText::_( 'EQZONALES'));
	        JToolBarHelper::preferences('com_eqzonales', 400, 575);
	}
}