<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

class comUserZonalesHelper
{
	function getZonales() 
	{
	  	$dbo	= & JFactory::getDBO();
		$query = 'SELECT ' . $dbo->nameQuote('f.id') . ', ' . $dbo->nameQuote('f.label')
                    	.' FROM ' . $dbo->nameQuote('#__custom_properties_fields') . ' f';
		$dbo->setQuery($query);
		return $dbo->loadObjectList();
	}

	function getZonalActual()
	{
		$session = JFactory::getSession();
		return $session->get('zonales_zonal_id', NULL);
	}
}

