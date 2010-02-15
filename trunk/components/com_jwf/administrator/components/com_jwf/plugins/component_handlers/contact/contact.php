<?php
/**
* Component handler plugin for Joomla! contacts (Alpha)
*
*
* @version		$Id: contact.php 1439 2009-08-16 12:41:13Z mostafa.muhammad $
* @package		Joomla
* @subpackage	JWF.plugins
* @author	Mostafa Muhammad <mostafa.mohmmed@gmail.com>
* @copyright	Copyright (C) 2009 Mostafa Muhammad. All rights reserved.
* @license		GNU/GPL
*/

class JWFComponentHandler_contact  extends JWFComponentHandler{

	function getCategories(){
	
		static $cachedCategories;
		if( $cachedCategories != null )return $cachedCategories;
		list($major,$minor,$version) = explode('.',JVERSION);
		if( $major == 1 && $minor == 5 ){
			$db  =& JFactory::getDBO();
			$sql = 'SELECT id, title FROM #__categories WHERE section = "com_contact_details"';
			$db->setQuery( $sql );
			$categories = $db->loadObjectList();
			for( $i=0;$i<count($categories); $i++ )$categories[$i]->children = array();
			$cachedCategories = $categories;
			return $cachedCategories;
		}
		
		if( $major == 1 && $minor == 6 ){
			return array();
		}
	}
	function lock( $id, $aclSystemId ){

	}
	
	function unlock( $id, $aclSystemId, $gid ){
	
	}
	
	function onNewEntry($workflow, $args){
	
		$categoriesArray = explode(',', $categories);
		$entry = $args[0];
		
		if(!in_array($entry->catid, $categoriesArray ))return false;
		
		//Check to see if this a new Article or an old one being modified
		$id     = JRequest::getInt( 'id', 0, 'post' );
		//Exit if this is an old one
		if( $id != 0 )return false;
		
		require_once JWF_BACKEND_PATH.DS.'models'.DS.'item.php';
		$model = new JWFModelItem();
		$firstStation = reset($workflow->stations);
		
		return $model->enter( $workflow->id, $firstStation->id, $entry->id, $entry->title, $entry->version );
	}
}