<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');
jimport('joomla.filesystem.file');

/**
 * Description of controller
 *
 * @author nacho
 */
class AapuController extends JController {

    /**
     * Constructor
     *
     * @param array $default
     */
    function __construct($default = array()) {
        parent::__construct($default);

        $this->registerTask('listUsers', 'listUsers');
        $this->registerTask('editUser', 'editUser');
        $this->registerTask('addUser', 'editUser');
        $this->registerTask('saveUser', 'saveUser');
        $this->registerTask('applyUser', 'saveUser');
        $this->registerTask('cancelUser', 'cancelUser');
        $this->registerTask('removeUser', 'removeUser');

        $this->registerTask('listTabs', 'listTabs');
        $this->registerTask('editTab', 'editTab');
        $this->registerTask('addTab', 'editTab');
        $this->registerTask('saveTab', 'saveTab');
        $this->registerTask('applyTab', 'saveTab');
        $this->registerTask('cancelTab', 'cancelTab');
        $this->registerTask('removeTab', 'removeTab');

        $this->registerTask('listAttributes', 'listAttributes');
        $this->registerTask('editAttribute', 'editAttribute');
        $this->registerTask('addAttribute', 'editAttribute');
        $this->registerTask('saveAttribute', 'saveAttribute');
        $this->registerTask('applyAttribute', 'saveAttribute');
        $this->registerTask('cancelAttribute', 'cancelAttribute');
        $this->registerTask('removeAttribute', 'removeAttribute');

        $this->registerTask( 'unpublish', 'publish' );
        $this->registerTask( 'validateAttr', 'validateAttr' );

        $this->registerTask('listDataTypes', 'listDataTypes');
        $this->registerTask('editDataType', 'editDataType');
        $this->registerTask('addDataType', 'editDataType');
        $this->registerTask('saveDataType', 'saveDataType');
        $this->registerTask('applyDataType', 'saveDataType');
        $this->registerTask('cancelDataType', 'cancelDataType');
        $this->registerTask('removeDataType', 'removeDataType');

    }

    /*
         * MANAGEMENT FUNCTIONS FOR USERS
    */

    function listUsers() {
        $this->baseDisplayTask('ListUsers', 'Users');
    }

    function editUser() {
        $document = &JFactory::getDocument();

        $vName = JRequest::getCmd('view', 'EditUser');
        $vType = $document->getType();
        $view = &$this->getView( $vName, $vType);

        $view->setModel($this->getModel('tabs', 'AapuModel'), true);
        $view->setModel($this->getModel('attributes', 'AapuModel'), true);
        $view->setModel($this->getModel('datatypes', 'AapuModel'), true);
        $view->setModel($this->getModel('attribute_entity', 'AapuModel'), true);

        // no se ejecuta si se accede al backend
        $app = JFactory::getApplication();

        if ($app->isAdmin()) {
            $this->baseDisplayTask($view, 'Users', 'default', 1);
        } else {
            $user =& JFactory::getUser();
            $this->baseDisplayTask($view, 'Users', 'default', 1, array('userId' => $user->id));
        }
    }

    function cancelUser() {
        $app = JFactory::getApplication();

        if ($app->isAdmin()) {
            $this->baseCancelTask(JText::_('INFO_CANCEL'), 'listUsers');
        } else {
            $this->setRedirect('index.php');
        }
    }

    function removeUser() {
        global $option;

        $model = &$this->getModel('users');
        $cids = $_POST['cid'];
        $users = $model->getSelected($cids);
        $attrEntityModel = &$this->getModel('attribute_entity');

        foreach ($users as $user) {
            $attrEntityModel->setWhere("object_id = $user->id");
            $attrsEntity = $attrEntityModel->getAll(true);
            foreach($attrsEntity as $attrEntity) {
                $aeCids[] = $attrEntity->id;
            }
        }

        if (!$attrEntityModel->delete($aeCids)) {
            $msg = JText::_( 'ERROR_REMOVE' );
        }
        else {
            $msg = JText::_( 'INFO_REMOVE' );
        }

        if (!$model->delete()) {
            $msg = JText::_( 'ERROR_REMOVE' );
        }
        else {
            $msg = JText::_( 'INFO_REMOVE' );
        }

        $this->redirectTask('listUsers', $msg);
    }

