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

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');
jimport('joomla.filesystem.file');

/**
 * Controlador
 *
 */
class EqZonalesControllerEq1 extends JController {

    /**
     * Constructor
     *
     * @param array $default
     */
    function __construct($default = array()) {
        parent::__construct($default);
    }

    function createEq() {
        $user_id = JRequest::getVar('user_id', 0, 'post', 'int');
        $eq_name = JRequest::getVar('eq_name', NULL, 'post', 'string');

        if ($user_id != 0 && $eq_name != NULL) {
            $this->createEqImpl($user_id, $eq_name);
        }
    }

    /**
     * Genera un nuevo ecualizador para el usuario indicado.
     * 
     * @param int $user_id id usuario
     * @param String $eq_name nombre del ecualizador
     */
    function createEqImpl($user_id, $eq_name) {

        // Chequea que el usuario haya iniciado sesión
        $user =& JFactory::getUser();
        if ($user->guest) {
            return;
        }

        // TODO: Chequea que el no exista un ecualizador con el mismo nombre

        // Crea nueva instancia del ecualizador
        $eqData = array(
                'nombre' => $eq_name,
                'user_id' => $user_id
        );

        // Agrega bandas por defecto del ecualizador


        // Almacena el ecualizador
        $model = &$this->getModel('Menu');

        if (!$model->store(false, true, $eqData)) {
            echo "<script> alert('".$model->getError()."'); window.history.go(-1); </script>\n";
            exit();
        }
    }
}