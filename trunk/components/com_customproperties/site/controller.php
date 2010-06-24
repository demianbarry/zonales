<?php
/**
 * Custom Properties for Joomla! 1.5.x
 * @package Custom Properties
 * @subpackage Component
 * @version 1.98
 * @revision $Revision: 1.4 $
 * @author Andrea Forghieri
 * @copyright (C) Andrea Forghieri, www.solidsystem.it
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
 */


// no direct access

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

/**
 * Custom Properties Component Controller
 *
 * @package    Custom Properties
 * @subpackage Component
 */
class CustompropertiesController extends JController {

    /**
     * constructor (registers additional tasks to methods)
     * @return void
     */
    function __construct() {
        parent::__construct();
        $this->registerTask('add', 		'assignCP');
        $this->registerTask('replace',	'assignCP');
    }

    /**
     * Method to display the view
     *
     * @access    public
     */
    function display() {

        $vName = JRequest::getCmd('view', 'show');
        $document = & JFactory::getDocument();
        $vType = $document->getType();

        switch ($vName) {
            case 'tagging' :

                $document->addStyleSheet('components/com_customproperties/css/customproperties.css');
                $vName = 'tagging';
                $vLayout = JRequest::getCmd('layout', 'default');
                // add dministrator views
                $this->addViewPath(JPATH_COMPONENT_ADMINISTRATOR . DS . 'views' . DS);
                $view = & $this->getView($vName, $vType);
                //add administrators models
                $this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR . DS . 'models' . DS);
                $view->setModel($this->getModel('assign', 'CustompropertiesModel'));
                $view->setModel($this->getModel('cpfields', 'CustompropertiesModel'));

                break;
            case 'hierarchictagging' :

                $document->addStyleSheet('components/com_customproperties/css/customproperties.css');
                $vName = 'hierarchictagging';
                $vLayout = JRequest::getCmd('layout', 'default');
                // add dministrator views
                $this->addViewPath(JPATH_COMPONENT_ADMINISTRATOR . DS . 'views' . DS);
                $view = & $this->getView($vName, $vType);
                //add administrators models
                $this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR . DS . 'models' . DS);
                $view->setModel($this->getModel('assignhierarchic', 'CustompropertiesModel'));
                $view->setModel($this->getModel('cpvalues', 'CustompropertiesModel'));

