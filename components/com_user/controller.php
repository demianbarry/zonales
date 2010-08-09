<?php
/**
 * @version		$Id: controller.php 12389 2009-07-01 00:34:45Z ian $
 * @package		Joomla
 * @subpackage	Content
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.controller');
#jimport('joomla.mail.mail');
jimport( 'joomla.utilities.utility' );
jimport('joomla.user.helper');

require_once JPATH_ROOT . DS . 'components' . DS . 'com_user' . DS . 'constants.php';
require_once 'helper.php';
require_once JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_aapu' . DS . 'models' . DS . 'attributes.php';
require_once JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_aapu' . DS . 'models' . DS . 'attribute_entity.php';

/**
 * User Component Controller
 *
 * @package		Joomla
 * @subpackage	Weblinks
 * @since 1.5
 */
class UserController extends JController {
    /**
     * Method to display a view
     *
     * @access	public
     * @since	1.5
     */
    function display() {
        parent::display();
    }

    function edit() {
        global $mainframe, $option;

        $db		=& JFactory::getDBO();
        $user	=& JFactory::getUser();

        if ( $user->get('guest')) {
            JError::raiseError( 403, JText::_('Access Forbidden') );
            return;
        }

        JRequest::setVar('layout', 'form');

        parent::display();
    }

    function save() {
        // Check for request forgeries
        JRequest::checkToken() or jexit( 'Invalid Token' );

        $user	 =& JFactory::getUser();
        $userid = JRequest::getVar( 'id', 0, 'post', 'int' );

        // preform security checks
        if ($user->get('id') == 0 || $userid == 0 || $userid <> $user->get('id')) {
            JError::raiseError( 403, JText::_('Access Forbidden') );
            return;
        }

        //clean request
        $post = JRequest::get( 'post' );
        $post['username']	= JRequest::getVar('username', '', 'post', 'username');
        $post['password']	= JRequest::getVar('password', '', 'post', 'string', JREQUEST_ALLOWRAW);
        $post['password2']	= JRequest::getVar('password2', '', 'post', 'string', JREQUEST_ALLOWRAW);

        // get the redirect
        $return = JURI::base();

        // do a password safety check
        if(strlen($post['password']) || strlen($post['password2'])) { // so that "0" can be used as password e.g.
            if($post['password'] != $post['password2']) {
                $msg	= JText::_('PASSWORDS_DO_NOT_MATCH');
                // something is wrong. we are redirecting back to edit form.
                // TODO: HTTP_REFERER should be replaced with a base64 encoded form field in a later release
                $return = str_replace(array('"', '<', '>', "'"), '', @$_SERVER['HTTP_REFERER']);
                if (empty($return) || !JURI::isInternal($return)) {
                    $return = JURI::base();
                }
                $this->setRedirect($return, $msg, 'error');
                return false;
            }
        }

        // we don't want users to edit certain fields so we will unset them
        unset($post['gid']);
        unset($post['block']);
        unset($post['usertype']);
        unset($post['registerDate']);
        unset($post['activation']);

        // store data
        $model = $this->getModel('user');


        $db = &JFactory::getDBO();
        $query='update #__users set birthdate=' . $db->Quote($post['birthdate']) . ' where id=' . $userid;
        $db->setQuery($query);
        $db->query();

        if ($model->store($post)) {
            $msg	= JText::_( 'Your settings have been saved.' );
        } else {
            //$msg	= JText::_( 'Error saving your settings.' );
            $msg	= $model->getError();
        }


        $this->setRedirect( $return, $msg );
    }


    function cancel() {
        $this->setRedirect( 'index.php' );
    }

    private function aliasreg($providerid,$externalid,$label,$email) {

        if ($providerid != 0 && $externalid != '' && $label != '' && $email != '') {
            $db = &JFactory::getDBO();
            $user =& JFactory::getUser();

            $externalid = urldecode($externalid);
            $email = urldecode($email);
            $label = urldecode($label);
            $status = $this->insertAlias('0', $user->id, $externalid, $providerid,$label,$email);
            return $status;
        }
        return false;
    }

