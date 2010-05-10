<?php

class UserHelper {

    static function showMessage($type,$message) {
        global $mainframe;
        
        $route = JRoute::_(
            '?status=' . $type . '&message=' . urlencode($message)
        );
        $mainframe->redirect($route);
    }
}

?>
