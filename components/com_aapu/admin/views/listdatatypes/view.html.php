<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.view');

class AapuViewListDataTypes extends AapuBaseView
{
	function display($tpl = null)
	{
		global $option, $mainframe;

		$this->_context = $option . 'ListDataTypes';		// nombre del contexto
		$this->_orderfield = 'dt.label';		// campo de ordenamiento
		$this->_searchfield = 'dt.label';		// campo de búsqueda
		$this->configure();

		$this->setPagination();                         // setea la paginación

		$dataTypes =& $this->get('All');
		foreach ($dataTypes as $dataType) {
			$dataType->link = JRoute::_('index.php?option=' . $option . '&cid[]=' . $dataType->id . '&task=editDataType');
		}

		// Titulo
		$document = & JFactory::getDocument();
		$document->setTitle(JText::_( 'Data Types administration' ));

		// Asigna variables en la vista y la muestra
		$this->assignRef('dataTypes', $dataTypes);

		parent::display($tpl);
	}
}