    function aliasregister() {
        // Check for request forgeries
        JRequest::checkToken('request') or jexit( 'Invalid Token' );

        $providerid = JRequest::getInt('providerid', '0', 'method');
        $externalid = JRequest::getVar('externalid', '', 'method', 'string');
        $email = JRequest::getVar('email', '', 'method', 'string');
        $label = JRequest::getVar('label', '', 'method', 'string');

        $statusAux = $this->aliasreg($providerid,$externalid,$label,$email);

        $status = ($statusAux) ? '0' : '1';

        $this->endAuthentication();
        global $mainframe;
        $mainframe->redirect(JRoute::_('index.php?option=com_alias&view=addmessage&status=' . $status));
    }

    function login($epUserdata = null) {
        $credentials = array();
        $options = array();
        if ($epUserdata == null) {
            // Check for request forgeries
            //JRequest::checkToken('request') or jexit( 'Invalid Token' );
            $credentials['provider'] = JRequest::getVar('provider', null, 'method', 'string');
            $credentials['username'] = JRequest::getVar('username', '', 'method', 'username');
            $credentials['userid'] = JRequest::getInt('userid', '0', 'method');
            $credentials['session'] = JRequest::getVar('session', '', 'method','string');
            $credentials['oauth_token'] = JRequest::getVar('oauth_token', '', 'method','string');
        }
        else {
            $credentials['provider'] = $epUserdata['epprovider'];
            $credentials['username'] = $epUserdata['epusername'];
            $options['userid'] = $epUserdata['userid'];
        }


        $credentials['provider'] = trim($credentials['provider']);
        $credentials['username'] = trim($credentials['username']);
        global $mainframe;

        $user =& JFactory::getUser();
        $registerNewAlias = (!$user->guest);

        if (($return = JRequest::getVar('return', '', 'method', 'base64'))) {
            $return = base64_decode($return);
            if (!JURI::isInternal($return)) {
                $return = '';
            }
        }


        $options['remember'] = JRequest::getBool('remember', false);
        $options['return'] = $return;

        $credentials['password'] = JRequest::getString('password', '', 'post', JREQUEST_ALLOWRAW);

        # agregado por G2P

        $options['providerid'] = JRequest::getInt('providerid', '0', 'method');
        $options['externalid'] = JRequest::getVar('externalid', '', 'method', 'string');
        $options['email'] = JRequest::getVar('email', '', 'method', 'string');
        $options['label'] = JRequest::getVar('label', '', 'method', 'string');

        ##### testing ##########
        $db = &JFactory::getDBO();

        //preform the login action
        $error = $mainframe->login($credentials, $options);

        $this->endAuthentication();

        if(!JError::isError($error)) {
            // Redirect if the return url is not registration or login
            if ( ! $return ) {
                $return	= 'index.php?option=com_user';
            }

            if ($credentials['userid'] == 0) {
                $this->aliasreg($options['providerid'],$options['externalid'],$options['label'],$options['email']);
            }
            else {
                try {
                    $session = & JFactory :: getSession();
                    $selectProvider = 'select p.id from #__providers p where p.name = "' . $credentials['provider'] . '"';
                    $db->setQuery($selectProvider);
                    $dbprovider = $db->loadObject();

                    $this->insertAlias(0, $credentials['userid'], $session->get('externalidentifier'), $dbprovider->id,$session->get('label'),$session->get('email'));

                }
                catch (Exception $ex) {
                    $message = JText::_("SYSTEM_ALIAS_REGISTERED");
                    UserHelper::showMessage(ERROR, $message);
                }
            }
            $zonal = UserHelper::getUsersZonal();

            require_once JPATH_ROOT . DS . 'components' . DS . 'com_zonales' . DS . 'helper.php';
            comZonalesHelper::setZonal($zonal);

            $i18nKey = ($registerNewAlias) ? 'SYSTEM_MESSAGE_SUCCESS_ALIAS_ADDED' : 'SYSTEM_MESSAGE_SUCCESS_LOGIN';
            $message = JText::_($i18nKey);
            UserHelper::showMessage(SUCCESS, $message);
        }
        else {
            $i18nKey = ($registerNewAlias) ? 'SYSTEM_MESSAGE_ERROR_ALIAS_NOT_ADDED' : 'SYSTEM_MESSAGE_ERROR_LOGIN';
            $message = JText::_($i18nKey);
            UserHelper::showMessage(ERROR, $message);
        }
    }

    private function endAuthentication() {
        $session = & JFactory :: getSession();
        $session->set('authenticationonprogress','false');
    }

