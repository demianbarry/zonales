<?php
defined('_JEXEC') or die ('Restricted Access');
class TableConfig extends JTable
{
	var $id = null;
	var $days_shown = null;
	var $prune = null;
	var $font_color = null;
	var $currency = null;
	var $distance = null;
	var $ad_state_font = null;
	var $ad_detail_font = null;
	var $access = null;
	var $map = null;
	var $emailusers = null;
	var $catimg = null;
		function __construct(&$db)
	{
		parent::__construct('#__aard_config', 'id', $db);
	}
}
?>
