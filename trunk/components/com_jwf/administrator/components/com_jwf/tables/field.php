<?php
/**
* Field table class
*
* @version		$Id: field.php 1439 2009-08-16 12:41:13Z mostafa.muhammad $
* @package		Joomla
* @subpackage	JWF.tables
* @copyright	Copyright (C) 2009 Mostafa Muhammad. All rights reserved.
* @license		GNU/GPL
*/

// no direct access
defined('_JEXEC') or die('Restricted access');


/**
 * Field Table Class : This table stores fields "Data" , Fields are chunks of data that are attached to an item (e.g. Article) as it goes through a given workflow , "Comments" are a good example of fields
 *
 * @package    Joomla
 * @subpackage JWF.tables
 */
class TableField extends JTable
{
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $id = null;

	/**
	 * @var int ID of the workflow under which the field entry is present
	 */
	var $wid = null;

	/**
	 * @var int ID of the station under which the field was created
	 */
	var $sid = null;

	/**
	 * @var int ID of the content item to which this field entry is attached
	 */
	var $iid = null;

	/**
	 * @var int
	 */
	var $tid = null;

	/**
	 * @var string Type of this field entry ( Also this is the name of the "Field handler" that will created and will render this field "e.g. comments") 
	 */
	var $type = null;

	
	/**
	 * @var datetime Time when this entry was created
	 */
	var $created = null;

	/**
	 * @var int ID of the user who created this field
	 */
	var $created_by = null;
	
	/**
	 * @var datetime Time when this entry was last modified
	 */
	var $modified = null;

	/**
	 * @var int ID of the user who last modified this entry
	 */
	var $modified_by = null;
	

	/**
	 * @var string Field_handler specific data, can be "Comment text" for example 
	 */
	var $value = null;

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function TableField(& $db) {
		parent::__construct('#__jwf_fields', 'id', $db);
	}
}