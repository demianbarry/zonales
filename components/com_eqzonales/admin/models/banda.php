<?php

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model' );

require_once JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_zonales' . DS . 'models' . DS . 'basemodel.php';

class EqZonalesModelBanda extends ZonalesModelBaseModel
{
	function _getQuery()
	{
		$dbo =& JFactory::getDBO();

		$query =
                    'SELECT '
                    .$dbo->nameQuote('e.id') .','. $dbo->nameQuote('e.valor') .','
                    .$dbo->nameQuote('e.peso') .','. $dbo->nameQuote('e.cp_value_id') .','
                    .$dbo->nameQuote('e.eq_id') .','. $dbo->nameQuote('e.default') .','
                    .$dbo->nameQuote('e.active')
                    .' FROM ' . $dbo->nameQuote('#__eqzonales_banda') . ' e';

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