                break;
            case 'show' :
            default :
                $view 		=& $this->getView($vName, $vType);
                $vLayout 	= JRequest::getCmd('layout', 'default');
                $view->setModel($this->getModel('search', 'CustompropertiesModel'), true);
                break;
        }

        // Get/Create the view
        $view->addTemplatePath(JPATH_COMPONENT_ADMINISTRATOR . DS . 'views' . DS . strtolower($vName) . DS . 'tmpl');

        // Set the layout
        $view->setLayout($vLayout);

        // Display the view
        $view->display();

    }

    /** Method to assign custom properties from the frontend
     *
     * @returns void
     */
    function assignCP() {
        /*
		 * TODO This function is a verbatim copy of the function in backend's controller
		 * thus it is not DRY compliant :(
		 * sooner or later I'll fix it'
        */

        global $cp_config;
        require_once (JPATH_COMPONENT_ADMINISTRATOR . DS . 'cp_config.php');

        $this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR . DS . 'models' . DS);
        $model = $this->getModel('assign', 'CustompropertiesModel');

        $content_id = JRequest::getVar('cid', 0, '', 'int');
        $database 	= JFactory::getDBO();
        $user 		= JFactory::getUser();

        switch ($this->getTask()) {
            case 'add' :
                $action = 'add';
                break;
            case 'replace' :
                $action = 'replace';
                break;
            default :
                return;
        }

        $option 	= JRequest::getCmd('option'		, 'com_customproperties');
        $controller = JRequest::getCmd('controller'	, 'tagging');
        $view 		= JRequest::getCmd('view'		, 'tagging');

        $return_to = "index.php?option=$option&controller=$controller&view=$view&tmpl=component_only&cid=$content_id";

        if ($content_id === 0) {
            $this->setRedirect($return_to, JText::_('CP_ERR_INVALID_ID'), 'error');
            return;
        };
        if ($cp_config['frontend_tagging'] != 1) {
            $this->setRedirect($return_to, JText::_('CP_ERR_FUNCTION_DISABLED'), 'error');
            return;
        }
        if ($user->get('gid') < $cp_config['editing_level']) {
            $this->setRedirect($return_to, JText::_('CP_ERR_NOAUTH'), 'error');
            return;
        }

        $model->assignCustomProperties($action);
        $this->setRedirect($return_to);

    }

    function removeTags() {
        global $cp_config;
        require_once (JPATH_COMPONENT_ADMINISTRATOR . DS . 'cp_config.php');
        $database 	= JFactory::getDBO();
        $user 		= JFactory::getUser();

        if ($user->get('gid') < $cp_config['editing_level']) {
            echo JText::_('CP_ERR_DELTAG');
            return;
        }

        $valueId = JRequest::getVar('valueId', 0, '', 'int');
        $fieldId = JRequest::getVar('fieldId', 0, '', 'int');
        $content_table = JRequest::getVar('content_table', 'content', '', 'string');
        $content_id = JRequest::getVar('cid', 0, '', 'int');

        $query = "delete FROM #__custom_properties
                                        WHERE content_id = '$content_id'
                                        AND ref_table = $content_table
                                        AND value_id = $valueId
                                        AND field_id = $fieldId";

        $database->setQuery($query);
        $database->query();

        return ;
    }

    function searchTags() {
        global $cp_config;
        require_once (JPATH_COMPONENT_ADMINISTRATOR . DS . 'cp_config.php');
        $database 	= JFactory::getDBO();
        $user 		= JFactory::getUser();

        if ($user->get('gid') < $cp_config['editing_level']) {
            echo JText::_('CP_ERR_DELTAG');
            return;
        }

        $cid = JRequest::getVar('cid', 0, '', 'cid');
        $search_exp = JRequest::getVar('exp', '', '', 'string');
        $selectedIds = JRequest::getVar('selectedIds', '', '', 'string');

        $query = "SELECT v.id AS value_id, v.label AS value, f.id AS field_id, f.label AS field, p.label AS parent
                    FROM #__custom_properties_values v
                    JOIN #__custom_properties_fields f ON (f.id = v.field_id)
                    LEFT JOIN #__custom_properties_values p ON (p.id = v.parent_id)
                    WHERE v.label LIKE '%$search_exp%'
                    AND v.parent_id IS NOT NULL"
                . (strlen($selectedIds)>0 ? " AND v.id NOT IN ($selectedIds)" : "");

        $database->setQuery($query);
        $tags = $database->loadObjectList();
        $result = '';

        if(count($tags) > 0) {
            $result .=  "<table>";
            $result .=  "<thead id=\"tagsThead$cid\">
                            <tr>
                                <td style=\"width: 30%\"><b/><u/>".JText::_('Tag')."</td>
                                <td style=\"width: 30%\"><b/><u/>".JText::_('Parent')."</td>
                                <td style=\"width: 30%\"><b/><u/>".JText::_('Field')."</td>
                                <td style=\"width: 10%\"></td>
                            <tr>
                         </thead>";
            foreach ($tags as $tag) {
                $result .=  "<tr>
                                <td style=\"width: 30%\">$tag->value</td>
                                <td style=\"width: 30%\">$tag->parent</td>
                                <td style=\"width: 30%\">$tag->field</td>
                                <td style=\"width: 10%\"><input type=\"checkbox\" name=\"cp_".$tag->field_id."_".$tag->value_id."\" value=\"0\" onchange=\"this.value = (this.checked ? 2 : 0)\" / onclick=\"$('tagsSearchField$cid').focus();\"></td>
                            </tr>";

            }

            $result .=  "</table>";
        }
        echo $result;
        return ;
    }

    function addTags() {
        global $cp_config;
        require_once (JPATH_COMPONENT_ADMINISTRATOR . DS . 'cp_config.php');
        require_once(JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_customproperties' . DS . 'contentelement.class.php');
        $database 	= JFactory::getDBO();
        $user 		= JFactory::getUser();

        if ($user->get('gid') < $cp_config['editing_level']) {
            echo JText::_('CP_ERR_DELTAG');
            return;
        }

        $cid = JRequest::getVar('cid', 0, '', 'array');
        $ce_name = JRequest::getVar('ce_name', 0, '', 'string');
        $ce = getContentElementByName($ce_name);
        $ref_table = $ce->table;


        // retrieve cp_fields id
        foreach ($cid as $content_id) {
            $i = 0;
            foreach ($_POST as $key => $value) {
                if (strpos($key, 'cp_') === 0 && $value == 2) {
                    $field = $database->getEscaped(substr($key, 3, strpos($key, '_', 3)-3));
                    $value = $database->getEscaped(substr($key, strpos($key, '_', 3)+1));

                    $query = " REPLACE INTO #__custom_properties (ref_table, content_id,field_id,value_id)
                                        VALUES ('$ref_table',$content_id,$field,$value)";

                    $database->setQuery($query);
                    $database->query();
                }
                $i++;
            }
            if($i != 0 && $ref_table == 'content') {
                $article  =& JTable::getInstance('content');
                $query =    " UPDATE #__content ".
                            " SET modified = '".gmdate('Y-m-d H:i:s')."'".
                            " WHERE id = $content_id";

                $database->setQuery($query);
                $database->query();

                $dispatcher =& JDispatcher::getInstance();
                $dispatcher->trigger('onAfterContentSave', array(&$article,($article->id < 1)));
            }
        }

    }

    function getAllTags() {
        global $cp_config;
        require_once (JPATH_COMPONENT_ADMINISTRATOR . DS . 'cp_config.php');
        $database 	= JFactory::getDBO();
        $user 		= JFactory::getUser();

        if ($user->get('gid') < $cp_config['editing_level']) {
            echo JText::_('CP_ERR_DELTAG');
            return;
        }

        $cid = JRequest::getVar('cid', 0, '', 'cid');
        $search_exp = JRequest::getVar('exp', '', '', 'string');
        $selectedIds = JRequest::getVar('selectedIds', '', '', 'string');

        $query = "SELECT v.id AS value_id, v.label AS value, f.id AS field_id, f.label AS field, p.label AS parent "
                    ." FROM #__custom_properties_values v "
                    ." JOIN #__custom_properties_fields f ON (f.id = v.field_id)"
                    ." LEFT JOIN #__custom_properties_values p ON (p.id = v.parent_id)"
                    ." WHERE v.parent_id IS NOT NULL";

        $database->setQuery($query);
        $tags = $database->loadObjectList();

        echo json_encode($tags);
        return ;
    }
}