    function saveUser() {
        global $option;

        $model	= &$this->getModel('Users');

        $postData = JRequest::get('post');

        if ($postData['id'] == 0) {
            $postData['registerDate'] = date("Y/m/d");
        }

        if (!$model->store(false,false,$postData)) {
            return $model->getError;
            exit();
        }

        $postData['id'] = $model->getId();

        $attrEntityModel = &$this->getModel('Attribute_entity');

        foreach ($postData as $clave => $valor) {
            if (strncmp($clave,'attr_',5) == 0) {

                if (is_array($valor)) {
                    $valor = implode(',', $valor);
                }

                $attr_id = (int)substr_replace($clave,'',0,5);
                //$attr_id = (int)substr($clave, 5, strlen($clave) - 5);
                $data->id = $postData['aeid_'.$attr_id];
		$data->value = null;
                $data->value_double = null;
                $data->value_date = null;
                $data->value_int = null;
                $data->value_boolean = null;
                if ($valor != '' || $valor != null) {
                    $data->$postData['aept_'.$attr_id] = $valor;
                } else {
                    $data->$postData['aept_'.$attr_id] = null;
                }
                $data->object_type = 'TABLE';
                $data->object_id = $postData['id'];
                $data->object_name = '#__users';
                $data->attribute_id = $attr_id;
                
                if (!$attrEntityModel->store(false,false,$data)) {
                    return $model->getError;
                    exit();
                }
            }
        }

        $user = $model->getData();
        $msg = JText::sprintf('INFO_SAVE', 'User');

        switch ($this->_task) {
            case 'applyUser':
                $link = 'index.php?option=' . $option . '&task=editUser&cid[]=' . $user->id;
                break;

            case 'saveUser':
            default:
                $link = 'index.php?option=' . $option . '&task=listUsers';
                break;
        }

        $app = JFactory::getApplication();

        if ($app->isAdmin()) {
            $this->setRedirect($link, $msg);
        } else {
            $this->setRedirect('index.php', JText::_( 'User save'));
        }
    }

    /*
         * MANAGEMENT FUNCTIONS FOR TABS
    */

    function listTabs() {
        $this->baseDisplayTask('ListTabs', 'tabs');
    }

    function editTab() {
        $this->baseDisplayTask('EditTab', 'tabs', 'default', 1);
    }

    function cancelTab() {
        $this->baseCancelTask(JText::_('INFO_CANCEL'), 'listTabs');
    }

    function removeTab() {
        global $option;
        $flag = true;

        $model = &$this->getModel('tabs');
        $cids = $_POST['cid'];
        $tabs = $model->getSelected($cids);
        $attrModel = &$this->getModel('attributes');

        foreach ($tabs as $tab) {
            $attrModel->setWhere("attribute_class_id = $tab->id");
            $attrTab = $attrModel->getAll(true);
            if ($attrTab != null) {
                $flag = false;
            }
        }

        if ($flag) {
            if (!$model->delete()) {
                $msg = JText::_( 'ERROR_REMOVE' );
            }
            else {
                $msg = JText::_( 'INFO_REMOVE' );
            }
        } else {
            $msg = JText::_( 'NO_REMOVE' );
        }

        $this->redirectTask('listTabs', $msg);
    }

    function saveTab() {
        global $option;

        $model	= &$this->getModel('tabs');

        //usr_ prefix before the name according to design decision
        $data->id = JRequest::getVar('id', 0, '', 'int');
        $data->name = JRequest::getVar('name', 0, '', 'string');
        $data->label = JRequest::getVar('label', 0, '', 'string');
        $data->description = JRequest::getVar('description', 0, '', 'string');
        if (strncmp($data->name,'usr_',4) != 0) {
            $data->name = 'usr_'.$data->name;
        }

        if (!$model->store(false,false,$data)) {
            echo "<script> alert('".$model->getError()."'); window.history.go(-1); </script>\n";
            exit();
        }

        $tab = $model->getData();
        $msg = JText::sprintf('INFO_SAVE', 'Tab');

        switch ($this->_task) {
            case 'applyTab':
                $link = 'index.php?option=' . $option . '&task=editTab&cid[]=' . $tab->id;
                break;

            case 'saveTab':
            default:
                $link = 'index.php?option=' . $option . '&task=listTabs';
                break;
        }

        $this->setRedirect($link, $msg);
    }

    /*
         * MANAGEMENT FUNCTIONS FOR ATTRIBUTES
    */

    function listAttributes() {
        $this->baseDisplayTask('ListAttributes', 'attributes');
    }

    function editAttribute() {
        $document = &JFactory::getDocument();

        $vName = JRequest::getCmd('view', 'EditAttribute');
        $vType = $document->getType();
        $view = &$this->getView( $vName, $vType);

        $view->setModel($this->getModel('tabs', 'AapuModel'), true);
        $view->setModel($this->getModel('datatypes', 'AapuModel'), true);

        $this->baseDisplayTask($view, 'attributes', 'default', 1);
    }

