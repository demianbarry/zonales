<?php
/**
 * @version	$Id$
 * @package	Zonales
 * @copyright	Copyright (C) 2009 Mediabit. All rights reserved.
 * @license	GNU/GPL, see LICENSE.php
 *
 * Zonales is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );
jimport('joomla.application.component.controller');

//require_once 'test/TestMotorEqSuite.php';
require_once 'hlapi.php';
jimport('joomla.user.user');

/**
 * Controlador
 *
 * @package Zonales
 * @since 1.5
 */
class EqZonalesController extends JController {
    /** @var class */
//	var $_zonalesHelper = null;

    function __construct($default = array()) {
        parent::__construct($default);
//		$this->_zonalesHelper = new comZonalesHelper();
    }

    function test() {
        global $option;

        /*$document = &JFactory::getDocument();
		$vType = $document->getType();
		$vName = JRequest::getCmd('view','test');
		$vLayout = JRequest::getCmd('layout','default');

		$view =& $this->getView($vName, $vType);
		$view->setLayout($vLayout);
		$view->display();*/
    }
        function testHLApi() {
            $userid = JRequest::getInt('user',1,'get');
//            $tags = HighLevelApi::getTags($userid,true);
//
//            foreach ($tags as $tag) {
//                echo $tag->id;
//                echo '-';
//                echo $tag->name;
//                echo '-';
//                echo $tag->label;
//                echo '-';
//                echo $tag->relevance;
//                echo '<br/>';
//            }
            $value = JRequest::getString('value','','get');
            $ancestors = HighLevelApi::getAncestors($userid, $value);
            print_r($ancestors);
        }
    //}

}
