<?php
/**
* Base class for component handler plugins
*
* These plugins handle different content types (e.g Article/Images/Forms/etc..), To allow JWF to handle different types of content without modifying the core
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
 * Base class, All component handlers should inherit this class
 *
 * @package		Joomla
 * @subpackage	JWF.plugins
 */
class JWFComponentHandler extends JObject{

	/**
	 * Returns an associative array with all categories in a given content type (e.g. Articles)
	 *	
	 * @access	public
	 * @return	array An associative array with all categories and their ids
 	 */
	function getCategories(){return array();}

	/**
	 * Returns a link to the "Edit page" for the given content item
	 *
	 * @access	public
	 * @param	int The ID of the content item to edit
	 * @return	string The url for the Edit page
 	 */
	function getEditLink( $id ){return JRoute::_("index.php");}
	
	/**
	 * Returns the revision number of the latest version of a given content item
	 *
	 * @access	public
	 * @param	int $id The ID of the content item
	 * @return	int the revision number of the latest stored revision
 	 */
	function getLatestRevisionId( $id ){return 0;}
	
	/**
	 * Returns the actual content for a given content item , use version parameter to specify a particular revision 
	 *
	 * @access	public
	 * @param	int $id The ID of the content item
	 * @param	int $version The revision number to be retrieved
	 * @return	string HTML output representing the requested content
 	 */
	function getItemRevision( $id, $version ){return "<p>dummy</p>";}
	
	/**
	 * locks a content item for all user 
	 *
	 * @access	public
	 * @param	int $id The ID of the content item
	 * @param	string $aclSystemId the ID of the ACL System to be used for locking
	 * @return	bool True on success , False on Failure
 	 */
	function lock( $id, $aclSystemId ){return true;}
	
	/**
	 * unlocks a content item for a specified ACL group 
	 *
	 * @access	public
	 * @param	int $id The ID of the content item
	 * @param	string $aclSystemId the ID of the ACL System to be used for unlocking
	 * @param	int $gid the ID of the ACL group to be allowed to access the content item
	 * @return	bool True on success , False on Failure
 	 */
	function unlock( $id, $aclSystemId, $gid ){return true;}
	
	/**
	 * Called when a new entry (article/image/etc) has been added to the system , should add a record to the #__jwf_steps table 
	 * This method is called by the core plugin when a particular system event has occured for instace "onAfterContentSave" for "content" elements
	 * 
	 *
	 * @access	public
	 * @param	object $workflow The workflow that the new item belongs to
	 * @param	array $args the arguments that were sent by the event (e.g. onAfterContentSave)
	 * @return	bool True on success , False on Failure
 	 */
	function onNewEntry($workflow, $args){return true;}
	
	/**
	 * Renders a history entry in the history view
	 * Since history entries are created by individual plugins, These plugins should also define how to render the data they stored in the DB
	 *
	 * @access	public
	 * @param	object $workflow The workflow that the new item belongs to
	 * @param	object $entry The data of the history entry , it was saved earlier to the DB by the plugin
	 * @return	string HTML output of the history entry
 	 */
	function renderHistoryEntry( $workflow, $entry ){return "<p>history</p>";}

	/**
	 * Deletes records in the table "#__jwf_steps" that refer to a content element (e.g. Article) that no longer exists
	 * This is called by the cron job engine every 15 minutes, This interval can be changed by modifying /path/to/joomla/administrator/components/com_jwf/files/cronjob/crontab.txt
	 *
	 * @access	public
	 * @return	bool True on success ,false on failure
 	 */
	function deleteOrphans(){return true;}
	
}