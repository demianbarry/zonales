<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/
jimport( 'joomla.application.module.helper' );

/**
 * Description of render_for_TEXT_data_type
 *
 * @author nacho
 */
class render_for_MODULE_data_type {
    //put your code here

    function render($id, $value = null, $label = null, $required = null, $params = null) {

        $modName = $params['values_list'];

        $module = JModuleHelper::getModule( strtolower($modName) );

        //$return = print_r($params['values_list']);
        //return $return;

        if ($module == null) {
            $return = '<td>'.$label.': '.JText::_('Module not exist').' - '.JText::_('Module name').': '.$modName.'</td>';
        } else {
            $module->position = null;

            $return = '<div name="attr_'.$id.'" id="attr_'.$id.'">
                        <td>'.JModuleHelper::renderModule($module).'</td>
                      </div>';
        }
        return $return;
    }

    //Retorna donde se guarda el dato: value, value_int, value_double, value_date, value_boolean
    function getPrimitiveType() {
        return 'value';
    }

}
?>
