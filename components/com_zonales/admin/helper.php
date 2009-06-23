<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

class comZonalesAdminHelper
{
	/** @var JCache */
	var $_cache = null;

	function __construct($default = array())
	{
		$this->_cache =& JFactory::getCache('com_zonales');
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

}