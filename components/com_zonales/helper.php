<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

class comZonalesHelper
{
	/** @var JCache */
	var $_cache = null;

	function __construct($default = array())
	{
		$this->_cache =& JFactory::getCache('com_zonales');
	}

	/**
	 * Recupera una lista de los zonales actualmente disponibles. 
	 *
	 * @return array Lista de zonales recuperados
	 */
	function getZonales()
	{
		$dbo	= & JFactory::getDBO();
		$query = 'SELECT ' . $dbo->nameQuote('f.id') .', '. $dbo->nameQuote('f.name') .', '. $dbo->nameQuote('f.label')
			.' FROM ' . $dbo->nameQuote('#__custom_properties_fields') . ' f';
		$dbo->setQuery($query);

		$zonales = $this->_cache->get(array($dbo, 'loadObjectList'), array());

		return $zonales;
	}

	/**
	 * Retorna el identificador del zonal actual de la sesión, o null en
	 * caso de que no se encuentre seteado ninguno.
	 *
	 * @return  string Identificador del zonal actual
	 */
	function getZonalActual()
	{
		$session = JFactory::getSession();
		return $session->get('zonales_zonal_name', null);
	}

	/**
	 * Recupera información acerca de un zonal en particular. Si no se
	 * especifica el nombre interno se recuperará información acerca del
	 * zonal actual.
	 *
	 * @param int zonal_name Nombre interno del zonal
	 * @return object Objeto con información acerca del zonal indicado
	 */
	function getZonal($zonal_name = null)
	{
		if (is_null($zonal_name))
		{
			$zonal_name = $this->getZonalActual();
			if (is_null($zonal_name)) return null;
		}

		$dbo	= & JFactory::getDBO();
		$query = 'SELECT ' . $dbo->nameQuote('f.id') .', '. $dbo->nameQuote('f.name') .', '. $dbo->nameQuote('f.label')
			.' FROM ' . $dbo->nameQuote('#__custom_properties_fields') . ' f'
			.' WHERE '. $dbo->nameQuote('f.name') .' = '. $dbo->quote($zonal_name);
		$dbo->setQuery($query);

		$zonal = $this->_cache->get(array($dbo, 'loadObject'), array());

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
		return $this->getZonal($name);
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

	/**
	 * Recupera los grupos de tags indicados como menúes.
	 *
	 * @param string prefix prefijo que indica que un grupo es un menú
	 * @return array Arreglo con menúes
	 */
	function getMenus($prefix)
	{
		if (is_null($prefix))
		{
			// TODO: error
		}

		$dbo	= & JFactory::getDBO();
		$query = 'SELECT ' . $dbo->nameQuote('f.id') .', '. $dbo->nameQuote('f.name') .', '. $dbo->nameQuote('f.label')
			.' FROM ' . $dbo->nameQuote('#__custom_properties_fields') . ' f'
			.' WHERE '. $dbo->nameQuote('f.name') .' REGEXP '. $dbo->quote('^'.$prefix);
		$dbo->setQuery($query);

		$zonal = $this->_cache->get(array($dbo, 'loadObjectList'), array());

		return $zonal;
	}

	/**
	 * Recupera los valores del tag indicado
	 *
	 * @param int id identificador del tag
	 * @return array Arreglo de objetos value
	 */
	function getMenuValues($id)
	{
		if (is_null($id) || !is_numeric($id))
		{
			return null;
		}

		$dbo	= & JFactory::getDBO();
		$query = 'SELECT ' . $dbo->nameQuote('v.id') .', '. $dbo->nameQuote('v.name') .', '. $dbo->nameQuote('v.label')
			.' FROM ' . $dbo->nameQuote('#__custom_properties_values') . ' v'
			.' WHERE '. $dbo->nameQuote('v.field_id') .' = '. $id;
		$dbo->setQuery($query);

		$zonal = $this->_cache->get(array($dbo, 'loadObjectList'), array());

		return $zonal;
	}

	/**
	 * Recupera una lista de los fields cargados en CP.
	 *
	 * @return array Lista de zonales recuperados
	 */
	function getCpFields()
	{
		$dbo	= & JFactory::getDBO();
		$query = 'SELECT ' . $dbo->nameQuote('f.id') .', '. $dbo->nameQuote('f.name') .', '. $dbo->nameQuote('f.label')
			.' FROM ' . $dbo->nameQuote('#__custom_properties_fields') . ' f';
		$dbo->setQuery($query);

		return $this->_cache->get(array($dbo, 'loadObjectList'), array());
	}

}