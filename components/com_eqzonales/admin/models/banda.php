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
                    .$dbo->nameQuote('e.active') .','. $dbo->nameQuote('cpv.parent_id') .','
                    .$dbo->nameQuote('cpv.field_id') .','. $dbo->nameQuote('cpv.label') .' AS band_label,'
                    .$dbo->nameQuote('cpf.label') .' AS group_label' .','. $dbo->nameQuote('cpv.name') .' AS band_name,'
                    .$dbo->nameQuote('cpf.name') .' AS group_name'
                    .' FROM ' . $dbo->nameQuote('#__eqzonales_banda') . ' e'
                    .' INNER JOIN ' . $dbo->nameQuote('#__custom_properties_values') . ' cpv'
                    .' ON ' . $dbo->nameQuote('cpv.id') .' = '. $dbo->nameQuote('e.cp_value_id')
                    .' INNER JOIN ' . $dbo->nameQuote('#__custom_properties_fields') . ' cpf'
                    .' ON ' . $dbo->nameQuote('cpv.field_id') .' = '. $dbo->nameQuote('cpf.id');

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