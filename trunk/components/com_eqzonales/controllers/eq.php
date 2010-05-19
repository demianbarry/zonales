<?php
/**
 * @version	$Id: eq.php 394 2010-02-26 19:35:59Z franpaez $
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
jimport('solr.query');
jimport('solr.client');

require_once JPATH_ROOT . DS . 'components' . DS . 'com_eqzonales' . DS . 'queries' . DS . 'userquery.php';
require_once JPATH_ROOT . DS . 'administrator'. DS . 'components' . DS . 'com_eqzonales' . DS . 'helper' . DS . 'helper.php';

/**
 * Controlador Motor de Ecualización - Ecualizador
 *
 * El presente controlador contiene todos los métodos necesarios para crear
 * nuevos ecualizadores así como para modificarlos, y otros métodos auxiliares.
 */
class EqZonalesControllerEq extends JController {

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
     * Crea un nuevo Ecualizador. Esta función esta pensanda para ser invocada
     * mediante Ajax desde el frontend. Espera recuperar como variable POST
     * un mensaje JSON con los datos necesarios para la creación del nuevo
     * ecualizador.
     *
     * Debido a que esta pensando para ejecutarse por medio de AJAX agrega
     * chequeos de cuestiones de seguridad, tales como si el usuario esta
     * registrado e inicio sesión.
     *
     * @return JSON con información acerca del resultado de la operación.
     */
    function createEqAjax() {
        $jtext = new JText();

        // Controla que el request haya sido enviado por un usuario registrado.
        $user =& JFactory::getUser();
        if ($user->guest) {
            return $this->helper->getEqJsonResponse(comEqZonalesHelper::FAILURE,
                        JText::_('ZONALES_EQ_SESSION_REQUIRED'));
        }

        // recupera parametro JSON desde POST
        $eq_params = JRequest::getVar('params', NULL, 'post', 'string');

        // parsea el request JSON
        $params = $this->helper->getJsonParams($eq_params, JText::_('ZONALES_EQ_EQ'));

        // falla si no se han pasado parametros para el ecualizador
        if (!$params) {
            return $this->helper->getEqJsonResponse(comEqZonalesHelper::FAILURE,
                    $jtext->sprintf('ZONALES_EQ_CREATE_FAILURE', JText::_('ZONALES_EQ_EQ')));
        }

        // intenta crear el ecualizador
        if(!$this->createEqImpl($params)) {
            return $this->helper->getEqJsonResponse(comEqZonalesHelper::FAILURE,
                    $jtext->sprintf('ZONALES_EQ_CREATE_FAILURE', JText::_('ZONALES_EQ_EQ')));
        } else {
            return $this->helper->getEqJsonResponse(comEqZonalesHelper::SUCCESS,
                    $jtext->sprintf('ZONALES_EQ_CREATE_SUCCESS', JText::_('ZONALES_EQ_EQ')));
        }
    }

    /**
     * Crea un nuevo Ecualizador. Esta función esta pensanda para ser invocada
     * directamente en el backend, durante el procesamiento del request.
     *
     * @param StdClass $params información para el nuevo ecualizador
     * @return boolean true en caso de que pueda crearse el ecualizador
     */
    function createEq($params) {
        // falla si no se han pasado parametros para el ecualizador
        if (!$params) {
            return false;
        }
        
        return $this->createEqImpl($params);
    }

    /**
     * TODO: comentar!!!
     * @return <type>
     */
    function modifyEq() {
        $eq_params = JRequest::getVar('params', NULL, 'post', 'string');

        $params = $this->helper->getJsonParams($eq_params, JText::_('ZONALES_EQ_EQ'));
        if (!$params) return;

        echo $this->modifyEqImpl($params);
        return;
    }

    function retrieveEq() {
        $eqId = JRequest::getInt('eq',0,'method');

        return $this->retrieveEqImpl($eqId);
    }

    function retrieveUserEq() {
        $userId = JRequest::getInt('user',0,'method');

        return $this->retrieveUserEqImpl($userId);
    }

    /**
     *  Metodos auxiliares
     */
    function setEqName() {
        $name = JRequest::getString('name',null,'method');

        // si no se otorgo el nombre a asignar
        if ($name == null){
            // indico que no se pudo hacer nada
            return false;
        }

        $model = &$this->getModel('Eq');
        $data = $this->retrieveEq();
        $eq = $data->eq;
        $eq->nombre = $name;

        return $model->store(false,false,$eq);
    }

    function getEqName() {
        $data = $this->retrieveEq();

        $eq = $data->eq;
        // si no se encontro retorno NULL
        if (empty ($eq)) return null;

        return $eq->nombre;
    }

    function getEqDesc() {
        $data = $this->retrieveEq();

        $eq = $data->eq;
        // si no se encontro retorno NULL
        if (empty ($eq)) return null;

        return $eq->descripcion;
    }

    function setEqDesc() {
        $desc = JRequest::getString('desc',null,'method');

        // si no se otorgo el nombre a asignar
        if ($desc == null){
            // indico que no se pudo hacer nada
            return false;
        }

        $model = &$this->getModel('Eq');
        $data = $this->retrieveEq();
        $eq = $data->eq;
        $eq->descripcion = $desc;

        return $model->store(false,false,$eq);
    }

    function setEqObservation() {
        $observation = JRequest::getString('obs',null,'method');

        // si no se otorgo el nombre a asignar
        if ($observation == null){
            // indico que no se pudo hacer nada
            return false;
        }

        $model = &$this->getModel('Eq');
        $data = $this->retrieveEq();
        $eq = $data->eq;
        $eq->observaciones = $observation;

        return $model->store(false,false,$eq);
    }

