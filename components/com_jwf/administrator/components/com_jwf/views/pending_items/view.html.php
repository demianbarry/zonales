<?php
/**
* List View for JWF Component
*
* @version		$Id: view.html.php 1480 2009-08-24 15:11:16Z mostafa.muhammad $
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
class JWFViewPending_items extends JView
{
	/**
	 * List view display method
	 *
	 * Displays a list of all pending tasks for the current user
	 *
	 * @return void
	 **/
	function display($items, $aclPairs)
	{
		if(defined('JWF_FRONTEND_RUNNING')){
			JHTML::_('stylesheet', 'pending_items-frontend.css', 'media/com_jwf/css/');
		} else {
			JHTML::_('stylesheet', 'pending_items-backend.css' , 'media/com_jwf/css/');
			JToolBarHelper::title(   JText::_( 'JWF' ), 'jwf-logo' );	
		}
		//Send data to the view
		$this->assignRef('items'     , $items['items']	);
		$this->assignRef('aclPairs'  , $aclPairs		);
		
		//Display the template
		parent::display();
	}
}
