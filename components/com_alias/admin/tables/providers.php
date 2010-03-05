<?php
defined('_JEXEC') or die();

/**
 * Description of providers
 *
 * @author kristian
 */
class TableProviders extends JTable {
    var $id = null;
    var $name = '';
    var $discovery_url = null;
    var $parameters = null;
    var $protocol_type_id = 0;
    var $description = null;
    var $observation = null;
    var $icon_url = '';
    var $access = 0;
    var $prefix = '';
    var $suffix = '';
    var $required_input = '::';
    var $apikey = null;
    var $secretkey = null;

    /**
     * @param database A database connector object
     */
    function __construct( &$db ) {
        parent::__construct( '#__providers', 'id', $db );
    }
}
?>
