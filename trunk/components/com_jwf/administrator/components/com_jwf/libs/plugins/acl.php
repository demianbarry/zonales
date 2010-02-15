<?php
/**
* ACL PluginManager class
*
* @version		$Id: acl.php 1439 2009-08-16 12:41:13Z mostafa.muhammad $
* @package		Joomla
* @subpackage	JWF.core
* @author	Mostafa Muhammad <mostafa.mohmmed@gmail.com>
* @copyright	Copyright (C) 2009 Mostafa Muhammad. All rights reserved.
* @license		GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');


class JWFACLPluginManager{

	/**
	 * @var array $loadedPlugins Stores loaded plugins parameters and settings
	 */
	 var $loadedPlugins = null;

	
	/**
	 * Returns a reference to loaded plugin parameters : Used by PluginManager
	 * 
	 * @access	public
	 * @return array a reference to array containing loaded plugin settings
	 */
	function &getSettings(){return $this->loadedPlugins;}

	
	/**
	 * Invokes a method and returns the plugin response
	 * 
	 * @access	public
	 * @param string $name Name of the method
	 * @param array $which array containing names of plugins to load , for instance if you're loading component plugin you can pass array('content') as a parameter, can be set to null if you want to invoke this method on all plugins under this particular type (e.g. "all component plugins")
	 * @param array $params array containing the parameters that are to be sent to the method
	 * @return array an Array containing responses from plugins indexed by plugin name , <br />Array ('PLUGIN NAME' => 'PLUGIN RESPOSNE','PLUGIN NAME' => 'PLUGIN RESPOSNE')
	 */
	function invokeMethod( $name, $which, $params  ){
	

		$response = array();
		foreach( $this->loadedPlugins as $p ){

			if( !is_null( $which ) && !in_array( $p->id, $which ) )continue;

			require_once $p->php;
			
			$className = 'JWFACLHandler_'.$p->id;
			//PHP 4 fix
			$methodExists = false;
			eval( '$x = new '.$className.'();$methodExists = method_exists($x,"'.$name.'");' );
			//End of PHP 4 Fix
			
			
			
			if( !$methodExists )$response[$p->id] = null;
			else $response[$p->id] = call_user_func_array(array($x,$name),$params);
		
		}

		return $response;
	}

	
	/**
	 * Parses "plugins.list" files and loads all plugins listed there
	 * 
	 * @access	public
	 * @return bool true on success , false on failure
	 */
	function loadPlugins()
	{
		
		$aclPath = JWF_BACKEND_PATH.DS.'plugins'.DS.'acl_handlers'.DS;
		
		$registry = new JRegistry();
		$registry->loadINI(file_get_contents($aclPath.'plugins.list'));
		$plugins = $registry->toArray();

		include_once $aclPath.'base.php';

		foreach($plugins as $id => $pluginName ){
			$this->_loadPlugin( $id, $pluginName );
		}
		
		return true;
	}
	
	/**
	 * Loads a single plugin from XML file including language files
	 * 
	 * @access	private
	 * @param string $id identification name of the plugin ,see plugins.list for more information
	 * @param string $name title of the plugin
	 * @return bool true on success , false on failure
	 */
	function _loadPlugin( $id, $name ){
		
		
		$plugin = new stdClass();
		$plugin->id   = $id;
		$plugin->name = $name;
	
		$aclPath = JWF_BACKEND_PATH.DS.'plugins'.DS.'acl_handlers'.DS;
		$plugin->php  = $aclPath.$id.'.php';
	
		$this->loadedPlugins[$id] = $plugin;
		
		//Load language files
		$lang =& JFactory::getLanguage();
		$lang->load('acl.'.ucfirst($id),JWF_BACKEND_PATH,null,false);
	
		return true;
	}
		
}