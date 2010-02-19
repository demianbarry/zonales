<?php
// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');
jimport( 'joomla.application.module.helper');

class UserViewZlogin extends JView {

    function display($tpl = null){
        $providerid = JRequest::getInt('providerid', '0', 'method');
        $externalid = JRequest::getVar('externalid', '', 'method', 'string');

        $moduleProviders = JModuleHelper::getModule('mod_zlogin');
        $htmlProviders = JModuleHelper::renderModule($moduleProviders);


        $this->assign('providerid',$providerid);
        $this->assign('externalid',$externalid);
        $this->assignRef('moduleproviders',$htmlProviders);
        parent::display($tpl);
    }
}

?>