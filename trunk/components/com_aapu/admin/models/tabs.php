<?php

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model' );

require_once 'basemodel.php';

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
                $this->addWhere("name LIKE 'usr_%'");
		return $query . $this->getWhereClause() . $this->getOrderByClause();
	}


	function afterCheck(&$row)
	{
		return true;
	}
}
?>
