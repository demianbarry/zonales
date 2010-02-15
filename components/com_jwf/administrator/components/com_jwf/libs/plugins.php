<?php
/**
* Plugin manager class
*
* @version		$Id: plugins.php 1440 2009-08-16 14:06:09Z mostafa.muhammad $
* @package		Joomla
* @subpackage	JWF.core
* @author	Mostafa Muhammad <mostafa.mohmmed@gmail.com>
* @copyright	Copyright (C) 2009 Mostafa Muhammad. All rights reserved.
* @license		GNU/GPL
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();


include_once 'plugins'.DS.'acl.php';
include_once 'plugins'.DS.'component.php';
include_once 'plugins'.DS.'field.php';
include_once 'plugins'.DS.'hook.php';
include_once 'plugins'.DS.'validation.php';

/**
 * JWF Plugin Manager Model
 *
 * @package    Joomla
 * @subpackage JWF.core
 */
class JWFPluginManager{

	
	/**
	 * @var array $settings Stores loaded plugins parameters and settings
	 */
	var $settings  = array();
	
	/**
	 * @var array $managers Stores loaded plugin engines
	 * @see JWFHookPluginManager
	 */
	var $managers = array();
	

	function JWFPluginManager(){
		$this->__construct();
	}
	
	/**
	 * Constructor : loads plugin engines but doesn't load setting files, they are loaded on demand using JWFPluginManager::loadPlugins()
	 * 
	 * @access	public
	 * @return	void
	 */
	function __construct(){
		
		$this->managers['acl']       = new JWFACLPluginManager(); 
		$this->managers['hook']      = new JWFHookPluginManager(); 
		$this->managers['field']     = new JWFFieldPluginManager(); 
		$this->managers['component'] = new JWFComponentPluginManager();
                $this->managers['validation'] = new JWFValidationPluginManager();
		
		$this->settings['acl']       = null;
		$this->settings['hook']      = null;
		$this->settings['field']     = null;		
		$this->settings['component'] = null;
                $this->settings['validation'] = null;
		
	}
	
	/**
	 * Invokes a method on a given plugin type and returns the plugin response
	 * 
	 * @access	public
	 * @param string $type the type of target plugin (e.g "component", "acl" ,etc)
	 * @param string $name Name of the method
	 * @param array $which array containing names of plugins to load , for instance if you're loading component plugin you can pass array('content') as a parameter, can be set to null if you want to invoke this method on all plugins under this particular type (e.g. "all component plugins")
	 * @param array $params array containing the parameters that are to be sent to the method
	 * @return array an Array containing responses from plugins indexed by plugin name , <br />Array ('PLUGIN NAME' => 'PLUGIN RESPOSNE','PLUGIN NAME' => 'PLUGIN RESPOSNE')
	 */
	function invokeMethod( $type, $name, $which, $params ){
		
		//Check $type for errors
		if( array_key_exists( $type , $this->managers)){
			return $this->managers[$type]->invokeMethod( $name, $which, $params );
		}
	}

	/**
	 * Loads the active element plugins "as listed in corresponding jwf_root_path/plugins/{plugin_type}/plugin.list" 
	 *
	 * @access	public
	 * @param string $type the type of target plugin (e.g "component", "acl" ,etc)
	 * @return void
	 */
	function loadPlugins($type){
		//Check $type for errors
		if( array_key_exists( $type , $this->managers)){
			//Check if plugins are already loaded
			if( $this->settings[$type] == null )
				//Load requested plugin settings
				$this->managers[$type]->loadPlugins();
				//Copy plugin settings from the inner class to this class
				$this->settings[$type] = $this->managers[$type]->getSettings();
		}
	}
}