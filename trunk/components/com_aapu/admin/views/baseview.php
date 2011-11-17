<?php

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.view');
jimport('joomla.html.pagination');

class AapuBaseView extends JView
{
	var $_context 		= null;
	var $_orderfield 	= null;
	var $_searchfield 	= null;
	var $_lists 		= null;

	/**
	 * Tareas comunes a pantalla de listas
	 *
	 */
	function configure()
	{
		$option = JRequest::getCMD('option'); 
                $mainframe = JFactory::getApplication();

		// orden
		$filter_order		= $mainframe->getUserStateFromRequest( $this->_context.'filter_order', 'filter_order', $this->_orderfield, 'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $this->_context.'filter_order_Dir', 'filter_order_Dir', 'asc', 'word' );
		// cadena de búsqueda
		$search = $mainframe->getUserStateFromRequest( $this->_context.'search', 'search', '', 'string' );
		$search	= JString::strtolower( $search );

		// Configuramos el modelo
		$model =& $this->getModel();
		$model->_orderby_filter_order = $filter_order;
		$model->_orderby_filter_order_dir = $filter_order_Dir;

		// seteamos búsqueda en el modelo
		$db = JFactory::getDBO();
		if ( $search ) {
			$model->setWhere('LOWER('. $this->_searchfield . ') LIKE '. $db->Quote( '%'.$db->getEscaped( $search, true ).'%', false ));
		}

		// table ordering
		$this->_lists['order_Dir']	= $filter_order_Dir;
		$this->_lists['order']		= $filter_order;
		$this->_lists['search'] 	= $search;

		$this->assignRef('lists', $this->_lists);
	}

	function setPagination()
	{
		// Barra de paginación
		$pagination =& $this->get('Pagination');

		// Asigna variables en la vista y la muestra
		$this->assignRef('pagination', $pagination);
	}

	function display($tpl = null)
	{
		parent::display($tpl);
	}
}