    function cancelAttribute() {
        $this->baseCancelTask(JText::_('INFO_CANCEL'), 'listAttributes');
    }

    function removeAttribute() {
        global $option;
        $flag = true;

        $model = &$this->getModel('attributes');
        $cids = $_POST['cid'];
        $attributes = $model->getSelected($cids);
        $attrEntityModel = &$this->getModel('attribute_entity');

        foreach ($attributes as $attribute) {
            $attrEntityModel->setWhere("attribute_id = $attribute->id");
            $attrsEntity = $attrEntityModel->getAll(true);
            if ($attrsEntity != null) {
                $flag = false;
            }
        }

        if ($flag) {
            if (!$model->delete()) {
                $msg = JText::_( 'ERROR_REMOVE' );
            }
            else {
                $msg = JText::_( 'INFO_REMOVE' );
            }
        } else {
            $msg = JText::_( 'NO_REMOVE' );
        }

        $this->redirectTask('listAttributes', $msg);
    }

    function publish() {
        $model = & $this->getModel('attributes');
        $model->publish();
        $link = 'index.php?option=com_aapu&controller=controller&task=listAttributes';
        $this->setRedirect($link);
    }

    function saveAttribute() {
        global $option;

        $model	= &$this->getModel('attributes');

        //usr_ prefix before the name according to design decision
        $data->id = JRequest::getVar('id', 0, '', 'int');
        $data->name = JRequest::getVar('name', '', '', 'string');
        $data->label = JRequest::getVar('label', '', '', 'string');
        $data->description = JRequest::getVar('description', '', '', 'string');
        $data->comments = JRequest::getVar('comments', '', '', 'string');
        $data->from = JRequest::getVar('from', '', '', 'date');
        $data->to = JRequest::getVar('to', '', '', 'date');
        $data->canceled = JRequest::getVar('canceled', false, '', 'boolean');
        $data->required = JRequest::getVar('required', false, '', 'boolean');
        $data->validator = JRequest::getVar('validator', null, 'files', 'array');
        $data->data_type_id = JRequest::getVar('data_type_id', 0, '', 'int');
        $data->attribute_class_id = JRequest::getVar('attribute_class_id', 0, '', 'int');
        $data->values_list = JRequest::getVar('values_list', '', '', 'string');

        $filename = $this->uploadFile($data->validator, 'validators', 'php');

        $msg = '';

        /*if ($filename == '') {
            $msg = JText::sprintf('INFO_NO_SAVE_FOR_ARCHIVE');
            //$data->validator = JRequest::getVar('oldValidator', '', '', 'string');
        } else {*/
            $data->validator = $filename;
            if (!$model->store(false,false,$data)) {
                $msg = JText::sprintf('INFO_NO_SAVE', $model->getError());
                //echo "<script> alert('".$model->getError()."'); window.history.go(-1); </script>\n";
                exit();
            }

            $attribute = $model->getData();
            $msg = JText::sprintf('INFO_SAVE', 'Attribute');
        //}

        switch ($this->_task) {
            case 'applyAttribute':
                $link = 'index.php?option=' . $option . '&task=editAttribute&cid[]=' . $attribute->id;
                break;

            case 'saveAttribute':
            default:
                $link = 'index.php?option=' . $option . '&task=listAttributes';
                break;
        }

        $this->setRedirect($link, $msg);
    }

    /*
         * MANAGEMENT FUNCTIONS FOR DATA TYPES
    */

    function listDataTypes() {
        $this->baseDisplayTask('ListDataTypes', 'datatypes');
    }

    function editDataType() {
        $this->baseDisplayTask('EditDataType', 'datatypes', 'default', 1);
    }

    function cancelDataType() {
        $this->baseCancelTask(JText::_('INFO_CANCEL'), 'listDataTypes');
    }

    function removeDataType() {
        global $option;
        $flag = true;

        $model = &$this->getModel('datatypes');
        $cids = $_POST['cid'];
        $dataTypes = $model->getSelected($cids);
        $attrModel = &$this->getModel('attributes');

        foreach ($dataTypes as $dataType) {
            $attrModel->setWhere("data_type_id = $dataType->id");
            $attrDataType = $attrModel->getAll(true);
            if ($attrDataType != null) {
                $flag = false;
            }
        }

        if ($flag) {
            if (!$model->delete()) {
                $msg = JText::_( 'ERROR_REMOVE' );
            }
            else {
                $msg = JText::_( 'INFO_REMOVE' );
            }
        } else {
            $msg = JText::_( 'NO_REMOVE' );
        }

        $this->redirectTask('listDataTypes', $msg);
    }

