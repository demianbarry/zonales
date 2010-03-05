<?php

/**
 * Description of ProviderModelProvider
 *
 * @author g2p
 */
class AliasModelProtocolType extends AliasModelBaseModel{

    function AliasModelProtocolType() {
        return $this->__construct();
    }

    function  __construct() {
        $this->addTablePath(dirname(dirname(__FILE__)). DIRECTORY_SEPARATOR . 'tables');
    }

    function _getQuery() {
        $dbo =& JFactory::getDBO();

        $query =
        'SELECT t.id, t.name, t.function as func '.
        'FROM #__protocol_types t ';

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
?>
