<?php

/**
 * Description of ProviderModelProvider
 *
 * @author g2p
 */
class AliasModelProtocolType extends AliasModelBaseModel{

    function _getQuery() {

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
