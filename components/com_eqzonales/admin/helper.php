<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

class comEqZonalesAdminHelper
{
	/** @var JCache */
	var $_cache = null;

	function __construct($default = array())
	{
		$this->_cache =& JFactory::getCache('com_zonales');
	}

	/**
	 * Recupera una lista de los items de menu publicados en Joomla!
	 *
	 * @return array Lista de menùes recuperados
	 */
	function getJoomlaMenuItems()
	{
		$dbo	= & JFactory::getDBO();
		$query = 'SELECT ' . $dbo->nameQuote('m.id') .', '. $dbo->nameQuote('m.name')
			.' FROM ' . $dbo->nameQuote('#__menu') . ' m'
			.' WHERE '. $dbo->nameQuote('published') . ' = 1';
		$dbo->setQuery($query);

		return $this->_cache->get(array($dbo, 'loadObjectList'), array());
	}

	/**
	 * Recupera una lista de los fields cargados en CP.
	 *
	 * @return array Lista de zonales recuperados
	 */
	function getCpFields()
	{
		$dbo	= & JFactory::getDBO();
		$query = 'SELECT ' . $dbo->nameQuote('f.id') .', '. $dbo->nameQuote('f.name') .', '. $dbo->nameQuote('f.label')
			.' FROM ' . $dbo->nameQuote('#__custom_properties_fields') . ' f';
		$dbo->setQuery($query);

		return $this->_cache->get(array($dbo, 'loadObjectList'), array());
	}

	/**
	 * Recupera información acerca de un tipo de tag.

	 * @param string $tipo nombre del tipo a recuperar
	 * @return object información del tipo recuperado
	 */
	function getTipo($tipo)
	{
		if (is_null($tipo)) {
			return null;
		}
		$dbo	= & JFactory::getDBO();
		$query = 'SELECT ' . $dbo->nameQuote('t.id') .', '. $dbo->nameQuote('t.tipo')
			.' FROM ' . $dbo->nameQuote('#__zonales_tipotag') . ' t'
			.' WHERE '. $dbo->nameQuote('t.tipo') .' = '. $dbo->quote($tipo);
		$dbo->setQuery($query);

		return $this->_cache->get(array($dbo, 'loadObject'), array());
	}

	/**
	 * Recupera una lista de fields usados en los menues
	 *
	 * @return array Lista de fields recuperados
	 */
	function getCpMenuFields()
	{
		$tipo = $this->getTipo('menu');

		$dbo	= & JFactory::getDBO();
		$query = 'SELECT ' . $dbo->nameQuote('f.id') .', '. $dbo->nameQuote('f.name') .', '. $dbo->nameQuote('f.label')
			.' FROM ' . $dbo->nameQuote('#__custom_properties_fields') . ' f'
			.' INNER JOIN '. $dbo->nameQuote('#__zonales_cp2tipotag') . ' tt'
			.' ON '. $dbo->nameQuote('tt.field_id') .' = '. $dbo->nameQuote('f.id')
			.' AND '. $dbo->nameQuote('tt.tipo_id') .' = '. $tipo->id;
		$dbo->setQuery($query);

		return $this->_cache->get(array($dbo, 'loadObjectList'), array());
	}

	/**
	 * Recupera una lista de los values asociados a un tag menu.
	 *
	 * @return array Lista de values recuperados
	 */
	function getCpMenuValues($field_id)
	{
		$tipo = $this->getTipo('menu');

		$dbo	= & JFactory::getDBO();
		$query = 'SELECT ' . $dbo->nameQuote('v.id') .', '. $dbo->nameQuote('v.name') .', '. $dbo->nameQuote('v.label')
			.' FROM ' . $dbo->nameQuote('#__custom_properties_values') . ' v'
			.' INNER JOIN '. $dbo->nameQuote('#__zonales_cp2tipotag') . ' tt'
			.' ON '. $dbo->nameQuote('tt.field_id') .' = '. $dbo->nameQuote('v.field_id')
			.' AND '. $dbo->nameQuote('tt.tipo_id') .' = '. $tipo->id
			.' AND '. $dbo->nameQuote('tt.field_id') .' = '. $field_id;
		$dbo->setQuery($query);

		return $this->_cache->get(array($dbo, 'loadObjectList'), array());
	}

}