<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/

/**
 * Description of render_for_TEXT_data_type
 *
 * @author nacho
 */
class render_for_SEX_data_type {
    //put your code here

    function render($id, $value = null, $label = null, $required = null, $params = null) {

        $req = $required == null ? '' : $required == 0 ? '' : 'required';
       $req == 'required' ? $reqLabel = '*' : $reqLabel = '';

        $checkedF = '';
        $checkedM = '';

        if ($value != null) {
            if ($value == 'F') {
                $checkedF = 'checked';
            }
            if ($value == 'M') {
                $checkedM = 'checked';
            }
        }

        $return =
            '<div class="render">
                 <td class="key">
                    <label>'.$label.' '.$reqLabel.'</label>
                 </td>
                 <td colspan="2">
                    <input type="radio" name="sex_'.$id.'" id="sex_'.$id.'" value="F" onBlur=validate_attr(attr_'.$id.') onclick="this.form.attr_'.$id.'.value='."'F'".';"'.$checkedF.' > Femenino
                    <input type="radio" name="sex_'.$id.'" id="sex_'.$id.'" value="M" onBlur=validate_attr(attr_'.$id.') onclick="this.form.attr_'.$id.'.value='."'M'".';"'.$checkedM.' > Masculino
                    <input class="'.$req.'" type="hidden" name="attr_'.$id.'" id="attr_'.$id.'" value="'.$value.'" />
                    <div id="valid_'.$id.'" style="float: right; color: red"></div>
                 </td>
             </div>';

        return $return;
    }

    //Retorna donde se guarda el dato: value, value_int, value_double, value_date, value_boolean
    function getPrimitiveType() {
        return 'value';
    }

}
?>
