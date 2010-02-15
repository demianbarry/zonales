<?php
// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();

class JTableAlias extends JTable
{
    var $id = null;
    var $user_id = null;
    var $name = null;
    var $provider_id = null;
    var $association_date = null;
    var $block = null;
    var $activation = null;

    /**
	* @param database A database connector object
	*/
	function __construct( &$db )
	{
		parent::__construct( '#__alias', 'id', $db );
	}
}

?>
