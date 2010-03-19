<?php

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model' );

/**
 * Description of tabs
 *
 * @author nacho
 */
class AapuModelAttributes extends AapuModelBaseModel {

    function _getQuery()
	{
		$dbo =& JFactory::getDBO();

		$query =
		'SELECT * '
		.' FROM ' . $dbo->nameQuote('#__aapu_attributes') . ' a';

		return $query;
	}

	function _buildQuery($customQuery = false)
	{
		$query = $this->_getQuery();

		if (!$customQuery) {
			$this->setWhere('a.id = ' . $this->_id );
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
