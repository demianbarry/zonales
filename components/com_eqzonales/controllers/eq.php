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

require_once(JPATH_ROOT.DS.'components'.DS.'com_eqzonales'.DS.'queries'.DS.'userquery.php');
require_once(JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_eqzonales'.DS.'helper'.DS.'helper.php');

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
            echo $this->helper->getEqJsonResponse(comEqZonalesHelper::FAILURE,
            JText::_('ZONALES_EQ_SESSION_REQUIRED'));
            return;
        }

        // recupera parametro JSON desde POST
        $eq_params = JRequest::getVar('params', NULL, 'post', 'string');

        // parsea el request JSON
        $params = $this->helper->getJsonParams($eq_params, JText::_('ZONALES_EQ_EQ'));

        // falla si no se han pasado parametros para el ecualizador
        if (!$params) {
            echo $this->helper->getEqJsonResponse(comEqZonalesHelper::FAILURE,
            $jtext->sprintf('ZONALES_EQ_CREATE_FAILURE', JText::_('ZONALES_EQ_EQ')));
            return;
        }

        // intenta crear el ecualizador
        if(!$this->createEqImpl($params)) {
            echo $this->helper->getEqJsonResponse(comEqZonalesHelper::FAILURE,
            $jtext->sprintf('ZONALES_EQ_CREATE_FAILURE', JText::_('ZONALES_EQ_EQ')));
        } else {
            echo $this->helper->getEqJsonResponse(comEqZonalesHelper::SUCCESS,
            $jtext->sprintf('ZONALES_EQ_CREATE_SUCCESS', JText::_('ZONALES_EQ_EQ')));
        }

        return;
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
     * Crea un nuevo ecualizador en base a la información del usuario.
     *
     * @param Array $user arreglo con datos del usuario.
     */
    function _createNewDefaultEq($user) {
        /**
         * Objeto con información del usurio y ecualizador a crear
         */
        $eq = new stdClass();
        $eq->user_id = $user->id;
        $eq->nombre = $user->name;
        $eq->descripcion = 'Ecualizador de ' . $eq->nombre;
        $eq->observaciones = 'Ecualizador de ' . $eq->nombre;

        /**
         * Crea el ecualizador con los datos del nuevo usuario.
         */
        $eqId = $this->createEq($eq);
        if ($eqId === FALSE) {
            return;
        } else {
            $eq->id = $eqId;
            /**
             * Instancia las bandas por defecto para el ecualizador.
             */
            require_once(JPATH_BASE.DS.'components'.DS.'com_eqzonales'.DS.'controllers'.DS.'band.php');
            $ctrlBand = new EqZonalesControllerBand();
            $ctrlBand->createDefaultBands($eq);
        }
        return array($eq);
    }

    /**
     * Modifica un Ecualizador. Esta función esta pensanda para ser invocada
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
    function modifyEqAjax() {
        $jtext = new JText();

        // Controla que el request haya sido enviado por un usuario registrado.
        $user =& JFactory::getUser();
        if ($user->guest) {
            echo $this->helper->getEqJsonResponse(comEqZonalesHelper::FAILURE,
            JText::_('ZONALES_EQ_SESSION_REQUIRED'));
            return;
        }

        // recupera parametro JSON desde POST
        $eq_params = JRequest::getVar('params', NULL, 'post', 'string');

        // parsea el request JSON
        $params = $this->helper->getJsonParams($eq_params, JText::_('ZONALES_EQ_EQ'));

        // falla si no se han pasado parametros para el ecualizador
        if (!$params) {
            echo $this->helper->getEqJsonResponse(comEqZonalesHelper::FAILURE,
            $jtext->sprintf('ZONALES_EQ_CREATE_FAILURE', JText::_('ZONALES_EQ_EQ')));
            return;
        }

        // modifica el ecualizador
        if(!$this->modifyEqImpl($params)) {
            echo $this->helper->getEqJsonResponse(comEqZonalesHelper::FAILURE,
            $jtext->sprintf('ZONALES_EQ_CREATE_FAILURE', JText::_('ZONALES_EQ_EQ')));
        } else {
            echo $this->helper->getEqJsonResponse(comEqZonalesHelper::SUCCESS,
            $jtext->sprintf('ZONALES_EQ_CREATE_SUCCESS', JText::_('ZONALES_EQ_EQ')));
        }

        return;
    }

    /**
     * Modifica un Ecualizador. Esta función esta pensanda para ser invocada
     * directamente en el backend, durante el procesamiento del request.
     *
     * TODO: Modificar recuperación de parametros (usar argumentos). Se debe
     * también modificar la función modifyEqImpl().
     *
     * @param StdClass $params información para el nuevo ecualizador
     * @return <type>
     */
    function modifyEq($params) {
        // falla si no se han pasado parametros para el ecualizador
        if (!$params) {
            return false;
        }

        $eq_params = JRequest::getVar('params', NULL, 'post', 'string');

        $params = $this->helper->getJsonParams($eq_params, JText::_('ZONALES_EQ_EQ'));
        if (!$params) return;

        echo $this->modifyEqImpl($params);
        return;
    }

    /**
     * Recupera un Ecualizador de Usuario. Esta función esta pensanda para ser
     * invocada mediante Ajax desde el frontend. Espera recuperar como variable
     * POST un mensaje JSON con los datos necesarios para la creación del nuevo
     * ecualizador.
     *
     * TODO: Este metodo aún no se utiliza. Modificar recuperación de parametros
     * dede POST.
     *
     * @return String JSON con información acerca del resultado de la operación.
     */
    function retrieveEqAjax() {
        $eqId = JRequest::getInt('eq',0,'method');

        return $this->retrieveEqImpl($eqId);
    }

    /**
     * Recupera un ecualizador de usuario.
     *
     * @param int $id Identificador del Ecualizador
     * @return stdClass Ecualizador de Usuario
     */
    function retrieveEq($id = null) {
        if (is_null($id)) {
            return null;
        }

        return $this->retrieveEqImpl($eqId);
    }

    /**
     * Recupera los ecualizadores del usuario actual. Este metodo debe ser
     * invocado por medio de un request AJAX.
     *
     * TODO: Modificar valor de retorno a un string JSON.
     *
     * @return Array arreglo con ecualizadores del usuario.
     */
    function retrieveUserEqAjax() {
        $userId = JRequest::getInt('user',0,'method');

        return $this->retrieveUserEqImpl($userId);
    }

    /**
     * Recupera los ecualizadores del usuario actual.
     *
     * @return Array arreglo con ecualizadores del usuario.
     */
    function retrieveUserEq() {
        // Controla que el request haya sido enviado por un usuario registrado.
        $user =& JFactory::getUser();
        if ($user->guest) {
            return;
        }

        return $this->retrieveUserEqImpl($user->id);
    }

    /**
     *  Metodos auxiliares
     */
    function setEqName() {
        $name = JRequest::getString('name',null,'method');

        // si no se otorgo el nombre a asignar
        if ($name == null) {
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
        if ($desc == null) {
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
        if ($observation == null) {
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

    /**
     * Crea un nuevo ecualizador a partir de los parametros especificados.
     *
     * @param stdClass $params Objeto con parametros del ecualizador.
     * @return El ID del ecualizador en la BD o FALSE si fallo la creación.
     */
    function createEqImpl($params = NULL) {
        if (is_null($params)) {
            return FALSE;
        }

        $user_id = property_exists($params, 'user_id') ? $params->user_id : NULL;
        $eq_name = property_exists($params, 'nombre') ? $params->nombre : NULL;

        // Chequea que el usuario indicado sea valido
        if ($user_id < 0) {
            return FALSE;
        }

        // Nombre por defecto para el ecualizador
        if (is_null($eq_name)) {
            $eq_name = "Ecualizador de " . $user->name;
        }

        // Crea nueva instancia del ecualizador
        $eqData = array(
                'id' => property_exists($params, 'id') ? $params->id : NULL,
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

    /**
     *
     * @param <type> $eqId
     * @return <type>
     */
    function retrieveEqImpl($eqId) {
        $session =& JFactory::getSession();
        $user =& JFactory::getUser();

        $data = null;

        if ($session->has('eq'.$eqId)) {
            $data = $session->get('eq'.$eqId);
        } else {

            $eqZonalesParams =& JComponentHelper::getParams('com_eqzonales');

            if ($user->guest) {
                $session =& JFactory::getSession();
                /*if ($session->has('eq')) {
                    $eqtemp = $session->get('eq');
                    //usort($eqtmp->bands, array('EqZonalesControllerBand', 'ordenaBandasPorPeso'));
                    return $eqtemp;
                } else {*/
                $eqId = $eqZonalesParams->get('eq_anon_id', 1);
                //}
            }

            if ($eqId > 0) {
                // datos del ecualizador solicitado
                $eqModel = &$this->getModel('Eq');
                $eqModel->setId($eqId);
                $eq = $eqModel->getData();

                if (!isset($eq->id)) {
                    return array();
                }

                // datos de las bandas asociadas al ecualizador
                $bandModel =& $this->getModel('Banda');
                $bandModel->setWhere('e.eq_id = ' . $eq->id);
                $bandModel->setLimitStart(0);
                $bandModel->setLimit(0);
                $bandModel->setOrderBy(
                        $eqZonalesParams->get('eq_order_by', 'band_label') . ' ' .
                        $eqZonalesParams->get('eq_order', 'asc'));
                $bands = $bandModel->getAll();

                $data = new stdClass();
                $data->eq = $eq;
                $data->bands = $bands;
            }

            // almaceno en sesión el eq si el usuario es anónimo
            //if ($user->guest) {
            // almaceno el ecualizador en la sesión del usuario
            $session->set('eq'.$eqId, $data);
            //}
        }
        return $data;
    }

    function retrieveUserEqImpl($userId) {
        $session =& JFactory::getSession();
        $user =& JFactory::getUser($userId);
        $userId = $user->id;
        $data = null;
        
        if ($session->has('eqs'.$user->guest)) {
            $data = $session->get('eqs'.$user->guest);
        } else {
            if ($userId >= 0) {
                $model = &$this->getModel('Eq');
                if(!($eqs = $model->getUserEqs($userId)))
                     $eqs = $this->_createNewDefaultEq($user);

                $data = array();
                foreach ($eqs as $currentEq) {
                    $data[] = $this->retrieveEqImpl($currentEq->id);
                }
                $session->set('eqs'.$user->guest,$data);
                if($session->has('eqs'.!$user->guest)) {
                    $this->clearEqFromSession($session->get('eqs'.!$user->guest));
                    $session->clear('eqs'.!$user->guest);
                }
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
            return $this->helper->getEqJsonResponse(comEqZonalesHelper::FAILURE, $message);
            ;
        }

        $jtext = new JText();
        $message = $jtext->sprintf('ZONALES_EQ_UPDATE_SUCCESS',JText::_('ZONALES_EQ_EQ'));
        return $this->helper->getEqJsonResponse(comEqZonalesHelper::SUCCESS, $message);
        ;
//        if (!$model->store(false, false, $eqData)) {
//            echo "<script> alert('".$model->getError()."'); window.history.go(-1); </script>\n";
//            exit();
//        }
    }
    function clearEqFromSession($eqs = array()) {
        $session =& JFactory::getSession();
        foreach ($eqs as $eq) {
            $session->clear('eq'.$eq->eq->id);
        }
    }
}