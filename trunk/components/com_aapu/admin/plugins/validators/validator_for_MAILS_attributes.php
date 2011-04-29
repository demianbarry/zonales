<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/

/**
 * Description of validator_for_DATES_attributes
 *
 * @author nacho
 */
class validator_for_MAILS_attributes {
    //put your code here

    function validate($email) {

        if ($email == null || $email == '') {
            return '';
        }

        if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {
            return JText::_('INVALID_MAIL');
        }
        $email_array = explode("@", $email);
        $local_array = explode(".", $email_array[0]);
        for ($i = 0; $i < sizeof($local_array); $i++) {
            if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) {
                return JText::_('INVALID_MAIL');
            }
        }
        if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) {
            $domain_array = explode(".", $email_array[1]);
            if (sizeof($domain_array) < 2) {
                return JText::_('INVALID_MAIL');
            }
            for ($i = 0; $i < sizeof($domain_array); $i++) {
                if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i])) {
                    return JText::_('INVALID_MAIL');
                }
            }
        }
        return '';
    }

}
?>
