<?php

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model' );

abstract class ZonalesModelBaseModel extends JModel
{
	var $_id		= null;
	var $_data 		= null;
	var $_all 		= null;

	var $_total 		= null;
	var $_pagination 	= null;
	var $_limitstart 	= 0;
	var $_limit 		= 0;

	var $_where 		= array();
	var $_group 		= array();
	var $_orderby 		= array();

	var $_orderby_filter_order 	= null;
	var $_orderby_filter_order_dir 	= 'asc';

	var $_table_name    = null;

	function ZonalesModelBaseModel()
	{
		$this->__construct();
	}

	function __construct()
	{
		parent::__construct();

		global $mainframe, $option;

		// obtenemos las variables para paginacion
		$limit		= $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
		$limitstart	= $mainframe->getUserStateFromRequest( $option.'.limitstart', 'limitstart', 0, 'int' );

		// si se cambio el limite
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$this->_limit = $limit;
		$this->_limitstart = $limitstart;

		$array = JRequest::getVar('cid',  0, '', 'array');
		$this->setId((int)$array[0]);

		$table =& $this->getTable();
		$this->_table_name = $table->getTableName();
	}

	/**
	 * Setea el identificador único
	 *
	 * @access public
	 * @param $id int identificador del modelo
	 * @return void
	 */
	function setId($id)
	{
		// Set id and wipe data
		$this->_id		= (int) $id;
		$this->_data 	= null;
	}

