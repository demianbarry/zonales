<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

class AapuViewEditDataType extends JView
{
	/** @var QuotaOptions */
	var $_tab;

	function display($tpl = null)
	{
		global $option, $mainframe;

		$this->_dataType =& $this->get('Data');

		// Setea la barra de menúes y el titulo del documento según la accion
		$document = & JFactory::getDocument();
		if (!$this->_dataType->id) {
			JToolBarHelper::title( JText::_( 'EDIT_DATA_TYPE' ) .': <small><small>[ '. JText::_( 'New' ) .' ]</small></small>', 'menu.png' );
			$document->setTitle(JText::_( 'EDIT_dataType' ) .': ['. JText::_( 'New' ) .']');
		} else {
			JToolBarHelper::title( JText::_( 'EDIT_dataType' ) .': <small><small>[ '. JText::_( 'Edit' ) .' ]</small></small>', 'menu.png' );
			$document->setTitle(JText::_( 'EDIT_dataType' ) .': ['. JText::_( 'Edit' ) .']');
		}


		// Asigna variables en la vista y la muestra
		$this->assignRef('dataType', $this->_dataType);

		parent::display($tpl);
	}
}