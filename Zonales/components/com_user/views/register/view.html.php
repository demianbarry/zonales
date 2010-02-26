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

/**
 * HTML View class for the Registration component
 *
 * @package		Joomla
 * @subpackage	Registration
 * @since 1.0
 */
class UserViewRegister extends JView {
    function display($tpl = null) {
        global $mainframe;

        // Check if registration is allowed
        $usersConfig = &JComponentHelper::getParams( 'com_users' );
        if (!$usersConfig->get( 'allowUserRegistration' )) {
            JError::raiseError( 403, JText::_( 'Access Forbidden' ));
            return;
        }

        $pathway  =& $mainframe->getPathway();
        $document =& JFactory::getDocument();
        $params	= &$mainframe->getParams();

        // Page Title
        $menus	= &JSite::getMenu();
        $menu	= $menus->getActive();

        // because the application sets a default page title, we need to get it
        // right from the menu item itself
        if (is_object( $menu )) {
            $menu_params = new JParameter( $menu->params );
            if (!$menu_params->get( 'page_title')) {
                $params->set('page_title',	JText::_( 'Registration' ));
            }
        } else {
            $params->set('page_title',	JText::_( 'Registration' ));
        }
        $document->setTitle( $params->get( 'page_title' ) );

        $pathway->addItem( JText::_( 'New' ));

        // Load the form validation behavior
        JHTML::_('behavior.formvalidation');

        $user =& JFactory::getUser();

        ########### agregado por G2P ##############
        $db =& JFactory::getDBO();
        $selectProviders = 'select p.name, p.icon_url, g.name as groupname, p.required_input, t.name as type ' .
            'from #__providers p, #__groups g, #__protocol_types t ' .
            'where p.access=g.id and t.id=p.protocol_type_id ' .
            'order by (select count(*) from #__alias a where p.id=a.provider_id) desc, t.name  asc';

        $db->setQuery($selectProviders);
        $providerslist = $db->loadObjectList();

        // parsea e interpreta la entrada requerida por cada proveedor
        // se lograra crear los elementos html necesarios de entrada
        // (algo asi como armar la pantalla al vuelo ;)
        $inputData = array();
        foreach ($providerslist as $provider) {
            $req_inputs = explode(';', $provider->required_input);
            $inputData[$provider->name] = array();
            foreach ($req_inputs as $input) {
                $data = explode(':', $input);
                $inputData[$provider->name][] =
                    array (
                    'type' => $data[0],
                    'name' => $data[1],
                    'message' => $data[2]
                );
            }
        }

        $providerid = JRequest::getInt('providerid', '0', 'method');
        $externalid = JRequest::getVar('externalid', '', 'method', 'string');
        $force = JRequest::getInt('force', '0', 'method');

        // enviar parametros a la plantilla
        $this->assignRef('providerslist',$providerslist);
        $this->assignRef('user', $user);
        $this->assignRef('params',$params);
        $this->assign('providerid',$providerid);
        $this->assign('externalid',$externalid);
        $this->assign('force',$force);
        $this->assignRef('inputData',$inputData);
        parent::display($tpl);
    }
}
