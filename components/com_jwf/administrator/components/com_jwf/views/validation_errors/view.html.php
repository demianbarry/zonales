<?php
/**
* Pending Item View for JWF Component
*
* @version		$Id: view.html.php 1407 2009-08-13 12:42:36Z mostafa.muhammad $
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
class JWFViewValidation_errors extends JView
{
	/**
	 * List view display method
	 *
	 * Displays a list of all workflows available in the database
	 *
	 * @return void
	 **/
	function display($errorsMsg)
	{
                //Send data to the view
		$this->assignRef('msg'   , $errorsMsg );
                
		
		//Display the template
		parent::display(null);
	}
}
