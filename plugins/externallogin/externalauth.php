<?php

jimport('facebookconnect.facebook');
jimport('twitter.EpiCurl');
jimport('twitter.EpiTwitter');
jimport('windowsliveid.windowslivelogin');
require_once 'externalloginconstants.php';
require_once 'twitter_helper.php';

function tradicional($credentials,$options) {
    $info = array();
    $info[STATUS] = Auth_FAILURE;
    return $info;
}

function facebookconnect($credentials,$options) {
    $db = JFactory::getDBO();
    $selectKeys = 'select p.apikey, p.secretkey from #__providers p where p.name=' . $db->Quote($credentials['provider']);
    $db->setQuery($selectKeys);
    $dbKeys = $db->loadObject();

    // se eliminan las llaves
    $withoutBeginning = substr($credentials['session'], 1);
    $rawString = substr($withoutBeginning, 0, strlen($withoutBeginning)-1);
    // se recuperan las duplas
    $rawSessionData = urldecode($rawString);
    $sessionItems = explode(',', $rawSessionData);
    $sessionData = array();
    // se arman las duplas
    foreach ($sessionItems as $item) {
        $components = explode(':', $item);
        list ($key,$value) = $components;
        $datakey = explode('"',$key);
        // la clave ya no tiene comillas
        $key = $datakey[1];
        // si es un string o un numero cambia donde hay que buscar
        $i = (substr($value,0,1) == '"') ? 1 : 0;
        $dataValue = explode('"',$value);
        // obtengo el valor segun la posicion que corresponda
        $value = $dataValue[$i];
        $sessionData[$key] = $value;
    }

    if (isset ($sessionData['uid']) && $sessionData['uid'] != 0 && $sessionData['uid'] != '') {
        $info[EXTERNAL_ID] = $sessionData['uid'];
        $info[STATUS] = Auth_SUCCESS;
    }
    else {
        $info[STATUS] = Auth_FAILURE;
    }

    return $info;

}

