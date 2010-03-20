<?php
defined('_JEXEC') or die ('Restricted Access');
class TableClassified extends JTable
{
	var $postcode = null;
	var $x = null;
	var $y = null;
		function __construct(&$db)
	{
		parent::__construct('#__aard_post', 'postcode', $db);
	}
}
?>
