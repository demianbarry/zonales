<?php

/**
 * Description of ProviderModelProvider
 *
 * @author g2p
 */

require_once JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_alias' . DS .'tables' . DS . 'protocoltypes.php';
require_once 'basemodel.php';


class AliasModelProtocolType extends AliasModelBaseModel{

    function AliasModelProtocolType() {
        $this->__construct();
    }

    function  __construct() {
        parent::__construct('TableProtocoltypes');
    }

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
