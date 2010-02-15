<?php
/**
* Workflow Model
*
* @version		$Id: workflow.php 1439 2009-08-16 12:41:13Z mostafa.muhammad $
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
 * JWF "Workflow" Model
 *
 * @package    Joomla
 * @subpackage JWF.models
 */
class JWFModelWorkflow extends JModel
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
	 * searches through workflows
	 * 
	 * @access	public
	 * @param bool $loadStations whether to load station information or not
	 * @param int $start starting records
	 * @param int $count number of records to return
	 * @param string $keyword keywords used for the LIKE clause
	 * @param bool $onlyPublished  whether or not to return only the published workflows
	 * @return array Array(<br />'overallTotal' => total number of workflows in db<br />'requestTotal' => total number of workflows returned by the request <br />'workflows'    => list of workflows)
	 */
	function search( $loadStations=false, $start=0, $count=0, $keyword='', $onlyPublished=false ){
	
		$db =& JFactory::getDBO();
		
		$start = intval( $start );
		$count = intval( $count );
		
		$onlyPublished = (bool) $onlyPublished;
		
		$keyword = $db->getEscaped( $keyword, true );
		
		$limit = '';
		if( $count != 0 ){
			$limit = "LIMIT $start, $count ";
		}
		
		$whereFragments = array();
		
		if( $keyword != ''){
			$whereFragments[] = "w.name LIKE '%$keyword%'";
		}
		
		if( $onlyPublished ){ 
			$jnow =& JFactory::getDate();
			$now = $jnow->toMySQL();
			$nullDate = $db->getNullDate();
			$whereFragments[]	= ' ( w.state = 1 OR w.state = -1)' .
			 	 ' AND ( w.publish_up = '.$db->Quote($nullDate).' OR w.publish_up <= '.$db->Quote($now).' )' .
				 ' AND ( w.publish_down = '.$db->Quote($nullDate).' OR w.publish_down >= '.$db->Quote($now).' )';
		}
		
		$where = '';
		if( count($whereFragments)){
			$where = 'WHERE'.implode( ' AND ', $whereFragments );
		}

		$sql =  
		"SELECT COUNT(*) FROM #__jwf_workflows";
		$db->setQuery($sql);
		$overallCount = $db->loadResult();
		
		$sql =  
		"SELECT COUNT(*) FROM #__jwf_workflows"
		."\n".$where;
		$db->setQuery($sql);
		$requestCount = $db->loadResult();

		$sql = 
		 "SELECT w.*, v.name as editor, u.name as author"
		."\nFROM #__jwf_workflows as w "
		."\nLEFT JOIN #__users AS u on w.created_by = u.id "
		."\nLEFT JOIN #__users AS v ON w.checked_out= v.id " 
		."\n".$where
		."\n"."ORDER BY id ASC"
		."\n".$limit;

		$db->setQuery( $sql );
		$workflows = $db->loadObjectList();
			
		if( $loadStations ){
			
			$ids = array();
			foreach($workflows as $w){
				$ids[] = intval($w->id);
			}
			$idString = implode(',', $ids);
			$db->setQuery("SELECT * FROM `#__jwf_stations` WHERE `wid` IN ($idString) ORDER BY `wid` ASC, `order` ASC");
			$stations = $db->loadObjectList();
			for($i=0;$i<count($workflows);$i++){
				$workflows[$i]->stations = array();
				foreach($stations as $s){
					if( $s->wid == $workflows[$i]->id ){
						$workflows[$i]->stations[$s->id] = $s;
					}
				}
				
			}
		
		}
		return array('overallTotal'  => $overallCount,
					'requestTotal'   => $requestCount,
					'workflows'      => $workflows );
	}
	
	
	/**
	 * Returns all data about one workflow  based on ID
	 * 
	 * @access	public
	 * @param int $id workflow id 
	 * @return object a workflow object loaded with station information, null on failure
	 */
	function get( $id )
	{
		$db =& JFactory::getDBO();
		
		//Load stations
		$db->setQuery("SELECT * FROM `#__jwf_workflows` WHERE `id`=$id");
		$workflow = $db->loadObject();
		
		if( $workflow == null ){
			return null;
		}
		
		if($workflow->publish_down == $db->getNullDate()){
			$workflow->publish_down = JText::_('Never');
		}
		
		//Load stations
		$db->setQuery("SELECT * FROM `#__jwf_stations` WHERE `wid`=$id ORDER BY `order` ASC");
		$workflow->stations = $db->loadObjectList('id');
		

		if( $workflow->stations == null ){
			return null;
		}
		
		return $workflow;
	}
	
	/**
	 * Checks-in the workflow , called when the user clicks "cancel"
	 *
	 * @access	public
	 * @param int $id workflow id
	 * @return	void
	 */
	function close( $id )
	{
		if( $id ){
			$row  = & JTable::getInstance('Workflow','Table');
			$row->load( $id );
			$row->checkin();
		}
	}
	
	/**
	 * Stores a Workflow to database
	 *
	 * @access	public
	 * @param object $data Workflow Data to be saved 
	 * @return int	ID of the newly saved workflow on success , 0 on failure
	 */
	function save( $data )
	{
		
		$user = & JFactory::getUser();
		$db   = & JFactory::getDBO();
		$row  = & JTable::getInstance('Workflow','Table');
		$nullDate = $db->getNullDate();
		
		if (!$row->bind($data)) {
			JError::raiseError( 500, $db->stderr() );
			return 0;
		}
		
		$row->id = intval($row->id);

		JArrayHelper::toInteger( $data['category']	);
		$row->category = implode( ',', $data['category'] );
		
		$newEntry = $row->id?false:true;

		//Copied directly from com_content saveContent()  
		
		// Are we saving from an item edit?
		if (!$newEntry) {
			$datenow =& JFactory::getDate();
			$row->modified 		= $datenow->toMySQL();
			$row->modified_by 	= $user->get('id');
		}

		$row->created_by 	= $row->created_by ? $row->created_by : $user->get('id');

		if ($row->created && strlen(trim( $row->created )) <= 10) {
			$row->created 	.= ' 00:00:00';
		}

		$config =& JFactory::getConfig();
		$tzoffset = $config->getValue('config.offset');
		$date =& JFactory::getDate($row->created, $tzoffset);
		$row->created = $date->toMySQL();

		
		
		if(strlen(trim($row->publish_up)) == 0){
			$date =& JFactory::getDate(1, $tzoffset);
		} else {
			$row->publish_up .= ' 00:00:00';
			$date =& JFactory::getDate($row->publish_up, $tzoffset);
		}
		$row->publish_up = $date->toMySQL();

		// Handle never unpublish date
		if (trim($row->publish_down) == JText::_('Never') || trim( $row->publish_down ) == '')
		{
			$row->publish_down = $nullDate;
		}
		else
		{
			if (strlen(trim( $row->publish_down )) <= 10) {
				$row->publish_down .= ' 00:00:00';
			}
			$date =& JFactory::getDate($row->publish_down, $tzoffset);
			$row->publish_down = $date->toMySQL();
		}
		
		// Make sure the data is valid
		if (!$row->check()) {
			JError::raiseError( 500, $db->stderr() );
		}

		// Store the content to the database
		if (!$row->store()) {
			JError::raiseError( 500, $db->stderr() );
		}
		
		// Check the form
		$row->checkin();
		//End of faithful copy
		
		if($newEntry){
			$row->id = lastInsertId();
		}

		if(!$newEntry){
			//Delete stations of this workflow
			$db->setQuery('DELETE FROM #__jwf_stations WHERE wid=' . $row->id);
			if (!$db->query()){
				JError::raiseError( 500, $db->getErrorMsg() );
			}
		}

		$queriesNewId = array();
		$queriesOldId = array();
		
		foreach($data['stations'] as $station){
			$wid    = intval($row->id);
			$title  = $db->getEscaped($station->title, true );
			$task   = $db->getEscaped($station->task ,true);
			$allocatedTime = intval($station->allocatedTime);
			$group  = intval($station->acl->id);
			$fields = $db->getEscaped($station->fields ,true);
			$hooks  = $station->activeHooks;
                        $validations  = $db->getEscaped($station->activeValidations ,true);
			$order  = intval( $station->order );
			if( $station->id == null ){
				$queriesNewId[] = "($wid, '$title', '$task', $allocatedTime, $group, '$fields' , '$hooks', '$validations', $order)";
			} else {
				$queriesOldId[] = "($station->id, $wid, '$title', '$task', $allocatedTime, $group, '$fields', '$hooks', '$validations', $order)";
			}
		}

		if( count($queriesNewId)){
			$sqlNewIds = implode( ',', $queriesNewId );
			$sqlNewIds = 'INSERT INTO `#__jwf_stations` (`wid`,`title`,`task`,`allocatedTime`,`group`,`fields`, `activeHooks`, `activeValidations`,  `order`) VALUES ' .$sqlNewIds;
			
			$db->setQuery($sqlNewIds);
			if (!$db->query()){
				JError::raiseError( 500, $db->getErrorMsg() );
			}
		}
	
		if( count($queriesOldId)){
			$sqlOldIds = implode( ',', $queriesOldId );
			$sqlOldIds = 'INSERT INTO `#__jwf_stations` (`id`,`wid`,`title`,`task`,`allocatedTime`,`group`,`fields`,`activeHooks`, `activeValidations`, `order`) VALUES ' .$sqlOldIds;
			$db->setQuery($sqlOldIds);
			if (!$db->query()){
				JError::raiseError( 500, $db->getErrorMsg() );
			}
		}
		
		//Update trigger cache
		$pManager =& getPluginManager();
		$pManager->loadPlugins('component');
		$plugins = $pManager->settings['component'];
		
		$trigger   = $plugins[$row->component]->trigger;
		$category  = $row->category;
		$component = $row->component;
		
		$triggerDataPath = JWF_FS_PATH.DS.'triggerCache.ini';
		$triggerCacheData = new JRegistry();
		$triggerCacheData->loadINI(file_get_contents($triggerDataPath));
		$triggerCacheData->setValue($row->id, $trigger.'-'.$component.'-'.$category);
		file_put_contents($triggerDataPath, $triggerCacheData->toString('INI'));

		return $row->id;
	}
	
	/**
	 * Deletes a workflow
	 *
	 * @access	public
	 * @param  array $ids list of IDs to delete 
	 * @return	bool true on success , false on failure
	 */
	function delete( $ids )
	{
		$db   = & JFactory::getDBO();

		JArrayHelper::toInteger( $ids );

		$additionalParameters = array();			
		
		if( !is_array( $ids ) || !count( $ids ) ){
			return false;
		}
		
		$idText = implode( ',', $ids );
		
		//Delete From the main workflow table
		$db->setQuery('DELETE FROM #__jwf_workflows WHERE id IN (' . $idText  . ')');
		if (!$db->query()){
			JError::raiseError( 500, $db->getErrorMsg() );
		}
		
		//Delete From the steps table
		$db->setQuery('DELETE FROM #__jwf_steps WHERE wid IN (' . $idText  . ')');
		if (!$db->query()){
			JError::raiseError( 500, $db->getErrorMsg() );		
		}
		
		//Delete from the stations table
		$db->setQuery('DELETE FROM #__jwf_stations WHERE wid IN (' . $idText . ')');
		if (!$db->query()){
			JError::raiseError( 500, $db->getErrorMsg() );
		}

		//Delete from the fields table
		$db->setQuery('DELETE FROM #__jwf_fields WHERE wid IN (' . $idText . ')');
		if (!$db->query()){
			JError::raiseError( 500, $db->getErrorMsg() );
		}
		
		//Delete from the history table
		$db->setQuery('DELETE FROM #__jwf_history WHERE wid IN (' . $idText . ')');
		if (!$db->query()){
			JError::raiseError( 500, $db->getErrorMsg() );
		}
		
		
		return true;
	}
	
	/**
	 * Unpublishes the workflows whose IDs are passed 
	 *
	 * @access	public
	 * @param array $ids list of form IDs to unpublish 
	 * @return	bool true on success , false on failure
	 */
	function unpublish($ids){
		
		JArrayHelper::toInteger( $ids );
		
		if( !is_array( $ids ) || !count( $ids ) ){
			return false;
		}
		
		$idText = implode( ',', $ids );
	
		$db   = & JFactory::getDBO();
			
		//Unpublish Workflow
		$db->setQuery('UPDATE #__jwf_workflows SET state=0 WHERE id IN (' . $idText . ') ');
		if (!$db->query()){
			JError::raiseError( 500, $db->getErrorMsg() );
		}
		
		return true;
	}
	
	/**
	 * Publishes the workflows whose IDs are passed 
	 *
	 * @access	public
	 * @param   array $ids list of form IDs to publish 
	 * @return	bool true on success , false on failure
	 */
	function publish($ids){
	
		JArrayHelper::toInteger( $ids );

		if( !is_array( $ids ) || !count( $ids ) ){
			return false;
		}

		$idText = implode( ',', $ids );
		
		$db   = & JFactory::getDBO();
			
		//Publish From
		$db->setQuery('UPDATE #__jwf_workflows SET state=1 WHERE id IN (' . $idText . ') ');

		if (!$db->query()){
			JError::raiseError( 500, $db->getErrorMsg() );
		}

		return true;
	}
}