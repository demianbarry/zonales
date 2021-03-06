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
class render_for_DATE_data_type {
    //put your code here

    function render($id, $value = null, $label = null, $required = null, $params = null) {

        $req = $required == null ? '' : $required == 0 ? '' : 'required';
        $req == 'required' ? $reqLabel = '*' : $reqLabel = '';

        $attrib = array('class' => $req, 'onBlur' => 'validate_attr(attr_'.$id.')');

        $return =
            '<div class="render">
                 <td class="key">
                    <label>'.$label.' '.$reqLabel.'</label>
                 </td>
                 <td colspan="2">'.
                     JHTML::calendar($value, 'attr_'.$id, 'attr_'.$id,'%Y-%m-%d',$attrib).
                    '<div id="valid_'.$id.'" style="float: right; color: red"></div>
                 </td>
             </div>';

        return $return;
    }

    //Retorna donde se guarda el dato: value, value_int, value_double, value_date, value_boolean
    function getPrimitiveType() {
        return 'value_date';
    }

}
?>
