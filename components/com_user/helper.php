<?php
require_once JPATH_ROOT . DS . 'components' . DS . 'com_zonales' . DS . 'helper.php';
require_once 'constants.php';

class UserHelper {
    const ZONAL_NOT_DEFINED = 0;

    static function showMessage($type,$message,$baseUrl = null) {
        global $mainframe;

        $url = ($baseUrl == NULL) ? '' : $baseUrl;
        $route = JRoute::_("?$url");
        $session =& JFactory::getSession();
        $session->set(MMSTATUS,$type);
        $session->set(MMMESSAGE,urlencode($message));
        $mainframe->redirect($route);
    }

    static function getZonal() {
        $helper = new comZonalesHelper();
        $user =& JFactory::getUser();
        $zonales = $helper->getZonales();

        $db = JFactory::getDBO();
        $query = "SELECT v.name FROM #__aapu_attributes a, #__aapu_attribute_entity e, #__custom_properties_values v WHERE e.attribute_id=a.id AND a.name='zonal' AND e.object_id=" . $user->id. " AND e.object_type='TABLE' AND object_name='#__users' AND v.id=e.value";
        $db->setQuery($query);
        $dbZonal = $db->loadObject();
        return $dbZonal->name;
    }

    static function setZonal($zonal) {
        $session = JFactory::getSession();
        $session->set('zonales_zonal_name',$zonal);
    }

    static function isEmailAddressWellformed($email) {
        // First, we check that there's one @ symbol,
        // and that the lengths are right.
        if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {
            // Email invalid because wrong number of characters
            // in one section or wrong number of @ symbols.
            return false;
        }
        // Split it into sections to make life easier
        $email_array = explode("@", $email);
        $local_array = explode(".", $email_array[0]);
        for ($i = 0; $i < sizeof($local_array); $i++) {
            if
            (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&
↪'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$",
            $local_array[$i])) {
                return false;
            }
        }
        // Check if domain is IP. If not,
        // it should be valid domain name
        if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) {
            $domain_array = explode(".", $email_array[1]);
            if (sizeof($domain_array) < 2) {
                return false; // Not enough parts to domain
            }
            for ($i = 0; $i < sizeof($domain_array); $i++) {
                if
                (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|
↪([A-Za-z0-9]+))$",
                $domain_array[$i])) {
                    return false;
                }
            }
        }
        return true;
    }

    static function emailExists($email) {
        $r = 0;
        if (!is_null($email)) {
            $db = JFactory::getDBO();
            $query = "SELECT count(*) FROM #__users u WHERE u.email='$email'";
            $db->setQuery($query);
            $r = $db->loadResult();
        }

        return ($r > 0);
    }

    static function checkDateFormat($date) {
        //match the format of the date
        if (preg_match ("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/", $date, $parts)) {
            //check weather the date is valid of not
            if(checkdate($parts[2],$parts[3],$parts[1]))
                return true;
            else
                return false;
        }
        else
            return false;
    }

}

?>