    function logout() {
        global $mainframe;

        //preform the logout action
        $error = $mainframe->logout();

        if(!JError::isError($error)) {
            $message = JText::_('SYSTEM_LOGOUT_SUCCESS');
            UserHelper::showMessage(SUCCESS, $message);
        } else {
            parent::display();
        }
    }

    /**
     * Prepares the registration form
     * @return void
     */
    function register() {
        $usersConfig = &JComponentHelper::getParams( 'com_users' );
        if (!$usersConfig->get( 'allowUserRegistration' )) {
            JError::raiseError( 403, JText::_( 'Access Forbidden' ));
            return;
        }

        $user 	=& JFactory::getUser();

        if ( $user->get('guest')) {
            JRequest::setVar('view', 'register');


        } else {
            $this->setredirect('index.php?option=com_user&task=edit',JText::_('You are already registered.'));
        }

        parent::display();
    }

    private function getVariations($string) {
        $variations = array();

        $variations[] = $string;
        $variations[] = strtolower($string);
        $variations[] = ucwords(strtolower($string));
        $variations[] = strtoupper($string);

        return $variations;

    }

    private function getUserId($db,$user,$username = false) {
        //            $id_query = 'SELECT u.id FROM #__users u'.
        //                ' WHERE u.name = ' . $db->Quote($user->get('name')) .
        //                ' AND u.birthdate="' . $user->get('birthdate') . '"';

        $variations = $this->getVariations($user->get('name'));
        $end = implode('","', $variations);
        $end = '"' . $end . '"';

        $useUsername = ($username) ? ' AND u.username ="' . $user->get('username') . '"' : '';

        $id_query = 'SELECT u.id FROM #__users u, #__aapu_attribute_entity ae '.
                ' WHERE ae.value_date="' . $user->get('birthdate') . '"' .
                $useUsername . " AND ae.object_id=u.id AND ae.object_type='TABLE' AND ae.object_name='#__users'" .
                ' AND u.name IN (' . $end . ')';
        $db->setQuery($id_query);
        $dbuserid = $db->loadObject();

        return ($dbuserid) ? $dbuserid->id : null;
    }

    function getRUserId($db,$username) {
        $id_query = "SELECT u.id FROM #__users u WHERE u.username='$username'";
        $db->setQuery($id_query);
        $dbuserid = $db->loadObject();
        return $dbuserid->id;
    }

    private function userExists($db,$user) {
        return ($this->getUserId($db, $user) != null);
    }

    private function insertAlias($block,$userid,$alias,$providerid,$label,$email) {
        try {
            $db = &JFactory::getDBO();
            $passphrase = JUtility::getHash( JUserHelper::genRandomPassword());

            $insertAlias = 'insert into #__alias(user_id,name,provider_id,association_date,block,activation,label,email) ' .
                    'values (' . $userid . ',"' . $alias .
                    '",' . $providerid . ',"' . date('Y-m-d') . '",' .
                    $block . ',"' . $passphrase . '","'.$label. '","' . $email . '")';

            $db->setQuery($insertAlias);
            return $db->query();
        }

        catch (Exception $ex) {
            $message = JText::_("SYSTEM_ALIAS_REGISTERED");
            UserHelper::showMessage(ERROR, $message);
        }
    }

