<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

class TOOLBAR_zonales_default
{
	function _DEFAULT() {
		JToolBarHelper::title( JText::_( 'ZONALES'));
	        JToolBarHelper::preferences('com_zonales', 400, 575);
	}
}

class TOOLBAR_zonales_tipotag
{
	function _NEW() {
		JToolBarHelper::save('saveTipoTag');
		JToolBarHelper::apply('applyTipoTag');
		JToolBarHelper::cancel('cancelTipoTag');
	}


	function _DEFAULT() {
		JToolBarHelper::title( JText::_( 'ZONALES'));
	        JToolBarHelper::preferences('com_zonales', 400, 575);
		JToolBarHelper::editList('editTipoTag');
		JToolBarHelper::deleteList(JText::_( 'DELETE_PLAN_WARNING' ), 'removeTipoTag');
		JToolBarHelper::addNew('addTipoTag');
	}
}

class TOOLBAR_zonales_cp2tipotag
{
	function _NEW() {
		JToolBarHelper::save('saveCp2TipoTag');
		JToolBarHelper::apply('applyCp2TipoTag');
		JToolBarHelper::cancel('cancelCp2TipoTag');
	}


	function _DEFAULT() {
		JToolBarHelper::title( JText::_( 'ZONALES'));
	        JToolBarHelper::preferences('com_zonales', 400, 575);
		JToolBarHelper::editList('editCp2TipoTag');
		JToolBarHelper::deleteList(JText::_( 'DELETE_PLAN_WARNING' ), 'removeCp2TipoTag');
		JToolBarHelper::addNew('addCp2TipoTag');
	}
}

class TOOLBAR_zonales_menu
{
	function _NEW() {
		JToolBarHelper::save('saveMenu');
		JToolBarHelper::apply('applyMenu');
		JToolBarHelper::cancel('cancelMenu');
	}


	function _DEFAULT() {
		JToolBarHelper::title( JText::_( 'ZONALES'));
	        JToolBarHelper::preferences('com_zonales', 400, 575);
		JToolBarHelper::editList('editMenu');
		JToolBarHelper::deleteList(JText::_( 'DELETE_PLAN_WARNING' ), 'removeMenu');
		JToolBarHelper::addNew('addMenu');
	}
}

