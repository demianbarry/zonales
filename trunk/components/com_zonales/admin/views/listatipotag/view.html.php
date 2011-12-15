<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.view');

class ZonalesViewListaTipoTag extends ZonalesViewBaseView
{
	function display($tpl = null)
	{
		$option = JRequest::getCMD('option'); 
                $mainframe=JFactory::getApplication();
		$this->_context = $option . 'ListaTipoTag';		// nombre del contexto
		$this->_orderfield = 't.tipo';					// campo de ordenamiento
		$this->_searchfield = 't.tipo';				// campo de búsqueda
		$this->configure();

		$this->setPagination();	// setea la paginación

		$tipos =& $this->get('All');
		foreach ($tipos as $tipo) {
			$tipo->link = JRoute::_('index.php?option=' . $option . '&cid[]=' . $tipo->id . '&task=editTipoTag');
		}

		// Titulo
		$document = & JFactory::getDocument();
		$document->setTitle(JText::_( 'Administrar tipos de Tags' ));

		// Asigna variables en la vista y la muestra
		$this->assignRef('tipos', $tipos);

		parent::display($tpl);
	}
}
