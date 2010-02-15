<?php
/**
* History Model
*
* @version		$Id: history.php 1439 2009-08-16 12:41:13Z mostafa.muhammad $
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
 * JWF "History" Model
 *
 * @package    Joomla
 * @subpackage JWF.models
 */
class JWFModelHistory extends JModel
{
	/**
	 * Constructor
	 *
	 * @access	public
	 * @return	void
	 */
	function __construct(){parent::__construct();}

	/**
	 * returns all history entries for a given item
	 * @access	public
	 * @param	int $wid workflow id
	 * @param	int $iid item id
	 * @return	mixed null on failure, array with all history entries on success
 	 */
	function get( $wid, $iid ){
	
		$db =& JFactory::getDBO();
		
		$wid = intval($wid);
		$iid = intval($iid);
		
		$sql = 
		"SELECT h.*, u.name as author"
		."\nFROM #__jwf_history as h "
		."\nLEFT JOIN #__users AS u on h.created_by = u.id "
		."\n WHERE `iid`=$iid AND `wid`=$wid"
		."\n"."ORDER BY id ASC";
		$db->setQuery($sql);
		$history = $db->loadObjectList();
		
		if( $history == null ){
			return null;
		}
		for($i=0;$i<count($history);$i++){
			$history[$i]->station = unserialize(base64_decode($history[$i]->station));
			$history[$i]->value   = unserialize(base64_decode($history[$i]->value));
		}
		return $history;
	}
	
	
	/**
	 * Creates a history entry
	 *
	 * @access	public
	 * @param	int $wid workflow id
	 * @param	object $station containing information about the station (see #__jwf_stations) table
	 * @param	int $iid item id
	 * @param	string $type the plugin the created this entry "e.g. (component.content) or (field.comments)", this will be used to send the history entry back to the plugin that created it to determine how to display these data
	 * @param	string $title title of the history entry
	 * @param	mixed $value more data about this history entry, these data will be serialized and stored to the DB , when it is time to display these data , they will be sent to the plugin the created them for translation
	 * @return	bool True on success, raises an HTTP error on failure
 	 *
	 */
	function add( $wid, $station, $iid, $type, $title, $value )
	{

		$user    = & JFactory::getUser();
		$db      = & JFactory::getDBO();
		$datenow = & JFactory::getDate();

		if( $user->guest )
			JError::raiseError( 403, JText::_('Not Authorized') );

		$created_by = $user->get('id');

		$wid = intval($wid);
		$iid = intval($iid);
		
		$stationTxt = base64_encode(serialize($station));
		$title      = $db->getEscaped($title, true );
		$type       = $db->getEscaped($type , true );
		$valueTxt   = base64_encode(serialize($value));
		$date       = $datenow->toMySQL();
		
		$db->setQuery(
		 "INSERT INTO `#__jwf_history` (`wid`,`station`,`iid`,`created_by`,`date`,`type`,`title`,`value`)"
		."\n VALUES ($wid,'$stationTxt',$iid,$created_by,'$date','$type','$title','$valueTxt')"
		);
		
		if (!$db->query())
			JError::raiseError( 500, $db->getErrorMsg() );
	
		return true;
	}
}