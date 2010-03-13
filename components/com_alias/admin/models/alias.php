<?php
/**
 * Description of ProviderModelProvider
 *
 * @author g2p
 */

require_once 'basemodel.php';

require_once JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_alias' . DS .'tables' . DS . 'alias.php';

class AliasModelAlias extends AliasModelBaseModel{

    function AliasModelAlias() {
        $this->__construct();
    }

    function  __construct() {
        parent::__construct('TableAlias');
    }

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
