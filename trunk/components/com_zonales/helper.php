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
		$query = 'SELECT ' . $dbo->nameQuote('f.id') .', '. $dbo->nameQuote('f.label')
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

		// Los identificadores utilizados en el mapa Flash fueron
		// tomados de la página web del Ministerio del Interior.

		// Deben reconfigurarse los identificadores internos de Joomla
		// Les puse los nombres de los partidos y la terminación _id, pero
		// estos pueden ser cualquier cosa, siempre que sean únicos

		// GBA - Zona Sur
		//$j2f["la_plata_id"] = "BUE066";
		$j2f["berisso_id"] = "BUE014";
		$j2f["ensenada_id"] = "BUE035";
		$j2f["berazategui_id"] = "BUE013";
		$j2f["varela_id"] = "BUE040";
		$j2f["quilmes_id"] = "BUE101";
		$j2f["almirante_brown_id"] = "BUE004";
		$j2f["echeverria_id"] = "BUE037";
		$j2f["ezeiza_id"] = "BUE039";
		$j2f["lomas_id"] = "BUE074";
		$j2f["lanus_id"] = "BUE067";
		$j2f["avellaneda_id"] = "BUE005";
		// GBA - Zona Oeste
		$j2f["matanza_id"] = "BUE065";
		$j2f["merlo_id"] = "BUE082";
		$j2f["moron_id"] = "BUE086";
		$j2f["tres_febrero_id"] = "BUE128";
		$j2f["san_martin_id"] = "BUE055";
		$j2f["hurlingham_id"] = "BUE060";
		$j2f["ituzaingo_id"] = "BUE061";
		$j2f["moreno_id"] = "BUE085";
		$j2f["san_miguel_id"] = "BUE116";
		$j2f["josecpaz_id"] = "BUE062";
		$j2f["malvinas_argentinas_id"] = "BUE078";
		// GBA - Zona Norte
		$j2f["vicente_lopez_id"] = "BUE131";
		$j2f["san_isidro_id"] = "BUE115";
		$j2f["san_fernando_id"] = "BUE114";
		$j2f["tigre_id"] = "BUE123";
		// Ciudad Autónoma de Buenos Aires
		$j2f["caba_id"] = "CABA";

		// Probablemente este array se pueda generar automáticamente por sql el día de mañana

		return $j2f;
	}
}