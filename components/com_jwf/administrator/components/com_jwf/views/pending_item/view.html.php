<?php
/**
 * Pending Item View for JWF Component
 *
 * @version		$Id: view.html.php 1407 2009-08-13 12:42:36Z mostafa.muhammad $
 * @package		Joomla
 * @subpackage	JWF
 * @copyright	Copyright (C) 2009 Mostafa Muhammad. All rights reserved.
 * @license		GNU/GPL
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );

/**
 * List View
 *
 * @package    Joomla
 * @subpackage JWF
 */
class JWFViewPending_item extends JView {
    /**
     * List view display method
     *
     * Displays a list of all workflows available in the database
     *
     * @return void
     **/
    function display($items,$workflow, $fieldsData, $currentStationId, $activeFields) {


        JHTML::_('JWF.reloadMootools');
        JHTML::_('script', 'utilities.js', 'media/com_jwf/scripts/');


        if(defined('JWF_FRONTEND_RUNNING')) {
            JHTML::_('stylesheet', 'pending_item-frontend.css', 'media/com_jwf/css/');
            JHTML::_('JWF.backButton');
            JHTML::_('JWF.title' ,     JText::_( 'JWF' ), 'jwf-logo');
        } else {
            JHTML::_('stylesheet', 'pending_item-backend.css', 'media/com_jwf/css/');
            JRequest::setVar('hidemainmenu', 1);
            JToolBarHelper::title(   JText::_( 'JWF' ), 'jwf-logo' );
            JToolBarHelper::back();
        }
        $pManager =& getPluginManager();
        $pManager->loadPlugins('field');

        foreach( $activeFields as $f ) {
            $inputData = array_key_exists( $f, $fieldsData )?$fieldsData[$f]:null;
            $response  = $pManager->invokeMethod( 'field', 'display',  array($f), array($items, $workflow, $inputData) );
            $fieldsHTML[$f]  = $response[$f];
        }

        $nextStation     = null;
        $previousStation = null;
        $currentStation  = $workflow->stations[$currentStationId];
        $currentStationOrder = $workflow->stations[$currentStationId]->order;

        foreach( $workflow->stations as $s ) {
            if( $s->order == $currentStationOrder+1)
                $nextStation     = $s;
            if( $s->order == $currentStationOrder-1)
                $previousStation = $s;
        }


        //Send data to the view
        $this->assignRef('items'   , $items );
        $this->assignRef('workflow', $workflow );
        $this->assignRef('fields'  , $fieldsHTML );
        $this->assignRef('nextStation'  , $nextStation );
        $this->assignRef('previousStation'  , $previousStation );
        $this->assignRef('currentStation'  , $currentStation );


        //Display the template
        parent::display(null);
    }
}