    function saveDataType() {
        global $option;

        $model	= &$this->getModel('datatypes');

        //usr_ prefix before the name according to design decision
        $data->id = JRequest::getVar('id', 0, '', 'int');
        $data->label = JRequest::getVar('label', '', '', 'string');
        $data->description = JRequest::getVar('description', '', '', 'string');
        $data->render = JRequest::getVar('render', null, 'files', 'array');

        $filename = $this->uploadFile($data->render, 'renders', 'php');

        $msg = '';

        if ($filename == '') {
            $msg = JText::sprintf('INFO_NO_SAVE_FOR_ARCHIVE');
            //$data->render = JRequest::getVar('oldRender', '', '', 'string');
        } else {
            $data->render = $filename;
            if (!$model->store(false,false,$data)) {
                $msg = JText::sprintf('INFO_NO_SAVE', $model->getError());
                //echo "<script> alert('".$model->getError()."'); window.history.go(-1); </script>\n";
                exit();
            }

            $dataType = $model->getData();
            $msg = JText::sprintf('INFO_SAVE', 'DataType');
        }

        switch ($this->_task) {
            case 'applyDataType':
                $link = 'index.php?option=' . $option . '&task=editDataType&cid[]=' . $dataType->id;
                break;

            case 'saveDataType':
            default:
                $link = 'index.php?option=' . $option . '&task=listDataTypes';
                break;
        }

        $this->setRedirect($link, $msg);
    }

    /*
         * GENERIC FUNCTIONS
    */

    function baseDisplayTask($view, $modelName, $layout = 'default', $hidemainmenu = 0, $vars = array()) {
        global $option;

        $document = &JFactory::getDocument();
        $vLayout = JRequest::getCmd( 'layout', $layout );

        if (gettype($view) == 'string') {
            // Get/Create the view
            $vType = $document->getType();
            $vName = JRequest::getCmd('view', $view);
            $view = &$this->getView( $vName, $vType);
        }

        // Get/Create the models
        $model = &$this->getModel($modelName);
        if ($model) {
            $view->setModel($model, true);
        }

        // Desactiva el menú principal
        JRequest::setVar('hidemainmenu', $hidemainmenu);

        // Set the layout
        $view->setLayout($vLayout);

        // Display the view
        if (array_key_exists('userId', $vars)) {
            $view->display(null, $vars['userId']);
        } else {
            $view->display();
        }
    }

    function baseRemoveTask($model, $task) {
        global $option;

        $model = &$this->getModel($model);

        if (!$model->delete()) {
            $msg = JText::_( 'ERROR_REMOVE' );
        }
        else {
            $msg = JText::_( 'INFO_REMOVE' );
        }

        $redirect = 'index.php?option=' . $option . '&task=' . $task;
        $this->setRedirect($redirect, $msg);
    }

    function redirectTask($task, $msg) {
        global $option;

        $redirect = 'index.php?option=' . $option . '&task=' . $task;
        $this->setRedirect($redirect, $msg);
    }

    function baseCancelTask($msg, $task) {
        global $option;
        $this->setRedirect('index.php?option='.$option.'&task='.$task, $msg);
    }


    function uploadFile($file, $directory, $extension) {

        //Retrieve file details from uploaded file, sent from upload form
        //$file = JRequest::getVar($fileCharger, null, 'files', 'array');

        //Import filesystem libraries. Perhaps not necessary, but does not hurt
        jimport('joomla.filesystem.file');

        //Clean up filename to get rid of strange characters like spaces etc
        $filename = JFile::makeSafe($file['name']);

        if ($filename != '') {

            //Set up the source and destination of the file
            $src = $file['tmp_name'];
            $dest = JPATH_COMPONENT_ADMINISTRATOR . DS . 'plugins' . DS . $directory. DS . $filename;

            //First check if the file has the right extension, we need jpg only
            if ( strtolower(JFile::getExt($filename) ) == $extension) {
                if ( !JFile::upload($src, $dest) ) {
                    $filename = '';
                }
            } else {
                $filename = '';
            }
        }

        return $filename;
    }

    function validateAttr() {
        $sid = JRequest::getVar('attrId', '', '', 'String');
        $id = (int)substr($sid, 5, strlen($sid) - 5);
        $value = JRequest::getVar('attrValue', '', '', 'String');

        $attrModel = &$this->getModel('Attributes');
        $attrModel->setId($id);
        $attr = $attrModel->getData(true);

        if ($attr->validator != '' || $attr->validator != null) {
            require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'plugins'.DS.'validators'.DS.$attr->validator );
            $classname = substr($attr->validator, 0, -4);
            $return = call_user_func_array( array( $classname, 'validate' ), array($value) );
        } else {
            $return = '';
        }
        echo $return;
	return;
    }
}
?>