function openid($credentials,$options) {
    $mainframe =& JFactory::getApplication();
    $provider = $credentials[PROVIDER];
    $db = JFactory::getDBO();
    $selectProvider = 'select p.id, p.discovery_url, p.prefix, p.suffix from #__providers p where p.name = "' . $provider . '"';
    $db->setQuery($selectProvider);
    $dbprovider = $db->loadObject();

    $prefix = trim($dbprovider->prefix);
    $suffix = trim($dbprovider->suffix);
    //$discovery = trim($dbprovider->discovery_url);
    //    $discovery = ($dbprovider->discovery_url == null) ? null : trim($dbprovider->discovery_url);
    $discovery = $dbprovider->discovery_url;
    $username = trim($credentials['username']);
    $beginning = substr($username, 0, strlen($prefix));
    $ending = substr($username, strlen($username) - strlen($suffix));

    if ($beginning != $prefix) {
        $username = $prefix . $username;
    }
    if ($ending != $suffix) {
        $username = $username . $suffix;
    }

    //$discovery_url = ($discovery) ? $discovery : $credentials['username'];
    $discovery_url = ($discovery) ? $discovery : $username;
    $username = ($discovery) ? '' : $username;

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
    $info = array();

    // Create and/or start using the data store
    $store_path = JPATH_ROOT . '/tmp/_joomla_openid_store';
    if (!JFolder :: exists($store_path) && !JFolder :: create($store_path)) {
        $info[STATUS] = Auth_FAILURE;
        //$response->type = JAUTHENTICATE_STATUS_FAILURE;
        //$response->error_message = "Could not create the FileStore directory '$store_path'. " . " Please check the effective permissions.";
        return false;
    }

    // Create store object
    $store = new Auth_OpenID_FileStore($store_path);

    // Create a consumer object
    $consumer = new Auth_OpenID_Consumer($store);

    if (!isset ($_SESSION['_openid_consumer_last_token'])) {
        // Begin the OpenID authentication process.
        if (!$auth_request = $consumer->begin($discovery_url)) {
            $info[STATUS] = Auth_FAILURE;
            //$response->type = JAUTHENTICATE_STATUS_FAILURE;
            //$response->error_message = 'Authentication error : could not connect to the openid server';
            return $info;
        }


        // if ($auth_request->endpoint->usesExtension(Auth_OpenID_AX_NS_URI)) {
        $ax_request = new Auth_OpenID_AX_FetchRequest();
        $ax_request->add(Auth_OpenID_AX_AttrInfo::make('http://axschema.org/contact/email', 1, true));

        //         }
        $sreg_request = Auth_OpenID_SRegRequest::build(array ('email'));

        if ($ax_request) {
            $auth_request->addExtension($ax_request);
            $auth_request->addExtension($sreg_request);
        }


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
        $process_url  = (isset ($username) && $username) ? sprintf("%s&username=%s",$process_url,urlencode($username)) : $process_url;
        $process_url .= '&'.JURI::buildQuery($options);
        $session->set('return_url', $process_url );

        $trust_url = $entry_url->toString(array (
                'path',
                'host',
                'port',
                'scheme'
        ));
        $session->set('trust_url', $trust_url);
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
    $result = $consumer->complete($session->get('return_url'));

// estandarizo el formato de salida de los datos necesarios
    $info[EXTERNAL_ID] = $result->getDisplayIdentifier();

    switch ($result->status) {
        case Auth_OpenID_SUCCESS:
            $info[STATUS] = Auth_SUCCESS;
            $ax_resp = Auth_OpenID_AX_FetchResponse::fromSuccessResponse($result);

            if ($ax_resp) {
                $email = $ax_resp->getSingle('http://axschema.org/contact/email');
                if ($email && !is_a($email, 'Auth_OpenID_AX_Error')) {
                    $info[EMAIL] = $email;
                }
            }


            $sreg_resp = Auth_OpenID_SRegResponse::fromSuccessResponse($result);

            if (!isset ($info[EMAIL]) && $sreg_resp) {
                $sreg = $sreg_resp->contents();
                if (isset ($sreg['email'])){
                    $info[EMAIL] = $sreg['email'];
                }
            }

            $info[EMAIL] = (isset ($info[EMAIL])) ? $info[EMAIL] : $info[EXTERNAL_ID];
            $info[LABEL] = ($discovery) ? $info[EMAIL] : $info[EXTERNAL_ID];
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

function twitteroauth($credentials,$options) {
    try {
        $session = & JFactory :: getSession();
        $mainframe =& JFactory::getApplication();
        $db = JFactory::getDBO();
        $db->setQuery('select p.apikey, p.secretkey as secret from #__providers p where p.name=' . $db->Quote($credentials['provider']));
        $consumer = $db->loadObject();
        $twitterObj = new EpiTwitter($consumer->apikey, $consumer->secret);

        // si no se ha iniciado el proceso de autenticacion
        $hasbegun = $session->get('twitterhasbegun');
        if (!isset ($hasbegun) || $hasbegun == 'false') {
            $session->set('twitterhasbegun','true');
            $mainframe->redirect($twitterObj->getAuthenticateUrl());
        }

        $session->set('twitterhasbegun','false');

        $twitterObj->setToken($credentials['oauth_token']);
        $token = $twitterObj->getAccessToken();
        $twitterObj->setToken($token->oauth_token, $token->oauth_token_secret);
        $data = $twitterObj->get_accountVerify_credentials();

        $info = array();
        $info[EXTERNAL_ID] = $data->id;
        $info[STATUS] = Auth_SUCCESS;

        return $info;
    }
    catch (EpiOAuthException $ex ) {
        $info = array();
        $info[STATUS] = Auth_FAILURE;
        return $info;
    }

}

function liveid($credentials,$options) {
    $session = & JFactory :: getSession();
    $mainframe =& JFactory::getApplication();
    $db = JFactory::getDBO();
    $db->setQuery('select p.apikey, p.secretkey, p.discovery_url as control_url, p.parameters from #__providers p where p.name=' . $db->Quote($credentials['provider']));
    $consumer = $db->loadObject();
    $appid = $consumer->apikey;
    $secret = $consumer->secret;
    $policyurl = JUri::base() . '/policy.html';
    $returnurl = JUri::base() . '/index.php';
    $wll = new WindowsLiveLogin($appid, $secret, 'wsignin1.0', null, $policyurl, $returnurl);

    $info = array();
    $token = @$_COOKIE['webauthtoken'];
    $info[EXTERNAL_ID] = null;
    $info[STATUS] = Auth_FAILURE;

    if ($token) {
        $user = $wll->processToken($token);
        if ($user) {
            $info[EXTERNAL_ID] = $user->getId();
            $info[STATUS] = Auth_SUCCESS;
        }
    }
    else {
        $hasbegun = $session->get('liveidhasbegun');
        if (isset ($hasbegun) || $hasbegun == 'false') {
            $session->set('liveidhasbegun','true');
            $url = $consumer->control_url . '?appid=' . $appid . '&' . $consumer->parameters;
            $mainframe->redirect($url);
        }

        $session->set('liveidhasbegun','false');

        $action = $credentials['action'];

        if ($action == '' || $action == 'login') {
            $user = $wll->processLogin($credentials);
            if ($user) {
                if ($user->usePersistentCookie()) {
                    $cookieTtl = time() + (10 * 365 * 24 * 60 * 60);
                    setcookie('webauthtoken', $user->getToken(), $cookieTtl);
                }
                else {
                    setcookie('webauthtoken', $user->getToken());
                }
                $info[EXTERNAL_ID] = $user->getId();
                $info[STATUS] = Auth_SUCCESS;
            }
            $info[STATUS] = Auth_FAILURE;
        }
    }
    return $info;
}
?>
