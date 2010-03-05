<?php
/**
 * Description of ProviderModelProvider
 *
 * @author g2p
 */
class AliasModelAlias extends AliasModelBaseModel{

    function _getQuery() {

        $query =
        'SELECT a.user_id, a.id, a.name as alias, a.association_date, '.
        'a.block, a.activation, p.name as provider '.
        'FROM #__alias a INNER JOIN #__providers p ON a.provider_id=p.id ';

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
