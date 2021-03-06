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

jimport( 'joomla.application.component.model' );

abstract class ZonalesModelBaseModel extends JModel {
    /**
     * Id del registro, utilizado para recuperar un solo dato.
     * @var int
     */
    var $_id		= null;
    /**
     * Datos del rgistros recuperado.
     * @var stdClass
     */
    var $_data 		= null;
    /**
     * Arreglo de stdClass, utilizado para recuperar múltiples registros.
     * @var array
     */
    var $_all 		= null;
    /**
     * Número de registros recuperados.
     * @var int
     */
    var $_total         = null;
    /**
     * Instancia de JPagination para este modelo.
     * @var JPagination
     */
    var $_pagination 	= null;
    /**
     * Recupera registros a partir de este offset.
     * @var int
     */
    var $_limitstart 	= 0;
    /**
     * Número de registros a recuperar de la base de datos.
     * @var int
     */
    var $_limit 	= 0;
    /**
     * Utilizar límite para el número de resultados.
     * @var boolean
     */
    var $_use_limit = true;
    /**
     * Sentencia WHERE a utilizar
     * @var string
     */
    var $_where 		= array();
    /**
     * Sentencia GROUP BY
     * @var string
     */
    var $_group 		= array();
    /**
     * Sentencia ORDER BY
     * @var array
     */
    var $_orderby 		= array();

    var $_orderby_filter_order 	= null;
    var $_orderby_filter_order_dir 	= 'asc';

    var $_table_name    = null;

    /** @var JCache */
    var $_cache = null;

    function ZonalesModelBaseModel() {
        $this->__construct();
    }

    function __construct() {
        parent::__construct();

        $mainframe = JFactory::getApplication(); $option = JRequest::getCMD('option');

        // obtenemos las variables para paginacion
        $limit		= $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
        $limitstart	= $mainframe->getUserStateFromRequest( $option.'.limitstart', 'limitstart', 0, 'int' );

        // si se cambio el limite
        $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

        $this->_limit = $limit;
        $this->_limitstart = $limitstart;

        $array = JRequest::getVar('cid',  0, '', 'array');
        $this->setId((int)$array[0]);

        $path = JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_zonales' . DS . 'tables';
        $this->addTablePath($path);
        $path = JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_eqzonales' . DS . 'tables';
        $this->addTablePath($path);
        $table =& $this->getTable();
        $this->_table_name = $table->getTableName();

        $this->_cache =& JFactory::getCache('com_zonales');
    }

    /**
     * Setea el identificador único
     *
     * @access public
     * @param int $id identificador del modelo
     */
    function setId($id) {
        // Set id and wipe data
        $this->_id      = (int) $id;
        $this->_data 	= null;
    }

    /**
     * Recupera el Id en la BD del registro que este modelo representa.
     *
     * @return Int Identificador único del registro en la BD
     */
    function getId() {
        return $this->_id;
    }

    /**
     * Recupera los datos desde el modelo (un solo registro)
     *
     * @param boolean $reload recargar datos desde bd o utilizar copia de la instancia
     * @return Object registro especificado en la bd
     */
    function &getData($reload = false, $customQuery = false) {
        if (empty($this->_data) || $reload) {
            $query = $this->_buildQuery($customQuery);
            $this->_db->setQuery($query);
            //$this->_data = $this->_db->loadObject();
            $this->_data = $this->_cache->get(array($this->_db, 'loadObject'), array());
        }
        if (!$this->_data) {
            $this->_data =& $this->getTable();
        }

        return $this->_data;
    }

    /**
     * Obtiene todos los registros que coincida con los criterios.
     *
     * @param boolean $reload recargar datos desde la bd o utilizar copia de la instancia
     * @return array lista de objetos
     */
    function &getAll($reload = false) {
        if (empty($this->_all) || $reload) {
            $query = $this->_buildAllQuery();
            $this->_all = $this->_getList($query, $this->_limitstart, $this->_limit);
        }
        return $this->_all;
    }

    /**
     * Setea el límite inicial para la paginación.
     *
     * @param int $limitstart número de registros a mostrar
     * @return void
     */
    function setLimitStart($limitstart) {
        $this->_limitstart = (int) $limitstart;
    }

