<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');
jimport( 'joomla.application.module.helper');

class AliasViewAddMessage extends JView {

    function display($tpl = null) {
        $status = JRequest::getInt('status', '0', 'method');
        $class = 'aliasmessage';
        $returnUrl = JRoute::_('index.php');
        $returnMessage = JText::_('Go home');

        switch ($status) {
            case 0:
                $message = JText::_('Your identifier has been added successfully as your new alias');
                $class = $class . '.success';
                break;

            default:
                $message = JText::_('Your identifier could not be added as your new alias');
                $class = $class . '.error';
                break;
        }

        $this->assignRef('message',$message);
        $this->assignRef('class',$class);
        $this->assignRef('return',$returnUrl);
        $this->assignRef('returnmessage',$returnMessage);
    }
}
?>
