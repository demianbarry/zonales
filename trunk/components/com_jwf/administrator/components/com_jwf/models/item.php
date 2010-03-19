<?php
/**
* Item Model ( Handles DB manipulation of items "Items are Articles/Images/Etc moving through the Workflow)
*
* @version		$Id: item.php 1480 2009-08-24 15:11:16Z mostafa.muhammad $
* @package		Joomla
* @subpackage	JWF.models
* @author	Mostafa Muhammad <mostafa.mohmmed@gmail.com>
* @copyright	Copyright (C) 2009 Mostafa Muhammad. All rights reserved.
* @license		GNU/GPL
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.model');

/**
 * JWF "Item" Model
 *
 * @package    Joomla
 * @subpackage JWF.models
 */
class JWFModelItem extends JModel
{
	/**
	 * Constructor
	 *
	 * @access	public
	 * @return	void
	 */
	function __construct(){
		parent::__construct();
	}
	
	/**
	 * Deletes an item from a workflow (Currently a stub)
	 * @access	public
	 * @param	int workflow id
	 * @param	int item id
	 * @return	bool True on success, false on failure
 	 */
	function delete( $wid, $iid ){
	
		/*
		$db->setQuery("DELETE FROM `#__jwf_steps` WHERE `wid`=$wid AND `iid`=$iid");
		if (!$db->query()){
			JError::raiseError( 500, $db->getErrorMsg() );		
		}
		*/
	}
	
	/**
	 * Just an alias for code readability (Used to insert an item in a workflow for the first time)
	 * @access	public
	 * @see JWFModelItem::move
	*/
	function enter( $wid, $sid ,$iid, $title, $version ){return $this->move($wid, $sid ,$iid, $title, $version);}
	
	/**
	 * Moves an item to a given station
	 * @access	public
	 * @param	int workflow id to which the item belongs
	 * @param	int the target station id
	 * @param	int the id of the item to be moved
	 * @param	string the title of the item to be moved (stored in the table, not used for access)
	 * @param	int the current version of the item
	 * @return	bool True on success, Raises an HTTP error on failure
 	 */
	function move( $wid, $sid ,$iid , $title, $version ){
		
		
		$db =& JFactory::getDBO();
		
		$wid = intval( $wid );
		$sid = intval( $sid );
		$iid = intval( $iid );
		$version = intval( $version );
		$title = $db->getEscaped( $title );
		$config =& JFactory::getConfig();
		$tzoffset = $config->getValue('config.offset');
		$date =& JFactory::getDate('', $tzoffset);
		$created = $date->toMySQL();
		
		$db->setQuery("UPDATE `#__jwf_steps` SET `current`=0 WHERE `wid`=$wid AND `iid`=$iid");
		if (!$db->query()){
			JError::raiseError( 500, $db->getErrorMsg() );
		}

		$db->setQuery("INSERT INTO `#__jwf_steps` (`wid`,`sid`,`iid`,`title`,`created`,`current`,`version`) VALUES ($wid, $sid, $iid, '$title', '$created', 1, $version)");
		if (!$db->query()){
			JError::raiseError( 500, $db->getErrorMsg() );
		}

		return true;
	}
	
	/**
	 * Returns the current station id for a given item
	 * @access	public
	 * @param	int workflow id to which the item belongs
	 * @param	int the id of the item
	 * @return	int the id of the station in which the items resides now 
 	 */
	function getCurrentStationId( $wid, $iid ){
	
		$db =& JFactory::getDBO();
		
		$wid = intval( $wid );
		$iid = intval( $iid );
		
		$db->setQuery("SELECT `sid` FROM `#__jwf_steps` WHERE `wid`=$wid AND `iid`=$iid AND current=1");
		$result = $db->loadResult();
		return intval($result);

	}
	
	/**
	 * Returns a list of all the steps this item has taken in the workflow
	 * 
	 * @access	public
	 * @param	int workflow id to which the item belongs
	 * @param	int the id of the item
	 * @return	array containing all the steps the item has taken, null on failure 
	*/
	function get( $wid, $iid ){
	
		$db =& JFactory::getDBO();
		
		$wid = intval( $wid );
		$iid = intval( $iid );
		
		//Load stations
		$db->setQuery("SELECT * FROM `#__jwf_steps` WHERE `wid`=$wid AND `iid`=$iid ORDER BY `id` ASC");
		$items = $db->loadObjectList('id');
		
		if( $items == null ){
			return null;
		}
		
		return $items;
	}
	
	/**
	 * searches through items
	 * 
	 * @access	public
	 * @param int starting records
	 * @param int number of records to return
	 * @param array containing all the GIDs of the current logged in user for all supported ACL systems 
	 * @param string keywords used for the LIKE clause
	 * @param bool whether or not to return the last step for items
	 * @return array Array('overallTotal' => total number of items<br />'requestTotal' => total number of items returned by the request<br />'items'    => list of items)
	 */
	function search( $start=0, $count=0, $aclPairs=null, $keyword='', $onlyCurrent=false ){
	
		$db =& JFactory::getDBO();
		
		$start = intval( $start );
		$count = intval( $count );
		
		$onlyCurrent = (bool) $onlyCurrent;
		
		$keyword = $db->getEscaped( $keyword, true );
		
		$limit = '';
		if( $count != 0 ){
			$limit = "LIMIT $start, $count ";
		}
		
		$whereFragments = array();
				
		if( $keyword != ''){
			$whereFragments['keyword'] = "w.name LIKE '%$keyword%'";
		}
		
		if( $aclPairs != null && !canManageWorkflows()){
			$whereFragments['acl'] = '';
			$aclWhereFragments = array();
			foreach($aclPairs as $system => $gid ){
				$gids = implode( ',' , array_keys($gid));
				$aclWhereFragments[] = "(w.acl = '$system' AND (w.admin_gid IN ($gids) OR s.group IN ($gids) ))";
			}
			$whereFragments['acl'] = '('.implode( ' OR ', $aclWhereFragments ).')';
		}
		
		if( $onlyCurrent ){
			$whereFragments['current'] = ' h.current=1';
		}
		

		
		$whereConditions = implode( ' AND ', $whereFragments);

		$where = '';
		if( $whereConditions != '' ){
			$where = 'WHERE '.$whereConditions;
		}

		$sql =  
		"SELECT COUNT(*) FROM #__jwf_steps";
		$db->setQuery($sql);
		$overallCount = $db->loadResult();
		
		$sql = "SELECT COUNT(*)"
		."\nFROM `#__jwf_steps` AS h"
		."\nINNER JOIN `#__jwf_stations` AS s ON s.id = h.sid"
		."\nINNER JOIN `#__jwf_workflows` AS w ON w.id = h.wid"
		."\n".$where;
		$db->setQuery($sql);
		$requestCount = $db->loadResult();

		$sql = "SELECT h.* , CONCAT(w.acl, '.', w.admin_gid) as administratingGroup,s.allocatedTime as taskTime, s.task as currentTask, s.title as currentStation , s.order as position , w.title as workflowTitle, w.component as contentType"
		."\nFROM `#__jwf_steps` AS h"
		."\nINNER JOIN `#__jwf_stations` AS s ON s.id = h.sid"
		."\nINNER JOIN `#__jwf_workflows` AS w ON w.id = h.wid"
		."\n".$where
		."\n"."ORDER BY h.created DESC"
		."\n".$limit;
		$db->setQuery( $sql );

		return array('overallTotal'  => $overallCount,
					 'requestTotal'  => $requestCount,
					 'items' 	     => $db->loadObjectList() );
		
	}
}