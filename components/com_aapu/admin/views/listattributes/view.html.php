<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.view');

class AapuViewListAttributes extends AapuBaseView
{
	function display($tpl = null)
	{
		global $option, $mainframe;

		$this->_context = $option . 'ListAttributes';		// nombre del contexto
		$this->_orderfield = 'a.label';		// campo de ordenamiento
		$this->_searchfield = 'a.label';		// campo de búsqueda
		$this->configure();

		$this->setPagination();                         // setea la paginación

		$attributes =& $this->get('All');
		foreach ($attributes as $attribute) {
                    if ($attribute->id > 3) {
			$attribute->link = JRoute::_('index.php?option=' . $option . '&cid[]=' . $attribute->id . '&task=editAttribute');
                    } else {
                        $attribute->link = JRoute::_('index.php?option=' . $option . '&task=listAttributes');
                    }
		}

		// Titulo
		$document = & JFactory::getDocument();
		$document->setTitle(JText::_( 'Attributes administration' ));

		// Asigna variables en la vista y la muestra
		$this->assignRef('attributes', $attributes);

		parent::display($tpl);
	}
}