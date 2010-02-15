<?php
/**
* Workflow table class
*
* @version		$Id: workflow.php 1439 2009-08-16 12:41:13Z mostafa.muhammad $
* @package		Joomla
* @subpackage	JWF.tables
* @copyright	Copyright (C) 2009 Mostafa Muhammad. All rights reserved.
* @license		GNU/GPL
*/

// no direct access
defined('_JEXEC') or die('Restricted access');


/**
 * Workflow Table Class : This table stores general information about the workflow
 *
 * @package    Joomla
 * @subpackage JWF.tables
 */
class TableWorkflow extends JTable
{
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $id = null;

	/**
	 * @var string Title of the workflow
	 */
	var $title = null;

	/**
	 * @var string Type of content this workflow manages ( also this is the name of the "component handler" plugin that will handle content on this workflow)
	 */
	var $component = null;

	/**
	 * @var string CSV with category IDs that this workflow manages
	 */
	var $category = null;

	/**
	 * @var string ACL handler that will manage Access control for this workflow ( also this is the name of the "acl handler" plugin that will handle content on this workflow)
	 */
	var $acl = null;

	/**
	 * @var int ACL group Id that will have managrial authority over the workflow "Can see all items under this workflow, will recieve notifications on delayed tasks, and a bunch of other previlages) 
	 */
	var $admin_gid = null;
	
	/**
	 * @var int Published/Unpublished
	 */
	var $state = null;
	
	/**
	 * @var datetime Time the workflow was created
	 */
    var $created = null;
	
	/**
	 * @var int id of the user who created the workflow
	 */
	var $created_by = null;
	
	/**
	 * @var datetime Time this Workflow was last modified
	 */
	var $modified;
	
	/**
	 * @var int id of the user who last modified the workflow
	 */
	var $modified_by; 
	
	/**
	 * @var int check_out flag 
	 */
	var $checked_out;
	
	/**
	 * @var datetime checkout time
	 */
	var $checked_out_time;
	
	/**
	 * @var datetime Start publish time
	 */
    var $publish_up;
	
	/**
	 * @var datetime End publish time
	 */
    var $publish_down;


	
	/**
	 * Constructor
	 *
	 * @param object $db Database connector object
	 */
	function TableWorkflow(& $db) {
		parent::__construct('#__jwf_workflows', 'id', $db);
	}
}