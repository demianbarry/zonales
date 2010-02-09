<?php
// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

class UserViewUserStatusRequest extends JView {

    function display($tpl = null){
        $externalid = JRequest::getVar('externalid','','method','string');
        $providerid = JRequest::getVar('providerid','','method','int');

        $this->assignRef('externalid', $externalid);
	$this->assignRef('providerid',$providerid);
        parent::display($tpl);
    }
}

?>
