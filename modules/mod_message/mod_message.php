<?php

require_once 'constants.php';

$session =& JFactory::getSession();
$status = (int) $session->get(MMSTATUS);
$messageRaw = urldecode($session->get(MMMESSAGE));

$session->set(MMSTATUS,'0');
$session->set(MMMESSAGE,urlencode(''));

if ($messageRaw != '') {
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

    $message = JText::_($messageRaw);

    $icon = 'images/message/' . $icon;

    $returnURL = JRoute::_('index.php');
    $returnMessage = JText::_('SYSTEM_RETURN_MESSAGE');

    require(JModuleHelper::getLayoutPath('mod_message'));
}

?>
