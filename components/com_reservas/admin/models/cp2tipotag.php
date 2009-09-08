<?php

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model' );

class ZonalesModelCp2TipoTag extends ZonalesModelBaseModel
{
	function _getQuery()
	{
		$dbo =& JFactory::getDBO();

		$query =
		'SELECT '
		.$dbo->nameQuote('c.id') .','. $dbo->nameQuote('c.tipo_id') .','. $dbo->nameQuote('c.field_id') .','
		.$dbo->nameQuote('t.tipo') .','. $dbo->nameQuote('f.label')
		.' FROM ' . $dbo->nameQuote('#__zonales_cp2tipotag') . ' c'
		.' INNER JOIN ' . $dbo->nameQuote('#__zonales_tipotag') . ' t'
		.' ON '. $dbo->nameQuote('t.id') .' = '. $dbo->nameQuote('c.tipo_id')
		.' INNER JOIN ' . $dbo->nameQuote('#__custom_properties_fields') . ' f'
		.' ON '. $dbo->nameQuote('f.id') .' = '. $dbo->nameQuote('c.field_id');

		return $query;
	}

	function _buildQuery($customQuery = false)
	{
		$query = $this->_getQuery();

		if (!$customQuery) {
			$this->setWhere('c.id = ' . $this->_id );
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