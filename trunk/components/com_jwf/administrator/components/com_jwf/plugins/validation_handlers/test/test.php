<?php
/**
 * Mail Hook handler plugin : Sends E-mail notifications to user/admins when and item moves through the workflow
 *
 * @version		$Id: mail.php 1439 2009-08-16 12:41:13Z mostafa.muhammad $
 * @package		Joomla
 * @subpackage	JWF.plugins
 * @author	Mostafa Muhammad <mostafa.mohmmed@gmail.com>
 * @copyright	Copyright (C) 2009 Mostafa Muhammad. All rights reserved.
 * @license		GNU/GPL
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

class JWFValidationHandler_test  extends JWFValidationHandler {

    function onDeparted($workflow, $currentStation, $currentStep) {
        // recupera tags del articulo
        $db =& JFactory::getDBO();
        $query = 'SELECT id' .
                ' FROM #__custom_properties' .
                ' WHERE content_id = ' . (int) $currentStep->iid.
                ' AND ref_table = "content"';
        $db->setQuery( $query );
        if(count($db->loadObjectList()) == 0)
            return "Debe taguear el articulo!!!";
        else
            return "";
    }
}