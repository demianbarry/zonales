<?php
// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();

class TableAlias extends JTable {
    var $id = null;
    var $user_id = 0;
    var $name = '';
    var $provider_id = 0;
    var $association_date;
    var $block = 1;
    var $activation = '';
    
    /**
     * @param database A database connector object
     */
    function __construct( &$db ) {
        $this->association_date = date('Y-m-d');
        parent::__construct( '#__alias', 'id', $db );
    }
}

?>
