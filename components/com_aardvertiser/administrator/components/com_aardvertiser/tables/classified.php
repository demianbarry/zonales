<?php
defined('_JEXEC') or die ('Restricted Access');
class TableClassified extends JTable
{
	var $id = null;
	var $cat_id = null;
	var $user_id = null;
	var $ad_name = null;
	var $ad_img1 = null;
	var $ad_img1small = null;
	var $ad_img2 = null;
	var $ad_img2small = null;
	var $ad_desc = null;
	var $ad_state = null;
	var $ad_price = null;
	var $ad_location = null;
	var $ad_latitude = null;
        var $ad_longitude = null;
	var $ad_delivery = null;
	var $contact_name = null;
	var $contact_tel = null;
	var $contact_email = null;
        var $contact_address = null;
	var $date_created = null;
        var $date_expiration = null;
	var $published = null;
        var $impressions = null;
        
		function __construct(&$db)
	{
		parent::__construct('#__aard_ads', 'id', $db);
	}
}
?>
