<?php
/**
 * @version		$Id: view.html.php 11673 2009-03-08 20:41:00Z willebil $
 * @package		Joomla
 * @subpackage	Registration
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');
require_once JPATH_ROOT . DS . 'components' . DS . 'com_zonales' . DS . 'helper.php';
require_once JPATH_ROOT . DS . 'components' . DS . 'com_user' . DS . 'helper.php';

/**
 * HTML View class for the Registration component
 *
 * @package		Joomla
 * @subpackage	Registration
 * @since 1.0
 */

        global $mainframe;

        // Check if registration is allowed
        $usersConfig = &JComponentHelper::getParams( 'com_users' );
        if (!$usersConfig->get( 'allowUserRegistration' )) {
            JError::raiseError( 403, JText::_( 'Access Forbidden' ));
            return;
        }

        $pathway  =& $mainframe->getPathway();
        $document =& JFactory::getDocument();
//        $modparams	= new JParameter();
//
//        // Page Title
//        $menus	= &JSite::getMenu();
//        $menu	= $menus->getActive();
//
//        // because the application sets a default page title, we need to get it
//        // right from the menu item itself
//        if (is_object( $menu )) {
//            $menu_params = new JParameter( $menu->params );
//            if (!$menu_params->get( 'page_title')) {
//                $params->set('page_title',	JText::_( 'Registration' ));
//            }
//        } else {
//            $params->set('page_title',	JText::_( 'Registration' ));
//        }
//        $document->setTitle( $params->get( 'page_title' ) );

        $pathway->addItem( JText::_( 'New' ));

        // Load the form validation behavior
        JHTML::_('behavior.formvalidation');

        $user =& JFactory::getUser();

        ########### agregado por G2P ##############
        $db =& JFactory::getDBO();
        $selectProviders = 'select p.name, p.icon_url, g.name as groupname, p.required_input, t.name as type ' .
            'from #__providers p, #__groups g, #__protocol_types t ' .
            'where p.access=g.id and p.enabled=1 and t.id=p.protocol_type_id ' .
            'order by (select count(*) from #__alias a where p.id=a.provider_id) desc, t.name  asc';

        $db->setQuery($selectProviders);
        $providerslist = $db->loadObjectList();

        // parsea e interpreta la entrada requerida por cada proveedor
        // se lograra crear los elementos html necesarios de entrada
        // (algo asi como armar la pantalla al vuelo ;)
        $inputData = array();
        foreach ($providerslist as $provider) {
            $req_inputs = explode('/', $provider->required_input);
            $inputData[$provider->name] = array();
            foreach ($req_inputs as $input) {
                $data = explode(':', $input);
                $inputData[$provider->name][] =
                    array (
                    'type' => $data[0],
                    'name' => $data[1],
                    'message' => $data[2],
                    'callback' => $data[3]
                );
            }
        }

        // averiguamos el zonal actual
        $helper = new comZonalesHelper();
        $zActual = $helper->getZonalActual();
//        $zName = $helper->getZonal($zActual);
//        $zonalCurrentMessage = ($zActual == NULL) ? JText::_('SYSTEM_ZONAL_CHOOSE') : $zName->label;
//        $zonalesParams = &JFactory::getApplication('site')->getParams('com_zonales');
//        $width = $zonalesParams->get('width_mapa_flash', '');
//        $height = $zonalesParams->get('height_mapa_flash', '');
        $zonalUserMessage = JText::_('SYSTEM_ZONAL_CURRENT_MESSAGE');
        $chooseZonalMessage = JText::_('SYSTEM_ZONAL_CHOOSE');
        $chooseZonal = UserHelper::ZONAL_NOT_DEFINED;

        $zonas = $helper->getValuesZonales();
        
        $zonal = $helper->getZonal();
        $selectedOption = 0;
        $localidades = array();
        $selectedParent = 0;

        if ($zonal != null) {
            if($zonal || $zonal->id != 0) {
                $selectedOption = $zonal->id;
            }
            $selectedParent = $zonal->parent_id;
        }

        // parametros
        $root = $helper->getRoot();

        // crea select de zonales disponibles
        $parents = $helper->getItems($root);
        $lists['provincias_select'] = JHTML::_('select.genericlist', $parents, 'reg_provincias',
                'size="1" class="reg_provincias_select required"', 'id', 'label', $selectedParent);


        $showColapsed = $params->get('show_colapsed');

        $providerid = JRequest::getInt('providerid', '0', 'method');
        $externalid = JRequest::getVar('externalid', '', 'method', 'string');
        $email = JRequest::getVar('email', '', 'method', 'string');
        $label = JRequest::getVar('label', '', 'method', 'string');
        $force = JRequest::getInt('force', '0', 'method');

        $nameMessage = JText::_( 'Name' );
        $usernameMessage = JText::_( 'User name' );
        $emailMessage = JText::_( 'Email' );
        $backupEmailMessage = JText::_( 'ZONALES_PROFILE_BACKUP_EMAIL' );
        $birthdateMessage = JText::_( 'ZONALES_PROFILE_BIRTHDATE' );
        $sexMessage = JText::_( 'ZONALES_PROFILE_SEX' );
        $femaleSexMessage = JText::_( 'ZONALES_PROFILE_SEX_FEMALE' );
        $maleSexMessage = JText::_( 'ZONALES_PROFILE_SEX_MALE' );
        $chooseProviderMessage = JText::_( 'ZONALES_PROVIDER_CHOOSE' );
        $passwordMessage = JText::_( 'Password' );
        $verifyPasswordMessage = JText::_( 'Verify Password' );
        $registerRequiredMessage = JText::_( 'REGISTER_REQUIRED' );
        $confirmRegisterMessage = JText::_('Register');
        $fixPart = JText::_('ZONALES_PROVIDER_CONNECT');

        require(JModuleHelper::getLayoutPath('mod_userregister'));


