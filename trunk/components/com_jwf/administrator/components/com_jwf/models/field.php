<?php
/**
* Field Model
*
* @version		$Id: field.php 1439 2009-08-16 12:41:13Z mostafa.muhammad $
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
class JWFModelField extends JModel
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
	 * Returns all fields from workflow based on ID
	 * 
	 * @access	public
	 * @param int $wid workflow id 
	 * @param int $iid item id whose field data to be loaded 
	 * @return array containing all loaded fields, null on failure
	 */
	function get( $wid, $iid )
	{
		$db =& JFactory::getDBO();
		
		$wid = intval($wid);
		$iid = intval($iid);
		
		//Load stations
		$db->setQuery(
		"SELECT f.*,u.username as creator, v.username as modifier" 
		."\nFROM `#__jwf_fields` as f"
		."\nLEFT JOIN #__users AS u on f.created_by  = u.id "
		."\nLEFT JOIN #__users AS v ON f.modified_by = v.id " 
		."\nWHERE `wid`=$wid AND `iid`=$iid");
		
		$fieldData = $db->loadObjectList();
		$orderedFieldData = array();
		foreach( $fieldData as $f ){
			if(!array_key_exists( $f->type, $orderedFieldData )){
				$orderedFieldData[$f->type] = array();
			}
			$orderedFieldData[$f->type][] = $f;
		}
		
		return $orderedFieldData;
	}
	
	
	
	/**
	 * Stores a field to database
	 *
	 * @access	public
	 * @param object $data Field data
	 * @return	bool true on success , false on failure
	 */
	function save( $data )
	{

		$db   = & JFactory::getDBO();
		$row  = & JTable::getInstance('Field','Table');
		$nullDate = $db->getNullDate();
		
		if (!$row->bind($data)) {
			return false;
		}
		
		// Make sure the data is valid
		if (!$row->check()) {
			return false;
		}

		// Store the content to the database
		if (!$row->store()) {
			return false;
		}

		return true;
	
	}
	
	/**
	 * Deletes a field
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
		
		$db->setQuery('DELETE FROM #__jwf_fields WHERE id IN (' . $idText  . ')');
		if (!$db->query()){
			return false;
		}
		return true;
	}
}