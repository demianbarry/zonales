<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

class TOOLBAR_reservas_default
{
	function _DEFAULT() {
		JToolBarHelper::title( JText::_( 'ZONALES'));
	        JToolBarHelper::preferences('com_reservas', 400, 575);
	}
}

class TOOLBAR_reservas_menu
{
	function _NEW() {
		JToolBarHelper::save('saveMenu');
		JToolBarHelper::apply('applyMenu');
		JToolBarHelper::cancel('cancelMenu');
	}


	function _DEFAULT() {
		JToolBarHelper::title( JText::_( 'ZONALES'));
	        JToolBarHelper::preferences('com_reservas', 400, 575);
		JToolBarHelper::editList('editMenu');
		JToolBarHelper::deleteList(JText::_( 'DELETE_PLAN_WARNING' ), 'removeMenu');
		JToolBarHelper::addNew('addMenu');
	}
}

