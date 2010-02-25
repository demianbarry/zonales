<?php
$user =& JFactory::getUser();

if (!$user->guest):
    $profileLink = $params->get('profilelink');
    $greetingMessage = sprintf(JText::_('ZONALES_SESSION_GREETING'),$user->get('name'));
    $sessionCloseMessage = JText::_('ZONALES_SESSION_CLOSE');
    $logoutRoute = JRoute::_('index.php?option=com_user&task=logout');
    require(JModuleHelper::getLayoutPath('mod_sessioninfo'));
endif
 ?>
