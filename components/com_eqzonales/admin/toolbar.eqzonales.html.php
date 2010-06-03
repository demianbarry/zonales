<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

class TOOLBAR_eqzonales_default
{
	function _DEFAULT() {
		JToolBarHelper::title( JText::_( 'EQZONALES'));
                //JToolBarHelper::addNewX('addAnonDefaultEq', 'Crear Ecualizador Anónimo');
                JToolBarHelper::custom( 'addAnonDefaultEq', 'download.png', 'download.png', 'Crear Ecualizador Anónimo', false, false);
	        JToolBarHelper::preferences('com_eqzonales', 400, 575);
	}
}