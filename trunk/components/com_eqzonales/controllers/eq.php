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
require_once JPATH_COMPONENT . DS . 'queries' . DS . 'userquery.php';

/**
 * Controlador
 *
 */
class EqZonalesControllerEq extends JController {

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

    function createEq() {
        $eq_params = JRequest::getVar('params', NULL, 'post', 'string');

        $params = $this->helper->getJsonParams($eq_params, JText::_('ZONALES_EQ_EQ'));
        if (!$params) return;

        echo $this->createEqImpl($params);
        return;
    }

    function modifyEq() {
        $eq_params = JRequest::getVar('params', NULL, 'post', 'string');

        $params = $this->helper->getJsonParams($eq_params, JText::_('ZONALES_EQ_EQ'));
        if (!$params) return;

        echo $this->modifyEqImpl($params);
        return;
    }

    function runQuery() {
        $data = array();
        $data[SolrQuery::VALUE] = JRequest::getString('value','','method');
        $data[SolrQuery::FIELD] = JRequest::getString('field',null,'method');
        $data[SolrQuery::BOOST] = JRequest::getString('boost',null,'method');

        $query = new UserQuery(JFactory::getUser(),$data);
        $client = new SolrClient();
        $client->setHost('http://localhost/solr');

        $results = $client->executeQuery($query);

        print_r($results);
    }

    /**
     * Genera un nuevo ecualizador para el usuario indicado.
     *
     * @param int $user_id id usuario
     * @param String $eq_name nombre del ecualizador
     */
    function createEqImpl($params = NULL) {
        // Chequea que el usuario haya iniciado sesión
        $user =& JFactory::getUser();
        if ($user->guest) {
            return $this->helper->getEqJsonResponse(comEqZonalesHelper::FAILURE, JText::_('ZONALES_EQ_SESSION_REQUIRED'));
        }

        $user_id = property_exists($params, 'user_id') ? $params->user_id : NULL;
        $eq_name = property_exists($params, 'nombre') ? $params->nombre : NULL;

        // Chequea que el usuario indicado sea valido
        if ($user_id <= 0) {
            return $this->helper->getEqJsonResponse(comEqZonalesHelper::FAILURE, JText::_('ZONALES_EQ_INVALID_USER'));;
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

        // Almacena el ecualizador
        $model = &$this->getModel('Eq');
        if (!$model->store(false, false, $eqData)) {
            $jtext = new JText();
            $message = $jtext->sprintf('ZONALES_EQ_CREATE_FAILURE',JText::_('ZONALES_EQ_EQ'));
            return $this->helper->getEqJsonResponse(comEqZonalesHelper::FAILURE, $message);;
        }

        $jtext = new JText();
        $message = $jtext->sprintf('ZONALES_EQ_CREATE_SUCCESS',JText::_('ZONALES_EQ_EQ'));
        return $this->helper->getEqJsonResponse(comEqZonalesHelper::SUCCESS, $message);;
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