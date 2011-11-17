<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');
jimport('joomla.filesystem.file');
jimport('joomla.application.component.helper'); // include libraries/application/component/helper.php


/**
 * Description of controller
 *
 * @author nacho
 */
class AapuController extends JController {

    /**
     Constructor

     *  El controlador mantiene el registro de todas las tareas y metodos
     */
    function __construct($default = array()) {
        parent::__construct($default);        //llamada al construcutor de la clase base


        //registra la tarea y el metodo que la ejecuta
        $this->registerTask('listUsers', 'listUsers');
        $this->registerTask('editUser', 'editUser');
        $this->registerTask('addUser', 'editUser');
        $this->registerTask('saveUser', 'saveUser');
        $this->registerTask('applyUser', 'saveUser');
        $this->registerTask('cancelUser', 'cancelUser');
        $this->registerTask('removeUser', 'removeUser');

        //Tabs
        $this->registerTask('listTabs', 'listTabs');
        $this->registerTask('editTab', 'editTab');
        $this->registerTask('addTab', 'editTab');
        $this->registerTask('saveTab', 'saveTab');
        $this->registerTask('applyTab', 'saveTab');
        $this->registerTask('cancelTab', 'cancelTab');
        $this->registerTask('removeTab', 'removeTab');

        //Atributos
        $this->registerTask('listAttributes', 'listAttributes');
        $this->registerTask('editAttribute', 'editAttribute');
        $this->registerTask('addAttribute', 'editAttribute');
        $this->registerTask('saveAttribute', 'saveAttribute');
        $this->registerTask('applyAttribute', 'saveAttribute');
        $this->registerTask('cancelAttribute', 'cancelAttribute');
        $this->registerTask('removeAttribute', 'removeAttribute');

        //Publicar, validar, Bloquear
        $this->registerTask( 'publish', 'publish' );
        $this->registerTask( 'unpublish', 'publish' );
        $this->registerTask( 'block', 'block' );
        $this->registerTask( 'unblock', 'block' );
        $this->registerTask( 'validateAttr', 'validateAttr' );

        //Tipos de datos
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
        $this->baseDisplayTask('ListUsers', 'Users'); //llamma baseDisplayTask con la vista y el modelo
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
        $app = JFactory::getApplication();      //obtiene referencia al objeto

        //Llama a baseDisplayTask dependiendo el usuario
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
        $option = JRequest::getCMD('option');


        $model = &$this->getModel('users');  //obtiene el modelo
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
       $option = JRequest::getCMD('option');

        $postData = JRequest::get('post');
        
        // get the ACL
        $acl =& JFactory::getACL();

        $userData = array();
        $usersParams = &JComponentHelper::getParams( 'com_users' ); // carga parametros

        // obtiene usertype por defecto
        $usertype = $usersParams->get( 'new_usertype' );
        if (!$usertype) {
            $usertype = 'Registered';
        }      

        if ($postData['id'] == 0) {
            $user = JFactory::getUser(0); // it's important to set the "0" otherwise your admin user information will be loaded
            $userData['id'] = $postData['id'];
            $userData['email'] = $postData['email'];
            $userData['name'] = $postData['name'];
            $userData['username'] = $postData['username'];
            $userData['password'] = $postData['passwordt'];
            $userData['password2'] = $postData['password2'];
            $userData['registerDate'] = date("Y/m/d");
            $userData['lastvisitDate'] = date("Y/m/d");
            $userData['gid'] = $acl->get_group_id( '', $usertype, 'ARO' );  // generate the gid from the usertype
            $userData['sendEmail'] = 1; // should the user receive system mails?
            $useractivation = $usersParams->get( 'useractivation' ); // in this example, we load the config-setting
            if ($useractivation == 1) { // yeah we want an activation
                jimport('joomla.user.helper'); // include libraries/user/helper.php
                $userData['block'] = 1; // block the User
                $userData['activation'] = JUtility::getHash( JUserHelper::genRandomPassword()); // set activation hash (don't forget to send an activation email)
            } else {
                $userData['block'] = 0; // dont't block the User
            }
            if (!$user->bind($userData)) { // now bind the data to the JUser Object, if it not works....
                $msg = JText::_( $user->getError()); // ...raise an Warning
            }
        } else {
            $user = JFactory::getUser($postData['id']); // it's important to set the "0" otherwise your admin user information will be loaded
            $user->set('name', $postData['name']);
            $user->set('username', $postData['username']);
            $user->set('email', $postData['email']);
        }

        if (!$user->save()) { // if the user is NOT saved...
            $msg = JText::_( $user->getError()); // ...raise an Warning
        } else {
            $db = &JFactory::getDBO();

            $postData['id'] = $this->getUserId($db, $user->username);

            $attrEntityModel = &$this->getModel('Attribute_entity');

            foreach ($postData as $clave => $valor) {
                if (strncmp($clave,'attr_',5) == 0) {

                    if (is_array($valor)) {
                        $valor = implode(',', $valor);
                    }

                    $attr_id = (int)substr_replace($clave,'',0,5);
                    //$attr_id = (int)substr($clave, 5, strlen($clave) - 5);
                    $data = new stdClass();
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
                        $msg = $model->getError;
                    } else {
                        $msg = JText::sprintf('INFO_SAVE', 'User');
                    }
                }
            }
        }

