<?php
/**
* Base class for Hook handler plugins
*
* These plugins are more or less , event listeners , currently only one event is supported which is
* onArrival
*
* @version		$Id: base.php 1439 2009-08-16 12:41:13Z mostafa.muhammad $
* @package		Joomla
* @subpackage	JWF.plugins
* @author	Mostafa Muhammad <mostafa.mohmmed@gmail.com>
* @copyright	Copyright (C) 2009 Mostafa Muhammad. All rights reserved.
* @license		GNU/GPL
*/


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();


/**
 * Base class, All hook handlers should inherit this class
 *
 * @package		Joomla
 * @subpackage	JWF.plugins
 */
class JWFHookHandler extends JObject{
	 
	 /**
	 * Called when an item arrives at a given station
	 * @access	public
	 * @param	object $hookParameters paremeters supplied by the user for this hook ( e.g. E-mail body for a mail hook handler ,etc)
	 * @param	object $workflow the workflow to which the item belongs
	 * @param	object $currentStation information about the Current Station (The station to which the item has arrived)
	 * @param	object $currentStep information about the current step ( The item id and title etc)
	 * @return	void
 	 */
	function onArrival( $hookParameters, $workflow, $currentStation, $currentStep){return;}
}