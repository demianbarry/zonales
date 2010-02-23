<?php
defined('_JEXEC') or die('Restricted access');

class TableEq extends JTable
{
    /** @var int */
    var $id             = null;
    /** @var string */
    var $nombre         = null;
    /** @var string */
    var $descripcion    = null;
    /** @var string */
    var $observaciones  = null;
    /** @var int */
    var $user_id        = null;
    /** @var string */
    var $solrquery_bq   = null;

    function __construct(&$db)
    {
        parent::__construct( '#__eqzonales_eq', 'id', $db );
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