    /**
     * Save user registration and notify users and admins if required
     * @return void
     */
    function register_save() {
        global $mainframe;

        // Check for request forgeries
        JRequest::checkToken() or jexit(JText::_('SYSTEM_TOKEN_INVALID'));

        $providerid = JRequest::getInt('providerid', '0', 'method');
        $externalid = JRequest::getVar('externalid', '', 'method', 'string');
        $aliasemail = JRequest::getVar('email', '', 'method', 'string');
        $label = JRequest::getVar('label', '', 'method', 'string');
        $force = JRequest::getInt('force', '0', 'method');
        $zonalId = JRequest::getInt('zonal',UserHelper::ZONAL_NOT_DEFINED,'method');
        $email2 = JRequest::getString('email2','','method');
        $birthday = JRequest::getString('birthdate','','method');
        $sex = JRequest::getString('sex','M','method');
        $username = JRequest::getString('usernamer','','method');

        if ($zonalId == UserHelper::ZONAL_NOT_DEFINED) {
            $notZonalMessage = JText::_('SYSTEM_ZONAL_NOT_DEFINED');
            $baseUrl = "option=com_user&view=register&map=0";
            UserHelper::showMessage(ERROR, $notZonalMessage, $baseUrl);
            return;
        }

        $db = &JFactory::getDBO();

        // Get required system objects
        $user 		= clone(JFactory::getUser());
        $pathway 	=& $mainframe->getPathway();
        $config		=& JFactory::getConfig();
        $authorize	=& JFactory::getACL();
        $document   =& JFactory::getDocument();

        // If user registration is not allowed, show 403 not authorized.
        $usersConfig = &JComponentHelper::getParams( 'com_users' );
        if ($usersConfig->get('allowUserRegistration') == '0') {
            JError::raiseError( 403, JText::_( 'Access Forbidden' ));
            return;
        }

        // Initialize new usertype setting
        $newUsertype = $usersConfig->get( 'new_usertype' );
        if (!$newUsertype) {
            $newUsertype = 'Registered';
        }

        // Bind the post array to the user object
        if (!$user->bind( JRequest::get('post'), 'usertype' )) {
            JError::raiseError( 500, $user->getError());
        }

        $userClone = clone($user);
        $useractivation = (int) $usersConfig->get( 'useractivation' );
        //$block = ($useractivation == 1) ? '1' : '0';
        $block = $useractivation;

        $userid = $this->getUserId($db, $user);
        $userExists = $this->userExists($db, $user);
        $requestNewAlias = true;

        if ($userExists) {
            $message = JText::_('SYSTEM_MESSAGE_ERROR_USER_EXISTS');
            UserHelper::showMessage(ERROR, $message);
        }

        if (!$userExists || $force == 1) {
            $password = JRequest::getString('passwordt', '', 'post', JREQUEST_ALLOWRAW);
            $username = JRequest::getString('usernamer','','method');

            // if ($password == '' && $externalid != '' && $providerid != 0){
            if ($password == '') {
                $password = JUserHelper::genRandomPassword();
                $block = '0';
            }

            // Set some initial user values
            $user->set('id', 0);
            $user->set('usertype', '');
            $user->set('gid', $authorize->get_group_id( '', $newUsertype, 'ARO' ));

            $date =& JFactory::getDate();
            $user->set('registerDate', $date->toMySQL());
            $user->set('password',md5($password));
            $user->set('username',$username);


            // If user activation is turned on, we need to set the activation information

            if ($useractivation == '1') {
                jimport('joomla.user.helper');
                $user->set('activation', JUtility::getHash( JUserHelper::genRandomPassword()) );
                $user->set('block', $block);
            }
            // If there was an error with registration, set the message and display form
            if ( !$user->save() ) {
                JError::raiseWarning('', JText::_( $user->getError()));

                $message = JText::_('SYSTEM_MESSAGE_ERROR_REGISTER');

                UserHelper::showMessage(ERROR, $message);
                return false;
            }
            $userid = $this->getRUserId($db, $user->get('username'));
            $userExists = true;

            // registramos los datos extras en la tabla de com_aapu

            // pbtenemos el id del atributo sexo
            $atributesModel = new AapuModelAttributes();
            $atributesModel->setWhere("a.name='sex'");
            $sexData = $atributesModel->getData(true, true);
            $sexId = $sexData->id;

            // obtenemos el id del atributo birthday
            $atributesModel->setWhere("a.name='birthday'");
            $birthdayData = $atributesModel->getData(true, true);
            $birthdayId = $birthdayData->id;

            // obtenemos el id del atributo zonal
            $atributesModel->setWhere("a.name='zonal'");
            $zonalData = $atributesModel->getData(true, true);
            $zonaId = $zonalData->id;

            $atributesModel->setWhere("a.name='email2'");
            $emailData = $atributesModel->getData(true, true);
            $emailId = $emailData->id;

            $dataSex = array(
                    'value' => $sex,
                    'object_id' => $userid,
                    'object_type' => 'TABLE',
                    'object_name' => '#__users',
                    'attribute_id' => $sexId
            );

            $dataBirthday = array(
                    'value_date' => $birthday,
                    'object_id' => $userid,
                    'object_type' => 'TABLE',
                    'object_name' => '#__users',
                    'attribute_id' => $birthdayId
            );

            $dataZonal = array(
                    'value_int' => $zonalId,
                    'object_id' => $userid,
                    'object_type' => 'TABLE',
                    'object_name' => '#__users',
                    'attribute_id' => $zonaId
            );

            $dataEmail2 = array(
                    'value' => $email2,
                    'object_id' => $userid,
                    'object_type' => 'TABLE',
                    'object_name' => '#__users',
                    'attribute_id' => $emailId
            );

            $attributesEntityModel = new AapuModelAttribute_entity();
            // If there was an error with registration, set the message and display form
            if ( !$attributesEntityModel->store(false, false, $dataSex) ||
                    !$attributesEntityModel->store(false, false, $dataBirthday) ||
                    !$attributesEntityModel->store(false, false, $dataZonal) ||
                    !$attributesEntityModel->store(false, false, $dataEmail2)) {

                JError::raiseWarning('', JText::_( $user->getError()));

                $message = JText::_('SYSTEM_MESSAGE_ERROR_REGISTER');

                UserHelper::showMessage(ERROR, $message);
                return false;
            }

            // Send registration confirmation mail

            $password = preg_replace('/[\x00-\x1F\x7F]/', '', $password); //Disallow control chars in the email
            UserController::_sendMail($user, $password);

        }



        ######### agregado por G2P ##############

        ##### testing ##########

        if ($userExists && $externalid != '' && $providerid != 0) {
            // hay que agregar un alias

            // $userid = $this->getUserId($db, $user);

            try {
                $externalid = urldecode($externalid);

                $this->insertAlias($block, $userid, $externalid,$user->get('providerid'),$label,$aliasemail);

                $requestNewAlias = false;

                // Send registration confirmation mail
                $selectpass = 'select u.password from #__users u where id=' . $userid;
                $db->setQuery($selectpass);
                $dbpass = $db->loadObject();

                $password = preg_replace('/[\x00-\x1F\x7F]/', '', $dbpass->password); //Disallow control chars in the email
                $userClone->set('activation', $passphrase);
            }
            catch (Exception $ex) {
                $message = JText::_("SYSTEM_ALIAS_REGISTERED");
                UserHelper::showMessage(ERROR, $message);
            }
            //UserController::_sendMail($userClone, $password);
        }
        else {
            // tomo los valores especificos del proveedor externo
            $epProveedor = JRequest::getVar('selprovider', '', 'method', 'string');

            if ($epProveedor && $epProveedor != 'Zonales') {
                $epUserData = array();
                $epUserData['epusername'] = JRequest::getVar('epusername', '', 'method', 'string');
                $epUserData['epprovider'] = $epProveedor;
                $epUserData['userid'] = $userid;
                $this->login($epUserData);
            }
        }

        //$return = 'index.php' . ($requestNewAlias) ? '?option=com_alias&view=alias&userid=' . $userid .'&'. JUtility::getToken() . '=1' : '';
        ############## fin #####################





        // Everything went fine, set relevant message depending upon user activation state and display message
        if ( $useractivation == '1' ) {
            $message  = JText::_( 'REG_COMPLETE_ACTIVATE' );
        } else {
            $message = JText::_( 'REG_COMPLETE' );
        }

        $this->endAuthentication();
        // fin ------
        UserHelper::showMessage(SUCCESS, $message);
    }

