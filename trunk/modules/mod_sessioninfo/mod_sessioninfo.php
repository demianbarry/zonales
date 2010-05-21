<?php
$user =& JFactory::getUser();
$session =& JFactory::getSession();

if (!$user->guest):
    $profileLink = $params->get('profilelink');
    $greetingMessage = sprintf(JText::_('ZONALES_SESSION_GREETING'),$user->get('name'));
    $sessionCloseMessage = JText::_('ZONALES_SESSION_CLOSE');
    $logoutRoute = JRoute::_('index.php?option=com_user&task=logout');
    $protocol = $session->get('accessprotocol');
    require(JModuleHelper::getLayoutPath('mod_sessioninfo'));
endif
 ?>
<script text="text/javascript">
    function logout(){
        <?php 
        $session =& JFactory::getSession();
        $protocol = $session->get('accessprotocol');
        $logoutRoute = JRoute::_('index.php?option=com_user&task=logout');
        echo ($protocol == 'Facebook Connect') ? 'FB.Connect.logout(function() {  });' : '' ?>
        window.location.href='<?php echo $logoutRoute ?>';
    }
</script>
