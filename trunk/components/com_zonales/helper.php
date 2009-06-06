<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

class comZonalesHelper
{
	/**
	 * Recupera una lista de los zonales actualmente disponibles
	 *
	 * @return array Lista de zonales recuperados
	 */
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

	/**
	 * Retorna el identificador del zonal actual de la sesión, o NULL en 
	 * caso de que no se encuentre seteado ninguno.
	 * 
	 * @return  int Identificador del zonal actual
	 */
	function getZonalActual()
	{
		$session = JFactory::getSession();
		return $session->get('zonales_zonal_id', NULL);
	}

	/**
	 * Recupera información acerca de un zonal en particular. Si no se
	 * especifica un identificador se recuperará información acerca del
	 * zonal actual.
	 * 
	 * @param int Identificador del zonal
	 * @return object Objeto con información acerca del zonal indicado
	 */
	function getZonal($zonal_id = NULL)
	{
		if (is_null($zonal_id))
		{
			$zonal_id = $this->getZonalActual();
			if (is_null($zonal_id)) return NULL;
		}

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