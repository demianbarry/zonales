<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

class ZonalesViewEditaCp2TipoTag extends JView
{
	/** @var QuotaOptions */
	var $_cp2tipotag;

	function display($tpl = null)
	{
		global $option, $mainframe;

		$this->_cp2tipotag =& $this->get('Data');

		// Setea la barra de menúes y el titulo del documento según la accion
		$document = & JFactory::getDocument();
		if (!$this->_cp2tipotag) {
			JToolBarHelper::title( JText::_( 'Asociar grupo a tag' ) .': <small><small>[ '. JText::_( 'New' ) .' ]</small></small>', 'menu.png' );
			$document->setTitle(JText::_( 'Asociar grupo a tag' ) .': ['. JText::_( 'New' ) .']');
		} else {
			JToolBarHelper::title( JText::_( 'Asociar grupo a tag' ) .': <small><small>[ '. JText::_( 'Edit' ) .' ]</small></small>', 'menu.png' );
			$document->setTitle(JText::_( 'Asociar grupo a tag' ) .': ['. JText::_( 'Edit' ) .']');
		}

		$lists = $this->getSelectLists($this->_cp2tipotag);


		// Asigna variables en la vista y la muestra
		$this->assignRef('cp2tipotag', $this->_cp2tipotag);
		$this->assignRef('lists', $this->getSelectLists($this->_cp2tipotag));

		parent::display($tpl);
	}

	/**
	 * Genera HTML Selects para seleccionar tag y field (CP).
	 * 
	 * @param object $cp2tipotag relación actual para obtener valores a editar
	 * @return array Arreglo con HTML Selects
	 */
	private function getSelectLists($cp2tipotag)
	{
		// opción nula
		$blank_option[] = JHTML::_('select.option', '0', JText::_('Seleccione una opcion'), 'id', 'tipo');
		// tipos de tags
		$tipotagmodel =& JModel::getInstance('TipoTag', 'ZonalesModel');
		$tipotags =& $tipotagmodel->getAll();
		// genera select html
		$tipo_list = array_merge($blank_option, $tipotags);
		$lists['tipo_lst'] = JHTML::_('select.genericlist', $tipo_list, 'tipo_id',
		    'size="1" required', 'id', 'tipo', $cp2tipotag->tipo_id);
		
		// fields de CP tags
		$helper = new comZonalesAdminHelper();
		$fields = $helper->getCpFields();
		// opción nula
		unset ($blank_option);
		$blank_option[] = JHTML::_('select.option', '0', JText::_('Seleccione una opcion'), 'id', 'label');
		// genera select html
		$field_lst = array_merge($blank_option, $fields);
		$lists['field_lst'] = JHTML::_('select.genericlist', $field_lst, 'field_id',
		    'size="1" required', 'id', 'label', $cp2tipotag->field_id);

		return $lists;
	}
}