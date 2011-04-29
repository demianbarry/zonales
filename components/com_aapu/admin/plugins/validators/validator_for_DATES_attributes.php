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
class validator_for_DATES_attributes {
    //put your code here

    function validate($value) {
       
        if ($value == null || $value == '') {
            return '';
        }

        $anio = (int)substr($value, 0, 4);
        $mes = (int)substr($value, 5, 2);
        $dia = (int)substr($value, 8, 2);

        if ($anio >= 1900 && $anio <= 2010 && $mes >= 1 && $mes <= 12 && $dia >= 1) {
            switch ($mes) {
                case 2:
                    if ($dia <= 29) { return ''; }
                case 4:
                case 6:
                case 9:
                case 11:
                    if ($dia <= 30) { return ''; }
                default:
                    if ($dia <= 31) { return ''; }
            }
        }
         
       return JText::_ ('INVALID DATE');
      //   return 'La fecha no es válida';
    }

}
?>
