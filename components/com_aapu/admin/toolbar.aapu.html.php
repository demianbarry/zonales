<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

class TOOLBAR_aapu_default
{
	function _DEFAULT() {
		JToolBarHelper::title( JText::_( 'AAPU'));
	        //JToolBarHelper::preferences('com_zonales', 400, 575);
	}
}

class TOOLBAR_aapu_tabs
{
	function _NEW() {
		JToolBarHelper::save('saveTab');
		JToolBarHelper::apply('applyTab');
		JToolBarHelper::cancel('cancelTab');
	}


	function _DEFAULT() {
		JToolBarHelper::title( JText::_( 'AAPU'));
	        //JToolBarHelper::preferences('com_zonales', 400, 575);
		JToolBarHelper::editList('editTab');
		JToolBarHelper::deleteList(JText::_( 'DELETE_PLAN_WARNING' ), 'removeTab');
		JToolBarHelper::addNew('addTab');
	}
}

class TOOLBAR_aapu_attributes
{
	function _NEW() {
		JToolBarHelper::save('saveAttribute');
		JToolBarHelper::apply('applyAttribute');
		JToolBarHelper::cancel('cancelAttribute');
	}


	function _DEFAULT() {
		JToolBarHelper::title( JText::_( 'AAPU'));
	        //JToolBarHelper::preferences('com_zonales', 400, 575);
		JToolBarHelper::editList('editAttribute');
		JToolBarHelper::deleteList(JText::_( 'DELETE_PLAN_WARNING' ), 'removeAttribute');
		JToolBarHelper::addNew('addAttribute');
	}
}

class TOOLBAR_aapu_datatypes
{
	function _NEW() {
		JToolBarHelper::save('saveDataType');
		JToolBarHelper::apply('applyDataType');
		JToolBarHelper::cancel('cancelDataType');
	}


	function _DEFAULT() {
		JToolBarHelper::title( JText::_( 'AAPU'));
	        //JToolBarHelper::preferences('com_zonales', 400, 575);
		JToolBarHelper::editList('editDataType');
		JToolBarHelper::deleteList(JText::_( 'DELETE_PLAN_WARNING' ), 'removeDataType');
		JToolBarHelper::addNew('addDataType');
	}
}

