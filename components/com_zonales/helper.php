<?php
/**
 * @version	$Id$
 * @package	Zonales
 * @copyright	Copyright (C) 2009 Mediabit. All rights reserved.
 * @license	GNU/GPL, see LICENSE.php
 *
 * Zonales is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die ( 'Restricted Access' );

class comZonalesHelper {
    /** @var JCache */
    var $_cache = null;

    function __construct($default = array()) {
        $this->_cache =& JFactory::getCache('com_zonales');
    }

    /**
     * Retorna el identificador del zonal actual de la sesión, o null en
     * caso de que no se encuentre seteado ninguno.
     *
     * @return  string Identificador del zonal actual
     */
    function getZonalActual() {
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
    function getZonal($zonal_name = null) {
        if (is_null($zonal_name)) {
            $zonal_name = $this->getZonalActual();
            if (is_null($zonal_name)) return null;
        }

        $dbo	= & JFactory::getDBO();
        $query = 'SELECT ' . $dbo->nameQuote('v.id') .', '. $dbo->nameQuote('v.name') .', '.
                $dbo->nameQuote('v.parent_id') .', '. $dbo->nameQuote('v.label')
                .' FROM ' . $dbo->nameQuote('#__custom_properties_values') . ' v'
                .' WHERE '. $dbo->nameQuote('v.name') .' = '. $dbo->quote($zonal_name);
        $dbo->setQuery($query);

        $zonal = $this->_cache->get(array($dbo, 'loadObject'), array());

        return $zonal;
    }

    /**
     * Recupera información acerca de un zonal a partir de su Id
     *
     * @param int zonal_id Id del zonal
     * @return object Objeto con información acerca del zonal indicado
     */
    function getZonalById($zonal_id) {

        $dbo	= & JFactory::getDBO();
        $query = 'SELECT ' . $dbo->nameQuote('v.id') .', '. $dbo->nameQuote('v.name') .', '.
                $dbo->nameQuote('v.parent_id') .', '. $dbo->nameQuote('v.label')
                .' FROM ' . $dbo->nameQuote('#__custom_properties_values') . ' v'
                .' WHERE '. $dbo->nameQuote('v.id') .' = '. $dbo->quote($zonal_id);
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
    function getZonalByName($name) {
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
    function getZif2SifMap() {
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
    function getTipo($tipo) {
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
    function getFields($tipo) {
        if (is_null($tipo)) {
            return null;
        }

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
     * Recupera fields de CP según el tipo de tag asociado.
     *
     * El query recupera todos los values que esten asociados al field
     * 'root_zonales', y cuyos nombres comienzen con el prefijo 'bue_'. Este
     * es por ahora un compromiso para recuperar unicamente aquellos
     * values que puedan ser asociados al mapa flash.
     *
     * @param object información del tipo asociado
     * @return array lista de fields recuperados
     */
    function getValuesZonales() {

        $dbo	= & JFactory::getDBO();
        $query = 'SELECT v.id, v.name, v.label'
                .' FROM #__custom_properties_values v '
                .' WHERE v.field_id = '
                .' (SELECT f.id FROM #__custom_properties_fields f'
                .' WHERE f.name = "root_zonales") AND v.name LIKE "bue\_%"';
        $dbo->setQuery($query);

        return  $this->_cache->get(array($dbo, 'loadObjectList'), array());
    }

    /**
     * Recupera localidades según partido.
     *
     * @param object información del tipo asociado
     * @return array lista de fields recuperados
     */
    function getLocalidadesByPartido($id) {

        $dbo	= & JFactory::getDBO();
        $query = 'SELECT v.id, v.name, v.label FROM #__custom_properties_values v WHERE v.parent_id  = '.$id;
        $dbo->setQuery($query);

        return  $this->_cache->get(array($dbo, 'loadObjectList'), array());
    }

    /**
     * Recupera los valores del field indicado.
     *
     * @param int id identificador del field.
     * @return array Arreglo de objetos value
     */
    function getFieldValues($id) {
        if (is_null($id)) {
            return null;
        }

        $dbo	= & JFactory::getDBO();
        $query = 'SELECT '. $dbo->nameQuote('v.id') .', '. $dbo->nameQuote('v.name') .', ' .$dbo->nameQuote('v.label')
                .' FROM '. $dbo->nameQuote('#__custom_properties_values') . ' v'
                .' WHERE '. $dbo->nameQuote('v.field_id') .' = '. $id;
        $dbo->setQuery($query);

        return $this->_cache->get(array($dbo, 'loadObjectList'), array());
    }

    /**
     * Recupera una lista de los zonales actualmente disponibles.
     *
     * @return array Lista de zonales recuperados
     */
    function getZonales() {
        //$tipo = $this->getTipo('zonal');
        //return $this->getFields($tipo);
        return $this->getValuesZonales();

    }

    /**
     * Recupera los grupos de tags indicados como menúes.
     *
     * TODO: Utilizar el modelo Menu de com_zonales.
     *
     * @return array Arreglo con menúes
     */
    function getMenus() {
        $tipo = $this->getTipo('menu');

        if (is_null($tipo)) {
            return null;
        }

        /**
         * El query a realizar opera de la siguiente manera:
         * 1) Recupera los fields cuyo tipo asociados es 'menu'. Esto se realiza
         *      mediante el INNER JOIN con cp2tipotag.
         * 2) Recupera el menú asociado con la tupla field/value.
         */
        $dbo	= & JFactory::getDBO();
        $query = 'SELECT '. $dbo->nameQuote('f.id') .', '. $dbo->nameQuote('f.name') .', '
                . $dbo->nameQuote('f.label') .', '. $dbo->nameQuote('m.id') .' as itemid, '
                . $dbo->nameQuote('m.link')
                .' FROM '. $dbo->nameQuote('#__custom_properties_fields') . ' f'
                .' INNER JOIN '. $dbo->nameQuote('#__zonales_cp2tipotag') . ' c'
                .' ON '. $dbo->nameQuote('c.field_id') .' = '. $dbo->nameQuote('f.id')
                .' AND '. $dbo->nameQuote('c.tipo_id') .' = '. $tipo->id
                .' LEFT JOIN '. $dbo->nameQuote('#__zonales_menu') . ' zm'
                .' ON '. $dbo->nameQuote('zm.field_id') .' = '. $dbo->nameQuote('f.id')
                .' AND '. $dbo->nameQuote('zm.value_id') .' = 0'
                .' LEFT JOIN '. $dbo->nameQuote('#__menu') .' m'
                .' ON '. $dbo->nameQuote('m.id') .' = '. $dbo->nameQuote('zm.menu_id');
        $dbo->setQuery($query);

        return  $this->_cache->get(array($dbo, 'loadObjectList'), array());
    }

    /**
     * Recupera los valores del tag indicado
     *
     * @param int id identificador del tag
     * @return array Arreglo de objetos value
     */
    function getMenuValues($id, $eq = false) {
        if (is_null($id)) {
            return null;
        }

        $dbo	= & JFactory::getDBO();
        $query = 'SELECT '. $dbo->nameQuote('v.id') .', '. $dbo->nameQuote('v.name') .', '
                .$dbo->nameQuote('v.label') .', '. $dbo->nameQuote('jm.link') .', '
                .$dbo->nameQuote('zm.menu_id')
                .($eq ? ', b.peso' : '')
                .' FROM '. $dbo->nameQuote('#__custom_properties_values') . ' v'
                .' INNER JOIN '. $dbo->nameQuote('#__zonales_menu') . ' zm'
                .' ON '. $dbo->nameQuote('zm.value_id') .' = '. $dbo->nameQuote('v.id')
                .' INNER JOIN '. $dbo->nameQuote('#__menu') . ' jm'
                .' ON '. $dbo->nameQuote('jm.id') .' = '. $dbo->nameQuote('zm.menu_id');

        // ecualiza
        if ($eq) {
            require_once(JPATH_BASE.DS.'components'.DS.'com_eqzonales'.DS.'controllers'.DS.'eq.php');
            JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_eqzonales'.DS.'tables');

            $ctrlEq = new EqZonalesControllerEq();
            $ctrlEq->addModelPath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_eqzonales'.DS.'models' );

            // recupera ecualizador del usuario
            $user =& JFactory::getUser();
            $result = $ctrlEq->retrieveUserEqImpl($user->id);

            if (!is_null($result) && !empty($result)) {
                $eq = $result[0];

                $query .= ' LEFT JOIN '. $dbo->nameQuote('#__eqzonales_banda') . ' b'
                        .' ON '. $dbo->nameQuote('v.id') .' = '. $dbo->nameQuote('b.cp_value_id')
                        .' AND '. $dbo->nameQuote('b.eq_id') .' = '. $eq->eq->id;
            }
        }

        // where
        $query .= ' WHERE '. $dbo->nameQuote('v.field_id') .' = '. $id;

        // ordena según ecualización
        if ($eq) {
            $query .= ' ORDER BY b.peso DESC';
        }

        $dbo->setQuery($query);

        return $this->_cache->get(array($dbo, 'loadObjectList'), array());
    }

    /**
     * Genera un arreglo conteniendo los parametros pasados
     * representación JSON del mismo.
     *
     * @param String $result resultado de la operación
     * @param String $msg mensaje al usuario
     * @param String $desc descripción opcional
     * @return String representación JSON de los parametros
     */
    function getJsonResponse($result, $msg, $desc = null) {
        $response = array();

        $response['result'] = $result;
        $response['msg'] = $msg;
        $response['desc'] = $desc;

        return json_encode($response);
    }

    /**
     * Genera un inner join con la tabla #__custom_properties, para usar
     * en un SELECT que requiera filtrar tambien por el zonal actual:
     *
     * INNER JOIN #__custom_properties <alias> ON  <id_field> = <alias>.content_id'
     *            AND <alias>.field_id = <zonal>;
     *
     * @param String $id_field id de la tabla
     * @param String $alias alias para la tabla #__custom_properties, por defecto jcp
     * @param String $ref_table tabla en donde esta el contenido marcado, por defecto 'content'
     * @return String sentencia inner join
     */
    function _buildCustomPropertiesJoinByTable($id_field, $ref_table = 'content', $alias = 'jcp') {
        $join = ' INNER JOIN #__custom_properties ' .$alias. ' ON ' .$id_field. ' = ' .$alias. '.content_id'.
                ' AND ' .$alias. '.ref_table = \''. $ref_table .'\' ';

        $helper = new comZonalesHelper();
        $zonal = $helper->getZonal();
        if ($zonal) {
            $join .= ' AND ' .$alias. '.field_id = ' . $zonal->id;
        }

        return $join;
    }

    /**
     * Chequea si se esta realizando alguna autenticación con algun proveedor externo
     *
     * @return boolean
     */
    function isAuthenticationOnProgress() {
        $session = JFactory::getSession();
        $onProgress = $session->get('authenticationonprogress');
        return ($onProgress == 'true');
    }

    /**
     * Verifica si hay que mostrar el mapa
     * @return boolean
     */
    function showMap() {
        $show = JRequest::getInt('map','1','method');
        return ($show == 1);
    }

    /**
     * Construye JOIN para recuperar contenidos de acuerdo al zonal seleccionado.
     * @return string
     */
    function _buildCustomPropertiesJoin() {
        $join = ' INNER JOIN #__custom_properties p ON a.id = p.content_id';
        if (($zonal = $this->getZonal())) {
            $join .= ' AND p.value_id IN (SELECT v.id FROM #__custom_properties_values v WHERE v.parent_id = '.$zonal->id.') ' ;
        }

        return $join;
    }

    /**
     * Recupera los tags jerarquicos que tengan como padre a un cierto identificador.
     * Utilizado por modulo mod_combozona.
     *
     * @param int $id identificador del value padre
     * @return array con hijos directos del identificador padre
     */
    function getItems($id) {
        $dbo =& JFactory::getDBO();

        // Recupera todos las etiquetas jerarquicas de primer nivel bajo el grupo noticias
        $query = "SELECT id, label FROM `#__custom_properties_values`".
                " WHERE `parent_id` = $id";
        
        $dbo->setQuery($query);
        $rows = $dbo->loadObjectList();

        return $rows;
    }

    /**
     * Retorna el parámetro Root value para la selección de zonas
     */
    function getRoot() {
        $zonalesParams = &JComponentHelper::getParams( 'com_zonales' );
        $root = $zonalesParams->get('root_value');
        return $root;
    }

}