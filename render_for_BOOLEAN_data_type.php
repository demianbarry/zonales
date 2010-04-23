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

    function render($id, $value = null, $label = null, $params = null) {

        $return =
            '<td class="key">
                <label>'.$label.'</label>
             </td>
             <td colspan="2">'.
                JHTML::_('select.booleanlist', 'attr_'.$id, '', $value ).
            '</td>';

        return $return;
    }

    //Retorna donde se guarda el dato: value, value_int, value_double, value_date, value_boolean
    function getPrimitiveType() {
        return 'value_boolean';
    }

}
?>
