<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.view');

class ZonalesViewListaMenu extends ZonalesViewBaseView
{
	function display($tpl = null)
	{
		global $option, $mainframe;

		$this->_context = $option . 'ListaMenu';		// nombre del contexto
		$this->_orderfield = 'm.id';				// campo de ordenamiento
		$this->_searchfield = 'm.id';				// campo de búsqueda
		$this->configure();

		$this->setPagination();	// setea la paginación

		$menus =& $this->get('All');
		foreach ($menus as $menu) {
			$menu->link = JRoute::_('index.php?option=' . $option . '&cid[]=' . $menu->id . '&task=editMenu');
			$menu->jmenu_edit_link = JRoute::_('http://zonales.localhost.com/administrator/index.php?option=com_menus&menutype=mainmenu&task=edit&cid[]='. $menu->menu_id);
		}

		// Titulo
		$document = & JFactory::getDocument();
		$document->setTitle(JText::_( 'Administrar Asociaciones de Menu' ));

		// Asigna variables en la vista y la muestra
		$this->assignRef('menus', $menus);

		parent::display($tpl);
	}
}
