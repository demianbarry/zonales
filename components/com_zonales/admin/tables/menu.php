<?php
defined('_JEXEC') or die('Restricted access');

class TableMenu extends JTable
{
    /** @var int */
    var $id		= null;
    /** @var int */
    var $value_id	= null;
    /** @var int */
    var $menu_id	= null;
    /** @var string */
    var $title		= null;

    function __construct(&$db)
    {
        parent::__construct( '#__zonales_menu', 'id', $db );
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
        parent::bind($from, $ignore);

        return true;
    }

}