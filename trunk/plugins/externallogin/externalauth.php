<?php

jimport('facebookconnect.facebook');
require_once 'externalloginconstants.php';

function facebookconnect($credentials,$options) {
    $db = JFactory::getDBO();
    $selectKeys = 'select p.apikey, p.secretkey from #__providers p where p.name=' . $db->Quote($credentials['provider']);
    $db->setQuery($selectKeys);
    $dbKeys = $db->loadObject();

    $apikey = $dbKeys->apikey;
    $secretkey = $dbKeys->secretkey;
    $facebook = new Facebook($apikey, $secretkey);
    $userid = $facebook->require_login();

    $info = array();
    $info[EXTERNAL_ID] = $userid;
    $info[STATUS] = Auth_SUCCESS;

    return $info;

}

function openid($credentials,$options) {
    $mainframe =& JFactory::getApplication();
    $provider = $credentials[PROVIDER];
    $db = JFactory::getDBO();
    $selectProvider = 'select p.id, p.discovery_url, p.prefix, p.suffix from #__providers p where p.name = "' . $provider . '"';
    $db->setQuery($selectProvider);
    $dbprovider = $db->loadObject();

    $beginning = substr($credentials['username'], 0, strlen($dbprovider->prefix));
    $ending = substr($credentials['username'], strlen($credentials['username']) - strlen($dbprovider->suffix));

    if ($beginning != $dbprovider->prefix) {
        $credentials['username'] = $dbprovider->prefix . $credentials['username'];
    }
    if ($ending != $dbprovider->suffix) {
        $credentials['username'] = $credentials['username'] . $dbprovider->suffix;
    }

    $discovery_url = (isset ($dbprovider->discovery_url)) ? $dbprovider->discovery_url : $credentials['username'];
    ################################################


    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        define('Auth_OpenID_RAND_SOURCE', null);
    } else {
        $f = @fopen('/dev/urandom', 'r');
        if ($f !== false) {
            define('Auth_OpenID_RAND_SOURCE', '/dev/urandom');
            fclose($f);
        } else {
            $f = @fopen('/dev/random', 'r');
            if ($f !== false) {
                define('Auth_OpenID_RAND_SOURCE', '/dev/urandom');
                fclose($f);
            } else {
                define('Auth_OpenID_RAND_SOURCE', null);
            }
        }
    }
    jimport('openid.consumer');
    jimport('joomla.filesystem.folder');

    // Access the session data
    $session = & JFactory :: getSession();

    // Create and/or start using the data store
    $store_path = JPATH_ROOT . '/tmp/_joomla_openid_store';
    if (!JFolder :: exists($store_path) && !JFolder :: create($store_path)) {
        $response->type = JAUTHENTICATE_STATUS_FAILURE;
        $response->error_message = "Could not create the FileStore directory '$store_path'. " . " Please check the effective permissions.";
        return false;
    }

    // Create store object
    $store = new Auth_OpenID_FileStore($store_path);

    // Create a consumer object
    $consumer = new Auth_OpenID_Consumer($store);

    if (!isset ($_SESSION['_openid_consumer_last_token'])) {
        logme($db,'se va a iniciar el proceso');
        // Begin the OpenID authentication process.
        if (!$auth_request = $consumer->begin($discovery_url)) {
            logme($db,'no se pudo iniciar el proceso');
            $response->type = JAUTHENTICATE_STATUS_FAILURE;
            $response->error_message = 'Authentication error : could not connect to the openid server';
            return false;
        }
        logme($db,'continuamos');

//        $policy_uris = array();
//        if ($this->params->get( 'phishing-resistant', 0)) {
//            $policy_uris[] = 'http://schemas.openid.net/pape/policies/2007/06/phishing-resistant';
//        }
//
//        if ($this->params->get( 'multi-factor', 0)) {
//            $policy_uris[] = 'http://schemas.openid.net/pape/policies/2007/06/multi-factor';
//        }
//
//        if ($this->params->get( 'multi-factor-physical', 0)) {
//            $policy_uris[] = 'http://schemas.openid.net/pape/policies/2007/06/multi-factor-physical';
//        }
//
//        $pape_request = new Auth_OpenID_PAPE_Request($policy_uris);
//        if ($pape_request) {
//            $auth_request->addExtension($pape_request);
//        }

        //Create the entry url
        $entry_url = isset ($options['entry_url']) ? $options['entry_url'] : JURI :: base();
        $entry_url = JURI :: getInstance($entry_url);

        unset ($options['entry_url']); //We don't need this anymore

        //Create the url query information
        $options['return'] = isset($options['return']) ? base64_encode($options['return']) : base64_encode(JURI::base());
        $options[JUtility::getToken()] = 1;

        $process_url  = sprintf($entry_url->toString()."?option=com_user&task=login&provider=%s",$provider);
        $process_url  = (isset ($credentials['username']) && $credentials['username'] != '') ? sprintf("%s&username=%s",$process_url,$credentials['username']) : $process_url;
        $process_url .= '&'.JURI::buildQuery($options);
        logme($db, 'la url de retorno es: ' . $process_url);
        $session->set('return_url', $process_url );

        $trust_url = $entry_url->toString(array (
            'path',
            'host',
            'port',
            'scheme'
        ));
        $session->set('trust_url', $trust_url);
        logme($db,'tomando decisiones');
        // For OpenID 1, send a redirect.  For OpenID 2, use a Javascript
        // form to send a POST request to the server.
        if ($auth_request->shouldSendRedirect()) {
            $redirect_url = $auth_request->redirectURL($trust_url, $process_url);

            // If the redirect URL can't be built, display an error
            // message.
            if (Auth_OpenID :: isFailure($redirect_url)) {
                displayError("Could not redirect to server: " . $redirect_url->message);
            } else {
            // Send redirect.
                $mainframe->redirect($redirect_url);
                return false;
            }
        } else {
        // Generate form markup and render it.
            $form_id = 'openid_message';
            $form_html = $auth_request->htmlMarkup($trust_url, $process_url, false, array (
                'id' => $form_id
            ));
            // Display an error if the form markup couldn't be generated;
            // otherwise, render the HTML.
            if (Auth_OpenID :: isFailure($form_html)) {
            //displayError("Could not redirect to server: " . $form_html->message);
            } else {
                JResponse :: setBody($form_html);
                echo JResponse :: toString($mainframe->getCfg('gzip'));
                $mainframe->close();
                return false;
            }
        }
    }
    logme($db,'voy a finalizar el proceso');
    $result = $consumer->complete($session->get('return_url'));

    // estandarizo el formato de salida de los datos necesarios
    $info = array();
    $info[EXTERNAL_ID] = $result->getDisplayIdentifier();

    switch ($result->status) {
        case Auth_OpenID_SUCCESS:
            $info[STATUS] = Auth_SUCCESS;
            break;
        case Auth_OpenID_CANCEL:
            $info[STATUS] = Auth_CANCEL;
            break;
        case Auth_OpenID_FAILURE:
            $info[STATUS] = Auth_FAILURE;
            break;
    }

    return $info;
}

    function logme($db,$message) {
        $query='insert into #__logs(info,timestamp) values ("' .
            $message . '","' . date('Y-m-d h:i:s') . '")';
        $db->setQuery($query);
        $db->query();
    }

?>
