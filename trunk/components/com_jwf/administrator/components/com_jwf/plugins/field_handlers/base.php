<?php
/**
* Base class for field handler plugins
*
* These plugins handle allow fields to be attached to items in the workflow
* for instance "comments"
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
 * Base class, All field handlers should inherit this class
 *
 * @package		Joomla
 * @subpackage	JWF.plugins
 */
class JWFFieldHandler extends JObject{

	/**
	 * Renders a history entry in the history view
	 * Since history entries are created by individual plugins, These plugins should also define how to render the data they stored in the DB
	 *
	 * @access	public
	 * @param	object $workflow The workflow that the new item belongs to
	 * @param	object $entry The data of the history entry , it was saved earlier to the DB by the plugin
	 * @return	string HTML output of the history entry
 	 */
	function renderHistoryEntry( $workflow, $entry ){return '<p>history</p>';}
	
	
	/**
	 * Called by the core when it wants to render a field "e.g. Comments field"
	 *
	 * @access	public
	 * @param	object $steps The workflow that the new item belongs to
	 * @param	object $workflow The data of the history entry , it was saved earlier to the DB by the plugin
	 * @param	object $data The data of the history entry , it was saved earlier to the DB by the plugin
	 * @return	string HTML output of the history entry
 	*/
	function display( $steps, $workflow, $data ){return '<p>dummy</p>';}
	
	/**
	 * Arbitrary methods, defined by the field developer
	 *
	 * The developer can set up as much methods he desires and call them via the field controller
	 * using URL like "option=com_jwf & controller=field & task=invoke & workflowID=5 & stationID=29 & itemID=16 & stepID=1 & method=delete & fieldType=comments & data[commentID]=8 "
	 *
	 * @access	public
	 * @param	object $workflow The workflow that the item belongs to
	 * @param	array $steps All the steps that this item has taken in the workflow (e.g. from station 1 to station 2 to station 1 again ,etc...)
	 * @param	array $storedData All field data for a given type that are stroed for this workflow (e.g. All comments stored for article id=5)
	 * @param	object $incomingData additional data that were sent using query url ( data[] variable )
	 * @return	string returns whatever is necessary , should match what the Javascript client is expecting	 
 	*/
	function doSomething( $workflow, $steps, $storedData, $incomingData ){return true;}
}