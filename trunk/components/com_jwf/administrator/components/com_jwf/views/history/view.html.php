<?php
/**
* Pending Item View for JWF Component
*
* @version		$Id: view.html.php 1439 2009-08-16 12:41:13Z mostafa.muhammad $
* @package		Joomla
* @subpackage	JWF
* @copyright	Copyright (C) 2009 Mostafa Muhammad. All rights reserved.
* @license		GNU/GPL
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );

/**
 * List View
 *
 * @package    Joomla
 * @subpackage JWF
 */
class JWFViewHistory extends JView
{
	/**
	 * List view display method
	 *
	 * Displays a list of all workflows available in the database
	 *
	 * @return void
	 **/
	function display($itemHistory, $workflow)
	{
		
	
		
		$pManager =& getPluginManager();
		$pManager->loadPlugins('component');
		$response = $pManager->invokeMethod( 'component', 'getItemRevision', array($workflow->component) ,array($itemHistory[0]->iid,'head') );
		$itemInformation = $response[$workflow->component];
	
		$title = JText::_('Item') .' ['.$itemInformation->title.'] '. JText::_('History');
			
		if(defined('JWF_FRONTEND_RUNNING')){
			JHTML::_('JWF.title' , $title, 'jwf-logo');
			JHTML::_('JWF.backButton');
			JHTML::_('stylesheet', 'history-frontend.css', 'media/com_jwf/css/');
		} else { 
			JHTML::_('stylesheet', 'history-backend.css', 'media/com_jwf/css/');
			JRequest::setVar('hidemainmenu', 1);
			JToolBarHelper::title( $title, 'jwf-logo');
			JToolBarHelper::back();
		}
		
		//Send data to the view
		$this->assignRef('history'     , $itemHistory );
		$this->assignRef('currentItem' , $itemInformation );
		$this->assignRef('workflow'    , $workflow );
		
		//Display the template
		parent::display(null);
	}
}