    function activate() {
        global $mainframe;

        // Initialize some variables
        $db			=& JFactory::getDBO();
        $user 		=& JFactory::getUser();
        $document   =& JFactory::getDocument();
        $pathway 	=& $mainframe->getPathWay();

        $usersConfig = &JComponentHelper::getParams( 'com_users' );
        $userActivation			= $usersConfig->get('useractivation');
        $allowUserRegistration	= $usersConfig->get('allowUserRegistration');

        // Check to see if they're logged in, because they don't need activating!
        if ($user->get('id')) {
            // They're already logged in, so redirect them to the home page
            $mainframe->redirect( 'index.php' );
        }

        if ($allowUserRegistration == '0' || $userActivation == '0') {
            JError::raiseError( 403, JText::_( 'Access Forbidden' ));
            return;
        }

        // create the view
        require_once (JPATH_COMPONENT.DS.'views'.DS.'register'.DS.'view.html.php');
        $view = new UserViewRegister();

        $message = new stdClass();

        // Do we even have an activation string?
        $activation = JRequest::getVar('activation', '', '', 'alnum' );
        $activation = $db->getEscaped( $activation );

        if (empty( $activation )) {
            // Page Title
            $document->setTitle( JText::_( 'REG_ACTIVATE_NOT_FOUND_TITLE' ) );
            // Breadcrumb
            $pathway->addItem( JText::_( 'REG_ACTIVATE_NOT_FOUND_TITLE' ));

            $message->title = JText::_( 'REG_ACTIVATE_NOT_FOUND_TITLE' );
            $message->text = JText::_( 'REG_ACTIVATE_NOT_FOUND' );
            $view->assign('message', $message);
            $view->display('message');
            return;
        }

        // Lets activate this user
        jimport('joomla.user.helper');
        if (JUserHelper::activateUser($activation)) {
            // Page Title
            $document->setTitle( JText::_( 'REG_ACTIVATE_COMPLETE_TITLE' ) );
            // Breadcrumb
            $pathway->addItem( JText::_( 'REG_ACTIVATE_COMPLETE_TITLE' ));

            $message->title = JText::_( 'REG_ACTIVATE_COMPLETE_TITLE' );
            $message->text = JText::_( 'REG_ACTIVATE_COMPLETE' );
        }
        else {
            // Page Title
            $document->setTitle( JText::_( 'REG_ACTIVATE_NOT_FOUND_TITLE' ) );
            // Breadcrumb
            $pathway->addItem( JText::_( 'REG_ACTIVATE_NOT_FOUND_TITLE' ));

            $message->title = JText::_( 'REG_ACTIVATE_NOT_FOUND_TITLE' );
            $message->text = JText::_( 'REG_ACTIVATE_NOT_FOUND' );
        }

        $view->assign('message', $message);
        $view->display('message');
    }

