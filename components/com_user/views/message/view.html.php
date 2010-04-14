<?php

// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

require_once 'constants.php';

class UserViewMessage extends JView {

    function display($tpl=null) {
        $status = JRequest::getInt('status', '0', 'method');
        $message = urldecode(JRequest::getString('message','','method'));

        switch ($status) {
            case ERROR:
                $icon = 'error.png';
                $class = 'error';
                $messageType = 'error';
                break;
            case WARNING:
                $icon = 'warning.png';
                $class = 'warning';
                $messageType = 'warning';
                break;
            case INFO:
                $icon = 'info.png';
                $class = 'info';
                $messageType = 'info';
                break;
            case ACTION:
                $icon = 'action.png';
                $class = 'action-required';
                $messageType = 'action-required';
                break;

            case SUCCESS:
            default:
                $icon = 'success.png';
                $class = 'success';
                $messageType = 'success';
                break;
        }

        $icon = 'images/message/' . $icon;

        $this->assign('message',$message);
        $this->assign('class',$class);
        $this->assign('icon',$icon);
        $this->assign('type',$messageType);

        parent::display($tpl);
    }
}

?>
