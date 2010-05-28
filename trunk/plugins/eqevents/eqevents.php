<?php
/**
 * @version		$Id$
 * @package		Zonales
 * @subpackage	Ecualizador de Interés
 * @copyright	Copyright (C) 2009 - 2010 Mediabit
 * @license		GNU/GPL, see LICENSE.php
 *
 * Zonales is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.plugin.plugin');

/**
 * EqEvents User Plugin
 *
 * El presente plugin se encarga de ejecutar las accioens de creación,
 * modificación o eliminación de ecualizadores según los distintos eventos que
 * puedan darse en la administración de usuarios.
 *
 * @package	Zonales
 * @subpackage	Ecualizador de Interés
 * @since 	1.0
 */
class plgUserEqevents extends JPlugin {

    /**
     * Controlador Ecualizador
     * @var EqZonalesControllerEq
     */
    var $_ctrlEq = null;
    /**
     * Controlador Banda
     * @var EqZonalesControllerBand
     */
    var $_ctrlBand = null;

    /**
     * Constructor
     *
     * For php4 compatability we must not use the __constructor as a
     * constructor for plugins because func_get_args ( void ) returns a
     * copy of all passed arguments NOT references. This causes problems
     * with cross-referencing necessary for the observer design pattern.
     *
     * @param object $subject The object to observe
     * @param array  $config  An array that holds the plugin configuration
     * @since 1.0
     */
    function plgUserEqevents(& $subject, $config) {
        parent::__construct($subject, $config);

        /**
         * Realiza el include de los controladores del componente EqZonales
         */
        require_once(JPATH_BASE.DS.'components'.DS.'com_eqzonales'.DS.'controllers'.DS.'eq.php');
        require_once(JPATH_BASE.DS.'components'.DS.'com_eqzonales'.DS.'controllers'.DS.'band.php');
        require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_eqzonales'.DS.'helper'.DS.'helper.php');
        JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_eqzonales'.DS.'tables');

        /**
         * Crea instancias de los controladores del componente EqZonales,
         * y les setea el path correcto hacia sus respectivos modelos.
         */
        $this->_ctrlEq = new EqZonalesControllerEq();
        $this->_ctrlEq->addModelPath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_eqzonales'.DS.'models' );
        $this->_ctrlBand = new EqZonalesControllerBand();
        $this->_ctrlBand->addModelPath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_eqzonales'.DS.'models' );
    }

    /**
     * Verifica que un usuario cuente con un Ecualizador, y en caso de no contar
     * con uno lo genera dinámicamente. Este chequeo puede ser desactivado
     * desde la configuración del plugin.
     *
     * @param Array $user Arreglo con información del usuario.
     * @param Array $options Arreglo con opciones varias.
     * @return True En todos los casos. Si falla la creació del Ecualizador el
     *              modulo correspondiente mostrará un mensaje de error.
     */
    function onLoginUser($user, $options) {
        $create_eq = $this->params->get('create_eq', true);

        // No chequear existencia de un ecualizador
        if (!$create_eq) {
            return true;
        }

        // no se ejecuta si se accede al backend
        $app = JFactory::getApplication();
        if ($app->isAdmin()) return true;

        jimport('joomla.user.helper');
        $instance = new JUser();
        $id = intval(JUserHelper::getUserId($user['username']));
        if ($id) {
            $instance->load($id);
        }

        $userTmp['id'] = intval($instance->get('id'));
        $userTmp['name'] = $instance->get('name');

        // Crear un ecualizador si el usuario no cuenta con uno
        $userEq = $this->_ctrlEq->retrieveUserEqImpl($id);

        if (is_null($userEq) || empty($userEq)) {
            $this->_createNewDefaultEq($userTmp);
        }

        return true;
    }

    /**
     * Luego de que un nuevo usuario es creado en la base de datos, este
     * método se encarga de generar un nuevo ecualizador asociado al nuevo
     * usuario, tomando como base un ecualizador por defecto.
     *
     * @param array     holds the new user data
     * @param boolean   true if a new user is stored
     * @param boolean   true if user was succesfully stored in the database
     * @param string    message
     */
    function onAfterStoreUser($user, $isnew, $success, $msg) {
        global $mainframe;

        /**
         * Si es un nuevo usuario, creamos el ecualizador.
         */
        if ($isnew) {
            $this->_createNewDefaultEq($user);
        }

        return true;
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
        $params = new stdClass();
        $params->user_id = $user['id'];
        $params->nombre = $user['name'];
        $params->descripcion = 'Ecualizador de ' . $user['name'];
        $params->observaciones = 'Ecualizador de ' . $user['name'];

        /**
         * Crea el ecualizador con los datos del nuevo usuario.
         */
        $eqId = $this->_ctrlEq->createEq($params);
        if ($eqId === FALSE) {
            return;
        } else {
            /**
             * Instancia las bandas por defecto para el ecualizador.
             */
            $this->_ctrlBand->createDefaultBands($eqId);
        }
    }
}
