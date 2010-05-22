<?php

class UserHelper {
    const ZONAL_NOT_DEFINED = 0;

    static function showMessage($type,$message,$baseUrl = null) {
        global $mainframe;

        $url = ($baseUrl == NULL) ? '' : $baseUrl . '&';
        $route = JRoute::_(
            "?$url"."status=" . $type . '&message=' . urlencode($message)
        );
        $mainframe->redirect($route);
    }
}

?>
