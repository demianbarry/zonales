<?php

define('APPLY', 2);
define('SUGGEST', 1);
define('UNASSIGNED', 0);

class ModuleTagger {
    var $eq;
    var $_cplist;

    function getBands() {
    $controller = new EqZonalesControllerEq();

    $rawBands = $controller->retrieveUserEq();
    return $rawBands;

//    $userBands = array();
//    foreach ($rawBands as $info) {
//        $auxBands = $info->bands;
//
//        foreach ($auxBands as $band) {
//            if (!property_exists($userBands, $band->id)){
//                $userBands[] = $band->cp_value_id;
//            }
//        }
//    }
//
//    return $userBands;

}

    function setTree(&$value, $model) {
        $value->children = $model->getCachedChildren($value->id);
        if(!empty($value->children)) {
            foreach ($value->children as $child)
                $this->setTree($child, $model);
        }
    }

    function setJSTree(&$value, $str, $row) {

        $value .= $str. ' = new Array; ';
        $value .= $str.'[\'caption\'] = \''.$row->label.'\'; ';
        $value .= $str.'[\'id\'] = '.$row->id.';';
        $value .= $str.'[\'parent_id\'] = '.$row->parent_id.'; ';
        $value .= $str.'[\'field_id\'] = '.$row->field_id.'; ';

        // si el tag se encuentra entre los sugeridos
        // lo marco como sugerido
        $status = UNASSIGNED;

        if(in_array($row->id, $this->_cplist))
            $status = APPLY;

        $value .= $str.'[\'isChecked\'] =' . $status . ";";

        if(!empty($row->children)) {
            $value .= $str.'[\'children\'] = new Array;';
            $i = 0;
            foreach ($row->children as $child)
                $this->setJSTree($value, $str.'[\'children\']['.$i++.']', $child);
        }
    }

        function display() {

        global $mainframe;

        // CP fields
        $cp 		= $this->getModel('cpvalues');
        $cp->getAllHierarchical();
        $cpvalues	= $cp->getCachedRoots();

        $assign     = $this->getModel('assignhierarchic');
        $content_id = $assign->_id;
        $item_title = $assign->getTitle();
        $properties = $assign->getProperties();

        $this->_cplist = array();
        // Carga lista de tags asignados
        $userBands = $this->getBands();
        foreach ($userBands as $value){
            $this->eq = $value->eq->id;
            foreach ($value->band as $band) {
                $this->_cplist[] = $band->cp_value_id;
            }

        }
        $this->assignRef('_cplist',		$this->_cplist);


        $user = & JFactory::getUser();
        $aid = $user->get('aid',0);

        // Construye el objeto PHP que genera el arbol
        foreach ($cpvalues as $root) {
            $this->setTree($root, $cp);
        }


        $query = "SELECT DISTINCT f.*
                    FROM #__custom_properties_fields f
                    JOIN #__custom_properties_values v
                        ON (f.id = v.field_id AND v.parent_id IS NOT NULL)";

        $database = JFactory::getDBO();
        $database->setQuery($query);
        $fields = $database->loadObjectList();

        $j = 0;
        $jsCode = "";
        $divs = "";
        foreach ($fields as $field) {

            //Generamos codigo Javascript que inicializa los arboles
            $i = 0;
            $jsCode .= 'var a'.$j.' = new Array;';
            foreach ($cpvalues as $row) {
                if($field->id == $row->field_id) {
                    $this->setJSTree($jsCode, 'a'.$j.'['.$i.']', $row);
                    $i++;
                }
            }
            $jsCode .= "t$j = new Bs_Tree();";
            $jsCode .= "t$j.imageDir = '".JRoute::_('/media/system/js/tree/img/win98/')."';";
            $jsCode .= "t$j.checkboxSystemImgDir = '".JRoute::_('/media/system/js/checkbox/img/win2k_noBorder/')."';";
            $jsCode .= "t$j.useCheckboxSystem = true;";
            $jsCode .= "t$j.checkboxSystemWalkTree = 0;";
            $jsCode .= "t$j.useAutoSequence = false;";
            $jsCode .= "t$j.checkboxSystemIfPartlyThenFull = false;";
            $jsCode .= "t$j.useFolderIcon = false;";
            $jsCode .= "t$j.useLeaf = false;";
            $jsCode .= "t$j.initByArray(a$j);";
            $jsCode .= "t$j.drawInto('cp_values$j');";

            //Generamos codigo HTML que crea los divs donde aparecen los arboles.
            $divs .= "<b>$field->label</b>";
            $divs .= '<div id="cp_values'.$j.'" class="cp_values"></div>';

            $j++;
        }

        $out = array(
            'eqId' => $this->eq,
            'jsCode' => $jsCode,
            'divs' => $divs
        );
        return $out;
    }
}

$m = new ModuleTagger();
$data = $m->display();

?>
