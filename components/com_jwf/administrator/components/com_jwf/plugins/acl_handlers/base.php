<?php
/**
* Base class for ACL handler plugins
*
* These plugins interface with a given ACL manager (Joomla's Core ACL or 3rd Party's)
*
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
 * Base class, All ACL handlers should inherit this class
 *
 * @package		Joomla
 * @subpackage	JWF.plugins
 */
class JWFACLHandler extends JObject{
	 
	 /**
	 * Returns the current user Group Id using a given ACL system
	 * For instance , a handler that interfaces with Joomla's Core ACL would return $user->gid
	 *
	 * @access	public
	 * @return	array GIDs of all groups that the logged in user is member of
 	 */
	function getMyGroupId(){return array();}
	
	 /**
	 * Returns a list of users that are members of a given ACL Group Id 
	 * @access	public
	 * @param	int $gid GID of the group whose users are to be returned
	 * @return	array containing the requested user objects ( User objects are like those of Joomla's Core)
 	 */
	function getUsers( $gid ){return array();}
	
	 /**
	 * Returns a list of ACL Group in a given ACL system
	 * @access	public
	 * @return	array containing ACL group names indexed by Group Ids
 	 */
	function getACL(){return array();}
}
