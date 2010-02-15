<?php
/**
* Workflow controller for JWF Component
*
* @version		$Id: controller.php 1440 2009-08-16 14:06:09Z mostafa.muhammad $
* @package		Joomla
* @subpackage	JWF.controllers
* @copyright	Copyright (C) 2009 Mostafa Muhammad. All rights reserved.
* @license		GNU/GPL
*/

jimport('joomla.application.component.controller');

/**
 * Workflow Controller
 *
 * @package    Joomla
 * @subpackage JWF.controllers
*/
class JWFControllerWorkflow extends JController
{

	/**
	 * constructor (registers additional tasks to methods)
	 *
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();

		// Register Extra tasks
		$this->registerTask( 'add'      , 'add');
		$this->registerTask( 'edit'     , 'edit');
		$this->registerTask( 'save'     ,  'save');
		$this->registerTask( 'publish'  , 'publish');
		$this->registerTask( 'unpublish', 'unpublish');
		$this->registerTask( 'delete'   , 'delete');
		$this->registerTask( 'cancel'   , 'cancel');
		$this->registerTask( 'back'     , 'back');
	
		
	}
	
	/**
	 * Task handler (Publish workflow)
	 *
	 * @return void
	 */
	function publish()
	{
		JRequest::checkToken('get') or jexit( 'Invalid Token' );
		
		$cids = JRequest::getVar( 'cid', array(), 'get', 'array' );
		
		JArrayHelper::toInteger($cids);
		
		if ( !count($cid) ) {
			$this->setRedirect('index.php?option=com_jwf', JText::_('Select an item to publish'), 'error');
		}

		$model = & $this->getModel('workflow');
	
		if( $model->publish($cids) ){
			$this->setRedirect('index.php?option=com_jwf', JText::_('Published successfuly'));
		} else {
			$this->setRedirect('index.php?option=com_jwf', JText::_('Failed to publish selected workflow(s)'), 'error');
		}
	}
	
	/**
	 * Task handler (unpublish a workflow)
	 *
	 * @return void
	 */
	function unpublish()
	{
		
		JRequest::checkToken('get') or jexit( 'Invalid Token' );
		
		$cids = JRequest::getVar( 'cid', array(), 'get', 'array' );
		
		JArrayHelper::toInteger($cids);
		
		if (!count($cids)) {
			$this->setRedirect('index.php?option=com_workflow', JText::_('Select an item to unpublish'), 'error');
		}

		
		$model = & $this->getModel('workflow');

		if( $model->unpublish($cids) ){	
			$this->setRedirect('index.php?option=com_jwf', JText::_('Unpublished successfuly'));
		} else {
			$this->setRedirect('index.php?option=com_jwf', JText::_('Failed to unpublish selected workflow(s)'), 'error');
		}
	}
	
	
	/**
	 * Task handler (Add new Workflow)
	 *
	 * @return void
	 */
	function add()
	{
		
		$document =& JFactory::getDocument();
		
		
		$viewType	= $document->getType();
		$viewName	= 'design';
		$viewLayout	= 'default';
		$view = & $this->getView( $viewName, $viewType, '', array( 'base_path'=>$this->_basePath));
			
		// Set the layout
		$view->setLayout($viewLayout);

		// Display the view
		$view->display(null);
	
	}
	
	/**
	 * Task handler (Edit existing workflow)
	 *
	 * @return void
	 */
	function edit()
	{

		$document =& JFactory::getDocument();
		
		$idArray = JRequest::getVar( 'cid', array(), 'get' );
		$id = intval( $idArray[0] );
		
		$viewType	= $document->getType();
		$viewName	= 'design';
		$viewLayout	= 'default';
		$view  =& $this->getView( $viewName, $viewType, '', array( 'base_path'=>$this->_basePath));
		$model =& $this->getModel('workflow');
		
		$workflow = $model->get( $id );
		
		// Set the layout
		$view->setLayout($viewLayout);

		// Display the view
		$view->display( $workflow );
	
	}
	
	/**
	 * Task handler (Save workflow to Database)
	 *
	 * @return void
	 */
	function save()
	{
		JRequest::checkToken() or jexit( 'Invalid Token' );

		require_once JPATH_COMPONENT.DS.'libs'.DS.'JSON.php';
		
		$params = JRequest::getVar( 'params', array(), 'post', 'array');
		$params['stations'] = array();
			
		$json = new Services_JSON();
		$stations = $json->decode($params['workflowData']);	
		foreach($stations as $s){
			$obj = $json->decode($s);
			$activeHooks = array();
			foreach($obj->activeHooks as $key => $hook){
				$activeHooks[$hook->name] = $hook;
				unset($obj->activeHooks[$key]->name);
			}
			$obj->activeHooks = base64_encode(serialize($activeHooks));                        
			$params['stations'][] = $obj;
		}


		// Get/Create Our model
		$model = & $this->getModel('workflow');
		
		//Send to the model to for saving
		$id = $model->save( $params );
		
		
		if( !$id ){
			$this->setRedirect('index.php?option=com_jwf', JText::_('Failed to save the workflow'), 'error');
		} else {
			$this->setRedirect('index.php?option=com_jwf', JText::_('Workflow saved'));
		}
	}
	
	/**
	 * Task handler (Delete workflow(s))
	 *
	 * @return void
	 */
	function delete()
	{
		JRequest::checkToken('get') or jexit( 'Invalid Token' );

		$cids = JRequest::getVar( 'cid', array(), 'get', 'array' );
		
		JArrayHelper::toInteger($cids);
		
		if (count($cids) ) {
			$this->setRedirect('index.php?option=com_jwf', JText::_('Select an item to delete'), 'error');
		}
		
		//Load Model
		$model = & $this->getModel('workflow');

		//Delete
		$model->delete( $cids );
		
		
		$this->setRedirect('index.php?option=com_jwf',  JText::_('Workflow(s) deleted'));
	}
	
	
	/**
	 * Task handler (Back)
	 *
	 * @return void
	 */
	function back(){
		$this->setRedirect('index.php?option=com_jwf');
	}
	
	/**
	 * Task handler (Cancel)
	 *
	 * @return void
	 */
	function cancel()
	{
		$params	= JRequest::getVar( 'params', array(), 'post', 'array');
		$id = intval( $params['id']);
		if( $id ){
			$model = & $this->getModel('workflow');
			$model->close($id);
		}
		$this->setRedirect('index.php?option=com_jwf',  JText::_('Action cancelled'), 'error');
	}
	
	/**
	 * Default task handler ( Displays workflows currently in the system )
	 *
	 * @access	public
	 */
	function display()
	{

		// Get/Create the model
		$model = & $this->getModel('workflow');
		$document =& JFactory::getDocument();
	
		//View preparation
		$viewType	= $document->getType();
		$viewName	= 'list';
		$viewLayout	= 'default';
		$view = & $this->getView( $viewName, $viewType, '', array( 'base_path'=>$this->_basePath));
		$view->setModel($model, true);
		$view->setLayout($viewLayout);
		$view->display();
	
	}
}
