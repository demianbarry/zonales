<?php
/**
* Global Constant definitions and utility functions
*
* @version		$Id: globals.php 1440 2009-08-16 14:06:09Z mostafa.muhammad $
* @package		Joomla
* @subpackage	JWF.core
* @copyright	Copyright (C) 2009 Mostafa Muhammad. All rights reserved.
* @license		GNU/GPL
*/

// no direct access
defined('_JEXEC') or die('Restricted access');


/**
 * Shortend access to JWF backend path
*/
define('JWF_BACKEND_PATH' ,  JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_jwf');

/**
 * Shortend access to JWF frontend path
*/
define('JWF_FRONTEND_PATH',  JPATH_ROOT.DS.'components'.DS.'com_jwf');

/**
 * Path to JWF writable directiory
*/
define('JWF_FS_PATH'      ,  JWF_BACKEND_PATH.DS.'files');

/**
 * Debug flag
*/
define('JWF_DEBUG_STATE',1);


//Create jwf file system directory
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

//Debug
if( JWF_DEBUG_STATE > 0 ){
	ini_set('display_errors',1);
	error_reporting(E_ALL);
}

/**
 * Utility function that searches an array of objects for a particular key with a particular value "e.g search for the object that has its 'name' property set to 'Ali'"
 * 
 * @access	public
 * @param array $array The array of objects to search through
 * @param string $key The key whose value to be looked for
 * @param mixed $value The value to look for
 * @param bool $return True=Return the object | False=Return the key that had the target value
 * @return mixed based on the value of $return , returns null if the value is not found
*/
function searchObjectArray( $array, $key, $value, $return=true ){
	 foreach($array as $address => $object){
		if( $object->$key == $value )if($return)return $object;else return $address;
	}
	return null;
}

if(!function_exists('nextInsertId')){
	/**
	 * Utility function to retrieve the next insert id for a given table
	 * 
	 * @access	public
	 * @param string $tableName table name
	 * @return int next insert id 
	 */
	function nextInsertId( $tableName ){
		$db =& JFactory::getDBO();$db->setQuery('SHOW CREATE TABLE `'.$tableName.'`');$result = $db->loadRow();
		$nextAutoIndex = 1;$matches = array();
		preg_match('/AUTO_INCREMENT=(\d+)/', $result[1], $matches );
		if(count( $matches ))$nextAutoIndex = intval( $matches[1] );
		return $nextAutoIndex;
		
	}
}
if(!function_exists('lastInsertId')){
	/**
	 * Utility function to retrieve the last insert id
	 * 
	 * @access	public
	 * @return int last insert id 
	 */function lastInsertId(){
		$db =& JFactory::getDBO();
		$db->setQuery( "SELECT LAST_INSERT_ID()" );
		return intval( $db->loadResult() );
	}
}

	
if(!function_exists('getPluginManager')){
	/**
	 * Utility function for quick access of the global variable $PluginManager
	 *
	 * @access	public
	 * @see JWFPluginManager
	 * @return object reference Reference to the global PluginManager Object
	 */
	function &getPluginManager(){return $GLOBALS['JWFGlobals']['PluginManager'];}
}

if(!function_exists('canManageWorkflows')){
	/**
	 * Central authorization function, determines whether or not a user has global premission to manage workflows, Under J! 1.6 will always return true, this behaviour will change as soon as ACL implementations in 1.6 is mature, Under 1.5 it will return true if the user is a super administrator
	 * 
	 * @access	public
	 * @return bool true if allowed, false if not, see function description for more information 
	 */
	 function canManageWorkflows(){
		$user = & JFactory::getUser();
		list($major,$minor,$version) = explode('.',JVERSION);
		//1.6
		if( $major == 1 && $minor == 6 ){return true;}
	
		//1.5
		if( $major == 1 && $minor == 5 ){
			if( $user->gid == 25 )return true;
		}
		return false;
	}
}