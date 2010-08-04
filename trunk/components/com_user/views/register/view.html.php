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
class UserViewRegister extends JView {
    function display($tpl = null) {
        if($tpl != 'message') {
            $module = JModuleHelper::getModule('mod_userregister');
            $html = JModuleHelper::renderModule($module);
            $this->assignRef('moduleregister',$html);
        }
        parent::display($tpl);
    }
}
