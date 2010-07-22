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
class render_for_ZONAL_data_type {
    //put your code here

    function render($id, $value = null, $label = null, $required = null, $params = null) {

        $req = $required == null ? '' : $required == 0 ? '' : 'required';
        $req == 'required' ? $reqLabel = '*' : $reqLabel = '';
        //$value = explode(',', $value);

        $attrib = array('class' => $req, 'onChange' => "validate_attr(attr_$id)");

        // helper zonales
        require_once (JPATH_ROOT.DS.'components'.DS.'com_zonales'.DS.'helper.php');
        //require_once (JPATH_BASE.DS.'components'.DS.'com_zonales'.DS.'controller.php');
        //$zController = new ZonalesController();
        $helper = new comZonalesHelper();
        $selection = new stdClass();

        $zonal = $helper->getZonalById($value);
        $localidades = array();

        if ($zonal != null) {
            $selectedOption = $zonal->id;
            $selectedParent = $zonal->parent_id;
        } else {
            $selectedOption = 0;
            $selectedParent = 0;
        }
        
        // parametros
        $root = $helper->getRoot();

        // crea select de zonales disponibles
        $parents = $helper->getItems($root);
        $provincias = JHTML::_('select.genericlist', $parents, 'aapu_provincias',
                'size="1" class="aapu_provincias_select required"', 'id', 'label', $selectedParent);

        $return =
            '<script language="javascript" type="text/javascript">
                <!--

                window.addEvent(\'domready\', function() {
                    $(\'aapu_provincias\').addEvent(\'change\', function() {
                        aapu_loadMunicipios();
                    });

                    aapu_loadMunicipios('.$selectedOption.');
                });

                function aapu_loadMunicipios(selected){
                    $(\'aapu_z_localidad_container\').empty().addClass(\'ajax-loading\');
                    var url=\'index.php?option=com_zonales&format=raw&task=getItemsAjax&id=\'+$(\'aapu_provincias\').value+\'&name=attr_'.$id.'&selected=\'+selected;
                    new Ajax(url, {
                        method: \'get\',
                        onComplete: function(response) {
                            $(\'aapu_z_localidad_container\').removeClass(\'ajax-loading\').setHTML(response);
                            $(\'aapu_municipio_container\').setStyle(\'display\',\'block\');
                        }
                    }).request();
                }
                //-->
            </script>
            <div class="render">
                <div id="aapu_z_provincias_container">
                    <td class="key">
                        <p><label class="combo_zonal_label">Provincia</label></p>
                    </td>
                    <td colspan="2">'.
                        $provincias.'
                    </td>
                </div>
                </tr>
                <tr>
                <div id="aapu_municipio_container" style="display: none;">
                    <td class="key">
                        <p><label class="combo_zonal_label">Municipio</label></p>
                    </td>
                    <td colspan="2">
                        <div id="aapu_z_localidad_container"></div>
                    </td>
                    <br/>
                </div>
            </div>';

        return $return;
    }

    //Retorna donde se guarda el dato: value, value_int, value_double, value_date, value_boolean
    function getPrimitiveType() {
        return 'value_int';
    }

}
?>
