<?php

class UserHelper {

    static function showMessage($type,$message) {
        global $mainframe;
        
        $route = JRoute::_(
            'index.php?option=com_user&view=message&status=' .
            $type . '&message=' .
            urlencode($message)
        );
        $mainframe->redirect($route);
    }
}

?>
