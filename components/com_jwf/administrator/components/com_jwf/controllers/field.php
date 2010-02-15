<?php
/**
* Field controller for JWF Component
*
* @version		$Id: field.php 1440 2009-08-16 14:06:09Z mostafa.muhammad $
* @package		Joomla
* @subpackage	JWF.controllers
* @copyright	Copyright (C) 2009 Mostafa Muhammad. All rights reserved.
* @license		GNU/GPL
*/

jimport('joomla.application.component.controller');

/**
 * Field Controller class
 *
 * @package    Joomla
 * @subpackage JWF.controllers
 */
class JWFControllerField extends JController
{

	/**
	 * constructor (registers additional tasks to methods)
	 *
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();
		JModel::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models');
		$this->registerTask( 'invoke'  , 'invoke');
	}
		
	/**
	 * Task handler ( Responds to an Ajax Request )
	 * This method act as an AJAX server for "Fields" , recieves requests and redirects them to the correct field handler
	 *
	 * @see JWFFieldHandler
	 * @return void
	 */
	function invoke(){
		
		JRequest::checkToken('get') or jexit( '0' );

		$wid       = JRequest::getInt( 'workflowID', 0 );
		$sid       = JRequest::getInt( 'stationID' , 0 );
		$iid       = JRequest::getInt( 'itemID'    , 0 );
		$tid       = JRequest::getInt( 'stepID'    , 0 );
		$fieldType = JRequest::getVar( 'fieldType' );
		$method    = JRequest::getVar( 'method' );

		$data = JRequest::getVar( 'data' );
		$data['wid'] = $wid;
		$data['sid'] = $sid;
		$data['iid'] = $iid;
		$data['tid'] = $tid;
		
		$iModel =& $this->getModel('item');
		$wModel =& $this->getModel('workflow');
		$fModel =& $this->getModel('field');
		
		$steps     = $iModel->get( $wid, $iid );
		$workflow  = $wModel->get( $wid );
		$fieldData = $fModel->get( $wid, $iid );
		
		$pManager =& getPluginManager();
		$pManager->loadPlugins('field');

		if(	array_key_exists( $fieldType, $pManager->settings['field'] ) ){
			$storedData = (isset($fieldData) && isset($fieldData[$fieldType]))?$fieldData[$fieldType]:null;
			$response   = $pManager->invokeMethod( 'field', $method,  array($fieldType), array($workflow, $steps, $storedData, $data) );
		}
		echo $response[$fieldType];
		jexit( 0 );
	}
}