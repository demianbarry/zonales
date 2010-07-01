<?php
require_once JPATH_ROOT . DS . 'components' . DS . 'com_zonales' . DS . 'helper.php';

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

    static function getZonal(){
        $helper = new comZonalesHelper();
        $user =& JFactory::getUser();
        $zonales = $helper->getZonales();

        $db = JFactory::getDBO();
        $query = "SELECT v.name FROM #__aapu_attributes a, #__aapu_attribute_entity e, #__custom_properties_values v WHERE e.attribute_id=a.id AND a.name='zonal' AND e.object_id=" . $user->id. " AND e.object_type='TABLE' AND object_name='#__users' AND v.id=e.value";
        $db->setQuery($query);
        $dbZonal = $db->loadObject();
        return $dbZonal->name;
    }

    static function setZonal($zonal){
        $session = JFactory::getSession();
        $session->set('zonales_zonal_name',$zonal);
    }
}

?>