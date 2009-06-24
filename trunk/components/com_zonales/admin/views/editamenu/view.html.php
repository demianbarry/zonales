<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

class ZonalesViewEditaMenu extends JView
{
	/** @var QuotaOptions */
	var $_menu;

	function display($tpl = null)
	{
		global $option, $mainframe;

		$this->_menu =& $this->get('Data');

		// Setea la barra de menúes y el titulo del documento según la accion
		$document = & JFactory::getDocument();
		if (!$this->_menu) {
			$accion = 'New';
		} else {
			$accion = 'Edit';
		}
		JToolBarHelper::title( JText::_( 'Asociar Item de Menú a Tag' ) .': <small><small>[ '. JText::_( $accion ) .' ]</small></small>', 'menu.png' );
		$document->setTitle(JText::_( 'Asociar Item de Menù a Tag' ) .': ['. JText::_( $accion ) .']');

		$lists = $this->getSelectLists($this->_menu);


		// Asigna variables en la vista y la muestra
		$this->assignRef('menu', $this->_menu);
		$this->assignRef('lists', $this->getSelectLists($this->_menu));

		parent::display($tpl);
	}

	/**
	 * Genera HTML Selects para seleccionar tag y field (CP).
	 * 
	 * @param object $menu relación actual para obtener valores a editar
	 * @return array Arreglo con HTML Selects
	 */
	private function getSelectLists($menu)
	{
		$helper = new comZonalesAdminHelper();

		// opción nula
		$blank_option[] = JHTML::_('select.option', '0', JText::_('Seleccione una opcion'), 'id', 'name');

		// values de fields asociados con un tipoo menu
		$values = $helper->getCpMenuValues();

		// genera select html
		$tipo_list = array_merge($blank_option, $values);
		$lists['value_lst'] = JHTML::_('select.genericlist', $tipo_list, 'value_id',
		    'size="1" required', 'id', 'name', $menu->value_id);
		
		// items de menu
		$itemsMenu = $helper->getJoomlaMenuItems();

		// opción nula
		unset ($blank_option);
		$blank_option[] = JHTML::_('select.option', '0', JText::_('Seleccione una opcion'), 'id', 'name');
		// genera select html
		$item_list = array_merge($blank_option, $itemsMenu);
		$lists['item_lst'] = JHTML::_('select.genericlist', $item_list, 'menu_id',
		    'size="1" required', 'id', 'name', $menu->menu_id);

		return $lists;
	}
}