        //$user = $model->getData();

        switch ($this->_task) {
            case 'applyUser':
                $link = 'index.php?option=' . $option . '&task=editUser&cid[]=' . $postData['id'];
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
       $option = JRequest::getCMD('option');

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
      $option = JRequest::getCMD('option');

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
        $option = JRequest::getCMD('option');

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

    function block() {
        $usersId = $_POST['cid'];
        $user = JFactory::getUser($usersId[0]);
        $isBlock = $user->get('block');
        if ($isBlock) {
            $user->set('block', 0);
        } else {
            $user->set('block', 1);
        }
        $user->save();
        $link = 'index.php?option=com_aapu&controller=controller&task=listUsers';
        $this->setRedirect($link);
    }

    function saveAttribute() {
        $option = JRequest::getCMD('option'); ;

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
         * MANEJO DE FUNCIONES PARA TIPOS DE DATOS.
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
        $option = JRequest::getCMD('option'); ;
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
        $option = JRequest::getCMD('option'); ;

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
         * FUNCIONES GENERICAS
          Se redefien el metodo Display para mostrar la vista
    */

    function baseDisplayTask($view, $modelName, $layout = 'default', $hidemainmenu = 0, $vars = array()) {
        $option = JRequest::getCMD('option'); ;

        $document = &JFactory::getDocument();   //obiene la referencia al objeto.
        $vLayout = JRequest::getCmd( 'layout', $layout );

        if (gettype($view) == 'string') {
            // Get/Crea la vista
            $vType = $document->getType();
            $vName = JRequest::getCmd('view', $view);
            $view = &$this->getView($vName, $vType);
        }

        // Get/Create the models

        $model = &$this->getModel(JRequest::getCmd('model', $modelName));
        if ($model) {
            $view->setModel($model, true);  // Agrega el modelo a la vista
        }

        
        JRequest::setVar('hidemainmenu', $hidemainmenu); // Desactiva el menú principal

       
        $view->setLayout($vLayout); // Setea el layout

        
        if (array_key_exists('userId', $vars)) {
            $view->display(null, $vars['userId']); // Despliega la vista con el userId
        } else {
            $view->display();
        }
    }

    function baseRemoveTask($model, $task) {
        $option = JRequest::getCMD('option'); ;

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
        $option = JRequest::getCMD('option'); ;

        $redirect = 'index.php?option=' . $option . '&task=' . $task;
        $this->setRedirect($redirect, $msg);
    }

    function baseCancelTask($msg, $task) {
        $option = JRequest::getCMD('option'); ;
        $this->setRedirect('index.php?option='.$option.'&task='.$task, $msg);
    }


    function uploadFile($file, $directory, $extension) {

        //Retrieve file details from uploaded file, sent from upload form
        //$file = JRequest::getVar($fileCharger, null, 'files', 'array');

        //Import filesystem libraries. Perhaps not necessary, but does not hurt
        jimport('joomla.filesystem.file');

        //limpia filneam de caracteres extraños y espacios blanco
        $filename = JFile::makeSafe($file['name']);

        if ($filename != '') {

            //setea fuente y destino del archivo 'file'
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

    function getUserId($db, $username) {
        $id_query = "SELECT u.id FROM #__users u WHERE u.username='$username'";
        $db->setQuery($id_query);
        $dbuserid = $db->loadObject();
        return $dbuserid->id;
    }

}
?>
