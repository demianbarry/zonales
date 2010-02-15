<?php
/**
* JWF Core plugin, This plugin intercepts certain "content addition" events defined by component handlers and calls appropriate component handler to deal with the recently added content
*
* @version		$Id: jwf.php 1441 2009-08-16 14:30:27Z mostafa.muhammad $
* @package		Joomla
* @subpackage	JWF.core
* @copyright	Copyright (C) 2009 Mostafa Muhammad. All rights reserved.
* @license		GNU/GPL
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

//Quit sliently if JWF Extension is not installed
if( !file_exists(JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_jwf'.DS.'globals.php')) return;

require_once JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_jwf'.DS.'globals.php';
require_once JWF_BACKEND_PATH.DS.'libs'.DS.'plugins.php';
require_once JWF_BACKEND_PATH.DS. 'models'.DS.'workflow.php';
require_once 'jwf.cron.php';

$GLOBALS['JWFGlobals'] = array();
$GLOBALS['JWFGlobals']['PluginManager'] = new JWFPluginManager();

//Load Trigger cache file
$triggerDataPath = JWF_FS_PATH.DS.'triggerCache.ini';
$triggerCacheData = new JRegistry();
$triggerCacheData->loadINI(file_get_contents($triggerDataPath));
$triggers = $triggerCacheData->toArray();

$GLOBALS['JWFGlobals']['Triggers']  = $triggers;

/**
 * JWF Core system plugin
 *
 * @package    Joomla
 * @subpackage JWF.core
 */
class plgSystemJwf extends JPlugin
{
	/**
	 * constructor 
	 *
	 * @return void
	 */
	 function plgSystemJwf(& $subject, $config) {
		parent::__construct($subject, $config);
	}

	/**
	 * onAfterInitialise event handler 
	 * This method registers all the events that are listed in the triggerCache.ini file 
	 *
	 * @return void
	 */
	 function onAfterInitialise() {
		
		$app = &JFactory::getApplication('administrator');
		
		$triggers = $GLOBALS['JWFGlobals']['Triggers'];
		
		$registeredEvents = array();
		
		foreach($triggers as $t){
			
			list($name,$component,$category) = explode('-', $t);
			
			if( in_array($name,$registeredEvents))continue;
			
			$registeredEvents[] = $name;
			
			$app->registerEvent( $name, 'plgJWFGlobalHandler' );

		}
		
	}
	
}

/**
 * Global event handler for JWF , this function recieves all events that were registered by the core plugin earlier "via onAfterInitialize" method and calls the appropriate component handler 
 *
 * @return void
*/
function plgJWFGlobalHandler(){
	
	//Get the event that triggered this call
	$backTrace = debug_backtrace();
	$event = $backTrace[2]['args'][0];

	//Get Arguments
	$args = func_get_args();
	
	//Get plugin Manager
	$pManager =& getPluginManager();
	$pManager->loadPlugins('component');
	

	$model = new JWFModelWorkflow();
	
	//The following loop routes events to component handlers based on the information in trigger cache
	//e.g. : onAfterContentSave events are routed to "com_content" event handler
	// see /path/to/joomla/administrator/components/com_jwf/plugins/component_handlers/content/content.php
	$triggers = $GLOBALS['JWFGlobals']['Triggers'];
	foreach($triggers as $id => $trigger){
		list($name,$component,$category) = explode('-', $trigger);
		if( $name == $event ){
			$workflow = $model->get( $id );
			$pManager->invokeMethod( 'component', 'onNewEntry',  array($component), array($workflow, $args) );
		}
	}
}

/**
 * Cron job handler : Checks for tasks that are overdue and reports them to administrators [ Currently a stub ]
 *
 * @return void
*/
function plgJWFCheckDelayedTasks(){;}

/**
 * Cron job handler : Deletes orphaned tasks , those are tasks that are pointing to a content element that has been deleted "e.g. an Article that has been deleted outside the workflow control"
 *
 * @return void
*/
function plgJWFDeletedOrphanedTasks(){

	//Clean up orphans in "steps" table
	$pManager =& getPluginManager();
	$pManager->loadPlugins('component');
	$pManager->invokeMethod( 'component', 'deleteOrphans',  null , null );	
	
	//Clean up orphans in "fields" table
	$db  =& JFactory::getDBO();
	$db->setQuery('DELETE FROM `#__jwf_fields` WHERE `tid` NOT IN (SELECT `id` FROM `#__jwf_steps`)');
	$db->query();
}

JWFCronJobManager::check();
