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

    /**
     * constructor (registers additional tasks to methods)
     * @return void
     */
    function __construct() {
        parent::__construct();

        // Register Extra tasks
        $this->registerTask( 'add'      , 'edit' );
        $this->registerTask( 'apply'    , 'save' );
        //$this->registerTask( 'unpublish', 'publish' );
        //$this->registerTask( 'remove'   , 'delete' );
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

        switch($this->getTask()) {
            case 'edit':
            case 'add':
            case 'ordervalup':
                $view->setModel($this->getModel('cpvalue', 'CustompropertiesModel'));
                $view->setLayout('edit');
                JRequest::setVar( 'hidemainmenu', 1);
                break;
            default:
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
        if($model->saveValue($pid = JRequest::getVar('pid', 0, '', 'int')) === true ) {
            $msg = JText::_('Value saved');
            $msg_type = "message";
            if($this->getTask() == 'save') {
                $link = 'index.php?option=com_customproperties&controller=values&cid='.$pid;
            }
            else {
                $link = 'index.php?option=com_customproperties&controller=values&task=edit&cid='.$model->_id;
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
        $model->deleteValues();
        $link = 'index.php?option=com_customproperties&controller=values&cid='.$pid;
        $this->setRedirect($link);
    }

    /*function orderValueUp() {
        $model = & $this->getModel('Cpfield');
        $model->orderValue(-1);
        $link = 'index.php?option=com_customproperties&controller=fields&task=edit&cid='.$model->_id;
        $this->setRedirect($link);
    }
    function orderValueDown() {
        $model = & $this->getModel('Cpfield');
        $model->orderValue(1);
        $link = 'index.php?option=com_customproperties&controller=fields&task=edit&cid='.$model->_id;
        $this->setRedirect($link);
    }

    function deleteValue() {
        $model = & $this->getModel('Cpfield');
        $model->deleteValue();
        $link = 'index.php?option=com_customproperties&controller=fields&task=edit&cid='.$model->_id;
        $this->setRedirect($link);
    }*/

}