<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

class comZonalesHelper
{
	function getZonales() 
	{
	  	$dbo	= & JFactory::getDBO();
		$query = 'SELECT ' . $dbo->nameQuote('f.id') . ', ' . $dbo->nameQuote('f.label')
                    	.' FROM ' . $dbo->nameQuote('#__custom_properties_fields') . ' f';
		$dbo->setQuery($query);

        $cache =& JFactory::getCache('com_zonales');
        $zonales = $cache->get(array($dbo, 'loadObjectList'), array());
		
        return $zonales;
	}

    function getZonalActual()
	{
		$session = JFactory::getSession();
		return $session->get('zonales_zonal_id', NULL);
	}

	function getZonal($zonal_id)
	{
	  	$dbo	= & JFactory::getDBO();
		$query = 'SELECT ' . $dbo->nameQuote('f.id') .', '. $dbo->nameQuote('f.label')
                    	.' FROM ' . $dbo->nameQuote('#__custom_properties_fields') . ' f'
			.' WHERE '. $dbo->nameQuote('f.id') .' = '. $zonal_id;
		$dbo->setQuery($query);

        $cache =& JFactory::getCache('com_zonales');
        $zonal = $cache->get(array($dbo, 'loadObject'), array());

		return $zonal;
	}
}