<?php

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model' );

require_once 'basemodel.php';

/**
 * Description of Attribute Entity
 *
 * @author nacho
 */
class AapuModelAttribute_entity extends AapuModelBaseModel {

    function _getQuery()
	{
		$dbo =& JFactory::getDBO();

		$query =
		'SELECT * '
		.' FROM ' . $dbo->nameQuote('#__aapu_attribute_entity') . ' ae';

		return $query;
	}

	function _buildQuery($customQuery = false)
	{
		$query = $this->_getQuery();

		if (!$customQuery) {
			$this->setWhere('ae.id = ' . $this->_id );
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
?>
