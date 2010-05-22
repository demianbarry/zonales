<?php

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model' );

require_once 'basemodel.php';

/**
 * Description of tabs
 *
 * @author nacho
 */
class AapuModelAttributes extends AapuModelBaseModel {

    function __construct() {
        $path = JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_aapu' . DS . 'tables';
        $this->addTablePath($path);
        parent::__construct();
    }

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

        /**
         * Method to publish / unpublish one ore more fields
         * @return void
         */
        function publish() {
            $cid = JRequest::getVar('cid', '', '', 'array');
            $attr = & JTable::getInstance('attributes', 'Table');
            $action = JRequest::getCmd('task') == 'publish' ? 1 : 0;
            $result = $attr->publish($cid, $action);
        }
}
?>
