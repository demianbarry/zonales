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

jimport( 'joomla.application.component.view' );

/**
 * Value Management View
 *
 * @package    Custom Properties
 * @subpackage Components
 */
class CustompropertiesViewCpvalues extends JView
{
    /**
     * CP Values view display method
     * @return void
     **/
    function display($tpl = null)
    {
        //$this->cid = JRequest::getInt('cid',0);
        //$this->pid = JRequest::getInt('pid',0);
        $this->cid = JRequest::getVar('cid', 0, '', 'array');
        $this->pid = JRequest::getVar('pid', 0, '', 'int');
        switch($this->getLayout()){
          case 'edit' :
            //$model = & JModel::getInstance('cpfield','custompropertiesModel');
            $model = $this->getModel('cpvalue');
            $value = $model->getData();
            $childs = $model->getChilds();
            //$values = $model->getValues();
            $this->assignRef('value', $value);
            $this->assignRef('childs', $childs);
            //$this->assignRef('values', $values);
            $types = array ();
            $model =& $this->getModel('cpvalues');
            $items = $model->getAll($this->cid[0]);
            $this->assignRef('items', $items);
            break;

          default:
            $model =& $this->getModel('cpvalues');
            $items = $model->getList(true,$this->cid[0]);
            $page = $model->getPagination();

            if ($this->cid[0] != 0) {
                $pmodel = $this->getModel('cpvalue');
                $pvalue = $pmodel->getData();
                $this->back = $pvalue->parent_id;
            }

            $this->assignRef( 'items', $items );
            $this->assignRef( 'page', $page );
          }
        parent::display($tpl);
    }
}
