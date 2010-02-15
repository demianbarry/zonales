<?php
/**
 * Item controller for JWF Component
 *
 * @version		$Id: item.php 1480 2009-08-24 15:11:16Z mostafa.muhammad $
 * @package		Joomla
 * @subpackage	JWF.controllers
 * @copyright	Copyright (C) 2009 Mostafa Muhammad. All rights reserved.
 * @license		GNU/GPL
 */

jimport('joomla.application.component.controller');


/**
 * Item Controller : This controller manages tasks that are performed on items like moving forward/backward, viewing history, etc.
 *
 * @package    Joomla
 * @subpackage	JWF.controllers
 */
class JWFControllerItem extends JController {

    /**
     * constructor (registers additional tasks to methods)
     *
     * @return void
     */
    function __construct( $config=array() ) {

        parent::__construct( $config );
        // Register Extra tasks
        JModel::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models');
        $this->registerTask( 'forward'  , 'moveForward');
        $this->registerTask( 'backward' , 'moveBackward');
        $this->registerTask( 'save'     , 'save');
        $this->registerTask( 'history'     , 'history');
        $this->registerTask( 'edit'     , 'edit');
        $this->registerTask( 'cancel'   , 'cancel');
    }

    /**
     * Task Handler ( Edit an item/task "Displays pending_item view" )
     *
     * @return void
     */
    function edit() {

        $document =& JFactory::getDocument();

        $wid = JRequest::getInt( 'wid', 0 );
        $iid = JRequest::getInt( 'iid', 0 );


        $viewType	= $document->getType();
        $viewName	= 'pending_item';
        $viewLayout	= 'default';
        $view  =& $this->getView( $viewName, $viewType, '', array( 'base_path'=>$this->_basePath));

        $iModel =& $this->getModel('item');
        $wModel =& $this->getModel('workflow');
        $fModel =& $this->getModel('field');

        $items    = $iModel->get( $wid, $iid );
        $workflow = $wModel->get( $wid );
        $fields   = null;
        $currentStationId = 0;
        foreach($items as $item) {
            if( $item->current == 1 ) {
                $currentStepId    = $item->id;
                $currentStationId = $item->sid;
                break;
            }
        }

        $fieldData    = $fModel->get( $wid, $iid );
        $activeFields = explode(',', $workflow->stations[$currentStationId]->fields);

        // Set the layout
        $view->setLayout($viewLayout);

        // Display the view
        $view->display( $items, $workflow, $fieldData, $currentStationId,  $activeFields);
    }

