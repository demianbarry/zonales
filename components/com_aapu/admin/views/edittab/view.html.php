<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

class AapuViewEditTab extends JView
{
	/** @var QuotaOptions */
	var $_tab;

	function display($tpl = null)
	{
		global $option, $mainframe;

		$this->_tab =& $this->get('Data');

		// Setea la barra de menúes y el titulo del documento según la accion
		$document = & JFactory::getDocument();
		if (!$this->_tab->id) {
			JToolBarHelper::title( JText::_( 'EDIT_TAB' ) .': <small><small>[ '. JText::_( 'New' ) .' ]</small></small>', 'menu.png' );
			$document->setTitle(JText::_( 'EDIT_TAB' ) .': ['. JText::_( 'New' ) .']');
		} else {
			JToolBarHelper::title( JText::_( 'EDIT_TAB' ) .': <small><small>[ '. JText::_( 'Edit' ) .' ]</small></small>', 'menu.png' );
			$document->setTitle(JText::_( 'EDIT_TAB' ) .': ['. JText::_( 'Edit' ) .']');
		}


		// Asigna variables en la vista y la muestra
		$this->assignRef('tab', $this->_tab);

		parent::display($tpl);
	}
}