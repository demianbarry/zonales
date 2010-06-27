<?php

defined('_JEXEC') or die ('Restricted Access');
class TableImage extends JTable
{
    var $id = null;
    var $name = null;
    var $ad_id = null;
    var $small = null;

    	function __construct(&$db)
	{
		parent::__construct('#__aard_ads_images', 'id', $db);
	}
}

?>
