<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

class comZonalesAdminHelper
{
	/** @var JCache */
	var $_cache = null;

	function __construct($default = array())
	{
		$this->_cache =& JFactory::getCache('com_reservas');
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

	
}