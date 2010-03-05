<?php

/**
 * Description of ProviderModelProvider
 *
 * @author g2p
 */
class AliasModelProvider extends AliasModelBaseModel{

    function AliasModelProvider() {
        return $this->__construct();
    }

    function  __construct() {
        $this->addTablePath(dirname(dirname(__FILE__)). DIRECTORY_SEPARATOR . 'tables');
    }

    function _getQuery() {
        $dbo =& JFactory::getDBO();

        $query =
        'SELECT p.id, p.name as provider, p.discovery_url, p.parameters, ' .
        'p.description, p.observation, p.icon_url, p.access, p.prefix, p.suffix, '.
        'p.required_input, p.apikey, p.secretkey, t.name as protocol '.
        'FROM #__providers p INNER JOIN #__protocol_types t '.
        'ON p.protocol_type_id=t.id ';

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
}
?>
