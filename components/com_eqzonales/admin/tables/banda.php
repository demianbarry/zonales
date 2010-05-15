<?php
defined('_JEXEC') or die('Restricted access');

class TableBanda extends JTable
{
    /** @var int */
    var $id             = null;
    /** @var string */
    var $valor          = null;
    /** @var int */
    var $peso           = null;
    /** @var int */
    var $cp_value_id    = null;
    /** @var int */
    var $eq_id          = null;
    /** @var boolean */
    var $default        = null;
    /** @var boolean */
    var $active         = null;

    function __construct(&$db)
    {
        parent::__construct( '#__eqzonales_banda', 'id', $db );
    }

    /**
     * Overloaded bind function
     *
     * @access public
     * @return boolean
     * @see JTable::bind
     * @since 1.5
     */
    function bind( $from, $ignore=array() )
    {
        return parent::bind($from, $ignore);
    }

}