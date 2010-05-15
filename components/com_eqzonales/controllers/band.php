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

/**
 * Controlador
 *
 */
class EqZonalesControllerBand extends JController {

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

    function modifyBandAjax() {
        $bands = JRequest::getVar('slider', NULL, 'post', 'array');
        $eqid = JRequest::getVar('eqid', NULL, 'post', 'int');

        $bandsArray = array();
        foreach ($bands as $band) {
            list($id, $valueId, $peso) = explode("-", $band);
            $bandsArray[] = (object) array('id' => $id, 'cp_value_id' => $valueId, 'peso' => $peso, 'eq_id' => $eqid);
        }

        $this->modifyBandImpl($bandsArray);
        return;
    }

    function modifyBand() {
        $band_params = JRequest::getVar('params', NULL, 'post', 'string');
        $params = $this->helper->getJsonParams($band_params, JText::_('ZONALES_EQ_BAND'));
        if (!$params) return;

        $this->modifyBandImpl($params);
    }

    function modifyBandImpl($params = NULL) {

        // Chequea que el usuario haya iniciado sesión
        $user =& JFactory::getUser();
        if ($user->guest) {
            return $this->helper->getEqJsonResponse(comEqZonalesHelper::FAILURE, JText::_('ZONALES_EQ_SESSION_REQUIRED'));
        }

        // Chequea que hayan sido pasados valores para las bandas
        if (is_null($params)) {
            return;
        }

        // Va guardando cada banda
        foreach ($params as $band) {

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
                return;
            }

            if (is_null($bandData['cp_value_id'])) {
                return;
            }

            // Almacena el ecualizador
            $model = &$this->getModel('Banda');
            if (!$model->store(false, false, $bandData)) {
                $jtext = new JText();
                $message = $jtext->sprintf('ZONALES_EQ_CREATE_FAILURE',JText::_('ZONALES_EQ_BAND'));
                echo $this->helper->getEqJsonResponse(comEqZonalesHelper::FAILURE, $message);
                return;
            }
            
        }

        $jtext = new JText();
        $message = $jtext->sprintf('ZONALES_EQ_CREATE_SUCCESS',JText::_('ZONALES_EQ_BAND'));
        echo $this->helper->getEqJsonResponse(comEqZonalesHelper::SUCCESS, $message);
        return;

        //var_dump($band);
        //var_dump($bandData);
    }
}