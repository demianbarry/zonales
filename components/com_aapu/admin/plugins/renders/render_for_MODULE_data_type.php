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

        $module = JModuleHelper::getModule( strtolower('mod_'.$params) );
        //$attribs['style'] = 'xhtml';

        $return = '<td>'.JModuleHelper::renderModule($module).'</td>';
        return $return;
    }

    //Retorna donde se guarda el dato: value, value_int, value_double, value_date, value_boolean
    function getPrimitiveType() {
        return 'value';
    }

}
?>