    /**
     * Password Reset Request Method
     *
     * @access	public
     */
    function requestreset() {
        // Check for request forgeries
        JRequest::checkToken() or jexit( 'Invalid Token' );

        // Get the input
        $email		= JRequest::getVar('email', null, 'post', 'string');

        // Get the model
        $model = &$this->getModel('Reset');

        // Request a reset
        if ($model->requestReset($email) === false) {
            $message = JText::sprintf('PASSWORD_RESET_REQUEST_FAILED', $model->getError());
            $this->setRedirect('index.php?option=com_user&view=reset&map=0', $message);
            return false;
        }

        $this->setRedirect('index.php?option=com_user&view=reset&layout=confirm&map=0');
    }

    /**
     * Password Reset Confirmation Method
     *
     * @access	public
     */
    function confirmreset() {
        // Check for request forgeries
        JRequest::checkToken() or jexit( 'Invalid Token' );

        // Get the input
        $token = JRequest::getVar('token', null, 'post', 'alnum');

        // Get the model
        $model = &$this->getModel('Reset');

        // Verify the token
        if ($model->confirmReset($token) === false) {
            $message = JText::sprintf('PASSWORD_RESET_CONFIRMATION_FAILED', $model->getError());
            $this->setRedirect('index.php?option=com_user&view=reset&layout=confirm&map=0', $message);
            return false;
        }

        $this->setRedirect('index.php?option=com_user&view=reset&layout=complete&map=0');
    }

    /**
     * Password Reset Completion Method
     *
     * @access	public
     */
    function completereset() {
        // Check for request forgeries
        JRequest::checkToken() or jexit( 'Invalid Token' );

        // Get the input
        $password1 = JRequest::getVar('password1', null, 'post', 'string', JREQUEST_ALLOWRAW);
        $password2 = JRequest::getVar('password2', null, 'post', 'string', JREQUEST_ALLOWRAW);

        // Get the model
        $model = &$this->getModel('Reset');

        // Reset the password
        if ($model->completeReset($password1, $password2) === false) {
            $message = JText::sprintf('PASSWORD_RESET_FAILED', $model->getError());
            $this->setRedirect('index.php?option=com_user&view=reset&layout=complete&map=0', $message);
            return false;
        }

        $message = JText::_('PASSWORD_RESET_SUCCESS');
        $this->setRedirect('index.php?option=com_user&view=zlogin&map=0', $message);
    }

    /**
     * Username Reminder Method
     *
     * @access	public
     */
    function remindusername() {
        // Check for request forgeries
        JRequest::checkToken() or jexit( 'Invalid Token' );

        // Get the input
        $email = JRequest::getVar('email', null, 'post', 'string');

        // Get the model
        $model = &$this->getModel('Remind');

        // Send the reminder
        if ($model->remindUsername($email) === false) {
            $message = JText::sprintf('USERNAME_REMINDER_FAILED', $model->getError());
            $this->setRedirect('index.php?option=com_user&view=remind&map=0', $message);
            return false;
        }

        $message = JText::sprintf('USERNAME_REMINDER_SUCCESS', $email);
        $this->setRedirect('index.php?option=com_user&view=zlogin&map=0', $message);
    }