    /**
     * Setea el límite para la paginación.
     *
     * @param int $limit número de registros a mostrar
     * @return void
     */
    function setLimit($limit) {
        $this->_limit = (int) $limit;
    }

    /**
     * Retorna el total de registros del modelo.
     *
     * @return int número de registros en el modelo
     */
    function getTotal() {
        if (empty($this->_total)) {
            $query = $this->_buildAllQuery();
            $this->_total = $this->_getListCount($query);
        }
        return $this->_total;
    }

    /**
     * Agrega una expresión SQL para la clausula ORDER BY.
     *
     * @param string $orderby expresión SQL para ORDER BY
     * @return void
     */
    function addOrderBy($orderby) {
        $this->_orderby[] = $orderby;
    }

    /**
     * Limpia la lista de expresiones para la clausula ORDER BY y agrega la
     * especificada en el parametro.
     *
     * @param string $orderyby expresión SQL para ORDER BY
     * @return void
     */
    function setOrderBy($orderby) {
        $this->_orderby = array();
        $this->addOrderBy($orderby);
    }

    /**
     * Retorna una clausula ORDER BY según los parametros indicados
     * TODO: Este método es susceptible de mejoras.
     *
     * @return string claúsula SQL ORDER BY
     */
    function getOrderByClause() {
//        $order_by = null;
//
//        if (!is_null($this->_orderby_filter_order)) {
//            $order_by = ' ORDER BY ' . $this->_orderby_filter_order . ' ' . $this->_orderby_filter_order_dir;
//
//            if (count($this->_orderby) > 0) {
//                $order_by .= ', ' . implode( ' ,', $this->_orderby );
//            }
//        }
//
//        return $order_by;
        $order_by = null;

        if (count($this->_orderby) > 0) {
            $order_by = ' ORDER BY ';
            $order_by .= implode(' ,', $this->_orderby);
        }

        return $order_by;
    }

    /**
     * Agrega una expresión a la clausula WHERE
     *
     * @param string $where expresión SQL para la claúsula WHERE
     * @return void
     */
    function addWhere($where) {
        $this->_where[] = $where;
    }

    /**
     * Limpia la lista de expresiones para la clausula WHERE y agrega la
     * especificada en el parametro.
     *
     * @param string $where expresión SQL para WHERE
     * @return void
     */
    function setWhere($where) {
        $this->_where = array();
        $this->addWhere($where);
    }

    /**
     * Retorna una clausula WHERE según los parametros indicados
     * TODO: Este método es susceptible de mejoras.
     *
     * @return string clausula SQL WHERE
     */
    function getWhereClause() {
        if (count($this->_where) > 0) {
            return ' WHERE ' . implode( ' AND ', $this->_where );
        }
    }

    /**
     * Agrega una expresión a la clausula GROUP BY
     *
     * @param string $group expresión SQL para la claúsula GROUP BY
     * @return void
     */
    function addGroupBy($group) {
        $this->_group[] = $group;
    }

    /**
     * Limpia la lista de expresiones para la clausula GROUP BY y agrega la
     * especificada en el parametro.
     *
     * @param string $group expresión para GROUP BY
     * @return void
     */
    function setGroupBy($group) {
        $this->_group = array();
        $this->addGroupBy($group);
    }

    /**
     * Retorna una clausula GROUP BY según los parametros indicados
     * TODO: Este método es susceptible de mejoras.
     *
     * @return string cadena para sección GROUP BY
     */
    function getGroupByClause() {
        if (count($this->_group) > 0) {
            return ' GROUP BY ' . implode( ' ,', $this->_group );
        }
    }

    /**
     * Retorna la instancia de JPagination de este modelo. Si no estuviera
     * instanciada, la crea.
     *
     * @return JPagination instancia de JPagination
     */
    function getPagination() {
        if (empty($this->_pagination)) {
            jimport('joomla.html.pagination');
            $this->_pagination = new JPagination($this->getTotal(), $this->_limitstart, $this->_limit);
        }

        return $this->_pagination;
    }

    /**
     * Query para obtener un registro particular desde la base de datos.
     * Clases que extienda este BaseModel deben reimplementar este metodo.
     */
    abstract function _buildQuery($customQuery = false);

