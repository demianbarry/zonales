<?php

$status = JRequest::getInt('status', '0', 'method');
$message = urldecode(JRequest::getString('message','','method'));

if ($message != '') {
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

    $returnURL = JRoute::_('index.php');
    $returnMessage = JText::_('SYSTEM_RETURN_MESSAGE');

    require(JModuleHelper::getLayoutPath('mod_message'));
}

?>