    /**
     * Task Handler ( Moves an item to the next station )
     *
     * @return void
     */
    function moveForward() {

        $pManager =& getPluginManager();
        $pManager->loadPlugins('hook');
        $pManager->loadPlugins('component');
        $pManager->loadPlugins('validation');

        $iModel =& $this->getModel('item');
        $wModel =& $this->getModel('workflow');

        $wid     = JRequest::getInt( 'wid', 0 );
        $iid     = JRequest::getInt( 'iid', 0 );


        $workflow = $wModel->get($wid);
        $steps    = $iModel->get($wid,$iid);

        $response  = $pManager->invokeMethod( 'component', 'getLatestRevisionId',  array($workflow->component),array($iid) );
        $version   = $response[$workflow->component];


        if( !$workflow || !$steps || !$iid ) {
            JError::raiseError( 500, JText::_('Invalid input') );
        }
        //Steps is an order array , last element is the current position
        list($currentStep) = array_values(array_reverse($steps));
        $currentStation = $workflow->stations[$currentStep->sid];
        $nextStation    = null;

        //Determine next station
        $flag = false;
        foreach($workflow->stations as $s) {
            if( $flag ) {
                $nextStation = $s;
                break;
            }
            if( $currentStation->id == $s->id )$flag = true;
        }

        //Validations of the current station are invoked
        $activeValidations = array();
        $activeValidations = split(",",$currentStation->activeValidations);
        if(count($validationMsg = $pManager->invokeMethod('validation', 'onDeparted',  $activeValidations, array($workflow,$nextStation,$currentStep))) != 0) {
            $document =& JFactory::getDocument();
            $viewType	= $document->getType();
            $viewName	= 'validation_errors';
            $viewLayout	= 'default';
            $view  =& $this->getView( $viewName, $viewType, '', array( 'base_path'=>$this->_basePath));

            // Set the layout
            $view->setLayout($viewLayout);

            // Display the view
            $view->display($validationMsg);
            return;
        }


        if( $nextStation == null )return;

        $iModel->move( $wid, $nextStation->id, $iid, $currentStep->title, $version );

        //Lock/Unlock (placeholder)
        $aclSystemId = $workflow->acl;
        $allowedGid  = intval($nextStation->group);
        $pManager->invokeMethod( 'component', 'lock',  array($workflow->component),array($iid,$aclSystemId));
        $pManager->invokeMethod( 'component', 'unlock',  array($workflow->component),array($iid,$aclSystemId,$allowedGid));

        //Create history entry
        $historyModel =& $this->getModel('history');

        $historyTitle = JText::_('Moved forward');
        $historyMsg   = new stdClass();
        $historyMsg->direction  = 'forward';
        $historyMsg->version    = $version;
        $historyMsg->destination= base64_encode(serialize($nextStation));

        $historyModel->add( $workflow->id,
                $currentStation,
                $iid,
                'core.move',
                $historyTitle,
                $historyMsg);

        //Done creating history entry

        //Hooks of the destination station are invoked
        $activeHooks = unserialize(base64_decode($nextStation->activeHooks));
        print_r($activeHooks);
        foreach( $activeHooks as $hookId => $hookData ) {
            $pManager->invokeMethod( 'hook', 'onArrival',  array($hookId), array($hookData,$workflow,$nextStation,$currentStep) );
        }

        $this->setRedirect('index.php?option=com_jwf&controller=item', JText::_('Moved successfuly'));
    }

    /**
     * Task Handler ( Moves an item to the previous station )
     *
     * @return void
     */
    function moveBackward() {

        $pManager =& getPluginManager();
        $pManager->loadPlugins('hook');
        $pManager->loadPlugins('component');


        $iModel =& $this->getModel('item');
        $wModel =& $this->getModel('workflow');


        $wid     = JRequest::getInt( 'wid', 0 );
        $iid     = JRequest::getInt( 'iid', 0 );


        $workflow = $wModel->get($wid);
        $steps    = $iModel->get($wid,$iid);

        $response  = $pManager->invokeMethod( 'component', 'getLatestRevisionId',  array($workflow->component),array($iid) );
        $version   = $response[$workflow->component];

        if( !$workflow || !$steps || !$iid ) {
            JError::raiseError( 500, JText::_('Invalid input') );
        }

        //Steps is an order array , last element is the current position
        list($currentStep) = array_values(array_reverse($steps));
        $currentStation    = $workflow->stations[$currentStep->sid];
        $previousStation   = null;

        //Determine previous station
        $flag = false;
        $reversedStations =  array_reverse($workflow->stations);
        foreach($reversedStations as $s) {
            if( $flag ) {
                $previousStation = $s;
                break;
            }
            if( $currentStation->id == $s->id )$flag = true;
        }
        if( $previousStation == null )return;

        //Lock/Unlock (placeholder)
        $aclSystemId = $workflow->acl;
        $allowedGid  = intval($previousStation->group);
        $pManager->invokeMethod( 'component', 'lock',  array($workflow->component),array($iid,$aclSystemId));
        $pManager->invokeMethod( 'component', 'unlock',  array($workflow->component),array($iid,$aclSystemId,$allowedGid));

        $iModel->move( $wid, $previousStation->id, $iid, $currentStep->title, $version );

        //Create history entry
        $historyModel =& $this->getModel('history');

        $historyTitle = JText::_('Moved backward');
        $historyMsg   = new stdClass();
        $historyMsg->direction  = 'backward';
        $historyMsg->version    = $version;
        $historyMsg->destination= base64_encode(serialize($previousStation));

        $historyModel->add( $workflow->id,
                $currentStation,
                $iid,
                'core.move',
                $historyTitle,
                $historyMsg);
        //Done creating history entry



        //Hooks of the destination station are invoked
        $activeHooks = unserialize(base64_decode($previousStation->activeHooks));
        foreach( $activeHooks as $hookId => $hookData ) {
            $pManager->invokeMethod( 'hook', 'onArrival',  array($hookId), array($hookData,$workflow,$previousStation,$currentStep) );
        }

        $this->setRedirect('index.php?option=com_jwf&controller=item', JText::_('Moved successfuly'));

    }

