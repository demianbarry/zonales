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
         * Si el nuevo usuario ha sido creado en la base de datos...
         */
        if ($isnew) {
            /**
             * Objeto con información del usurio y ecualizador a crear
             */
            $params = new stdClass();
            $params->user_id = $user['id'];
            $params->nombre = $user['name'];
            $params->descripcion = 'Ecualizador de ' . $user['name'];
            $params->observaciones = 'Ecualizador de ' . $user['name'];

            /**
             * Realiza el include de los controladores del componente EqZonales
             */
            require_once(JPATH_BASE.DS.'components'.DS.'com_eqzonales'.DS.'controllers'.DS.'eq.php');
            require_once(JPATH_BASE.DS.'components'.DS.'com_eqzonales'.DS.'controllers'.DS.'band.php');
            require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_eqzonales'.DS.'helper'.DS.'helper.php');
            JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_eqzonales'.DS.'tables');

            // Instancia el helper de EqZonales
            $helper = new comEqZonalesHelper();

            /**
             * Crea instancias de los controladores del componente EqZonales,
             * y les setea el path correcto hacia sus respectivos modelos.
             */
            $ctrlEq = new EqZonalesControllerEq();
            $ctrlEq->addModelPath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_eqzonales'.DS.'models' );
            $ctrlBand = new EqZonalesControllerBand();
            $ctrlBand->addModelPath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_eqzonales'.DS.'models' );

            /**
             * Crea el ecualizador con los datos del nuevo usuario.
             */
            $eqId = $ctrlEq->createEq($params);
            if ($eqId === FALSE) {
                return;
            } else {
                /**
                 * Instancia las bandas por defecto para el ecualizador.
                 */
                $ctrlBand->createDefaultBands($eqId);
            }
        }
    }

    /**
     * Example store user method
     *
     * Method is called before user data is deleted from the database
     *
     * @param 	array		holds the user data
     */
    function onBeforeDeleteUser($user) {
        global $mainframe;
    }

    /**
     * Example store user method
     *
     * Method is called after user data is deleted from the database
     *
     * @param 	array		holds the user data
     * @param	boolean		true if user was succesfully stored in the database
     * @param	string		message
     */
    function onAfterDeleteUser($user, $succes, $msg) {
        global $mainframe;

        // only the $user['id'] exists and carries valid information

        // Call a function in the external app to delete the user
        // ThirdPartyApp::deleteUser($user['id']);
    }
}