    function _sendMail(&$user, $password) {
        global $mainframe;

        $db		=& JFactory::getDBO();

        $name 		= $user->get('name');
        $email 		= $user->get('email');
        $username 	= $user->get('username');

        $usersConfig 	= &JComponentHelper::getParams( 'com_users' );
        $sitename 		= $mainframe->getCfg( 'sitename' );
        $useractivation = $usersConfig->get( 'useractivation' );
        $mailfrom 		= $mainframe->getCfg( 'mailfrom' );
        $fromname 		= $mainframe->getCfg( 'fromname' );
        $siteURL		= JURI::base();

        $subject 	= sprintf ( JText::_( 'Account details for' ), $name, $sitename);
        $subject 	= html_entity_decode($subject, ENT_QUOTES);

        if ( $useractivation == 1 ) {
            $message = sprintf ( JText::_( 'SEND_MSG_ACTIVATE' ), $name, $sitename, $siteURL."index.php?option=com_user&task=activate&map=0&activation=".$user->get('activation'), $siteURL, $username, $password);
        } else {
            $message = sprintf ( JText::_( 'SEND_MSG' ), $name, $sitename, $siteURL,$username, $password);
        }

        $message = html_entity_decode($message, ENT_QUOTES);

        //get all super administrator
        $query = 'SELECT name, email, sendEmail' .
                ' FROM #__users' .
                ' WHERE LOWER( usertype ) = "super administrator"';
        $db->setQuery( $query );
        $rows = $db->loadObjectList();

        // Send email to user
        if ( ! $mailfrom  || ! $fromname ) {
            $fromname = $rows[0]->name;
            $mailfrom = $rows[0]->email;
        }

        JUtility::sendMail($mailfrom, $fromname, $email, $subject, $message);

        // Send notification to all administrators
        $subject2 = sprintf ( JText::_( 'Account details for' ), $name, $sitename);
        $subject2 = html_entity_decode($subject2, ENT_QUOTES);

        // get superadministrators id
        foreach ( $rows as $row ) {
            if ($row->sendEmail) {
                $message2 = sprintf ( JText::_( 'SEND_MSG_ADMIN' ), $row->name, $sitename, $name, $email, $username);
                $message2 = html_entity_decode($message2, ENT_QUOTES);
                JUtility::sendMail($mailfrom, $fromname, $row->email, $subject2, $message2);
            }
        }
    }

    // registration validations
    function checkEmail() {
        $email = urldecode(JRequest::getString('email',NULL,'method'));

        // validate email format
        if (!UserHelper::isEmailAddressWellformed($email)) {
            echo JText::_("SYSTEM_EMAIL_INVALID_FORMAT");
            return ;
        }

        // verify that email is not used
        if (UserHelper::emailExists($email)) {
            echo JText::_("SYSTEM_EMAIL_ALREADY_USED");
            return ;
        }
    }

    function checkBirthDate() {
        $birthdate = urldecode(JRequest::getString('birthdate',NULL,'method'));

        // validate date format
        if (!UserHelper::checkDateFormat($birthdate)) {
            echo JText::_("SYSTEM_DATE_INVALID");
            return ;
        }

        // verify user age
        $usersConfig 	= &JComponentHelper::getParams( 'com_users' );
        //$minAge = $usersConfig->get( 'minage' );
        $minAge = 18;
        $today = strtotime(date("Y-m-d"));
        $birthdateUnix = strtotime($birthdate);
        $yearsDiff = (int) (($today - $birthdateUnix)/(365 * 24 * 60 * 60));

        if ($yearsDiff < $minAge) {
            echo sprintf(JText::_("SYSTEM_DATE_BIRTH_VERY_YOUNG"),$minAge);
            return ;
        }
    }

    function checkUserExistence(){
        $birthdate = urldecode(JRequest::getString('birthdate',NULL,'method'));
        $fullname = urldecode(JRequest::getString('fullname',NULL,'method'));

        $user = new JUser();
        $user->set("birthdate", $birthdate);
        $user->set("name", $fullname);

        $db = JFactory::getDBO();
        $id = $this->getUserId($db, $user);

        if ($id){
            echo JText::_("SYSTEM_USER_EXISTS");
            return ;
        }
    }
}
?>

