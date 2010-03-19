<?php

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model' );

/**
 * Description of tabs
 *
 * @author nacho
 */
class AapuModelTabs extends AapuModelBaseModel {

    function _getQuery()
	{
		$dbo =& JFactory::getDBO();

		$query =
		'SELECT * '
		.' FROM ' . $dbo->nameQuote('#__aapu_attribute_class') . ' ac';

		return $query;
	}

	function _buildQuery($customQuery = false)
	{
		$query = $this->_getQuery();

		if (!$customQuery) {
			$this->setWhere('ac.id = ' . $this->_id );
		}
		return $query . $this->getWhereClause();
	}

	function _buildAllQuery()
	{
		$query = $this->_getQuery();
		return $query . $this->getWhereClause() . " WHERE name LIKE 'usr_%' " . $this->getOrderByClause();
	}


	function afterCheck(&$row)
	{
		return true;
	}
}
?>
