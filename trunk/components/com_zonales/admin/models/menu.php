<?php

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model' );

class ZonalesModelMenu extends ZonalesModelBaseModel
{
	function _getQuery()
	{
		$dbo =& JFactory::getDBO();

		$query =
		'SELECT '
		.$dbo->nameQuote('m.id') .','. $dbo->nameQuote('m.menu_id') . ','
		.$dbo->nameQuote('m.value_id') .','. $dbo->nameQuote('m.title') . ','
		.$dbo->nameQuote('v.name') .' AS vname,'. $dbo->nameQuote('jm.name') .' AS jname,'
		.$dbo->nameQuote('v.field_id')
		.' FROM ' . $dbo->nameQuote('#__zonales_menu') . ' m'
		.' INNER JOIN '. $dbo->nameQuote('#__menu') . ' jm'
		.' ON '. $dbo->nameQuote('jm.id') .' = ' .$dbo->nameQuote('m.menu_id')
		.' INNER JOIN ' . $dbo->nameQuote('#__custom_properties_values') . ' v'
		.' ON '. $dbo->nameQuote('v.id') .' = '. $dbo->nameQuote('m.value_id');

		return $query;
	}

	function _buildQuery($customQuery = false)
	{
		$query = $this->_getQuery();

		if (!$customQuery) {
			$this->setWhere('m.id = ' . $this->_id );
		}
		return $query . $this->getWhereClause();
	}

	function _buildAllQuery()
	{
		$query = $this->_getQuery();
		return $query . $this->getWhereClause() . $this->getOrderByClause();
	}

	function afterCheck(&$row)
	{
		return true;
	}
}