<?php
/**
 * @version	$Id: band.php 394 2010-02-26 19:35:59Z franpaez $
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

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');
jimport('joomla.filesystem.file');

// operaciones sobre las bandas
define('REMOVE', 'REM');
define('ADD', 'ADD');
define('MOD', 'MOD');

/**
 * Controlador Motor de Ecualización - Bandas
 *
 * El presente controlador contiene todos los métodos necesarios para crear
 * nueva bandas así como para modificarlas, y otros métodos auxiliares.
 */
class EqZonalesControllerBand extends JController {

    /**
     * Helper de Zonales
     * @var comEqZonalesHelper
     */
    var $helper = null;

    /**
     * Constructor
     *
     * @param array $default
     */
    function __construct($default = array()) {
        parent::__construct($default);
        $this->helper = new comEqZonalesHelper();
    }

    /**
     * Crea o modifica un conjunto de bandas. La función esta pensada para ser
     * invocada mediante Ajax desde el frontend. Espera recuperar como variable
     * POST un mensaje JSON con los datos necesarios para la creación del nuevo
     * ecualizador.
     *
     * Debido a que esta pensando para ejecutarse por medio de AJAX agrega
     * chequeos de cuestiones de seguridad, tales como si el usuario esta
     * registrado e inicio sesión.
     *
     * @return JSON con información acerca del resultado de la operación.
     */
    function modifyBandAjax() {
        // Controla que el request haya sido enviado por un usuario registrado.
        $user =& JFactory::getUser();

        $jtext = new JText();

        /**
         * Recupera los parametros desde POST
         */
        $bands = JRequest::getVar('slider', NULL, 'post', 'array');
        $eqid = JRequest::getVar('eqid', NULL, 'post', 'int');

        /**
         * Genera un arreglo (bandsArray) con instancias de stdClass que
         * contienen los parametros necesarios para las bandas.
         */
        $bandsArray = array();
        foreach ($bands as $band) {
            list($id, $valueId, $peso, $operation) = explode("-", $band);
            $bandsArray[] = (object) array('id' => $id, 'cp_value_id' => $valueId, 'peso' => $peso, 'eq_id' => $eqid, 'operation' => $operation);
        }

        /**
         * Crea/modifica las bandas del ecualizador según la configuración
         * especificada.
         */
        $result = $user->guest ? $this->modifyBandAnonImpl($bandsArray) : $this->modifyBandImpl($bandsArray);

        // Según la operación selecciona el tipo de mensaje de respuesta - ver com_eqzonales.ini
        switch ($operation) {
            case REMOVE:
                $op = 'DEL';
                break;
            case ADD:
                $op = 'CREATE';
                break;
            case MOD:
                $op = 'UPDATE';
                break;
        }

        if(!$result) {
            echo $this->helper->getEqJsonResponse(comEqZonalesHelper::FAILURE,
            $jtext->sprintf('ZONALES_EQ_'.$op.'_FAILURE', JText::_('ZONALES_EQ_BAND')));
        } else {
            echo $this->helper->getEqJsonResponse(comEqZonalesHelper::SUCCESS,
            $jtext->sprintf('ZONALES_EQ_'.$op.'_SUCCESS', JText::_('ZONALES_EQ_BAND')));
        }

        return;
    }

    /**
     * Crea o modifica un conjunto de bandas. Esta función esta pensanda para
     * ser invocada directamente en el backend, durante el procesamiento del
     * request.
     *
     * TODO: Se debe modificar este modulo para utilizar lo datos pasados en el
     * argumento.
     *
     * @param Array $params Arreglo de stdClass con datos a modificar.
     * @return Boolean TRUE en caso de poder modificar exitosamente las bandas.
     */
    function modifyBand($params) {
        $band_params = JRequest::getVar('params', NULL, 'post', 'string');
        $params = $this->helper->getJsonParams($band_params, JText::_('ZONALES_EQ_BAND'));
        if (!$params) return;

        $this->modifyBandImpl($params);
    }

