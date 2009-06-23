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
	 * Recupera información acerca de un tipo de tag.

	 * @param string $tipo nombre del tipo a recuperar
	 * @return object información del tipo recuperado
	 */
	function getTipo($tipo)
	{
		if (is_null($tipo)) {
			return null;
		}
		$dbo	= & JFactory::getDBO();
		$query = 'SELECT ' . $dbo->nameQuote('t.id') .', '. $dbo->nameQuote('t.tipo')
			.' FROM ' . $dbo->nameQuote('#__zonales_tipotag') . ' t'
			.' WHERE '. $dbo->nameQuote('t.tipo') .' = '. $dbo->quote($tipo);
		$dbo->setQuery($query);

		return $this->_cache->get(array($dbo, 'loadObject'), array());
	}

	/**
	 * Recupera fields de CP según el tipo de tag asociado.
	 * 
	 * @param object información del tipo asociado
	 * @return array lista de fields recuperados
	 */
	function getFields($tipo)
	{
		if (is_null($tipo)) return null;

		$dbo	= & JFactory::getDBO();
		$query = 'SELECT ' . $dbo->nameQuote('f.id') .', '. $dbo->nameQuote('f.name') .', '. $dbo->nameQuote('f.label')
			.' FROM ' . $dbo->nameQuote('#__custom_properties_fields') . ' f'
			.' INNER JOIN '. $dbo->nameQuote('#__zonales_cp2tipotag') . ' c'
			.' ON '. $dbo->nameQuote('c.field_id') .' = '. $dbo->nameQuote('f.id')
			.' AND '. $dbo->nameQuote('c.tipo_id') .' = '. $tipo->id;
		$dbo->setQuery($query);

		return  $this->_cache->get(array($dbo, 'loadObjectList'), array());
	}

	/**
	 * Recupera una lista de los zonales actualmente disponibles.
	 *
	 * @return array Lista de zonales recuperados
	 */
	function getZonales()
	{
		$tipo = $this->getTipo('zonal');
		return $this->getFields($tipo);
	}

	/**
	 * Recupera los grupos de tags indicados como menúes.
	 *
	 * @return array Arreglo con menúes
	 */
	function getMenus()
	{
		$tipo = $this->getTipo('menu');
		return $this->getFields($tipo);
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
}