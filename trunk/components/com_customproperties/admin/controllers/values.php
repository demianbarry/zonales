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

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controller');

/**
 * Custom Properties Component Controller - Fields
 *
 * @package    Custom Properties
 * @subpackage Components
 */
class CustompropertiesControllerValues extends JController {

    /**7
     * constructor (registers additional tasks to methods)
     * @return void
     */
    function __construct() {
        parent::__construct();

        // Register Extra tasks
        $this->registerTask( 'add'      , 'edit' );
        $this->registerTask( 'apply'    , 'save' );
        //$this->registerTask( 'unpublish', 'publish' );
        $this->registerTask( 'remove'   , 'delete' );
        $this->registerTask( 'ordvalup' , 'orderValueUp' );
        $this->registerTask( 'ordvaldn' , 'orderValueDown' );

    }

    /**
     * Method to display the view
     *
     * @access    public
     */
    function display() {
        $view = $this->getView('cpvalues', 'html');
        $view->setModel($this->getModel('cpvalues', 'CustompropertiesModel'), true);
        $view->setModel($this->getModel('cpfields', 'CustompropertiesModel'), true);
        $view->setModel($this->getModel('cpfield', 'CustompropertiesModel'), true);

        switch($this->getTask()) {
            case 'add':
                JRequest::setVar('cid', 0);
            case 'edit':
            case 'ordervalup':
                $view->setModel($this->getModel('cpvalue', 'CustompropertiesModel'));
                $view->setLayout('edit');
                JRequest::setVar( 'hidemainmenu', 1);
                break;
            default:
                $view->setModel($this->getModel('cpvalue', 'CustompropertiesModel'));
        }

        $view->display();
    }

    function orderup() {
        $model = & $this->getModel('Cpvalue');
        $model->orderValue(-1);
        $link = 'index.php?option=com_customproperties&controller=values';
        $this->setRedirect($link);
    }
    function orderdown() {
        $model = & $this->getModel('Cpvalue');
        $model->orderValue(1);
        $link = 'index.php?option=com_customproperties&controller=values';
        $this->setRedirect($link);
    }
    function saveorder() {
        $model = & $this->getModel('Cpvalue');
        $model->saveValuesOrder();
        $link = 'index.php?option=com_customproperties&controller=values';
        $this->setRedirect($link);
    }
    function publish() {
        $model = & $this->getModel('Cpvalue');
        $model->publishValues();
        $link = 'index.php?option=com_customproperties&controller=values';
        $this->setRedirect($link);
    }
    function cancel() {
        $pid = JRequest::getVar('pid', 0, '', 'int');
        $link = 'index.php?option=com_customproperties&controller=values&cid='.$pid;
        $this->setRedirect($link);
    }
    function save() {
        $model = & $this->getModel('Cpvalue');

        $pid = JRequest::getVar('pid', 0, '', 'int');
        $cid = JRequest::getVar('cid', 0, '', 'int');
        if ($cid != 0) {
            $newpid = JRequest::getVar('newpid', null, '', 'int');
            if ($newpid != null && $pid != $newpid) {
                $pid = $newpid;
            }
        }
        $field = JRequest::getVar('field', 0, '', 'int');

        $fatherModel = $this->getModel('Cpvalue');
        $fatherModel->setId($pid);
        $father = $fatherModel->getData();

        if ($field == 0) {
            $field = $father->field_id;
        }
        
        if($model->saveValue($pid, $field) === true ) {
            $msg = JText::_('Value saved');
            $msg_type = "message";
            if($this->getTask() == 'save') {
                $link = 'index.php?option=com_customproperties&controller=values&cid='.$pid;
            }
            else {
                $link = 'index.php?option=com_customproperties&controller=values&task=edit&cid='.$model->_id.'&pid='.$pid;
            }
        }
        else {
            $msg = JText::_('ERRSAVE').": " . implode(', ',$model->getErrors());
            $msg_type = "error";
            $link = 'index.php?option=com_customproperties&controller=values&task=edit&cid='.$model->_id;
        }
        $this->setRedirect($link, $msg, $msg_type);
    }
    function delete() {
        $pid = JRequest::getVar('pid', 0, '', 'int');
        $model = & $this->getModel('Cpvalue');
        if ($model->deleteValues()) {
            $link = 'index.php?option=com_customproperties&controller=values&cid='.$pid;
            $msg = JText::_('VALUE DELETE');
            $msg_type = "message";
        } else {
            $msg = JText::_('NODE NO LEAF');
            $msg_type = "error";
            $link = 'index.php?option=com_customproperties&controller=values&cid='.$pid;
        }
        $this->setRedirect($link, $msg, $msg_type);
    }

    function orderValueUp() {
        $model = & $this->getModel('Cpvalue');
        $model->orderChildValue(-1);
        $link = 'index.php?option=com_customproperties&controller=values&task=edit&cid='.$model->_id;
        $this->setRedirect($link);
    }
    function orderValueDown() {
        $model = & $this->getModel('Cpvalue');
        $model->orderChildValue(1);
        $link = 'index.php?option=com_customproperties&controller=values&task=edit&cid='.$model->_id;
        $this->setRedirect($link);
    }

    function deleteValue() {
        $pid = JRequest::getVar('pid', 0, '', 'int');
        $model = & $this->getModel('Cpvalue');
        $model->deleteValue();
        $link = 'index.php?option=com_customproperties&controller=values&task=edit&cid='.$model->_id.'&pid='.$pid;
        $this->setRedirect($link);
    }

}