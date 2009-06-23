<?php

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model' );

class ZonalesModelTipoTag extends ZonalesModelBaseModel
{
	function _getQuery()
	{
		$dbo =& JFactory::getDBO();

		$query =
		'SELECT '
		.$dbo->nameQuote('t.id') .','. $dbo->nameQuote('t.tipo')
		.' FROM ' . $dbo->nameQuote('#__zonales_tipotag') . ' t';

		return $query;
	}

	function _buildQuery($customQuery = false)
	{
		$query = $this->_getQuery();

		if (!$customQuery) {
			$this->setWhere('t.id = ' . $this->_id );
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