<?php
defined('_JEXEC') or die ('Restricted Access');
class TableEmail extends JTable
{
	var $id = null;
	var $subject = null;
	var $fromname = null;
	var $fromemail = null;
	function __construct(&$db)
	{
		parent::__construct('#__aard_email', 'id', $db);
	}
}
?>