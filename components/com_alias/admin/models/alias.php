<?php
/**
 * Description of ProviderModelProvider
 *
 * @author g2p
 */
require_once JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_alias' . DS .'tables' . DS . 'alias.php';
require_once 'provider.php';
require_once 'basemodel.php';

class AliasModelAlias extends AliasModelBaseModel{

    function AliasModelAlias() {
        $this->__construct();
    }

    function  __construct() {
        parent::__construct('TableAlias');
    }

    function _getQuery() {

        $query = 'SELECT * FROM #__alias a ';

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

        function getProviderModel() {
            $model = $this->getDependentModel('provider');
            $model->setId($this->getData()->provider_id);

            return $model;
        }
        
}

?>
