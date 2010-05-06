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
class render_for_LISTBOX_data_type {
    //put your code here

   function render($id, $value = null, $label = null, $required = null, $params = null) {

        $req = $required == null ? '' : $required == 0 ? '' : 'required';
        $req == 'required' ? $reqLabel = '*' : $reqLabel = '';
        $value = explode(',', $value);

        if (!is_array($params['values_list'])) {
            $values = explode(',',$params['values_list']);
            foreach ($values as $k => $v) {
                $valuesList[$k] = new stdClass();
                $valuesList[$k]->key = $v;
                $valuesList[$k]->value = $v;
            }
        } else {
            $values = $params['values_list'];
            foreach ($values as $k => $v) {
                $valuesList[$k] = new stdClass();
                $valuesList[$k]->key = $v[0];
                $valuesList[$k]->value = $v[1];
            }
        }

        $return =
            '<div class="render">
                 <td class="key">
                    <label>'.$label.' '.$reqLabel.'</label>
                 </td>
                 <td colspan="2">'.
                    JHTML::_('select.genericlist', $valuesList, 'attr_'.$id, 'class="listbox" size="5"', 'value', 'value', $value )
                    .'<div id="valid_'.$id.'" style="float: right; color: red"></div>
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

