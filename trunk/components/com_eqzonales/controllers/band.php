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
        if ($user->guest) {
            echo $this->helper->getEqJsonResponse(comEqZonalesHelper::FAILURE,
                JText::_('ZONALES_EQ_SESSION_REQUIRED'));
            return;
        }

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
            list($id, $valueId, $peso) = explode("-", $band);
            $bandsArray[] = (object) array('id' => $id, 'cp_value_id' => $valueId, 'peso' => $peso, 'eq_id' => $eqid);
        }

        /**
         * Crea/modifica las bandas del ecualizador según la configuración
         * especificada.
         */
        if(!$this->modifyBandImpl($bandsArray)) {
            echo $this->helper->getEqJsonResponse(comEqZonalesHelper::FAILURE,
            $jtext->sprintf('ZONALES_EQ_CREATE_FAILURE', JText::_('ZONALES_EQ_BAND')));
        } else {
            echo $this->helper->getEqJsonResponse(comEqZonalesHelper::SUCCESS,
            $jtext->sprintf('ZONALES_EQ_CREATE_SUCCESS', JText::_('ZONALES_EQ_BAND')));
        }

        return;
    }

    /**
     *
     * @return <type>
     */
    function modifyBand() {
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
     * @param int $eqid id del ecualizador al cual asignar las bandas.
     * @return boolean TRUE al agregar con exito las bandas al ecualizador.
     */
    function createDefaultBands($eqid = null) {
        if (is_null($eqid)) {
            return false;
        }

        // TODO: Generar bandas desde Ecualizador 0 (por defecto)

        $params = array ();

        $banda = new stdClass();
        $banda->valor = 'Valor';
        $banda->peso = '50';
        $banda->cp_value_id = '123';
        $banda->eq_id = $eqid;
        $banda->default = '0';
        $banda->active = '0';
        
        $params[] = $banda;

        /**
         * Crea/modifica las bandas del ecualizador según la configuración
         * especificada.
         */
        if($this->modifyBandImpl($params)) {
            return true;
        }
        return false;
    }

    /**
     *
     * @param <type> $params
     * @return <type>
     */
    function modifyBandImpl($params = NULL) {

        // Chequea que hayan sido pasados valores para las bandas
        if (is_null($params)) {
            return false;
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
}