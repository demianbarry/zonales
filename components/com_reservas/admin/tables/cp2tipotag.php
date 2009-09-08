<?php
defined('_JEXEC') or die('Restricted access');

class TableCp2TipoTag extends JTable
{
	/** @var int */
	var $id		= null;
	/** @var int */
	var $tipo_id	= null;
	/** @var int */
	var $field_id	= null;

	function __construct(&$db)
	{
		parent::__construct( '#__zonales_cp2tipotag', 'id', $db );
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

		if ($this->tipo_id == 0 || $this->field_id == 0) {
			return false;
		}
		return true;
	}
}