    /**
     * Query para obtener múltiples registros desde la base de datos.
     * Clases que extienda este BaseModel deben reimplementar este metodo.
     */
    abstract function _buildAllQuery();

    /**
     * Resetea datos y parametros del modelo
     */
    function clear() {
        $this->_where 	= array();
        $this->_all 	= NULL;
        $this->_id 	= NULL;
    }

    /**
     * Persiste el contenido del modelo en la base de datos.
     *
     * @param boolean $updateNulls FALSE para no actualizar campos que contengan NULL
     * @param boolean $use_post_data TRUE para recuperar datos desde POST
     * @param Array $data Si $use_post_data es FALSE, especifica los datos a persistir
     * @return boolean TRUE si el registro fue guardado exitosamente
     */
    function store($updateNulls = false, $use_post_data = true, $data = null) {
        $row =& $this->getTable();

        /**
         * Decide de donde recuperar la información a persistir. Esta puede
         * provenir de las variables POST, ser los datos ya contenidos en el
         * modelo ($this->_data) o ser especificada por medio del parametro
         * $data.
         */
        if ($use_post_data) {
            $data = JRequest::get('post');
        } else {
            if (is_null($data)) {
                $data = $this->_data;
            } else {
                $this->_data = $data;
            }
        }

        /**
         * Realiza el binding de los datos a persistir con el objeto JTable, y
         * ejecuta a continuación el método afterBind(). Este último metodo
         * debe ser sobrecargado en los modelos que extiendan BaseModel.
         */
        if (!$row->bind($data)) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        $this->afterBind($row);

        // Texto generado por editor
        if (!is_null(JRequest::getVar('text',null))) {
            ContentHelper::saveContentPrep($row);
        }

        // Almancena los metadatos si estos estan presentes
        $metadata = JRequest::getVar( 'meta', null, 'post', 'array');
        if (is_array($metadata)) {
            $txt = array();
            foreach ($metadata as $k => $v) {
                if ($k == 'description') {
                    $row->metadesc = $v;
                } elseif ($k == 'keywords') {
                    $row->metakey = $v;
                } else {
                    $txt[] = "$k=$v";
                }
            }
            $row->metadata = implode("\n", $txt);
        }


        /**
         * Realiza el chequeo de los datos a persistir. En caso de éxito pasa
         * a ejecutar el metodo afterCheck() el cual debe ser sobrecargado en
         * los modelos que extiendan BaseModel para ofrecer chequeos especificos
         * para cada caso.
         */
        if (!$row->check()) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        $this->afterCheck($row);

        // Persiste el modelo en la base de datos
        if (JError::isError($row->store($updateNulls))) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        // ¿Hack?
        $this->setId($row->id);

        return true;
    }

    /**
     * Acciones a realizar luego del binding. Los modelos que extiendan esta
     * BaseModel pueden sobreescribir este método para realizar acciones extras
     * luego del binding.
     *
     * @param JTable $row referencia al objeto JTable del modelo
     * @return boolean true en caso de no haber errores, false en contrario
     */
    function afterBind(&$row) {
        return true;
    }

    /**
     * Acciones a realizar luego del chequeo. Los modelos que extiendan esta
     * BaseModel pueden sobreescribir este método para realizar acciones extras
     * luego del chequeo.
     *
     * @param JTable $row referencia al objeto JTable del modelo
     * @return boolean true en caso de no haber errores, false en contrario
     */
    function afterCheck(&$row) {
        return true;
    }

    /**
     * Elimina el contenido de la base de datos
     *
     * @return boolean true en caso de éxito, false en caso contrario
     */
    function delete() {
        $cids = JRequest::getVar( 'cid', array(), '', 'array' );

        $row =& $this->getTable();

        for ($i = 0, $n = count($cids); $i < $n; $i++) {
            if (!$row->delete( $cids[$i] )) {
                $this->setError( $this->_db->getErrorMsg() );
                return false;
            }
        }

        return true;
    }

    /**
     * Incrementa el contador del modelo
     *
     * @access public
     * @return boolean True on success
     */
    function hit() {
        global $mainframe;

        if ($this->_id) {
            $tabla =& $this->getTable();
            $tabla->hit($this->_id);
            return true;
        }
        return false;
    }

    function toString() {
        return $this->_id . '-' . $this->_name;
    }
}