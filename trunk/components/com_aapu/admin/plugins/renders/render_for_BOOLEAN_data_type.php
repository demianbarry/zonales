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
class render_for_BOOLEAN_data_type {
    //put your code here

    function render($id, $value = null, $label = null, $required = null, $params = null) {

        $req = $required == null ? '' : $required == 0 ? '' : 'required';
        $req == 'required' ? $reqLabel = '*' : $reqLabel = '';
        $attrib = array('class' => $req, 'onBlur' => 'validate_attr(attr_'.$id.')');
        $checked = '';

        if ($value == '1') {
            $checked = 'checked';
        }

        $return =
            '<div class="render">
                 <td class="key">
                    <label>'.$label.' '.$reqLabel.'</label>
                 </td>
                 <td colspan="2">
                    <input type="checkbox" name="boolean_attr" id="boolean_attr" onchange="this.form.attr_'.$id.'.value=this.checked ? 1 : 0" '.$checked.'/>
                    <input class="'.$req.'" type="hidden" name="attr_'.$id.'" id="attr_'.$id.'" value="'.$value.'" />
                </td>
             </div>';

        return $return;
    }

    //Retorna donde se guarda el dato: value, value_int, value_double, value_date, value_boolean
    function getPrimitiveType() {
        return 'value_boolean';
    }

}
?>
