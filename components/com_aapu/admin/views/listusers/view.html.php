<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.view');

class AapuViewListUsers extends AapuBaseView
{
	function display($tpl = null)
	{
		$option = JRequest::getCMD('option');
                $mainframe = JFactory::getApplication();

		$this->_context = $option . 'ListUsers';		// nombre del contexto
		$this->_orderfield = 'u.name';		// campo de ordenamiento
		$this->_searchfield = 'u.name';		// campo de búsqueda
		$this->configure();

		$this->setPagination();                         // setea la paginación

		$users =& $this->get('All');
		foreach ($users as $user) {
			$user->link = JRoute::_('index.php?option=' . $option . '&cid[]=' . $user->id . '&task=editUser');
		}

		// Titulo
		$document = & JFactory::getDocument();
		$document->setTitle(JText::_( 'Users administration' ));

		// Asigna variables en la vista y la muestra
		$this->assignRef('users', $users);

		parent::display($tpl);
	}
}