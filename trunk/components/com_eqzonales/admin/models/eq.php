<?php

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model' );

class EqZonalesModelEq extends ZonalesModelBaseModel
{
	function _getQuery()
	{
		$dbo =& JFactory::getDBO();

		$query =
                    'SELECT '
                    .$dbo->nameQuote('e.id') .','. $dbo->nameQuote('e.nombre') .','
                    .$dbo->nameQuote('e.descripcion') .','. $dbo->nameQuote('e.observaciones') .','
                    .$dbo->nameQuote('e.user_id') .','. $dbo->nameQuote('e.solrquery_bq')
                    .' FROM ' . $dbo->nameQuote('#__eqzonales_eq') . ' e';

		return $query;
	}

	function _buildQuery($customQuery = false)
	{
		$query = $this->_getQuery();

		if (!$customQuery) {
			$this->setWhere('e.id = ' . $this->_id );
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