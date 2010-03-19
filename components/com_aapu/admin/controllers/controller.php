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

        $this->registerTask('listDataTypes', 'listDataTypes');
        $this->registerTask('editDataType', 'editDataType');
        $this->registerTask('addDataType', 'editDataType');
        $this->registerTask('saveDataType', 'saveDataType');
        $this->registerTask('applyDataType', 'saveDataType');
        $this->registerTask('cancelDataType', 'cancelDataType');
        $this->registerTask('removeDataType', 'removeDataType');

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
        $this->baseRemoveTask('tabs', 'listTabs');
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
        $this->baseRemoveTask('attributes', 'listAttributes');
    }

    function saveAttribute() {
        global $option;

        $model	= &$this->getModel('attributes');


        if (!$model->store(false,true)) {
            echo "<script> alert('".$model->getError()."'); window.history.go(-1); </script>\n";
            exit();
        }

        $attribute = $model->getData();
        $msg = JText::sprintf('INFO_SAVE', 'Attribute');

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
        $this->baseRemoveTask('datatypes', 'listDataTypes');
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

        if ($filename == '') {
            $data->render = JRequest::getVar('oldRender', '', '', 'string');
        } else {
            $data->render = $filename;
        }

        if (!$model->store(false,false,$data)) {
            echo "<script> alert('".$model->getError()."'); window.history.go(-1); </script>\n";
            exit();
        }

        $dataType = $model->getData();
        $msg = JText::sprintf('INFO_SAVE', 'DataType');

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
        $view->display();
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
                  echo "<script> alert('No se pudo subir el archivo'); window.history.go(-1); </script>\n";
               }
            } else {
               echo "<script> alert('Extensión Incorrecta'); window.history.go(-1); </script>\n";
            }
        }

        return $filename;
    }

}
?>
