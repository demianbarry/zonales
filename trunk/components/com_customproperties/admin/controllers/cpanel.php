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
 * Custom Properties Component Controller - About and config
 *
 * @package    Custom Properties
 * @subpackage Components
 */
class CustompropertiesControllerCpanel extends JController {

    /**
     * constructor (registers additional tasks to methods)
     * @return void
     */
    function __construct() {
        parent :: __construct();

    }

    /**
     * Method to display the view
     *
     * @access    public
     */
    function display() {
        $this->addViewPath(JPATH_COMPONENT_ADMINISTRATOR . DS . 'views' . DS);
        $this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR . DS . 'models' . DS);
        switch ($this->getTask()) {
            case 'configure' :
                JRequest :: setVar('layout', 'default');
                JRequest :: setVar('view', 'cpanel');
                break;
            case 'tags' :
            case 'getAllTags' :
                JRequest :: setVar('layout', 'default');
                JRequest :: setVar('view', 'tags');
                break;
            case 'about' :
            default :
                JRequest :: setVar('layout', 'about');
                JRequest :: setVar('view', 'cpanel');
                break;
        }
        parent :: display();
    }

    function saveConfig() {
        $model = & $this->getModel('config');
        $link = "index.php?option=com_customproperties&controller=cpanel&task=configure";
        if ($model->saveConfig()) {
            $msg = JText :: _('Configuration saved');
            $msg_type = 'message';
        } else {
            $msg = implode(',', $this->getErrors());
            $msg = JText :: _('ERRCANTSAVECFG') . $msg;
            $msg_type = 'error';
        }
        $this->setRedirect($link, $msg, $msg_type);
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
        require_once(JPATH_ROOT . DS . 'components' . DS . 'com_customproperties' . DS . 'controller.php');
        $cp = new CustompropertiesController();
        $cp->removeTags();
    }

    function searchTags() {
        require_once(JPATH_ROOT . DS . 'components' . DS . 'com_customproperties' . DS . 'controller.php');
        $cp = new CustompropertiesController();
        $cp->searchTags();
    }

    function addTags() {        
        require_once(JPATH_ROOT . DS . 'components' . DS . 'com_customproperties' . DS . 'controller.php');
        $cp = new CustompropertiesController();
        $cp->addTags();
    }

    function getAllTags() {
        require_once(JPATH_ROOT . DS . 'components' . DS . 'com_customproperties' . DS . 'controller.php');
        $cp = new CustompropertiesController();
        $cp->getAllTags();
    }

}