<?php
defined('_JEXEC') or die ('Restricted Access');
class TableCategory extends JTable
{
	var $id = null;
	var $cat_name = null;
	var $cat_img = null;
	var $cat_desc = null;
	var $ordering = null;
	var $published = null;
	function __construct(&$db)
	{
		parent::__construct('#__aard_cats', 'id', $db);
	}
}
?>