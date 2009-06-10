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
		$query = 'SELECT ' . $dbo->nameQuote('f.id') .', '. $dbo->nameQuote('f.name') .', '. $dbo->nameQuote('f.label')
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
	 * @param int zonal_id Identificador del zonal
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
		$query = 'SELECT ' . $dbo->nameQuote('f.id') .', '. $dbo->nameQuote('f.name') .', '. $dbo->nameQuote('f.label')
			.' FROM ' . $dbo->nameQuote('#__custom_properties_fields') . ' f'
			.' WHERE '. $dbo->nameQuote('f.id') .' = '. $zonal_id;
		$dbo->setQuery($query);

		$cache =& JFactory::getCache('com_zonales');
		$zonal = $cache->get(array($dbo, 'loadObject'), array());

		return $zonal;
	}

	/**
	 * Recupera información de un zonal a partir de un nombre.
	 * 
	 * @param string $name Nombre del zonal
	 * @return object Objeto con información acerca del zonal indicado
	 */
	function getZonalByName($name)
	{
		$dbo	= & JFactory::getDBO();
		$query = 'SELECT ' . $dbo->nameQuote('f.id') .', '. $dbo->nameQuote('f.label')
			.' FROM ' . $dbo->nameQuote('#__custom_properties_fields') . ' f'
			.' WHERE '. $dbo->nameQuote('f.name') .' = '. $dbo->quote($name);
		$dbo->setQuery($query);

		$cache =& JFactory::getCache('com_zonales');
		$zonal = $cache->get(array($dbo, 'loadObject'), array());

		return $zonal;
	}

	/**
	 * Devuelve un array para convertir identificadores de zona de
	 * Joomla a un identificador de zona del mapa flash (map.swf)
	 * Los índices del array asociativo contienen los ZoneID habilitados
	 * El valor correspondiente a este índice es el FlashID usado en map.swf
	 *
	 * @return array Arreglo con indetificadores
	 */
	function getZif2SifMap()
	{
		$j2f = array();

		$zonales = $this->getZonales();

		foreach ($zonales as $zonal) {
			$j2f[$zonal->name] = $zonal->name;
		}

		return $j2f;
	}
}