<?php
/**
* Station table class
*
* @version		$Id: station.php 1439 2009-08-16 12:41:13Z mostafa.muhammad $
* @package		Joomla
* @subpackage	JWF.tables
* @copyright	Copyright (C) 2009 Mostafa Muhammad. All rights reserved.
* @license		GNU/GPL
*/

// no direct access
defined('_JEXEC') or die('Restricted access');


/**
 * Station Table Class : This table stores information about stations, stations are the building blocks of workflows, each workflow is composed of at least 2 stations
 *
 * @package    Joomla
 * @subpackage JWF.tables
 */
class TableStation extends JTable
{
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $id = null;

	/**
	 * @var int workflow id
	 */
	var $wid = null;

	/**
	 * @var string Title of the station
	 */
	var $title = null;

	/**
	 * @var string Job description of this station
	 */
	var $task = null;

	/**
	 * @var int ID of the ACL group that works on this station
	 */
	var $group = null;


	/**
	 * @var string Active Fields on this station "e.g. comments"
	 */
	var $fields = null;

        /**
	 * @var string Active Validations on this station
	 */
	var $activeValidations = null;
	
	/**
	 * @var int the position of this station in the workflow
	 */
        var $order = null;
	
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function TableStation(& $db) {
		parent::__construct('#__jwf_stations', 'id', $db);
	}
}