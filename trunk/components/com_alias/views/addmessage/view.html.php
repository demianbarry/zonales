<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

class AliasViewAddMessage extends JView {

    function display($tpl = null) {
        $status = JRequest::getInt('status', '0', 'method');
        $class = 'aliasmessage';
        $returnUrl = JRoute::_('?option=com_aapu');
        $returnMessage = JText::_('ZONALES_ALIAS_COMEBACK');

        switch ($status) {
            case 0:
                $message = JText::_('ZONALES_ALIAS_ADDED_SUCCESSFULLY');
                $class = $class . '-success';
                break;

            default:
                $message = JText::_('ZONALES_ALIAS_NOT_ADDED');
                $class = $class . '-error';
                break;
        }

        $this->assign('message',$message);
        $this->assign('class',$class);
        $this->assign('return',$returnUrl);
        $this->assign('returnmessage',$returnMessage);

        parent::display($tpl);
    }
}
?>
