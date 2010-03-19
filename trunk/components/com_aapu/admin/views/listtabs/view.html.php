<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.view');

class AapuViewListTabs extends AapuBaseView
{
	function display($tpl = null)
	{
		global $option, $mainframe;

		$this->_context = $option . 'ListTabs';		// nombre del contexto
		$this->_orderfield = 'ac.label';		// campo de ordenamiento
		$this->_searchfield = 'ac.label';		// campo de búsqueda
		$this->configure();

		$this->setPagination();                         // setea la paginación

		$tabs =& $this->get('All');
		foreach ($tabs as $tab) {
			$tab->link = JRoute::_('index.php?option=' . $option . '&cid[]=' . $tab->id . '&task=editTab');
		}

		// Titulo
		$document = & JFactory::getDocument();
		$document->setTitle(JText::_( 'Tabs administration' ));

		// Asigna variables en la vista y la muestra
		$this->assignRef('tabs', $tabs);

		parent::display($tpl);
	}
}