<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/

/**
 * Description of validator_for_DOUBLES_attributes
 *
 * @author nacho
 */
class validator_for_DOUBLES_attributes {
    //put your code here

    //Retorna una cadena vacía en caso de ser correcta la validación, u otro mensaje en caso contrario
    function validate($value) {

        //Si no hay ningun valor valida como positivo (Si el atributo es required no valida por javascript)
        if ($value == null || $value == '') {
            return '';
        }

        return ($value == strval(floatval($value))) ? '' : 'Valor no válido';
    }

}