	/**
	 * Recupera los datos desde el modelo (un solo registro)
	 *
	 * @param boolean $reload recargar datos desde bd o utilizar copia de la instancia
	 * @return Object registro especificado en la bd
	 */
	function &getData($reload = false, $customQuery = false)
	{
		if (empty($this->_data) || $reload)
		{
			$query = $this->_buildQuery($customQuery);
			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();
		}
		if (!$this->_data)
		{
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
	function &getAll($reload = false)
	{
		if (empty($this->_all) || $reload)
		{
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
	function setLimitStart($limitstart)
	{
		$this->_limitstart = (int) $limitstart;
	}

	/**
	 * Setea el límite para la paginación.
	 *
	 * @param int $limit número de registros a mostrar
	 * @return void
	 */
	function setLimit($limit)
	{
		$this->_limit = (int) $limit;
	}

	/**
	 * Retorna el total de registros del modelo.
	 *
	 * @return int número de registros en el modelo
	 */
	function getTotal()
	{
		if (empty($this->_total))
		{
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
	function addOrderBy($orderby)
	{
		$this->_orderby[] = $orderby;
	}

	/**
	 * Limpia la lista de expresiones para la clausula ORDER BY y agrega la
	 * especificada en el parametro.
	 *
	 * @param string $orderyby expresión SQL para ORDER BY
	 * @return void
	 */
	function setOrderBy($orderby)
	{
		$this->_orderby = array();
		$this->addOrderBy($orderby);
	}

	/**
	 * Retorna una clausula ORDER BY según los parametros indicados
	 * TODO: Este método es susceptible de mejoras.
	 *
	 * @return string claúsula SQL ORDER BY
	 */
	function getOrderByClause()
	{
		$order_by = null;

		if (!is_null($this->_orderby_filter_order))
		{
			$order_by = ' ORDER BY ' . $this->_orderby_filter_order . ' ' . $this->_orderby_filter_order_dir;

			if (count($this->_orderby) > 0)
			{
				$order_by .= ', ' . implode( ' ,', $this->_orderby );
			}
		}

		return $order_by;
	}

	/**
	 * Agrega una expresión a la clausula WHERE
	 *
	 * @param string $where expresión SQL para la claúsula WHERE
	 * @return void
	 */
	function addWhere($where)
	{
		$this->_where[] = $where;
	}

	/**
	 * Limpia la lista de expresiones para la clausula WHERE y agrega la
	 * especificada en el parametro.
	 *
	 * @param string $where expresión SQL para WHERE
	 * @return void
	 */
	function setWhere($where)
	{
		$this->_where = array();
		$this->addWhere($where);
	}

	/**
	 * Retorna una clausula WHERE según los parametros indicados
	 * TODO: Este método es susceptible de mejoras.
	 *
	 * @return string clausula SQL WHERE
	 */
	function getWhereClause()
	{
		if (count($this->_where) > 0)
		{
			return ' WHERE ' . implode( ' AND ', $this->_where );
		}
	}

	/**
	 * Agrega una expresión a la clausula GROUP BY
	 *
	 * @param string $group expresión SQL para la claúsula GROUP BY
	 * @return void
	 */
	function addGroupBy($group)
	{
		$this->_group[] = $group;
	}

	/**
	 * Limpia la lista de expresiones para la clausula GROUP BY y agrega la
	 * especificada en el parametro.
	 *
	 * @param string $group expresión para GROUP BY
	 * @return void
	 */
	function setGroupBy($group)
	{
		$this->_group = array();
		$this->addGroupBy($group);
	}

	/**
	 * Retorna una clausula GROUP BY según los parametros indicados
	 * TODO: Este método es susceptible de mejoras.
	 *
	 * @return string cadena para sección GROUP BY
	 */
	function getGroupByClause()
	{
		if (count($this->_group) > 0)
		{
			return ' GROUP BY ' . implode( ' ,', $this->_group );
		}
	}

	/**
	 * Retorna la instancia de JPagination de este modelo. Si no estuviera
	 * instanciada, la crea.
	 *
	 * @return JPagination instancia de JPagination
	 */
	function getPagination()
	{
		if (empty($this->_pagination))
		{
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
	 * Resetea los datos y los parametros del modelo.
	 *
	 * @return void
	 */
	function clear()
	{
		$this->_where 	= array();
		$this->_all 	= NULL;
		$this->_id 	= NULL;
	}

	/**
	 * Salva el contenido del modelo en la base de datos.
	 *
	 * @return boolean true si el registro fue guardado exitosamente
	 */
	function store($updateNulls = false, $use_post_data = true)
	{
		$row =& $this->getTable();

		$data = $use_post_data ? JRequest::get('post') : $this->_data;

		if (!$row->bind($data))
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		$this->afterBind($row);

		// Texto generado por editor
		if (!is_null(JRequest::getVar('text',null)))
		{
			ContentHelper::saveContentPrep($row);
		}

		// Almancena los metadatos si estos estan presentes
		$metadata = JRequest::getVar( 'meta', null, 'post', 'array');
		if (is_array($metadata))
		{
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


		// Make sure the record is valid
		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		$this->afterCheck($row);

		// Store the table to the database
		//if (!$row->store($updateNulls)) {
		if (JError::isError($row->store($updateNulls))) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// ¿Hack?
		$this->setId($row->id);

		return true;
	}

	/**
	 * Acciones a realizar luego del binding.
	 * Los modelos que extiendan esta BaseModel pueden sobreescribir este método
	 * para realizar acciones extras luego del binding.
	 *
	 * @param JTable $row referencia al objeto JTable del modelo
	 * @return boolean true en caso de no haber errores, false en contrario
	 */
	function afterBind(&$row)
	{
		return true;
	}

	/**
	 * Acciones a realizar luego del chequeo.
	 * Los modelos que extiendan esta BaseModel pueden sobreescribir este método
	 * para realizar acciones extras luego del chequeo.
	 *
	 * @param JTable $row referencia al objeto JTable del modelo
	 * @return boolean true en caso de no haber errores, false en contrario
	 */
	function afterCheck(&$row)
	{
		return true;
	}

	/**
	 * Elimina el contenido de la base de datos
	 *
	 * @return boolean true en caso de éxito, false en caso contrario
	 */
	function delete()
	{
		$cids = JRequest::getVar( 'cid', array(), '', 'array' );

		$row =& $this->getTable();

		for ($i = 0, $n = count($cids); $i < $n; $i++)
		{
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
	function hit()
	{
		global $mainframe;

		if ($this->_id)
		{
			$tabla =& $this->getTable();
			$tabla->hit($this->_id);
			return true;
		}
		return false;
	}

	function toString()
	{
		return $this->_id . '-' . $this->_name;
	}
}