    function getEqObservation() {
        $data = $this->retrieveEq();

        $eq = $data->eq;
        // si no se encontro retorno NULL
        if (empty ($eq)) return null;

        return $eq->observaciones;
    }

    /*
     * Fin metodos auxiliares
     */

    function runQuery() {
        $default = '###';
        $data = array();
        $value = JRequest::getString('value',$default,'method');
        $field = JRequest::getString('field',$default,'method');
        $type = JRequest::getString('type',$default,'method');

        $data[SolrQuery::VALUE] = ($value == $default) ? null : $value;
        $data[SolrQuery::FIELD] = ($field == $default) ? null : $field;
        $data[SolrQuery::TYPE] = ($type == $default) ? SolrQuery::QUERY : $type;

        $dataArray = array();
        $dataArray[] = $data;

        $query = new UserQuery(JFactory::getUser(),$dataArray);
        $client = new SolrClient();
        $componentHelper = new JComponentHelper();
        $client->setHost($componentHelper->getParams('com_eqzonales')->get('solrurl'));

        $results = $client->executeQuery($query);

       // print_r($results);
    }

    /**
     * Genera un nuevo ecualizador para el usuario indicado.
     *
     * @param int $user_id id usuario
     * @param String $eq_name nombre del ecualizador
     * @return El id en la bd del ecualizador o FALSE si no se pudo crear.
     */
    function createEqImpl($params = NULL) {
        if (is_null($params)) {
            return FALSE;
        }

        $user_id = property_exists($params, 'user_id') ? $params->user_id : NULL;
        $eq_name = property_exists($params, 'nombre') ? $params->nombre : NULL;

        // Chequea que el usuario indicado sea valido
        if ($user_id <= 0) {
            return FALSE;
        }

        // Nombre por defecto para el ecualizador
        if (is_null($eq_name)) {
            $eq_name = "Ecualizador de " . $user->name;
        }

        // Crea nueva instancia del ecualizador
        $eqData = array(
                'nombre' => $eq_name,
                'descripcion' => property_exists($params, 'descripcion') ? $params->descripcion : NULL,
                'observaciones' => property_exists($params, 'observaciones') ? $params->observaciones : NULL,
                'user_id' => $user_id
        );

        // Agrega bandas por defecto del ecualizador
        // TODO: Incorporar generación de bandas por default

        // Almacena el ecualizador
        $model = &$this->getModel('Eq');
        if (!$model->store(false, false, $eqData)) {
            return FALSE;
        }

        return $model->getData()->id;
    }

    function retrieveEqImpl($eqId) {
        $data = null;

        if ($eqId > 0){
            // recupero los datos del ecualizador solicitado
            $model = &$this->getModel('Eq');
            $model->setId($eqId);
            $eq = $model->getData(true);

            if (!isset ($eq->id)){
                return array();
            }

            // recupero los datos de las bandas asociadas al ecualizador
            $model = &$this->getModel('Banda');
            $model->setWhere('e.eq_id = ' . $eq->id);
            $bands = $model->getAll(true);

            $data = new stdClass();
            $data->eq = $eq;
            $data->bands = $bands;

        }
        
        return $data;
    }

    function retrieveUserEqImpl($userId) {
        $data = null;

        if ($userId >= 0){
            $model = &$this->getModel('Eq');
            $eqs = $model->getUserEqs($userId);

            $data = array();
            foreach ($eqs as $currentEq) {
                $data[] = $this->retrieveEqImpl($currentEq->id);
            }
        }

        return $data;
    }

    /**
     *
     * @param <type> $eq_id
     * @param <type> $params
     * @return <type>
     */
    function modifyEqImpl($params = null) {

        // Chequea que el usuario haya iniciado sesión
        $user =& JFactory::getUser();
        if ($user->guest) {
            return;
        }

        $eq_id = property_exists($params, 'eq_id') ? $params->id : NULL;

        // Chequea que el usuario indicado sea valido
        if ($eq_id <= 0) {
            return;
        }

        // Crea nueva instancia del ecualizador
        $eqData = array(
                'id' => $eq_id,
                'nombre' => property_exists($params, 'nombre') ? $params->nombre : NULL,
                'descripcion' => property_exists($params, 'descripcion') ? $params->descripcion : NULL,
                'observaciones' => property_exists($params, 'observaciones') ? $params->observaciones : NULL,
                'solrquery_bq' => property_exists($params, 'solrquery_bq') ? $params->solrquery_bq : NULL,
        );

        // Almacena el ecualizador
        $model = &$this->getModel('Eq');
                if (!$model->store(false, false, $eqData)) {
            $jtext = new JText();
            $message = $jtext->sprintf('ZONALES_EQ_UPDATE_FAILURE',JText::_('ZONALES_EQ_EQ'));
            return $this->helper->getEqJsonResponse(comEqZonalesHelper::FAILURE, $message);;
        }

        $jtext = new JText();
        $message = $jtext->sprintf('ZONALES_EQ_UPDATE_SUCCESS',JText::_('ZONALES_EQ_EQ'));
        return $this->helper->getEqJsonResponse(comEqZonalesHelper::SUCCESS, $message);;
//        if (!$model->store(false, false, $eqData)) {
//            echo "<script> alert('".$model->getError()."'); window.history.go(-1); </script>\n";
//            exit();
//        }
    }
}