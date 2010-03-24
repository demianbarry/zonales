<?php

/**
 * Description of ProviderModelProvider
 *
 * @author g2p
 */
require_once JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_alias' . DS .'tables' . DS . 'providers.php';
require_once 'basemodel.php';

class AliasModelProvider extends AliasModelBaseModel{

    function AliasModelProvider() {
        $this->__construct();
    }

    function  __construct() {
        parent::__construct('TableProviders');
    }

    function _getQuery() {

        $query = 'SELECT * FROM #__providers p ';

        return $query;
    }

    function _buildQuery($customQuery = false)
	{
		$query = $this->_getQuery();

		if (!$customQuery) {
			$this->setWhere('p.id = ' . $this->_id );
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

        function getProtocolTypeModel() {
            $model = $this->getDependentModel('protocoltype');
            $model->setId($this->getData()->protocol_type_id);

            return $model;
        }
}
?>
