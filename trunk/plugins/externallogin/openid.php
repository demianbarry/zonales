<?php

/**
 * @version		$Id: openid.php 11403 2009-01-06 06:19:31Z ian $
 * @package		Joomla
 * @subpackage	JFramework
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');
jimport('joomla.database.database');
jimport('joomla.user.helper');
jimport('joomla.utilities.utility');

require_once 'externalauth.php';

/**
 * OpenID Authentication Plugin
 *
 * @package		Joomla
 * @subpackage	openID
 * @since 1.5
 */

class plgAuthenticationOpenID extends JPlugin {
/**
 * Constructor
 *
 * For php4 compatability we must not use the __constructor as a constructor for plugins
 * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
 * This causes problems with cross-referencing necessary for the observer design pattern.
 *
 * @param 	object $subject The object to observe
 * @param 	array  $config  An array that holds the plugin configuration
 * @since 1.5
 */
    function plgAuthenticationOpenID(& $subject, $config) {
        parent::__construct($subject, $config);
    }

    /**
     * This method should handle any authentication and report back to the subject
     *
     * @access	public
     * @param   array 	$credentials Array holding the user credentials
     * @param 	array   $options     Array of extra options (return, entry_url)
     * @param	object	$response	Authentication response object
     * @return	boolean
     * @since 1.5
     */
    function onAuthenticate($credentials, $options, & $response) {
        $mainframe =& JFactory::getApplication();
        ###########
        $db = &JFactory::getDBO();
        $this->logme($db, 'en el plugin openid');


        ################################################
        ## modificacion para que acepte gmail y yahoo ##
        ################################################

        ## asignar valor a $provider!!!!!!
        $provider = (isset($credentials['provider']) && $credentials['provider'] != null) ? $credentials['provider'] : 'OpenID';

        $selectProtocol = 'select t.function from #__providers p, #__protocol_types t ' .
            'where p.name = "' . $provider . '" ' . 
            'and p.protocol_type_id=t.id';
        $db->setQuery($selectProtocol);
        $dbprovider = $db->loadObject();
        
        // obtengo el nombre de la funcion
        $function = $db->function;
        
        // invoco la funcion correspondiente que iniciara el proceso de autenticacion
        $info = $function($credentials,$options);


        $this->logme($db,'se va a iniciar la interpretacion de los resultados');
        switch ($info[STATUS]) {
            case Auth_SUCCESS : {
                    $usermode = $this->params->get('usermode', 2);

                    $response->status = JAUTHENTICATE_STATUS_SUCCESS;
                    $response->error_message = '';
                    $session->set('externalidentifier',$info[EXTERNAL_ID]);

/* in the following code, we deal with the transition from the old openid version to the new openid version
   In the old version, the username was always taken straight from the login form.  In the new version, we get a
   username back from the openid provider.  This is necessary for a number of reasons.  First, providers such as
   yahoo.com allow you to enter only the provider name in the username field (i.e. yahoo.com or flickr.com).  Taking
   this as the username would obviously cause problems because everybody who had an id from yahoo.com would have username
   yahoo.com.  Second, it is necessary because with the old way, we rely on the user entering the id the same every time.
   This is bad because if the user enters the http:// one time and not the second time, they end up as two different users.
   There are two possible settings here - the first setting, is to always use the new way, which is to get the username from
   the provider after authentication.  The second setting is to check if the username exists that we got from the provider.  If it
   doesn't, then we check if the entered username exists.  If it does, then we update the database with the username from the provider
   and continue happily along with the new username.
   We had talked about a third option, which would be to always used the old way, but that seems insecure in the case of somebody using
   a yahoo.com ID.
*/
                    if ($usermode && $usermode == 1) {
                        $response->username = $info[EXTERNAL_ID];
                    } else {

                        $query = 'SELECT u.username, a.block as aliasblocked, u.block as userblocked' .
                            ' FROM #__alias a, #__providers p, #__users u'.
                            ' WHERE a.name='.$db->Quote($info[EXTERNAL_ID]).
                            ' AND a.provider_id = p.id' .
                            ' AND u.id = a.user_id' .
                            ' AND p.name = ' . $db->Quote($provider);
                        $db->setQuery($query);
                        $dbresult = $db->loadObject();

                        $this->logme($db, 'realizo la consulta en busca del alias');

                        if ($dbresult) {
                        // if so, we set our username value to the provided value
                            $response->username = $dbresult->username;

                            $this->logme($db, 'el alias fue encontrado :D');

                            // si el alias o el usuario se encuentran bloqueados
                            // el acceso es cancelado
                            if ($dbresult->aliasblocked || $dbresult->userblocked) {
                                $response->status = JAUTHENTICATE_STATUS_FAILURE;
                                $response->error_message = 'The identifier is Blocked';
                                return false;
                            }
                        } else { // si el alias no existe
                            $this->logme($db, 'el alias no existe :(');

                            $session->set('authenticationonprogress','true');

                            if ($credentials['userid'] == 0) {
                                $user =& JFactory::getUser();
                                if ($user->guest) {
                                    $mainframe->redirect('index.php?option=com_user&view=userstatusrequest&externalid=' . $info[EXTERNAL_ID] .
                                        '&providerid=' . $dbprovider->id);
                                }
                                else {
                                    $token = JUtility::getToken();
                                    $mainframe->redirect('index.php?option=com_user&task=aliasregister&externalid=' . urlencode($info[EXTERNAL_ID]) .
                                        '&providerid=' . $dbprovider->id . '&' . $token .'=1');
                                }
                            }




                        }
                    }

                }
                break;

            case Auth_CANCEL : {
                    $response->status = JAUTHENTICATE_STATUS_CANCEL;
                    $response->error_message = 'Authentication cancelled';
                }
                break;

            case Auth_FAILURE : {
                    $response->status = JAUTHENTICATE_STATUS_FAILURE;
                    $response->error_message = 'Authentication failed';
                }
                break;
        }
    }

    //	function

    private function logme($db,$message) {
        $query='insert into #__logs(info,timestamp) values ("' .
            $message . '","' . date('Y-m-d h:i:s') . '")';
        $db->setQuery($query);
        $db->query();
    }

}

