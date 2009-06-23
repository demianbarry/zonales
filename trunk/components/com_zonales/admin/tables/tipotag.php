<?php
defined('_JEXEC') or die('Restricted access');

class TableTipoTag extends JTable
{
    /** @var int */
    var $id	= null;
    /** @var string */
    var $tipo	= null;

    function __construct(&$db)
    {
        parent::__construct( '#__zonales_tipotag', 'id', $db );
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