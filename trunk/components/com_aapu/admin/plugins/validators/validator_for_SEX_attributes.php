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
class validator_for_SEX_attributes {
    //put your code here

    function validate($value) {

        if ($value == null || $value == '') {
            return '';
        }

        if ($value == 'M' || $value == 'F' || $value == 'm' || $value == 'f') {
            return '';
        }

        return 'Sexo no válido';
    }

}
?>