    function save() {/* stub */

    }

    /**
     * Task Handler ( Cancel , Moves the user back to the pending items view )
     *
     * @return void
     */
    function cancel() {
        $this->setRedirect('index.php?option=com_jwf&controller=item', JText::_('Action cancelled'));
    }

    /**
     * Task Handler ( View history of a given item )
     *
     * @return void
     */
    function history() {


        $app      =& JFactory::getApplication('site');
        $document =& JFactory::getDocument();
        $pManager =& getPluginManager();

        $wid     = JRequest::getInt( 'wid', 0 );
        $iid     = JRequest::getInt( 'iid', 0 );

        $workflowModel =& $this->getModel('workflow');
        $workflow = $workflowModel->get( $wid );

        if( !$workflow )
            JError::raiseError( 404, JText::_("Workflow not found"));


        /* Authorization */
        $pManager->loadPlugins('acl');
        $response = $pManager->invokeMethod( 'acl', 'getMyGroupId',  array($workflow->acl),null );
        $userGroups = $response[$workflow->acl];
        $user     =& JFactory::getUser();

        if( $user->guest )
            JError::raiseError( 403, JText::_("Access Forbidden") );

        if(	!canManageWorkflows() && !in_array($workflow->admin_gid,array_keys($userGroups)))
            JError::raiseError( 403, JText::_("Access Forbidden"));
        /* User is autorized */

        $historyModel =& $this->getModel('history');
        $itemHistory = $historyModel->get( $wid, $iid );

        if( !$itemHistory )
            JError::raiseError( 404, JText::_("Item not found or No history stored for this item"));



        /* Prepare and display the view */
        $viewType	= $document->getType();
        $viewName	= 'history';
        $viewLayout	= 'default';

        $view = & $this->getView( $viewName, $viewType, '', array( 'base_path'=>$this->_basePath));


        // Set the layout
        $view->setLayout($viewLayout);

        //Display the view
        $view->display($itemHistory, $workflow);

    }

    /**
     * Default Task Handler ( Displays pending tasks for the logged in user )
     *
     * @return void
     */
    function display() {


        //Prepare View

        $document =& JFactory::getDocument();
        $viewType	= $document->getType();
        $viewName	= 'pending_items';
        $viewLayout	= 'default';
        //$templatePath = JWF_BACKEND_PATH.DS.'views'.DS.$viewName.DS.'tmpl';
        $view = & $this->getView( $viewName, $viewType, '',  array( 'base_path'=>$this->_basePath));
        $view->setLayout($viewLayout);

        //Load
        $pManager =& getPluginManager();
        $pManager->loadPlugins('acl');
        $aclPairs = array();
        foreach($pManager->settings['acl'] as $handler) {
            $id        = $handler->id;
            $response  = $pManager->invokeMethod( 'acl', 'getMyGroupId',  array($id),null );
            $aclPairs[$id] = $response[$id];
        }

        // Get/Create the model
        $iModel =& $this->getModel('item');
        $items  = $iModel->search( 0, 0, $aclPairs, '', true );


        // Display the view
        $view->display($items ,$aclPairs);
    }
}
