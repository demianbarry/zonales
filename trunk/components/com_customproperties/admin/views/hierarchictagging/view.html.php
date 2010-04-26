<?php
/**
 * Custom Properties for Joomla! 1.5.x
 * @package Custom Properties
 * @subpackage Component
 * @version 1.98
 * @revision $Revision: 1.3 $
 * @author Andrea Forghieri
 * @copyright (C) Andrea Forghieri, www.solidsystem.it
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.view');
/**
 * Frontend tagging View
 *
 * @package    Custom Properties
 * @subpackage Components
 */
class CustompropertiesViewHierarchictagging extends JView {

    /**
     * CPs list
     *
     * @var array
     */
    var $_cplist;

    var $_suggestList;

    function setSuggestTags($documentId) {
//        $params = &JComponentHelper::getParams( 'com_customproperties' );
//        $url = $params->get('taganalizerurl');
        $url = 'http://192.168.0.29:39229/TomcatServletExample/servlet/TagsAnalizer/';
        $req =& new HTTPRequest($url,HTTP_METH_POST);

        // recupero todos los tags registrados
        $query = 'select v.name from #__custom_properties_values v ';
        $db = JFactory::getDBO();
        $db->setQuery($query);
        $tagsDb = $db->loadObjectList();

        $tagsRaw = array();
        foreach ($tagsDb as $currentTag) {
            $tagsRaw[] = $currentTag->name;
        }
        $tags = implode(',', $tagsRaw);

        // preparo los datos a enviar
        $postData = array(
            'id' => $documentId,
            'content' => 'content',
            'tags' => $tags
        );

        $req->setPostFields($postData);

        // envio la solicitud
        $req->send();

        if ($req->getResponseCode() != 200){
            throw new Exception('we had a comunication problem');
        }

        // recupero los tags
        $rawResponse = $req->getResponseData();
        $responseTags = $rawResponse['body']['tags'];

        // parseo los tags y los guardo
        $this->_suggestList = explode(',', $responseTags);
    }

    function setTree(&$value, $model) {
        $value->children = $model->getCachedChildren($value->id);
        if(!empty($value->children)) {
            foreach ($value->children as $child)
                $this->setTree($child, $model);
        }
    }

    function setJSTree(&$value, $str, $row) {
        define('APPLY', 2);
        define('SUGGEST', 1);
        define('UNASSIGNED', 0);

        $value .= $str. ' = new Array; ';
        $value .= $str.'[\'caption\'] = \''.$row->label.'\'; ';
        $value .= $str.'[\'id\'] = '.$row->id.';';
        $value .= $str.'[\'parent_id\'] = '.$row->parent_id.'; ';
        $value .= $str.'[\'field_id\'] = '.$row->field_id.'; ';

        // si el tag se encuentra entre los sugeridos
        // lo marco como sugerido
        $status = UNASSIGNED;
        if(in_array($row->id, $this->_suggestList))
            $status = SUGGEST;

        if(in_array($row->id, $this->_cplist))
            $status = APPLY;

        $value .= $str.'[\'isChecked\'] =' . $status . "';";

        if(!empty($row->children)) {
            $value .= $str.'[\'children\'] = new Array;';
            $i = 0;
            foreach ($row->children as $child)
                $this->setJSTree($value, $str.'[\'children\']['.$i++.']', $child);
        }
    }

    function getAssignedTags($ref_table, $content_id) {
        $database = JFactory::getDBO();

        if($content_id != 0) { // show current content's properties
            $query = "SELECT cp.value_id as value_id
			FROM #__custom_properties cp
				JOIN #__custom_properties_values v
				 ON (cp.value_id = v.id AND v.parent_id IS NOT NULL)
			WHERE cp.ref_table = '$ref_table'
                        AND cp.content_id = '$content_id'";
        }

        $database->setQuery($query);
        return $database->loadObjectList();
    }

    /**
     * CP Fields assign method
     * @return void
     **/
    function display($tpl = null) {

        global $mainframe,$cp_config;
        require_once(JPATH_COMPONENT_ADMINISTRATOR .DS.'cp_config.php');

        // content element
        $ce_name		 		= JRequest::getVar('ce_name', null, '', 'string');
        if(! $content_element 	= getContentElementByName("$ce_name")) {
            $content_element 	= getContentElementByName("content");
            $ce_name			= 'content';
        };

        // CP fields
        $cp 		= $this->getModel('cpvalues');
        $cp->getAllHierarchical();
        $cpvalues	= $cp->getCachedRoots();

        $assign 	= $this->getModel('assignhierarchic');
        $content_id = $assign->_id;
        $item_title = $assign->getTitle();
        $properties = $assign->getProperties();

        $this->_cplist = array();
        // Carga lista de tags asignados
        foreach ($this->getAssignedTags($ce_name, $content_id) as $value)
            $this->_cplist[] = $value->value_id;
        $this->assignRef('_cplist',		$this->_cplist);


        $user = & JFactory::getUser();
        $aid = $user->get('aid',0);

        if(	$mainframe->isSite() && ($cp_config['frontend_tagging'] != '1') ) {
            JError::raiseError( 500, JText::_( 'CP_ERR_FUNCTION_DISABLED' ) );
        }
        if($aid < $cp_config['editing_level']) {
            JError::raiseError( 500, JText::_( 'CP_ERR_NOAUTH' ) );
        }
        if(empty($content_id) ) {
            JError::raiseError( 500, JText::_( 'CP_ERR_INVALID_ID' ) );
        }

        $this->setSuggestTags($content_id);

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
            $jsCode .= "t$j.imageDir = '/zonales/media/system/js/tree/img/win98/';";
            $jsCode .= "t$j.checkboxSystemImgDir = '/zonales/media/system/js/checkbox/img/win2k_noBorder/';";
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

        $this->assignRef('content_element',     $content_element);
        $this->assignRef('ce_name',		$ce_name);
        $this->assignRef('jsCode',		$jsCode);
        $this->assignRef('divs',		$divs);
        $this->assignRef('content_id',		$content_id);
        $this->assignRef('item_title',		$item_title);
        $this->assignRef('properties',		$properties);

        parent::display($tpl);
    }
}
