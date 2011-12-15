<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.view');

class ZonalesViewListaCp2TipoTag extends ZonalesViewBaseView
{
	function display($tpl = null)
	{
		$option = JRequest::getCMD('option'); 
                $mainframe=JFactory::getApplication();
		$this->_context = $option . 'ListaCp2TipoTag';		// nombre del contexto
		$this->_orderfield = 'c.id';					// campo de ordenamiento
		$this->_searchfield = 'c.id';				// campo de búsqueda
		$this->configure();

		$this->setPagination();	// setea la paginación

		$tipos =& $this->get('All');
		foreach ($tipos as $tipo) {
			$tipo->link = JRoute::_('index.php?option=' . $option . '&cid[]=' . $tipo->id . '&task=editCp2TipoTag');
		}

		// Titulo
		$document = & JFactory::getDocument();
		$document->setTitle(JText::_( 'ADMINISTRAR_ASOCIACIONES' ));

		// Asigna variables en la vista y la muestra
		$this->assignRef('tipos', $tipos);

		parent::display($tpl);
	}
}
