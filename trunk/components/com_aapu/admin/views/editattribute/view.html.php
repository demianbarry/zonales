<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

class AapuViewEditAttribute extends JView
{
	/** @var QuotaOptions */
	var $_attribute;

	function display($tpl = null)
	{
		$option = JRequest::getCMD('option');
                $mainframe = JFactory::getApplication();

		$this->_attribute =& $this->get('Data');

		// Setea la barra de menúes y el titulo del documento según la accion
		$document = & JFactory::getDocument();
		if (!$this->_attribute->id) {
			JToolBarHelper::title( JText::_( 'EDIT_ATTRIBUTE' ) .': <small><small>[ '. JText::_( 'New' ) .' ]</small></small>', 'menu.png' );
			$document->setTitle(JText::_( 'EDIT_ATTRIBUTE' ) .': ['. JText::_( 'New' ) .']');
		} else {
			JToolBarHelper::title( JText::_( 'EDIT_ATTRIBUTE' ) .': <small><small>[ '. JText::_( 'Edit' ) .' ]</small></small>', 'menu.png' );
			$document->setTitle(JText::_( 'EDIT_ATTRIBUTE' ) .': ['. JText::_( 'Edit' ) .']');
		}

                $typesModel = &$this->getModel('tabs');
                $types = $typesModel->getAll();

                $dataTypesModel = &$this->getModel('datatypes');
                $dataTypes = $dataTypesModel->getAll();

		// Asigna variables en la vista y la muestra
		$this->assignRef('attribute', $this->_attribute);
                $this->assignRef('types', $types);
                $this->assignRef('dataTypes', $dataTypes);

		parent::display($tpl);
	}
}