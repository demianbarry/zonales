<?php
defined('_JEXEC') or die();

/**
 * Description of protocoltypes
 *
 * @author kristian
 */
class TableProtocoltypes extends JTable {
    var $id = null;
    var $name = '';
    var $function = '';

    /**
     * @param database A database connector object
     */
    function __construct( &$db ) {
        parent::__construct( '#__protocol_types', 'id', $db );
    }
}
?>