    /**
     * Genera un conjunto de bandas por defecto para un ecualizador dado. Este
     * método esta pensando principalmente para ser invocado durante la creación
     * de un ecualizador por defecto para un nuevo usuario.
     *
     * @param int $eq stdclass con datos del ecualizador al cual asignar las bandas.
     * @return boolean TRUE al agregar con exito las bandas al ecualizador.
     */
    function createDefaultBands($eq = null) {
        if (is_null($eq)) {
            return false;
        }

        // Parametros grupo de etiquetas noticias y peso por default
        $paramsEq =& JComponentHelper::getParams('com_eqzonales');
        $noticias_field = $paramsEq->get('noticias_field', null);
        $peso_default = $paramsEq->get('peso_default', 50);

        // No se especifico la url de solr
        if ($noticias_field == null) {
            return false;
        }

        // Obtiene una referencia a la base de datos
        $db =& JFactory::getDBO();

        // Recupera todos las etiquetas jerarquicas de primer nivel bajo el grupo noticias
        $query = "SELECT id AS cp_value_id, label AS valor FROM `#__custom_properties_values`".
            " WHERE `parent_id` = 0 AND `field_id` = $noticias_field AND `default` = 1";
        $db->setQuery($query);
        $rows1 = $db->loadObjectList();

        // Se agrupan los datos requeridos para las bandas temáticas
        foreach ($rows1 as $row) {
            $row->peso = $peso_default;
            $row->eq_id = $eq->id;
            $row->default = 0;
            $row->active = 0;
        }

        // Recupera los zonales por defecto especificados por el usuario
        /*
        $query = "SELECT e.value AS cp_value_id, v.label AS valor".
                " FROM #__aapu_attribute_entity e".
                " INNER JOIN #__custom_properties_values v ON v.id = e.value".
                " WHERE e.object_type = 'TABLE' AND e.object_name = '#__users'".
                " AND e.attribute_id = 3 AND e.object_id = $eq->user_id";
        $db->setQuery($query);
        $rows2 = $db->loadObjectList();

        foreach ($rows2 as $row) {
            $row->peso = 100;
            $row->eq_id = $eq->id;
            $row->default = 0;
            $row->active = 0;
        }
         */
        
        //$rows = array_merge($rows1, $rows2);

        /**
         * Crea/modifica las bandas del ecualizador según la configuración
         * especificada.
         */
        if($this->modifyBandImpl($rows1)) {
            return true;
        }
        return false;
    }

    /**
     * Modifica las bandas especificadas.
     *
     * @param Array $params Arreglo de stdClass con información para cada banda.
     * @return Boolean TRUE en caso de éxito en la modificación de todas las bandas.
     */
    function modifyBandImpl($params = NULL) {

        // Chequea que hayan sido pasados valores para las bandas
        if (is_null($params)) {
            return false;
        }

        $model = &$this->getModel('Banda');
        $table = $model->getTable();

        // Va guardando cada banda
        foreach ($params as $band) {
            // elimina la banda en caso de estar marcada como REMOVE
            if (property_exists($band, 'operation') && $band->operation == REMOVE) {
                if (!$table->delete($band->id)) {
                    return false;
                }
            }

            // Crea nueva instancia del ecualizador
            $bandData = array(
                    'id' => property_exists($band, 'id') ? $band->id : NULL,
                    'valor' => property_exists($band, 'valor') ? $band->valor : NULL,
                    'peso' => property_exists($band, 'peso') ? $band->peso : NULL,
                    'cp_value_id' => property_exists($band, 'cp_value_id') ? $band->cp_value_id : NULL,
                    'eq_id' => property_exists($band, 'eq_id') ? $band->eq_id : NULL,
                    'default' => property_exists($band, 'default') ? $band->default : NULL,
                    'active' => property_exists($band, 'active') ? $band->active : NULL
            );

            if (is_null($bandData['eq_id'])) {
                return false;
            }

            if (is_null($bandData['cp_value_id'])) {
                return false;
            }

            // Almacena el ecualizador
            $model = &$this->getModel('Banda');
            if (!$model->store(false, false, $bandData)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Modifica las bandas especificadas -- ecualizador de usuario anonimo.
     *
     * @param Array $params Arreglo de stdClass con información para cada banda.
     * @return Boolean TRUE en caso de éxito en la modificación de todas las bandas.
     */
    function modifyBandAnonImpl($params = null) {
        $session =& JFactory::getSession();
        if (!$session->has('eq')) {
            return FALSE;
        }

        $eq = $session->get('eq');

        if (!is_null($eq) && !empty($eq)) {
            $eqtmp = $eq;

            // actualizamos los valores del ecualizador
            foreach ($eqtmp->bands as $band) {
                foreach ($params as $param) {
                    if (property_exists($param, 'id')) {
                        if ($param->id == $band->id) {
                            $band->peso = $param->peso;
                        }
                    }
                }
            }

            // ordenamos bandas de mayor a menor según el peso
            usort($eqtmp->bands, array('EqZonalesControllerBand', 'ordenaBandasPorPeso'));
        }

        return true;
    }

    /**
     * Compara dos bandas según el peso asignado a las mismas.
     *
     * @param stdClass $bandaA
     * @param stdClass $bandaB
     * @return 0 si son identicas, 1 si bandaA < bandaB, -1 si bandaA > bandaB
     */
    function ordenaBandasPorPeso($bandaA, $bandaB) {
        // Parametros grupo de etiquetas noticias y peso por default
        $paramsEq =& JComponentHelper::getParams('com_eqzonales');
        $order_field = $paramsEq->get('eq_order_by', NULL);
        $order_dir = $paramsEq->get('eq_order', 'asc');
        $order = 1;

        if (strcasecmp($order_field, "desc") == 0) {
            $order = -1;
        }

        if (strcasecmp($order_field, "band_label") == 0) {
            return strcasecmp($bandaA->band_label, $bandaB->band_label) * $order;
        }
        else {
            if ($bandaA->peso == $bandaB->peso) {
                return 0;
            }
            return (($bandaA->peso > $bandaB->peso) ? 1 : -1) * $order;
        }
    }

}