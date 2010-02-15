<?php
/**
* Component handler plugin for Joomla! core content
*
*
* @version		$Id: content.php 1439 2009-08-16 12:41:13Z mostafa.muhammad $
* @package		Joomla
* @subpackage	JWF.plugins
* @author	Mostafa Muhammad <mostafa.mohmmed@gmail.com>
* @copyright	Copyright (C) 2009 Mostafa Muhammad. All rights reserved.
* @license		GNU/GPL
*/


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

class JWFComponentHandler_content  extends JWFComponentHandler{
	
	function _arrangeCategories( $categories, $rootId ){
		
		$arrangedCategories = array();
		foreach($categories as $c ){
			$c->children = array();
			if( $c->parent_id == $rootId ){

				$c->children = $this->_getChildren( $c, $categories );
				$arrangedCategories[] = $c;
			}
		}
		return $arrangedCategories;
		
	}
	
	function _getChildren( $category, &$categories){
	
	
		$children = array();
		for($i=0;$i<count($categories);$i++){
			$c = $categories[$i];
			if( $c->parent_id == $category->id ){
				$c->children = $this->_getChildren($c, $categories);
				$children[] = $c;
			}
		}
		return $children;
	}
	
	function getCategories(){
	
		static $cachedCategories;
		if( $cachedCategories != null )return $cachedCategories;
		list($major,$minor,$version) = explode('.',JVERSION);
		if( $major == 1 && $minor == 5 ){
			$db  =& JFactory::getDBO();
			$sql = 'SELECT id, title FROM `#__categories` WHERE `section` != "com_contact_details"';
			$db->setQuery( $sql );
			$categories = $db->loadObjectList();
			for( $i=0;$i<count($categories); $i++ )$categories[$i]->children = array();
			$uncategorizedCategory = new stdClass();
			$uncategorizedCategory->id = 0;
			$uncategorizedCategory->title = 'uncategorized';
			$uncategorizedCategory->children = array();
			$categories[] = $uncategorizedCategory;
			$categories = array_reverse($categories);
			$cachedCategories = $categories;
		}
		
		if( $major == 1 && $minor == 6 ){
			$db  =& JFactory::getDBO();
			$sql = 'SELECT id, parent_id, title FROM #__categories WHERE extension = "com_content"';
			$db->setQuery( $sql );
			$categories = $db->loadObjectList();
			$categories = $this->_arrangeCategories(array_reverse($categories),1);
			$cachedCategories = $categories;
		}
		return $cachedCategories;
	}
	
	function publish( $id ){
	
	}
	
	function delete( $id ){
	
	}
	
	function move( $id, $category ){
	
	}
	
	function getEditLink( $id ){
		return JRoute::_("index.php?option=com_content&task=edit&id=$id");
	}
	
	function getLatestRevisionId( $id ){
		
		$db  =& JFactory::getDBO();
		
		if( isset( $_POST ) && isset( $_POST['version'] )){
			return intval( $_POST['version'] );
		}
		
		$sql = "SELECT MAX(`version`) FROM #__content";
		$db->setQuery( $sql );
		return $db->loadResult();
		
	}
	
	function deleteOrphans(){

		$db  =& JFactory::getDBO();
		$sql = "DELETE FROM `#__jwf_steps` WHERE `iid` NOT IN (SELECT `id` FROM `#__content`)";
		$db->setQuery( $sql );
		$db->query();

	}
	
	function getItemRevision( $id, $version ){
		
		$db  =& JFactory::getDBO();
		$versionWhere = " AND `version`=$version ";
		if( strtolower($version) == 'head' ){
			$versionWhere = "";
		}
		
		$sql = "SELECT `title`,`introtext`,`fulltext` FROM #__content WHERE `id`=$id $versionWhere";
		$db->setQuery( $sql );
		$content = $db->loadObject();
	
		$response = new stdClass();
		$response->title = $content->title;
		$response->body  = $content->introtext.$content->fulltext;
		$response->additional = null;
		
		
		return $response;
	}
	
	function lock( $id, $aclSystemId ){
		
		$pManager =& getPluginManager();
		$pManager->loadPlugins('acl');
		//Todo: Actual lock implementation
	}
	
	function unlock( $id, $aclSystemId, $gid ){
		
		$pManager =& getPluginManager();
		$pManager->loadPlugins('acl');
		//Todo: Actual unlock implementation
		
	}
	
	function renderHistoryEntry( $workflow, $entry ){
	
		$historyMsg = $entry->value;
		switch($historyMsg->type){
			case 'modify':
				return JText::_('Article')." '$historyMsg->title' ".JText::_('Modified').' '.JText::_('To').' '.JText::_('Version').' '.$historyMsg->version;
			case 'create':
				return JText::_('Article')." '$historyMsg->title' ".JText::_('Created');
		}
		return '';
	}
	
	function onNewEntry($workflow, $args){
	
		$categoriesArray = explode(',', $workflow->category);
		$entry = $args[0];

		if(!in_array($entry->catid, $categoriesArray ))return false;
		
		//Check to see if this a new Article or an old one being modified
		$id     = JRequest::getInt( 'id', 0, 'post' );
		
		//Create History Entry
		require_once JWF_BACKEND_PATH.DS.'models'.DS.'history.php';
		require_once JWF_BACKEND_PATH.DS.'models'.DS.'item.php';
		
		$historyModel = new JWFModelHistory();
		$itemModel    = new JWFModelItem();
		
		if( $id == 0 ){
			$historyTitle        = JText::_('New Item Created');
			$historyMsg          = new stdClass();
			$historyMsg->type    = 'create';
			$historyMsg->title   = $_POST['title'];
			$historyMsg->version = 0;
			$historyItemId       = lastInsertId();
			$currentStation      = reset($workflow->stations); //First station
		} else {
			$historyTitle        = JText::_('Item modified');
			$historyMsg          = new stdClass();
			$historyMsg->type    = 'modify';
			$historyMsg->title   = $_POST['title'];
			$historyMsg->version = $this->getLatestRevisionId( $id );
			$historyItemId       = $id;
			$stationId           = $itemModel->getCurrentStationId( $workflow->id, $id );
			$currentStation      = $workflow->stations[$stationId];
		}
		if( $currentStation != null ){
			$historyModel->add( $workflow->id, 
								$currentStation, 
								$historyItemId, 
								'component.content', 
								$historyTitle,
								$historyMsg);
		}
		//Done creating history entry
		
		//Exit if this is an old one
		if( $id != 0 )return false;
		
		

		$firstStation = reset($workflow->stations);

		return $itemModel->enter( $workflow->id, $firstStation->id, $entry->id, $entry->title, $entry->version );
	}

}