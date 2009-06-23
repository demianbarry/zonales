<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

class ZonalesViewEditaTipoTag extends JView
{
	/** @var QuotaOptions */
	var $_tipotag;

	function display($tpl = null)
	{
		global $option, $mainframe;

		$this->_tipotag =& $this->get('Data');

		// Setea la barra de menúes y el titulo del documento según la accion
		$document = & JFactory::getDocument();
		if (!$this->_tipotag) {
			JToolBarHelper::title( JText::_( 'EDITAR_TIPO_TAG' ) .': <small><small>[ '. JText::_( 'New' ) .' ]</small></small>', 'menu.png' );
			$document->setTitle(JText::_( 'EDITAR_TIPO_TAG' ) .': ['. JText::_( 'New' ) .']');
		} else {
			JToolBarHelper::title( JText::_( 'EDITAR_TIPO_TAG' ) .': <small><small>[ '. JText::_( 'Edit' ) .' ]</small></small>', 'menu.png' );
			$document->setTitle(JText::_( 'EDITAR_TIPO_TAG' ) .': ['. JText::_( 'Edit' ) .']');
		}


		// Asigna variables en la vista y la muestra
		$this->assignRef('tipotag', $this->_tipotag);

		parent::display($tpl);
	}
}